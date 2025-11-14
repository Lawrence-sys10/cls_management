<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add missing columns to lands table
        Schema::table('lands', function (Blueprint $table) {
            if (!Schema::hasColumn('lands', 'status')) {
                $table->string('status')->default('available')->after('location');
            }
            if (!Schema::hasColumn('lands', 'size')) {
                $table->decimal('size', 10, 2)->nullable()->after('status');
            }
            if (!Schema::hasColumn('lands', 'ownership_status')) {
                $table->string('ownership_status')->default('vacant')->after('size');
            }
            if (!Schema::hasColumn('lands', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('coordinates');
            }
            if (!Schema::hasColumn('lands', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });

        // Add missing columns to staff table
        Schema::table('staff', function (Blueprint $table) {
            if (!Schema::hasColumn('staff', 'employee_id')) {
                $table->string('employee_id')->unique()->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('staff', 'assigned_area')) {
                $table->string('assigned_area')->nullable()->after('phone');
            }
        });

        // Add missing columns to allocations table if needed
        Schema::table('allocations', function (Blueprint $table) {
            if (!Schema::hasColumn('allocations', 'status')) {
                $table->string('status')->default('pending')->after('purpose');
            }
        });
    }

    public function down()
    {
        Schema::table('lands', function (Blueprint $table) {
            $table->dropColumn(['status', 'size', 'ownership_status', 'latitude', 'longitude']);
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'assigned_area']);
        });

        Schema::table('allocations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};