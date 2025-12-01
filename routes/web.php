<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\ChiefController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Chief-specific Controllers
use App\Http\Controllers\Chief\ChiefDashboardController;
use App\Http\Controllers\Chief\ChiefLandController;
use App\Http\Controllers\Chief\ChiefClientController;
use App\Http\Controllers\Chief\ChiefAllocationController;
use App\Http\Controllers\Chief\ChiefDisputeController;

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

// Explicit logout route to ensure it's defined
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

// Seeder route for creating sample lands and clients
Route::get('/run-sample-seeder', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    if (!$user->hasRole('chief')) {
        return "You must be a chief to use this";
    }
    
    echo "<h2>Running Sample Seeder for Chief: " . $user->name . "</h2>";
    
    // Run the seeder
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'SampleChiefLandsSeeder']);
        $output = \Illuminate\Support\Facades\Artisan::output();
        
        echo "<pre>" . $output . "</pre>";
        echo "<br><strong>✅ Seeder completed successfully!</strong><br>";
        echo "<a href='/chief/allocations/create'>Go to Allocation Form</a><br>";
        echo "<a href='/debug-lands-detailed'>Check Lands</a>";
        
    } catch (\Exception $e) {
        echo "<strong>❌ Error running seeder:</strong> " . $e->getMessage() . "<br>";
        echo "Make sure you've created the SampleChiefLandsSeeder first.<br>";
        echo "<a href='/create-sample-lands'>Try creating sample lands manually instead</a>";
    }
})->middleware(['auth', 'role:chief']);

// Smart land creation that handles existing lands
Route::get('/smart-create-lands', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    if (!$user->hasRole('chief')) {
        return "You must be a chief to use this";
    }
    
    echo "<h2>Smart Land Creation for Chief: " . $user->name . "</h2>";
    
    $sampleLands = [
        [
            'plot_number' => 'PLOT-001',
            'location' => 'East District, Block A',
            'size' => 2.5,
            'landmark' => 'Near the main road',
            'land_use' => 'residential'
        ],
        [
            'plot_number' => 'PLOT-002', 
            'location' => 'West District, Block B',
            'size' => 5.0,
            'landmark' => 'Behind the community center',
            'land_use' => 'commercial'
        ],
        [
            'plot_number' => 'PLOT-003',
            'location' => 'North District, Block C',
            'size' => 10.0,
            'landmark' => 'Next to the river',
            'land_use' => 'agricultural'
        ],
        [
            'plot_number' => 'PLOT-004',
            'location' => 'South District, Block D',
            'size' => 3.0,
            'landmark' => 'Near the market',
            'land_use' => 'residential'
        ],
        [
            'plot_number' => 'PLOT-005',
            'location' => 'Central District, Block E',
            'size' => 7.5,
            'landmark' => 'Opposite the school',
            'land_use' => 'commercial'
        ]
    ];
    
    $processedCount = 0;
    $createdCount = 0;
    $updatedCount = 0;
    
    foreach ($sampleLands as $landData) {
        $existingLand = \App\Models\Land::where('plot_number', $landData['plot_number'])->first();
        
        if ($existingLand) {
            // Update existing land
            $existingLand->update([
                'chief_id' => $user->id,
                'ownership_status' => 'vacant',
                'status' => 'vacant',
                'location' => $landData['location'],
                'size' => $landData['size'],
                'landmark' => $landData['landmark'],
                'land_use' => $landData['land_use'],
                'area_acres' => $landData['size'],
                'area_hectares' => $landData['size'] * 0.404686
            ]);
            echo "✓ Updated existing land: " . $landData['plot_number'] . "<br>";
            $updatedCount++;
        } else {
            // Create new land
            try {
                \App\Models\Land::create([
                    'plot_number' => $landData['plot_number'],
                    'location' => $landData['location'],
                    'landmark' => $landData['landmark'],
                    'size' => $landData['size'],
                    'area_acres' => $landData['size'],
                    'area_hectares' => $landData['size'] * 0.404686,
                    'land_use' => $landData['land_use'],
                    'chief_id' => $user->id,
                    'ownership_status' => 'vacant',
                    'status' => 'vacant',
                    'registration_date' => now()->format('Y-m-d'),
                    'description' => 'Sample land for testing allocation system'
                ]);
                
                echo "✓ Created new land: " . $landData['plot_number'] . "<br>";
                $createdCount++;
                
            } catch (\Exception $e) {
                echo "✗ Error creating " . $landData['plot_number'] . ": " . $e->getMessage() . "<br>";
            }
        }
        $processedCount++;
    }
    
    echo "<br><strong>Processed {$processedCount} lands:</strong><br>";
    echo "- Created: {$createdCount} new lands<br>";
    echo "- Updated: {$updatedCount} existing lands<br>";
    echo "<a href='/chief/allocations/create'>Go to Allocation Form</a><br>";
    echo "<a href='/debug-lands-detailed'>Check Lands Again</a>";
})->middleware(['auth', 'role:chief']);

// Smart client creation that handles existing clients - ADDED THIS ROUTE
Route::get('/smart-create-clients', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    if (!$user->hasRole('chief')) {
        return "You must be a chief to use this";
    }
    
    echo "<h2>Smart Client Creation for Chief: " . $user->name . "</h2>";
    
    // Sample clients data with unique phone numbers
    $sampleClients = [
        [
            'full_name' => 'Kwame Mensah',
            'id_number' => 'GHA-001-123456',
            'phone' => '0241234567',
            'email' => 'kwame.mensah@example.com',
            'id_type' => 'ghanacard',
            'address' => '123 Main Street, Accra',
            'date_of_birth' => '1985-05-15',
            'gender' => 'male',
            'occupation' => 'Teacher'
        ],
        [
            'full_name' => 'Ama Serwaa',
            'id_number' => 'GHA-002-234567', 
            'phone' => '0242345678',
            'email' => 'ama.serwaa@example.com',
            'id_type' => 'ghanacard',
            'address' => '456 Oak Avenue, Kumasi',
            'date_of_birth' => '1990-08-22',
            'gender' => 'female',
            'occupation' => 'Nurse'
        ],
        [
            'full_name' => 'Kofi Asare',
            'id_number' => 'GHA-003-345678',
            'phone' => '0243456789',
            'email' => 'kofi.asare@example.com',
            'id_type' => 'ghanacard',
            'address' => '789 Palm Road, Takoradi',
            'date_of_birth' => '1988-12-10',
            'gender' => 'male',
            'occupation' => 'Farmer'
        ],
        [
            'full_name' => 'Esi Boateng',
            'id_number' => 'GHA-004-456789',
            'phone' => '0244567890',
            'email' => 'esi.boateng@example.com',
            'id_type' => 'ghanacard',
            'address' => '321 Cedar Lane, Tamale',
            'date_of_birth' => '1992-03-30',
            'gender' => 'female',
            'occupation' => 'Trader'
        ]
    ];
    
    $processedCount = 0;
    $createdCount = 0;
    $updatedCount = 0;
    
    foreach ($sampleClients as $clientData) {
        // Check if client exists by phone number OR id_number
        $existingClient = \App\Models\Client::where('phone', $clientData['phone'])
                            ->orWhere('id_number', $clientData['id_number'])
                            ->first();
        
        if ($existingClient) {
            // Update existing client to be assigned to current chief
            try {
                $existingClient->update([
                    'chief_id' => $user->id,
                    'full_name' => $clientData['full_name'],
                    'id_number' => $clientData['id_number'],
                    'email' => $clientData['email'],
                    'id_type' => $clientData['id_type'],
                    'address' => $clientData['address'],
                    'date_of_birth' => $clientData['date_of_birth'],
                    'gender' => $clientData['gender'],
                    'occupation' => $clientData['occupation']
                ]);
                echo "✓ Updated existing client: " . $clientData['full_name'] . " (Phone: " . $clientData['phone'] . ")<br>";
                $updatedCount++;
            } catch (\Exception $e) {
                echo "✗ Error updating " . $clientData['full_name'] . ": " . $e->getMessage() . "<br>";
            }
        } else {
            // Create new client
            try {
                \App\Models\Client::create(array_merge($clientData, [
                    'chief_id' => $user->id
                ]));
                
                echo "✓ Created new client: " . $clientData['full_name'] . " - " . $clientData['id_number'] . "<br>";
                $createdCount++;
                
            } catch (\Exception $e) {
                echo "✗ Error creating " . $clientData['full_name'] . ": " . $e->getMessage() . "<br>";
            }
        }
        $processedCount++;
    }
    
    echo "<br><strong>Processed {$processedCount} clients:</strong><br>";
    echo "- Created: {$createdCount} new clients<br>";
    echo "- Updated: {$updatedCount} existing clients<br>";
    echo "<a href='/chief/allocations/create'>Go to Allocation Form</a><br>";
    echo "<a href='/debug-lands-detailed'>Check Lands</a>";
})->middleware(['auth', 'role:chief']);

// Debug routes for testing search functionality
Route::get('/debug-search-clients', function (Illuminate\Http\Request $request) {
    if (!auth()->check()) return "Please log in first";
    
    $user = auth()->user();
    $query = $request->get('q', 'test');
    
    echo "<h2>Debug Client Search</h2>";
    echo "User: " . $user->name . " (ID: " . $user->id . ")<br>";
    echo "Query: " . $query . "<br><br>";
    
    $clients = \App\Models\Client::where('chief_id', $user->id)
        ->where(function ($q) use ($query) {
            $q->where('full_name', 'like', "%{$query}%")
              ->orWhere('id_number', 'like', "%{$query}%")
              ->orWhere('phone', 'like', "%{$query}%");
        })
        ->get();
    
    echo "Found clients: " . $clients->count() . "<br>";
    foreach ($clients as $client) {
        echo "Client: " . $client->full_name . " - " . $client->id_number . " - " . $client->phone . "<br>";
    }
    
    echo "<br><h3>JSON Response:</h3>";
    return response()->json([
        'success' => true,
        'clients' => $clients
    ]);
})->middleware(['auth', 'role:chief']);

Route::get('/debug-search-lands', function (Illuminate\Http\Request $request) {
    if (!auth()->check()) return "Please log in first";
    
    $user = auth()->user();
    $query = $request->get('q', 'test');
    
    echo "<h2>Debug Land Search</h2>";
    echo "User: " . $user->name . " (ID: " . $user->id . ")<br>";
    echo "Query: " . $query . "<br><br>";
    
    $lands = \App\Models\Land::where('chief_id', $user->id)
        ->where(function($q) {
            $q->where('ownership_status', 'vacant')
              ->orWhere('ownership_status', 'available')
              ->orWhere('status', 'vacant')
              ->orWhere('status', 'available')
              ->orWhereNull('ownership_status')
              ->orWhereNull('status');
        })
        ->where(function($q) use ($query) {
            $q->where('plot_number', 'like', "%{$query}%")
              ->orWhere('location', 'like', "%{$query}%")
              ->orWhere('size', 'like', "%{$query}%");
        })
        ->get();
    
    echo "Found lands: " . $lands->count() . "<br>";
    foreach ($lands as $land) {
        echo "Land: Plot " . $land->plot_number . " - " . $land->location . " - " . $land->size . " acres<br>";
        echo "Status: " . ($land->ownership_status ?? 'NULL') . " / " . ($land->status ?? 'NULL') . "<br><br>";
    }
    
    echo "<br><h3>JSON Response:</h3>";
    return response()->json([
        'success' => true,
        'lands' => $lands
    ]);
})->middleware(['auth', 'role:chief']);

// Create sample lands for testing
Route::get('/create-sample-lands', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    if (!$user->hasRole('chief')) {
        return "You must be a chief to use this";
    }
    
    echo "<h2>Creating Sample Lands for Chief: " . $user->name . "</h2>";
    
    // Sample lands data
    $sampleLands = [
        [
            'plot_number' => 'PLOT-001',
            'location' => 'East District, Block A',
            'size' => 2.5,
            'landmark' => 'Near the main road',
            'land_use' => 'residential'
        ],
        [
            'plot_number' => 'PLOT-002', 
            'location' => 'West District, Block B',
            'size' => 5.0,
            'landmark' => 'Behind the community center',
            'land_use' => 'commercial'
        ],
        [
            'plot_number' => 'PLOT-003',
            'location' => 'North District, Block C',
            'size' => 10.0,
            'landmark' => 'Next to the river',
            'land_use' => 'agricultural'
        ],
        [
            'plot_number' => 'PLOT-004',
            'location' => 'South District, Block D',
            'size' => 3.0,
            'landmark' => 'Near the market',
            'land_use' => 'residential'
        ],
        [
            'plot_number' => 'PLOT-005',
            'location' => 'Central District, Block E',
            'size' => 7.5,
            'landmark' => 'Opposite the school',
            'land_use' => 'commercial'
        ]
    ];
    
    $createdCount = 0;
    
    foreach ($sampleLands as $landData) {
        // Check if plot number already exists
        $existingLand = \App\Models\Land::where('plot_number', $landData['plot_number'])->first();
        
        if (!$existingLand) {
            try {
                \App\Models\Land::create([
                    'plot_number' => $landData['plot_number'],
                    'location' => $landData['location'],
                    'landmark' => $landData['landmark'],
                    'size' => $landData['size'],
                    'area_acres' => $landData['size'],
                    'area_hectares' => $landData['size'] * 0.404686,
                    'land_use' => $landData['land_use'],
                    'chief_id' => $user->id,
                    'ownership_status' => 'vacant',
                    'status' => 'vacant',
                    'registration_date' => now()->format('Y-m-d'),
                    'description' => 'Sample land for testing allocation system'
                ]);
                
                echo "✓ Created land: " . $landData['plot_number'] . " - " . $landData['location'] . "<br>";
                $createdCount++;
                
            } catch (\Exception $e) {
                echo "✗ Error creating " . $landData['plot_number'] . ": " . $e->getMessage() . "<br>";
            }
        } else {
            echo "ⓘ Land already exists: " . $landData['plot_number'] . "<br>";
        }
    }
    
    echo "<br><strong>Created " . $createdCount . " new lands for chief " . $user->name . "</strong><br>";
    echo "<a href='/chief/allocations/create'>Go to Allocation Form</a><br>";
    echo "<a href='/debug-lands-detailed'>Check Lands Again</a>";
})->middleware(['auth', 'role:chief']);

// Create sample clients for testing
Route::get('/create-sample-clients', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    if (!$user->hasRole('chief')) {
        return "You must be a chief to use this";
    }
    
    echo "<h2>Creating Sample Clients for Chief: " . $user->name . "</h2>";
    
    // Sample clients data
    $sampleClients = [
        [
            'full_name' => 'Kwame Mensah',
            'id_number' => 'GHA-001-123456',
            'phone' => '0241234567',
            'email' => 'kwame.mensah@example.com',
            'id_type' => 'ghanacard',
            'address' => '123 Main Street, Accra',
            'date_of_birth' => '1985-05-15',
            'gender' => 'male',
            'occupation' => 'Teacher'
        ],
        [
            'full_name' => 'Ama Serwaa',
            'id_number' => 'GHA-002-234567', 
            'phone' => '0242345678',
            'email' => 'ama.serwaa@example.com',
            'id_type' => 'ghanacard',
            'address' => '456 Oak Avenue, Kumasi',
            'date_of_birth' => '1990-08-22',
            'gender' => 'female',
            'occupation' => 'Nurse'
        ],
        [
            'full_name' => 'Kofi Asare',
            'id_number' => 'GHA-003-345678',
            'phone' => '0243456789',
            'email' => 'kofi.asare@example.com',
            'id_type' => 'ghanacard',
            'address' => '789 Palm Road, Takoradi',
            'date_of_birth' => '1988-12-10',
            'gender' => 'male',
            'occupation' => 'Farmer'
        ],
        [
            'full_name' => 'Esi Boateng',
            'id_number' => 'GHA-004-456789',
            'phone' => '0244567890',
            'email' => 'esi.boateng@example.com',
            'id_type' => 'ghanacard',
            'address' => '321 Cedar Lane, Tamale',
            'date_of_birth' => '1992-03-30',
            'gender' => 'female',
            'occupation' => 'Trader'
        ]
    ];
    
    $createdCount = 0;
    
    foreach ($sampleClients as $clientData) {
        // Check if client already exists
        $existingClient = \App\Models\Client::where('id_number', $clientData['id_number'])->first();
        
        if (!$existingClient) {
            try {
                \App\Models\Client::create(array_merge($clientData, [
                    'chief_id' => $user->id
                ]));
                
                echo "✓ Created client: " . $clientData['full_name'] . " - " . $clientData['id_number'] . "<br>";
                $createdCount++;
                
            } catch (\Exception $e) {
                echo "✗ Error creating " . $clientData['full_name'] . ": " . $e->getMessage() . "<br>";
            }
        } else {
            echo "ⓘ Client already exists: " . $clientData['full_name'] . "<br>";
        }
    }
    
    echo "<br><strong>Created " . $createdCount . " new clients for chief " . $user->name . "</strong><br>";
    echo "<a href='/chief/allocations/create'>Go to Allocation Form</a><br>";
    echo "<a href='/debug-lands-detailed'>Check Lands</a>";
})->middleware(['auth', 'role:chief']);

// Assign all lands to current chief
Route::get('/assign-all-lands-to-me', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    if (!$user->hasRole('chief')) {
        return "You must be a chief to use this";
    }
    
    echo "<h2>Assigning All Lands to Chief: " . $user->name . "</h2>";
    
    // Get all lands that don't have chief_id set or have different chief_id
    $lands = \App\Models\Land::whereNull('chief_id')
                ->orWhere('chief_id', '!=', $user->id)
                ->get();
    
    echo "Lands found to assign: " . $lands->count() . "<br><br>";
    
    $assignedCount = 0;
    foreach ($lands as $land) {
        echo "Processing land: " . $land->plot_number . "<br>";
        echo "Current chief_id: " . ($land->chief_id ?? 'NULL') . "<br>";
        
        $land->update([
            'chief_id' => $user->id,
            'ownership_status' => 'vacant',
            'status' => 'vacant'
        ]);
        
        echo "→ Assigned to chief " . $user->id . "<br>";
        $assignedCount++;
        echo "---<br>";
    }
    
    echo "<br><strong>Assigned " . $assignedCount . " lands to chief " . $user->name . "</strong><br>";
    echo "<a href='/chief/allocations/create'>Go to Allocation Form</a><br>";
    echo "<a href='/debug-lands-detailed'>Check Lands Again</a>";
})->middleware(['auth', 'role:chief']);

// Emergency fix - assign specific lands to current chief - UPDATED
Route::get('/emergency-fix-lands', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    if (!$user->hasRole('chief')) {
        return "You must be a chief to use this fix";
    }
    
    echo "<h2>Emergency Lands Fix</h2>";
    echo "Chief: " . $user->name . " (ID: " . $user->id . ")<br><br>";
    
    // Get ONLY lands that don't have a chief assigned or belong to current chief
    $availableLands = \App\Models\Land::whereNull('chief_id')->get();
    
    echo "Available lands (no chief assigned): " . $availableLands->count() . "<br><br>";
    
    $assignedCount = 0;
    foreach ($availableLands as $land) {
        echo "Processing land: " . $land->plot_number . "<br>";
        echo "Current chief_id: " . ($land->chief_id ?? 'NULL') . "<br>";
        
        // Assign this land to current chief
        $land->update([
            'chief_id' => $user->id,
            'ownership_status' => 'vacant',
            'status' => 'vacant'
        ]);
        
        echo "→ Assigned to chief " . $user->id . " and set status to 'vacant'<br>";
        $assignedCount++;
        echo "---<br>";
    }
    
    echo "<br><strong>Emergency fix completed! Assigned " . $assignedCount . " lands to chief " . $user->name . ".</strong><br>";
    echo "<a href='/chief/allocations/create'>Go to Allocation Form</a><br>";
    echo "<a href='/debug-lands-detailed'>Check Lands Again</a>";
});

// Enhanced debug route to check lands - UPDATED
Route::get('/debug-lands-detailed', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    echo "<h2>Detailed Lands Debug Information</h2>";
    echo "User ID: " . $user->id . "<br>";
    echo "User Name: " . $user->name . "<br>";
    echo "User Roles: " . $user->getRoleNames()->implode(', ') . "<br><br>";
    
    // Get ONLY lands assigned to this chief
    $chiefLands = \App\Models\Land::where('chief_id', $user->id)->get();
    
    echo "<h3>Lands Assigned to This Chief Only:</h3>";
    echo "Total lands: " . $chiefLands->count() . "<br><br>";
    
    foreach ($chiefLands as $land) {
        echo "<strong>Land ID:</strong> " . $land->id . "<br>";
        echo "<strong>Plot Number:</strong> " . $land->plot_number . "<br>";
        echo "<strong>Location:</strong> " . $land->location . "<br>";
        echo "<strong>Size:</strong> " . $land->size . " acres<br>";
        echo "<strong>Ownership Status:</strong> " . ($land->ownership_status ?? '<span style="color: red">NULL</span>') . "<br>";
        echo "<strong>Status:</strong> " . ($land->status ?? '<span style="color: red">NULL</span>') . "<br>";
        echo "<strong>Chief ID:</strong> " . $land->chief_id . "<br>";
        
        // Check if land would be considered available
        $isAvailable = in_array($land->ownership_status, ['vacant', 'available', null]) || 
                      in_array($land->status, ['vacant', 'available', null]);
        echo "<strong>Would be available for allocation:</strong> " . ($isAvailable ? '<span style="color: green">YES</span>' : '<span style="color: red">NO</span>') . "<br>";
        echo "---<br><br>";
    }
    
    // Check what the controller is actually querying
    echo "<h3>Controller Query Debug:</h3>";
    
    $queryLands = \App\Models\Land::where('chief_id', $user->id)
        ->where(function($query) {
            $query->where('ownership_status', 'vacant')
                  ->orWhere('ownership_status', 'available')
                  ->orWhere('status', 'vacant')
                  ->orWhere('status', 'available')
                  ->orWhereNull('ownership_status')
                  ->orWhereNull('status');
        })
        ->get();
    
    echo "Available lands for allocation: " . $queryLands->count() . "<br>";
    
    foreach ($queryLands as $land) {
        echo "✓ " . $land->plot_number . " - " . $land->location . "<br>";
    }
    
    echo "<br><a href='/chief/allocations/create'>Back to Allocation Form</a>";
});

// Manual land status fix - UPDATED
Route::get('/fix-land-status', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    if (!$user->hasRole('chief')) {
        return "You must be a chief to use this fix";
    }
    
    echo "<h2>Manual Land Status Fix</h2>";
    echo "Chief: " . $user->name . " (ID: " . $user->id . ")<br><br>";
    
    // Get only chief's lands
    $lands = \App\Models\Land::where('chief_id', $user->id)->get();
    
    echo "Total lands for this chief: " . $lands->count() . "<br><br>";
    
    $updatedCount = 0;
    foreach ($lands as $land) {
        echo "Processing land: " . $land->plot_number . "<br>";
        echo "Current ownership_status: " . ($land->ownership_status ?? 'NULL') . "<br>";
        echo "Current status: " . ($land->status ?? 'NULL') . "<br>";
        
        // If both status fields are NULL or empty, set them to vacant
        if (empty($land->ownership_status) && empty($land->status)) {
            $land->update([
                'ownership_status' => 'vacant',
                'status' => 'vacant'
            ]);
            echo "→ Updated both status fields to 'vacant'<br>";
            $updatedCount++;
        }
        // If ownership_status is NULL but status has value
        elseif (empty($land->ownership_status) && !empty($land->status)) {
            $land->update([
                'ownership_status' => $land->status
            ]);
            echo "→ Set ownership_status to: " . $land->status . "<br>";
            $updatedCount++;
        }
        // If status is NULL but ownership_status has value
        elseif (!empty($land->ownership_status) && empty($land->status)) {
            $land->update([
                'status' => $land->ownership_status
            ]);
            echo "→ Set status to: " . $land->ownership_status . "<br>";
            $updatedCount++;
        }
        // If land has some other status that's not vacant/available
        elseif (!in_array($land->ownership_status, ['vacant', 'available']) || 
                !in_array($land->status, ['vacant', 'available'])) {
            $land->update([
                'ownership_status' => 'vacant',
                'status' => 'vacant'
            ]);
            echo "→ Reset status to 'vacant'<br>";
            $updatedCount++;
        } else {
            echo "→ No changes needed<br>";
        }
        echo "---<br>";
    }
    
    echo "<br><strong>Fix completed! Updated " . $updatedCount . " lands.</strong><br>";
    echo "<a href='/chief/allocations/create'>Go to Allocation Form</a><br>";
    echo "<a href='/debug-lands-detailed'>Check Lands Again</a>";
});

// Quick fix route to assign lands to current chief - UPDATED
Route::get('/fix-my-lands', function () {
    if (!auth()->check()) {
        return "Please log in first";
    }
    
    $user = auth()->user();
    
    if (!$user->hasRole('chief')) {
        return "You must be a chief to use this fix";
    }
    
    echo "<h2>Fixing Lands Assignment</h2>";
    echo "Assigning lands to chief: " . $user->name . " (ID: " . $user->id . ")<br><br>";
    
    // Get lands that are not assigned to any chief
    $unassignedLands = \App\Models\Land::whereNull('chief_id')->get();
    
    echo "Unassigned lands found: " . $unassignedLands->count() . "<br>";
    
    foreach ($unassignedLands as $land) {
        $land->update([
            'chief_id' => $user->id,
            'ownership_status' => 'vacant',
            'status' => 'vacant'
        ]);
        echo "✓ Assigned land: " . $land->plot_number . " to chief<br>";
    }
    
    // Also update lands with wrong status (only for this chief)
    $landsWithWrongStatus = \App\Models\Land::where('chief_id', $user->id)
        ->where(function($query) {
            $query->whereNotIn('ownership_status', ['vacant', 'available'])
                  ->orWhereNull('ownership_status');
        })
        ->get();
    
    echo "<br>Lands with wrong status: " . $landsWithWrongStatus->count() . "<br>";
    
    foreach ($landsWithWrongStatus as $land) {
        $land->update([
            'ownership_status' => 'vacant',
            'status' => 'vacant'
        ]);
        echo "✓ Fixed status for land: " . $land->plot_number . "<br>";
    }
    
    echo "<br><strong>Fix completed!</strong><br>";
    echo "<a href='/chief/allocations/create'>Go to Allocation Form</a><br>";
    echo "<a href='/debug-lands-detailed'>Check Lands Again</a>";
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // IMPORTANT FIX: Move the default dashboard routes AFTER role-specific routes
    // This prevents chiefs from being redirected to the wrong dashboard
    
    // Chief-specific routes (Restricted to Chief role only) - MOVED TO TOP
    Route::middleware(['role:chief'])->prefix('chief')->name('chief.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ChiefDashboardController::class, 'index'])->name('dashboard');
        
        // Lands Management - Chief can only manage their own lands
        Route::prefix('lands')->name('lands.')->group(function () {
            Route::get('/', [ChiefLandController::class, 'index'])->name('index');
            Route::get('/create', [ChiefLandController::class, 'create'])->name('create');
            Route::post('/', [ChiefLandController::class, 'store'])->name('store');
            Route::get('/{land}', [ChiefLandController::class, 'show'])->name('show');
            Route::get('/{land}/edit', [ChiefLandController::class, 'edit'])->name('edit');
            Route::put('/{land}', [ChiefLandController::class, 'update'])->name('update');
            Route::get('/{land}/delete', [ChiefLandController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{land}', [ChiefLandController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // ADDED: Search route for lands
            Route::get('/search', [ChiefLandController::class, 'search'])->name('search');
            
            // ADDED: Debug route for available lands
            Route::get('/debug-available-lands', [ChiefLandController::class, 'debugAvailableLands'])->name('debug-available-lands');
            
            // ADDED: Get available lands route
            Route::get('/available-lands', [ChiefLandController::class, 'getAvailableLands'])->name('available-lands');
            
            // Land-specific actions
            Route::get('/{land}/documents', [ChiefLandController::class, 'documents'])->name('documents');
            Route::post('/{land}/documents', [ChiefLandController::class, 'storeDocument'])->name('store-document');
            
            // Statistics - Only for this chief's lands
            Route::get('/stats', [ChiefLandController::class, 'getLandStats'])->name('stats');
        });

        // Clients Management - Chief can only manage their own clients
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [ChiefClientController::class, 'index'])->name('index');
            Route::get('/create', [ChiefClientController::class, 'create'])->name('create');
            Route::post('/', [ChiefClientController::class, 'store'])->name('store');
            Route::get('/{client}', [ChiefClientController::class, 'show'])->name('show');
            Route::get('/{client}/edit', [ChiefClientController::class, 'edit'])->name('edit');
            Route::put('/{client}', [ChiefClientController::class, 'update'])->name('update');
            Route::get('/{client}/delete', [ChiefClientController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{client}', [ChiefClientController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // ADDED: Search route for clients
            Route::get('/search', [ChiefClientController::class, 'search'])->name('search');
            
            // Client-specific actions
            Route::get('/{client}/allocations', [ChiefClientController::class, 'allocations'])->name('allocations');
            Route::get('/{client}/documents', [ChiefClientController::class, 'documents'])->name('documents');
        });

        // Allocations Management - Chief can only manage their own allocations
        Route::prefix('allocations')->name('allocations.')->group(function () {
            Route::get('/', [ChiefAllocationController::class, 'index'])->name('index');
            Route::get('/create', [ChiefAllocationController::class, 'create'])->name('create');
            Route::post('/', [ChiefAllocationController::class, 'store'])->name('store');
            Route::get('/{allocation}', [ChiefAllocationController::class, 'show'])->name('show');
            Route::get('/{allocation}/edit', [ChiefAllocationController::class, 'edit'])->name('edit');
            Route::put('/{allocation}', [ChiefAllocationController::class, 'update'])->name('update');
            Route::get('/{allocation}/delete', [ChiefAllocationController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{allocation}', [ChiefAllocationController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // Allocation-specific actions
            Route::get('/{allocation}/allocation-letter', [ChiefAllocationController::class, 'generateAllocationLetter'])->name('allocation-letter');
            Route::get('/{allocation}/certificate', [ChiefAllocationController::class, 'generateCertificate'])->name('certificate');
            
            // Statistics - Only for this chief's allocations
            Route::get('/stats', [ChiefAllocationController::class, 'getAllocationStats'])->name('stats');
        });

        // Disputes Management - Chief can manage disputes for their lands
        Route::prefix('disputes')->name('disputes.')->group(function () {
            Route::get('/', [ChiefDisputeController::class, 'index'])->name('index');
            Route::get('/create', [ChiefDisputeController::class, 'create'])->name('create');
            Route::post('/', [ChiefDisputeController::class, 'store'])->name('store');
            Route::get('/{dispute}', [ChiefDisputeController::class, 'show'])->name('show');
            Route::get('/{dispute}/edit', [ChiefDisputeController::class, 'edit'])->name('edit');
            Route::put('/{dispute}', [ChiefDisputeController::class, 'update'])->name('update');
            Route::get('/{dispute}/delete', [ChiefDisputeController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{dispute}', [ChiefDisputeController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // Dispute resolution
            Route::post('/{dispute}/resolve', [ChiefDisputeController::class, 'resolve'])->name('resolve');
            Route::post('/{dispute}/close', [ChiefDisputeController::class, 'close'])->name('close');
            Route::post('/{dispute}/reopen', [ChiefDisputeController::class, 'reopen'])->name('reopen');
            
            // Statistics - Only for this chief's disputes
            Route::get('/stats', [ChiefDisputeController::class, 'getDisputeStats'])->name('stats');
        });

        // Chief reports access (only their data)
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'chiefIndex'])->name('index');
            Route::get('/lands', [ReportController::class, 'chiefLandReport'])->name('lands');
            Route::get('/allocations', [ReportController::class, 'chiefAllocationReport'])->name('allocations');
            Route::get('/clients', [ReportController::class, 'chiefClientReport'])->name('clients');
            Route::get('/disputes', [ReportController::class, 'chiefDisputeReport'])->name('disputes');
        });
    });

    // FIX: Default dashboard routes - Moved after role-specific routes
    // This ensures chiefs get their specific dashboard
    Route::get('/', function () {
        $user = auth()->user();
        
        if ($user->hasRole('chief')) {
            return redirect()->route('chief.dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    })->name('home');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/dashboard/quick-stats', [DashboardController::class, 'getQuickStats'])->name('dashboard.quick-stats');

    // Profile Routes - Accessible to all authenticated users
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/password', [ProfileController::class, 'editPassword'])->name('password.edit');
        Route::get('/settings', function () {
            return view('profile.settings');
        })->name('settings');
    });

    // Staff/Admin Management Routes (Restricted to staff and admin)
    Route::middleware(['role:admin|staff'])->group(function () {
        // Lands Management
        Route::prefix('lands')->name('lands.')->group(function () {
            // Standard CRUD routes
            Route::get('/', [LandController::class, 'index'])->name('index');
            Route::get('/create', [LandController::class, 'create'])->name('create');
            Route::post('/', [LandController::class, 'store'])->name('store');
            Route::get('/{land}', [LandController::class, 'show'])->name('show');
            Route::get('/{land}/edit', [LandController::class, 'edit'])->name('edit');
            Route::put('/{land}', [LandController::class, 'update'])->name('update');
            Route::get('/{land}/delete', [LandController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{land}', [LandController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // Export/Import
            Route::get('/export', [LandController::class, 'export'])->name('export');
            Route::post('/import', [LandController::class, 'import'])->name('import');
            Route::get('/download-template', [LandController::class, 'downloadImportTemplate'])->name('download-template');
            
            // GIS Routes
            Route::get('/map', [LandController::class, 'map'])->name('map');
            Route::get('/api/geojson', [LandController::class, 'getLandGeoJson'])->name('geojson');
            
            // Bulk actions
            Route::post('/bulk-actions', [LandController::class, 'bulkActions'])->name('bulk-actions');
            Route::post('/bulk-delete', [LandController::class, 'bulkDelete'])->name('bulk-delete');
            
            // Land-specific actions
            Route::get('/{land}/documents', [LandController::class, 'documents'])->name('documents');
            Route::post('/{land}/documents', [LandController::class, 'storeDocument'])->name('store-document');
            Route::post('/{land}/verify', [LandController::class, 'verify'])->name('verify');
            
            // Statistics
            Route::get('/stats/land-stats', [LandController::class, 'getLandStats'])->name('stats');
        });

        // Clients Management
        Route::prefix('clients')->name('clients.')->group(function () {
            // Standard CRUD routes
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::get('/create', [ClientController::class, 'create'])->name('create');
            Route::post('/', [ClientController::class, 'store'])->name('store');
            Route::get('/{client}', [ClientController::class, 'show'])->name('show');
            Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('/{client}', [ClientController::class, 'update'])->name('update');
            Route::get('/{client}/delete', [ClientController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // Export/Import
            Route::get('/export', [ClientController::class, 'export'])->name('export');
            Route::post('/import', [ClientController::class, 'import'])->name('import');
            Route::get('/import-template', [ClientController::class, 'downloadImportTemplate'])->name('import-template');
            
            // Bulk actions
            Route::post('/bulk-actions', [ClientController::class, 'bulkActions'])->name('bulk-actions');
            Route::post('/bulk-delete', [ClientController::class, 'bulkDelete'])->name('bulk-delete');
            
            // Client-specific actions
            Route::get('/{client}/allocations', [ClientController::class, 'allocations'])->name('allocations');
            Route::get('/{client}/documents', [ClientController::class, 'documents'])->name('documents');
        });

        // Allocations Management
        Route::prefix('allocations')->name('allocations.')->group(function () {
            // Standard CRUD routes
            Route::get('/', [AllocationController::class, 'index'])->name('index');
            Route::get('/create', [AllocationController::class, 'create'])->name('create');
            Route::post('/', [AllocationController::class, 'store'])->name('store');
            Route::get('/{allocation}', [AllocationController::class, 'show'])->name('show');
            Route::get('/{allocation}/edit', [AllocationController::class, 'edit'])->name('edit');
            Route::put('/{allocation}', [AllocationController::class, 'update'])->name('update');
            Route::get('/{allocation}/delete', [AllocationController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{allocation}', [AllocationController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // Export/Import
            Route::get('/export', [AllocationController::class, 'export'])->name('export');
            Route::post('/import', [AllocationController::class, 'import'])->name('import');
            Route::get('/import-template', [AllocationController::class, 'downloadImportTemplate'])->name('import-template');
            
            // Bulk actions
            Route::post('/bulk-approve', [AllocationController::class, 'bulkApprove'])->name('bulk-approve');
            Route::post('/bulk-reject', [AllocationController::class, 'bulkReject'])->name('bulk-reject');
            Route::post('/bulk-delete', [AllocationController::class, 'bulkDelete'])->name('bulk-delete');
            
            // Approval Workflow
            Route::post('/{allocation}/approve', [AllocationController::class, 'approve'])->name('approve');
            Route::post('/{allocation}/reject', [AllocationController::class, 'reject'])->name('reject');
            Route::post('/{allocation}/pending', [AllocationController::class, 'markPending'])->name('pending');
            Route::get('/{allocation}/allocation-letter', [AllocationController::class, 'generateAllocationLetter'])->name('allocation-letter');
            Route::get('/{allocation}/certificate', [AllocationController::class, 'generateCertificate'])->name('certificate');
            
            // Statistics
            Route::get('/stats/allocation-stats', [AllocationController::class, 'getAllocationStats'])->name('stats');
        });

        // Chiefs Management
        Route::prefix('chiefs')->name('chiefs.')->group(function () {
            // Standard CRUD routes
            Route::get('/', [ChiefController::class, 'index'])->name('index');
            Route::get('/create', [ChiefController::class, 'create'])->name('create');
            Route::post('/', [ChiefController::class, 'store'])->name('store');
            Route::get('/{chief}', [ChiefController::class, 'show'])->name('show');
            Route::get('/{chief}/edit', [ChiefController::class, 'edit'])->name('edit');
            Route::put('/{chief}', [ChiefController::class, 'update'])->name('update');
            Route::get('/{chief}/delete', [ChiefController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{chief}', [ChiefController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // Export/Import
            Route::get('/export', [ChiefController::class, 'export'])->name('export');
            Route::post('/import', [ChiefController::class, 'import'])->name('import');
            Route::get('/import-template', [ChiefController::class, 'downloadImportTemplate'])->name('import-template');
            
            // Bulk actions
            Route::post('/bulk-actions', [ChiefController::class, 'bulkActions'])->name('bulk-actions');
            
            // Chief-specific actions
            Route::patch('/{chief}/toggle-status', [ChiefController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/{chief}/lands', [ChiefController::class, 'lands'])->name('lands');
            Route::get('/{chief}/allocations', [ChiefController::class, 'allocations'])->name('allocations');
            
            // Statistics
            Route::get('/stats/chief-stats', [ChiefController::class, 'getChiefStats'])->name('stats');
        });

        // Documents Management
        Route::prefix('documents')->name('documents.')->group(function () {
            // Standard CRUD routes
            Route::get('/', [DocumentController::class, 'index'])->name('index');
            Route::post('/', [DocumentController::class, 'store'])->name('store');
            Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
            Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
            Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
            Route::get('/{document}/delete', [DocumentController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // Bulk actions
            Route::post('/bulk-actions', [DocumentController::class, 'bulkActions'])->name('bulk-actions');
            Route::post('/bulk-delete', [DocumentController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk-verify', [DocumentController::class, 'bulkVerify'])->name('bulk-verify');
            
            // Document-specific actions
            Route::get('/{document}/preview', [DocumentController::class, 'preview'])->name('preview');
            Route::post('/{document}/verify', [DocumentController::class, 'verify'])->name('verify');
            Route::post('/{document}/reject', [DocumentController::class, 'reject'])->name('reject');
            Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            // Main reports page
            Route::get('/', [ReportController::class, 'index'])->name('index');
            
            // Reports Export Route - Support both GET and POST methods
            Route::match(['get', 'post'], '/export', [ReportController::class, 'export'])->name('export');
            
            // View Reports (GET routes for HTML views)
            Route::get('/lands', [ReportController::class, 'landReport'])->name('lands');
            Route::get('/allocations', [ReportController::class, 'allocationReport'])->name('allocations');
            Route::get('/clients', [ReportController::class, 'clientReport'])->name('clients');
            Route::get('/chiefs', [ReportController::class, 'chiefReport'])->name('chiefs');
            Route::get('/system', [ReportController::class, 'systemReport'])->name('system');
            
            // Generate/Download Reports (POST routes for exports with form data)
            Route::post('/lands/generate', [ReportController::class, 'generateLandReport'])->name('lands.generate');
            Route::post('/allocations/generate', [ReportController::class, 'generateAllocationReport'])->name('allocations.generate');
            Route::post('/clients/generate', [ReportController::class, 'generateClientReport'])->name('clients.generate');
            Route::post('/chiefs/generate', [ReportController::class, 'generateChiefReport'])->name('chiefs.generate');
            Route::post('/system/generate', [ReportController::class, 'generateSystemReport'])->name('system.generate');
            
            // Quick Export Routes (GET routes for direct exports without filters)
            Route::get('/lands/export', [ReportController::class, 'exportLandReport'])->name('lands.export');
            Route::get('/allocations/export', [ReportController::class, 'exportAllocationReport'])->name('allocations.export');
            Route::get('/clients/export', [ReportController::class, 'exportClientReport'])->name('clients.export');
            Route::get('/chiefs/export', [ReportController::class, 'exportChiefReport'])->name('chiefs.export');
            Route::get('/system/export', [ReportController::class, 'exportSystemReport'])->name('system.export');
            
            // Report management
            Route::get('/{report}/download', [ReportController::class, 'download'])->name('download');
            Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
        });
    });

    // Admin Management (Restricted to Admin role only)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            // Standard CRUD routes
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::get('/{user}/delete', [UserController::class, 'delete'])->name('delete'); // Delete confirmation page
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy'); // Actual deletion
            
            // Bulk actions
            Route::post('/bulk-actions', [UserController::class, 'bulkActions'])->name('bulk-actions');
            Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk-activate', [UserController::class, 'bulkActivate'])->name('bulk-activate');
            Route::post('/bulk-deactivate', [UserController::class, 'bulkDeactivate'])->name('bulk-deactivate');
            
            // User-specific actions
            Route::post('/{user}/activate', [UserController::class, 'activate'])->name('activate');
            Route::post('/{user}/deactivate', [UserController::class, 'deactivate'])->name('deactivate');
            Route::post('/{user}/impersonate', [UserController::class, 'impersonate'])->name('impersonate');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        });
        
        // System Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', function () {
                return view('admin.settings.index');
            })->name('index');
            
            Route::get('/general', function () {
                return view('admin.settings.general');
            })->name('general');
            
            Route::get('/system', function () {
                return view('admin.settings.system');
            })->name('system');
            
            Route::get('/backup', function () {
                return view('admin.settings.backup');
            })->name('backup');
            
            // Settings API routes
            Route::post('/general', [UserController::class, 'updateGeneralSettings'])->name('update.general');
            Route::post('/system', [UserController::class, 'updateSystemSettings'])->name('update.system');
            Route::post('/backup', [UserController::class, 'createBackup'])->name('backup.create');
        });
        
        // System Logs
        Route::get('/logs', function () {
            return view('admin.logs.index');
        })->name('logs');
        
        // System Health
        Route::get('/health', function () {
            return view('admin.health.index');
        })->name('health');
    });
});

// Public API Routes for GIS (No auth required for public maps)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/lands/geojson', [LandController::class, 'getLandGeoJson'])->name('lands.geojson');
    Route::get('/chiefs/{chief}/geojson', [ChiefController::class, 'getChiefGeoJson'])->name('chiefs.geojson');
    
    // Public statistics (read-only)
    Route::get('/public/stats', [DashboardController::class, 'getPublicStats'])->name('public.stats');
    
    // Land statistics
    Route::get('/land-stats', [LandController::class, 'getLandStats'])->name('land-stats');
    
    // Allocation statistics
    Route::get('/allocation-stats', [AllocationController::class, 'getAllocationStats'])->name('allocation-stats');
    
    // Chief statistics
    Route::get('/chief-stats', [ChiefController::class, 'getChiefStats'])->name('chief-stats');
});

// Health check route (for monitoring)
Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
});

// Fallback Route
Route::fallback(function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->hasRole('chief')) {
            return redirect()->route('chief.dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }
    return redirect()->route('login');
});