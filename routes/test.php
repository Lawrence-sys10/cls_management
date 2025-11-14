<?php

Route::get('/test-system', function () {
    $checks = [];
    
    // Check database connections
    try {
        \DB::connection()->getPdo();
        $checks['database'] = ['status' => '✅', 'message' => 'Database connected'];
    } catch (\Exception $e) {
        $checks['database'] = ['status' => '❌', 'message' => 'Database failed: ' . $e->getMessage()];
    }
    
    // Check models
    try {
        $userCount = \App\Models\User::count();
        $checks['users'] = ['status' => '✅', 'message' => "Users: $userCount"];
    } catch (\Exception $e) {
        $checks['users'] = ['status' => '❌', 'message' => 'Users table error'];
    }
    
    try {
        $landCount = \App\Models\Land::count();
        $checks['lands'] = ['status' => '✅', 'message' => "Lands: $landCount"];
    } catch (\Exception $e) {
        $checks['lands'] = ['status' => '❌', 'message' => 'Lands table error'];
    }
    
    try {
        $clientCount = \App\Models\Client::count();
        $checks['clients'] = ['status' => '✅', 'message' => "Clients: $clientCount"];
    } catch (\Exception $e) {
        $checks['clients'] = ['status' => '❌', 'message' => 'Clients table error'];
    }
    
    // Check authentication
    try {
        $adminUser = \App\Models\User::where('email', 'admin@cls.com')->first();
        if ($adminUser) {
            $checks['auth'] = ['status' => '✅', 'message' => 'Admin user exists'];
        } else {
            $checks['auth'] = ['status' => '❌', 'message' => 'Admin user not found'];
        }
    } catch (\Exception $e) {
        $checks['auth'] = ['status' => '❌', 'message' => 'Auth check failed'];
    }
    
    return response()->json([
        'system' => 'CLS Management System',
        'status' => 'operational',
        'checks' => $checks,
        'timestamp' => now()->toDateTimeString()
    ]);
});
