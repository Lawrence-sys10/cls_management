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
