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
