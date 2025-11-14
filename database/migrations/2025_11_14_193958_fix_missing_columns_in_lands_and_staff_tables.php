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
            if (!Schema::hasColumn('lands', 'size')) {
                $table->decimal('size', 10, 2)->nullable()->after('location');
            }
            if (!Schema::hasColumn('lands', 'ownership_status')) {
                $table->string('ownership_status')->default('vacant')->after('status');
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
            if (!Schema::hasColumn('staff', 'assigned_area')) {
                $table->string('assigned_area')->nullable()->after('phone');
            }
        });
    }

    public function down()
    {
        Schema::table('lands', function (Blueprint $table) {
            $table->dropColumn(['size', 'ownership_status', 'latitude', 'longitude']);
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('assigned_area');
        });
    }
};