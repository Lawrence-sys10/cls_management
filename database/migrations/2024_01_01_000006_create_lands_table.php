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
