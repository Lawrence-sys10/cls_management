<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if table exists and has correct structure
        if (Schema::hasTable('notifications')) {
            // Add any missing columns if needed
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'read_at')) {
                    $table->timestamp('read_at')->nullable()->after('data');
                }
            });
        }
    }

    public function down()
    {
        // Nothing to rollback
    }
};