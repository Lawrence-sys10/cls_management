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
