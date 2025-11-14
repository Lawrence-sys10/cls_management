#!/bin/bash

# CLS Management System Deployment Script
# Ubuntu 20.04+ Server Deployment

set -e

echo "🚀 Starting CLS Management System Deployment..."
echo "=============================================="

# Colors for output
RED=''\033[0;31m''
GREEN=''\033[0;32m''
YELLOW=''\033[1;33m''
NC=''\033[0m'' # No Color

# Configuration
DB_NAME="cls_management"
DB_USER="cls_user"
DB_PASS=$(openssl rand -base64 32)
APP_URL="your-domain.com" # Change this to your domain
APP_NAME="Techiman CLS"

# Function to print colored output
print_status() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    print_error "Please run as root"
    exit 1
fi

# Update system
print_status "Updating system packages..."
apt update && apt upgrade -y

# Install required packages
print_status "Installing required packages..."
apt install -y nginx mysql-server php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-mbstring php8.1-bcmath php8.1-common

# Install Composer
print_status "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Configure MySQL
print_status "Configuring MySQL database..."
mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS ''$DB_USER''@''localhost'' IDENTIFIED BY ''$DB_PASS'';"
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO ''$DB_USER''@''localhost'';"
mysql -e "FLUSH PRIVILEGES;"

# Create application directory
print_status "Creating application directory..."
mkdir -p /var/www/cls
chown -R www-data:www-data /var/www/cls

# Clone or upload your Laravel application here
# For this script, we assume the application is already in /var/www/cls

print_warning "Please upload your Laravel application to /var/www/cls before continuing"
read -p "Press Enter to continue after uploading the application..."

# Set proper permissions
print_status "Setting file permissions..."
cd /var/www/cls
chown -R www-data:www-data .
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Install Laravel dependencies
print_status "Installing Laravel dependencies..."
sudo -u www-data composer install --no-dev --optimize-autoloader

# Create environment file
print_status "Creating environment configuration..."
cp .env.example .env

# Generate application key
sudo -u www-data php artisan key:generate

# Configure environment
print_status "Configuring environment variables..."
sed -i "s/APP_NAME=.*/APP_NAME=\"$APP_NAME\"/" .env
sed -i "s/APP_URL=.*/APP_URL=https:\/\/$APP_URL/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env

# Set production environment
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env

# Run database migrations and seed
print_status "Running database setup..."
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --force

# Generate storage link
sudo -u www-data php artisan storage:link

# Optimize application
print_status "Optimizing application..."
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Configure Nginx
print_status "Configuring Nginx..."
cat > /etc/nginx/sites-available/cls << EOF
server {
    listen 80;
    server_name $APP_URL;
    root /var/www/cls/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files \\$uri \\$uri/ /index.php?\\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\\$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \\$realpath_root\\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\\.(?!well-known).* {
        deny all;
    }

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
}
EOF

# Enable site
ln -sf /etc/nginx/sites-available/cls /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

# Restart services
print_status "Restarting services..."
systemctl restart nginx
systemctl restart php8.1-fpm
systemctl enable nginx
systemctl enable php8.1-fpm

# Configure firewall
print_status "Configuring firewall..."
ufw allow ''Nginx Full''
ufw allow OpenSSH
ufw --force enable

# Setup SSL with Let''s Encrypt (optional)
print_warning "Would you like to setup SSL with Let''s Encrypt? (y/n)"
read -r setup_ssl
if [[ \\$setup_ssl =~ ^[Yy]\\$ ]]; then
    apt install -y certbot python3-certbot-nginx
    certbot --nginx -d \\$APP_URL
    # Auto-renewal
    (crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet") | crontab -
fi

# Create backup script
print_status "Creating backup script..."
cat > /usr/local/bin/backup-cls.sh << ''EOF''
#!/bin/bash
# Backup script for CLS Management System

BACKUP_DIR="/var/backups/cls"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="cls_management"

mkdir -p \\$BACKUP_DIR

# Backup database
mysqldump \\$DB_NAME > \\$BACKUP_DIR/cls_db_\\$DATE.sql
gzip \\$BACKUP_DIR/cls_db_\\$DATE.sql

# Backup application files
tar -czf \\$BACKUP_DIR/cls_files_\\$DATE.tar.gz /var/www/cls

# Cleanup old backups (keep last 30 days)
find \\$BACKUP_DIR -name "*.gz" -type f -mtime +30 -delete

echo "Backup completed: \\$BACKUP_DIR/cls_db_\\$DATE.sql.gz"
echo "Backup completed: \\$BACKUP_DIR/cls_files_\\$DATE.tar.gz"
EOF

chmod +x /usr/local/bin/backup-cls.sh

# Setup daily backups
echo "0 2 * * * root /usr/local/bin/backup-cls.sh" > /etc/cron.d/cls-backup

# Create logrotate configuration
cat > /etc/logrotate.d/cls << EOF
/var/www/cls/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    copytruncate
}
EOF

print_status "🎉 Deployment completed successfully!"
echo ""
print_warning "Important Information:"
echo "  Database Name: \\$DB_NAME"
echo "  Database User: \\$DB_USER"
echo "  Database Password: \\$DB_PASS"
echo "  Application URL: https://\\$APP_URL"
echo ""
print_warning "Next steps:"
echo "  1. Configure your DNS to point to this server"
echo "  2. Access the application at https://\\$APP_URL"
echo "  3. Login with: admin@cls.com / password"
echo "  4. Review and configure additional settings in the admin panel"
echo ""
print_status "Backup script location: /usr/local/bin/backup-cls.sh"
print_status "Backups run daily at 2 AM and are stored in /var/backups/cls/"
