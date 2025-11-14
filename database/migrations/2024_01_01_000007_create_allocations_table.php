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
