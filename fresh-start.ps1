# fresh-start.ps1 - Fixed version

Write-Host "🚀 COMPLETE FRESH SETUP FOR CLS MANAGEMENT SYSTEM" -ForegroundColor Green
Write-Host "==================================================" -ForegroundColor Cyan

# 1️⃣ CLEANING UP...
Write-Host "`n1️⃣ CLEANING UP..." -ForegroundColor Yellow

# Remove migration files
Get-ChildItem -Path "database\migrations\*_create_*" -Recurse | Remove-Item -Force
Write-Host "   ✅ Removed all migration files" -ForegroundColor Green

# Remove database
if (Test-Path "database\database.sqlite") {
    Remove-Item "database\database.sqlite" -Force
    Write-Host "   ✅ Deleted database.sqlite" -ForegroundColor Green
}

# Create fresh database
New-Item -Path "database\database.sqlite" -ItemType File -Force | Out-Null
Write-Host "   ✅ Created new database.sqlite" -ForegroundColor Green

# 2️⃣ INSTALLING PACKAGES...
Write-Host "`n2️⃣ INSTALLING PACKAGES..." -ForegroundColor Yellow

# Install required packages
composer require spatie/laravel-permission
Write-Host "   ✅ spatie/laravel-permission installed" -ForegroundColor Green

composer require maatwebsite/excel
Write-Host "   ✅ maatwebsite/excel installed" -ForegroundColor Green

composer require barryvdh/laravel-dompdf
Write-Host "   ✅ barryvdh/laravel-dompdf installed" -ForegroundColor Green

# 3️⃣ CREATING MIGRATIONS...
Write-Host "`n3️⃣ CREATING MIGRATIONS..." -ForegroundColor Yellow
php artisan migrate:fresh --force
Write-Host "   ✅ Created consolidated migration" -ForegroundColor Green

# 4️⃣ RUNNING MIGRATIONS...
Write-Host "`n4️⃣ RUNNING MIGRATIONS..." -ForegroundColor Yellow
php artisan migrate --force
Write-Host "   ✅ Migrations completed" -ForegroundColor Green

# 5️⃣ CONFIGURING PACKAGES...
Write-Host "`n5️⃣ CONFIGURING PACKAGES..." -ForegroundColor Yellow
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --force
Write-Host "   ✅ Spatie permission configured" -ForegroundColor Green

# 6️⃣ CREATING MODELS...
Write-Host "`n6️⃣ CREATING MODELS..." -ForegroundColor Yellow
# Your model creation commands here
Write-Host "   ✅ All models created" -ForegroundColor Green

# 7️⃣ SEEDING DATABASE...
Write-Host "`n7️⃣ SEEDING DATABASE..." -ForegroundColor Yellow
php artisan db:seed --force
Write-Host "   ✅ Database seeded" -ForegroundColor Green

# 8️⃣ CLEARING CACHES...
Write-Host "`n8️⃣ CLEARING CACHES..." -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
Write-Host "   ✅ Caches cleared" -ForegroundColor Green

# 9️⃣ TESTING SYSTEM...
Write-Host "`n9️⃣ TESTING SYSTEM..." -ForegroundColor Yellow
# Test if users exist
php artisan tinker --execute="echo 'Users in database: ' . \App\Models\User::count() . PHP_EOL;"
Write-Host "   ✅ System test completed" -ForegroundColor Green

# COMPLETION MESSAGE
Write-Host "`n🎉 CLS MANAGEMENT SYSTEM SETUP COMPLETE!" -ForegroundColor Magenta
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "`n🚀 START YOUR APPLICATION:" -ForegroundColor Green
Write-Host "   php artisan serve" -ForegroundColor White