<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lands', function (Blueprint $table) {
            // Add registration_date if it doesn't exist
            if (!Schema::hasColumn('lands', 'registration_date')) {
                $table->date('registration_date')->nullable();
            }
            
            // Add landmark if it doesn't exist
            if (!Schema::hasColumn('lands', 'landmark')) {
                $table->string('landmark')->nullable();
            }
            
            // Add coordinates if it doesn't exist
            if (!Schema::hasColumn('lands', 'coordinates')) {
                $table->string('coordinates')->nullable();
            }
            
            // Add description if it doesn't exist
            if (!Schema::hasColumn('lands', 'description')) {
                $table->text('description')->nullable();
            }
            
            // Add ownership_status if it doesn't exist
            if (!Schema::hasColumn('lands', 'ownership_status')) {
                $table->enum('ownership_status', ['vacant', 'allocated', 'under_dispute'])->default('vacant');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lands', function (Blueprint $table) {
            // Only remove columns if they were added by this migration
            $columns = ['registration_date', 'landmark', 'coordinates', 'description', 'ownership_status'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('lands', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};