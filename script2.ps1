# Step 2: Generate Laravel Migrations for CLS Management System
# Save this as generate-migrations.ps1 and run from project root

Write-Host "📁 Generating Laravel Migrations for CLS Management System..." -ForegroundColor Cyan

# Create migrations directory if it doesn't exist
$migrationsPath = "database/migrations"
if (!(Test-Path $migrationsPath)) {
    New-Item -ItemType Directory -Path $migrationsPath -Force
}

# 1. Create users table migration
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->enum('user_type', ['admin', 'chief', 'staff', 'registrar', 'viewer'])->default('staff');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_type', 'is_active']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000001_create_users_table.php" -Encoding UTF8
Write-Host "✅ Created users table migration" -ForegroundColor Green

# 2. Create roles and permissions tables (Spatie)
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('guard_name')->default('web');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('guard_name')->default('web');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000002_create_permission_tables.php" -Encoding UTF8
Write-Host "✅ Created permission tables migration" -ForegroundColor Green

# 3. Create chiefs table migration
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chiefs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('jurisdiction');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->json('area_boundaries')->nullable(); // GeoJSON for chief jurisdiction
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['jurisdiction', 'is_active']);
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chiefs');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000003_create_chiefs_table.php" -Encoding UTF8
Write-Host "✅ Created chiefs table migration" -ForegroundColor Green

# 4. Create staff table migration
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('department');
            $table->string('phone');
            $table->string('assigned_area');
            $table->string('employee_id')->unique();
            $table->date('date_joined');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['department', 'assigned_area']);
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000004_create_staff_table.php" -Encoding UTF8
Write-Host "✅ Created staff table migration" -ForegroundColor Green

# 5. Create clients table migration
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->enum('id_type', ['ghanacard', 'passport', 'drivers_license', 'voters_id'])->default('ghanacard');
            $table->string('id_number');
            $table->text('address');
            $table->string('occupation');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('emergency_contact')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['full_name', 'phone']);
            $table->index('id_number');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000005_create_clients_table.php" -Encoding UTF8
Write-Host "✅ Created clients table migration" -ForegroundColor Green

# 6. Create lands table migration (FIXED - removed spatial index)
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->string('plot_number')->unique();
            $table->decimal('area_acres', 10, 2);
            $table->decimal('area_hectares', 10, 2);
            $table->string('location');
            $table->text('boundary_description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 10, 8)->nullable();
            $table->json('polygon_boundaries')->nullable(); // GeoJSON for land boundaries
            $table->enum('ownership_status', ['vacant', 'allocated', 'under_dispute', 'reserved'])->default('vacant');
            $table->foreignId('chief_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 15, 2)->default(0);
            $table->enum('land_use', ['residential', 'commercial', 'agricultural', 'industrial', 'mixed'])->default('residential');
            $table->string('soil_type')->nullable();
            $table->string('topography')->nullable();
            $table->json('access_roads')->nullable();
            $table->json('utilities')->nullable(); // Array of available utilities
            $table->date('registration_date');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            // Removed spatial index as it's not supported by SQLite
            // $table->spatialIndex('polygon_boundaries');
            $table->index(['plot_number', 'ownership_status']);
            $table->index(['latitude', 'longitude']);
            $table->index(['chief_id', 'is_verified']);
            $table->index('registration_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000006_create_lands_table.php" -Encoding UTF8
Write-Host "✅ Created lands table migration (fixed spatial index)" -ForegroundColor Green

# 7. Create allocations table migration
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('chief_id')->constrained()->onDelete('cascade');
            $table->foreignId('processed_by')->constrained('staff')->onDelete('cascade');
            $table->dateTime('allocation_date');
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'finalized'])->default('pending');
            $table->dateTime('chief_approval_date')->nullable();
            $table->dateTime('registrar_approval_date')->nullable();
            $table->string('allocation_letter_path')->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->decimal('payment_amount', 15, 2)->default(0);
            $table->dateTime('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['land_id', 'client_id']); // Prevent duplicate allocations
            $table->index(['approval_status', 'is_finalized']);
            $table->index(['allocation_date', 'chief_id']);
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000007_create_allocations_table.php" -Encoding UTF8
Write-Host "✅ Created allocations table migration" -ForegroundColor Green

# 8. Create documents table migration
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('land_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('allocation_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type'); // MIME type
            $table->unsignedBigInteger('file_size');
            $table->enum('document_type', [
                'id_card', 
                'passport_photo', 
                'survey_plan', 
                'site_plan', 
                'title_deed', 
                'allocation_letter', 
                'supporting_letter', 
                'other'
            ])->default('other');
            $table->text('description')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            
            $table->index(['client_id', 'document_type']);
            $table->index(['land_id', 'document_type']);
            $table->index(['allocation_id', 'document_type']);
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000008_create_documents_table.php" -Encoding UTF8
Write-Host "✅ Created documents table migration" -ForegroundColor Green

# 9. Create activity_logs table migration
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // created, updated, deleted, etc.
            $table->string('model_type'); // App\Models\Land, etc.
            $table->unsignedBigInteger('model_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('url');
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->timestamp('logged_at')->useCurrent();
            
            $table->index(['user_id', 'logged_at']);
            $table->index(['model_type', 'model_id']);
            $table->index('action');
            $table->index('logged_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000009_create_activity_logs_table.php" -Encoding UTF8
Write-Host "✅ Created activity_logs table migration" -ForegroundColor Green

# 10. Create notifications table migration
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->enum('sent_via', ['email', 'sms', 'both'])->default('email');
            $table->text('message');
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index('read_at');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000010_create_notifications_table.php" -Encoding UTF8
Write-Host "✅ Created notifications table migration" -ForegroundColor Green

# 11. Create reports table migration
@'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['lands', 'allocations', 'clients', 'payments', 'chiefs']);
            $table->json('date_range')->nullable(); // {start: date, end: date}
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->string('file_path');
            $table->json('parameters')->nullable(); // Search/filter parameters used
            $table->boolean('is_scheduled')->default(false);
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['type', 'generated_at']);
            $table->index('generated_by');
            $table->index('is_scheduled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
'@ | Out-File -FilePath "$migrationsPath/2024_01_01_000011_create_reports_table.php" -Encoding UTF8
Write-Host "✅ Created reports table migration" -ForegroundColor Green

Write-Host "`n🎉 ALL MIGRATIONS GENERATED SUCCESSFULLY!" -ForegroundColor Green
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "📊 Total migrations created: 11" -ForegroundColor White
Write-Host "📁 Files created in database/migrations/:" -ForegroundColor White
Write-Host "   2024_01_01_000001_create_users_table.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000002_create_permission_tables.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000003_create_chiefs_table.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000004_create_staff_table.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000005_create_clients_table.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000006_create_lands_table.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000007_create_allocations_table.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000008_create_documents_table.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000009_create_activity_logs_table.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000010_create_notifications_table.php" -ForegroundColor Gray
Write-Host "   2024_01_01_000011_create_reports_table.php" -ForegroundColor Gray
Write-Host "`n🚀 Next Steps:" -ForegroundColor Yellow
Write-Host "   1. Run: php artisan migrate" -ForegroundColor White
Write-Host "   2. Create seeders for initial data" -ForegroundColor White
Write-Host "   3. Generate controllers and routes" -ForegroundColor White