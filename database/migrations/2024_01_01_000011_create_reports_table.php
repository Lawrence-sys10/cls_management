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
