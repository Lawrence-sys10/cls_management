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

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Lands Management
    Route::prefix('lands')->name('lands.')->group(function () {
        Route::get('/', [LandController::class, 'index'])->name('index');
        Route::get('/create', [LandController::class, 'create'])->name('create');
        Route::post('/', [LandController::class, 'store'])->name('store');
        
        // Export/Import - MOVED BEFORE parameterized routes
        Route::get('/export', [LandController::class, 'export'])->name('export');
        Route::post('/import', [LandController::class, 'import'])->name('import');
        
        // GIS Routes - MOVED BEFORE parameterized routes
        Route::get('/api/geojson', [LandController::class, 'getLandGeoJson'])->name('api.geojson');
        
        // Parameterized routes LAST
        Route::get('/{land}', [LandController::class, 'show'])->name('show');
        Route::get('/{land}/edit', [LandController::class, 'edit'])->name('edit');
        Route::put('/{land}', [LandController::class, 'update'])->name('update');
        Route::delete('/{land}', [LandController::class, 'destroy'])->name('destroy');
    });

    // Clients Management - FIXED ROUTE ORDER
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        
        // Export/Import - MOVED BEFORE parameterized routes
        Route::get('/export', [ClientController::class, 'export'])->name('export');
        Route::post('/import', [ClientController::class, 'import'])->name('import');
        
        // Parameterized routes LAST
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
    });

    // Allocations Management - FIXED ROUTE ORDER
    Route::prefix('allocations')->name('allocations.')->group(function () {
        Route::get('/', [AllocationController::class, 'index'])->name('index');
        Route::get('/create', [AllocationController::class, 'create'])->name('create');
        Route::post('/', [AllocationController::class, 'store'])->name('store');
        
        // Approval Workflow - MOVED BEFORE parameterized routes
        Route::post('/{allocation}/approve', [AllocationController::class, 'approve'])->name('approve');
        Route::post('/{allocation}/reject', [AllocationController::class, 'reject'])->name('reject');
        Route::get('/{allocation}/allocation-letter', [AllocationController::class, 'generateAllocationLetter'])->name('allocation-letter');
        
        // Parameterized routes LAST
        Route::get('/{allocation}', [AllocationController::class, 'show'])->name('show');
        Route::get('/{allocation}/edit', [AllocationController::class, 'edit'])->name('edit');
        Route::put('/{allocation}', [AllocationController::class, 'update'])->name('update');
        Route::delete('/{allocation}', [AllocationController::class, 'destroy'])->name('destroy');
    });

    // Chiefs Management - FIXED ROUTE ORDER
    Route::prefix('chiefs')->name('chiefs.')->group(function () {
        Route::get('/', [ChiefController::class, 'index'])->name('index');
        Route::get('/create', [ChiefController::class, 'create'])->name('create');
        Route::post('/', [ChiefController::class, 'store'])->name('store');
        
        // GIS Routes - MOVED BEFORE parameterized routes
        Route::get('/{chief}/geojson', [ChiefController::class, 'getChiefGeoJson'])->name('geojson');
        
        // Parameterized routes LAST
        Route::get('/{chief}', [ChiefController::class, 'show'])->name('show');
        Route::get('/{chief}/edit', [ChiefController::class, 'edit'])->name('edit');
        Route::put('/{chief}', [ChiefController::class, 'update'])->name('update');
        Route::delete('/{chief}', [ChiefController::class, 'destroy'])->name('destroy');
    });

    // Documents Management
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        
        // Parameterized routes
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::post('/{document}/verify', [DocumentController::class, 'verify'])->name('verify');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/lands', [ReportController::class, 'landReport'])->name('lands');
        Route::post('/lands', [ReportController::class, 'generateLandReport'])->name('lands.generate');
        Route::get('/allocations', [ReportController::class, 'allocationReport'])->name('allocations');
        Route::post('/allocations', [ReportController::class, 'generateAllocationReport'])->name('allocations.generate');
        
        // Parameterized route LAST
        Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
    });

    // Admin Management (Restricted to Admin only)
    Route::middleware(['can:admin'])->prefix('admin')->name('admin.')->group(function () {
        // User Management - FIXED ROUTE ORDER
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            
            // Parameterized routes LAST
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
        
        // System Settings
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
    });
});

// Public API Routes for GIS
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/lands/geojson', [LandController::class, 'getLandGeoJson'])->name('lands.geojson');
    Route::get('/chiefs/{chief}/geojson', [ChiefController::class, 'getChiefGeoJson'])->name('chiefs.geojson');
});

// Fallback Route
Route::fallback(function () {
    return redirect()->route('dashboard');
});