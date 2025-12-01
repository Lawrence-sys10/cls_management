<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Chief;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all users with chief role that don't have a chief record
        $chiefUsers = User::role('chief')
            ->whereDoesntHave('chief')
            ->get();
        
        foreach ($chiefUsers as $user) {
            Chief::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'jurisdiction' => 'Default Region', // You can modify this as needed
                'phone' => $user->phone ?? 'default-phone-' . $user->id,
                'email' => $user->email,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // If there are any chief records without users, create user accounts for them
        $chiefsWithoutUsers = Chief::whereNull('user_id')->get();
        
        foreach ($chiefsWithoutUsers as $chief) {
            $user = User::create([
                'name' => $chief->name,
                'email' => $chief->email ?? strtolower(str_replace(' ', '.', $chief->name)) . '@chief.local',
                'phone' => $chief->phone,
                'password' => bcrypt('temp_password'), // User should reset this
                'email_verified_at' => now(),
            ]);
            
            // Assign chief role
            $user->assignRole('chief');
            
            // Update chief with user_id
            $chief->update(['user_id' => $user->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a data migration, so rolling back is complex
        // We'll just leave the data as is
    }
};