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

        // Add purpose column to allocations table (after allocation_date)
        Schema::table('allocations', function (Blueprint $table) {
            if (!Schema::hasColumn('allocations', 'purpose')) {
                $table->text('purpose')->nullable()->after('allocation_date');
            }
        });
    }

    public function down()
    {
        Schema::table('lands', function (Blueprint $table) {
            $columns = ['status', 'size', 'ownership_status', 'latitude', 'longitude'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('lands', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('staff', function (Blueprint $table) {
            $columns = ['employee_id', 'assigned_area'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('staff', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('allocations', function (Blueprint $table) {
            if (Schema::hasColumn('allocations', 'purpose')) {
                $table->dropColumn('purpose');
            }
        });
    }
};