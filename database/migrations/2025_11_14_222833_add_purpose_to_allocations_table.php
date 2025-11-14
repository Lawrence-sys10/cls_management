<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('allocations', function (Blueprint $table) {
            if (!Schema::hasColumn('allocations', 'purpose')) {
                $table->text('purpose')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('allocations', function (Blueprint $table) {
            $table->dropColumn(['purpose']);
        });
    }
};