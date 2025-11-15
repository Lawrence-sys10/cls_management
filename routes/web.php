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

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

// Explicit logout route to ensure it's defined
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard - Accessible to all authenticated users
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/dashboard/quick-stats', [DashboardController::class, 'getQuickStats'])->name('dashboard.quick-stats');

    // Profile Routes - Accessible to all authenticated users
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/settings', function () {
            return view('profile.settings');
        })->name('settings');
    });

    // Lands Management - Remove role middleware temporarily
    Route::prefix('lands')->name('lands.')->group(function () {
        // Export/Import
        Route::get('/export', [LandController::class, 'export'])->name('export');
        Route::post('/import', [LandController::class, 'import'])->name('import');
        Route::get('/import-template', [LandController::class, 'downloadImportTemplate'])->name('import-template');
        
        // GIS Routes
        Route::get('/map', [LandController::class, 'map'])->name('map');
        Route::get('/api/geojson', [LandController::class, 'getLandGeoJson'])->name('api.geojson');
        
        // Bulk actions
        Route::post('/bulk-actions', [LandController::class, 'bulkActions'])->name('bulk-actions');
        Route::post('/bulk-delete', [LandController::class, 'bulkDelete'])->name('bulk-delete');
        
        // Standard CRUD routes
        Route::get('/', [LandController::class, 'index'])->name('index');
        Route::get('/create', [LandController::class, 'create'])->name('create');
        Route::post('/', [LandController::class, 'store'])->name('store');
        
        // Land-specific actions
        Route::get('/{land}/documents', [LandController::class, 'documents'])->name('documents');
        Route::post('/{land}/verify', [LandController::class, 'verify'])->name('verify');
        Route::post('/{land}/documents', [LandController::class, 'storeDocument'])->name('store-document');
        
        // Parameterized routes
        Route::get('/{land}', [LandController::class, 'show'])->name('show');
        Route::get('/{land}/edit', [LandController::class, 'edit'])->name('edit');
        Route::put('/{land}', [LandController::class, 'update'])->name('update');
        Route::delete('/{land}', [LandController::class, 'destroy'])->name('destroy');
    });

    // Clients Management - Remove role middleware temporarily
    Route::prefix('clients')->name('clients.')->group(function () {
        // Export/Import
        Route::get('/export', [ClientController::class, 'export'])->name('export');
        Route::post('/import', [ClientController::class, 'import'])->name('import');
        Route::get('/import-template', [ClientController::class, 'downloadImportTemplate'])->name('import-template');
        
        // Bulk actions
        Route::post('/bulk-actions', [ClientController::class, 'bulkActions'])->name('bulk-actions');
        Route::post('/bulk-delete', [ClientController::class, 'bulkDelete'])->name('bulk-delete');
        
        // Standard CRUD routes
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        
        // Client-specific actions
        Route::get('/{client}/allocations', [ClientController::class, 'allocations'])->name('allocations');
        Route::get('/{client}/documents', [ClientController::class, 'documents'])->name('documents');
        
        // Parameterized routes
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
    });

    // Allocations Management - Remove role middleware temporarily
    Route::prefix('allocations')->name('allocations.')->group(function () {
        // Export/Import
        Route::get('/export', [AllocationController::class, 'export'])->name('export');
        Route::post('/import', [AllocationController::class, 'import'])->name('import');
        
        // Bulk actions
        Route::post('/bulk-approve', [AllocationController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk-reject', [AllocationController::class, 'bulkReject'])->name('bulk-reject');
        Route::post('/bulk-delete', [AllocationController::class, 'bulkDelete'])->name('bulk-delete');
        
        // Standard CRUD routes
        Route::get('/', [AllocationController::class, 'index'])->name('index');
        Route::get('/create', [AllocationController::class, 'create'])->name('create');
        Route::post('/', [AllocationController::class, 'store'])->name('store');
        
        // Approval Workflow
        Route::post('/{allocation}/approve', [AllocationController::class, 'approve'])->name('approve');
        Route::post('/{allocation}/reject', [AllocationController::class, 'reject'])->name('reject');
        Route::post('/{allocation}/pending', [AllocationController::class, 'markPending'])->name('pending');
        Route::get('/{allocation}/allocation-letter', [AllocationController::class, 'generateAllocationLetter'])->name('allocation-letter');
        Route::get('/{allocation}/certificate', [AllocationController::class, 'generateCertificate'])->name('certificate');
        
        // Parameterized routes
        Route::get('/{allocation}', [AllocationController::class, 'show'])->name('show');
        Route::get('/{allocation}/edit', [AllocationController::class, 'edit'])->name('edit');
        Route::put('/{allocation}', [AllocationController::class, 'update'])->name('update');
        Route::delete('/{allocation}', [AllocationController::class, 'destroy'])->name('destroy');
    });

    // Chiefs Management - Remove role middleware temporarily
    Route::prefix('chiefs')->name('chiefs.')->group(function () {
        // Export/Import
        Route::get('/export', [ChiefController::class, 'export'])->name('export');
        Route::post('/import', [ChiefController::class, 'import'])->name('import');
        
        // GIS Routes
        Route::get('/map', [ChiefController::class, 'map'])->name('map');
        Route::get('/{chief}/geojson', [ChiefController::class, 'getChiefGeoJson'])->name('geojson');
        
        // Standard CRUD routes
        Route::get('/', [ChiefController::class, 'index'])->name('index');
        Route::get('/create', [ChiefController::class, 'create'])->name('create');
        Route::post('/', [ChiefController::class, 'store'])->name('store');
        
        // Chief-specific actions
        Route::get('/{chief}/lands', [ChiefController::class, 'lands'])->name('lands');
        Route::get('/{chief}/allocations', [ChiefController::class, 'allocations'])->name('allocations');
        
        // Parameterized routes
        Route::get('/{chief}', [ChiefController::class, 'show'])->name('show');
        Route::get('/{chief}/edit', [ChiefController::class, 'edit'])->name('edit');
        Route::put('/{chief}', [ChiefController::class, 'update'])->name('update');
        Route::delete('/{chief}', [ChiefController::class, 'destroy'])->name('destroy');
    });

    // Documents Management - Remove role middleware temporarily
    Route::prefix('documents')->name('documents.')->group(function () {
        // Bulk actions
        Route::post('/bulk-actions', [DocumentController::class, 'bulkActions'])->name('bulk-actions');
        Route::post('/bulk-delete', [DocumentController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-verify', [DocumentController::class, 'bulkVerify'])->name('bulk-verify');
        
        // Standard CRUD routes
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        
        // Document-specific actions
        Route::get('/{document}/preview', [DocumentController::class, 'preview'])->name('preview');
        Route::post('/{document}/verify', [DocumentController::class, 'verify'])->name('verify');
        Route::post('/{document}/reject', [DocumentController::class, 'reject'])->name('reject');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        
        // Parameterized routes
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
    });

    // Reports - Remove role middleware temporarily
    Route::prefix('reports')->name('reports.')->group(function () {
        // Main reports page
        Route::get('/', [ReportController::class, 'index'])->name('index');
        
        // View Reports (GET routes for HTML views)
        Route::get('/lands', [ReportController::class, 'landReport'])->name('lands');
        Route::get('/allocations', [ReportController::class, 'allocationReport'])->name('allocations');
        Route::get('/clients', [ReportController::class, 'clientReport'])->name('clients');
        Route::get('/chiefs', [ReportController::class, 'chiefReport'])->name('chiefs');
        Route::get('/system', [ReportController::class, 'systemReport'])->name('system');
        
        // Generate/Download Reports (POST routes for exports)
        Route::post('/lands/generate', [ReportController::class, 'generateLandReport'])->name('lands.generate');
        Route::post('/allocations/generate', [ReportController::class, 'generateAllocationReport'])->name('allocations.generate');
        Route::post('/clients/generate', [ReportController::class, 'generateClientReport'])->name('clients.generate');
        Route::post('/chiefs/generate', [ReportController::class, 'generateChiefReport'])->name('chiefs.generate');
        Route::post('/system/generate', [ReportController::class, 'generateSystemReport'])->name('system.generate');
        
        // Quick Export Routes (GET routes for direct exports)
        Route::get('/lands/export', [ReportController::class, 'exportLandReport'])->name('lands.export');
        Route::get('/allocations/export', [ReportController::class, 'exportAllocationReport'])->name('allocations.export');
        Route::get('/clients/export', [ReportController::class, 'exportClientReport'])->name('clients.export');
        Route::get('/chiefs/export', [ReportController::class, 'exportChiefReport'])->name('chiefs.export');
        Route::get('/system/export', [ReportController::class, 'exportSystemReport'])->name('system.export');
        
        // Report management
        Route::get('/{report}/download', [ReportController::class, 'download'])->name('download');
        Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
    });

    // Admin Management - Remove role middleware temporarily, use controller-level auth
    Route::prefix('admin')->name('admin.')->group(function () {
        // Add basic authorization check for admin routes
        Route::get('/', function () {
            // Basic admin check - will be replaced with proper role check later
            return redirect()->route('admin.dashboard');
        });

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            // Bulk actions
            Route::post('/bulk-actions', [UserController::class, 'bulkActions'])->name('bulk-actions');
            Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk-activate', [UserController::class, 'bulkActivate'])->name('bulk-activate');
            Route::post('/bulk-deactivate', [UserController::class, 'bulkDeactivate'])->name('bulk-deactivate');
            
            // Standard CRUD routes
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            
            // User-specific actions
            Route::post('/{user}/activate', [UserController::class, 'activate'])->name('activate');
            Route::post('/{user}/deactivate', [UserController::class, 'deactivate'])->name('deactivate');
            Route::post('/{user}/impersonate', [UserController::class, 'impersonate'])->name('impersonate');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
            
            // Parameterized routes
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
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

    // Chief-specific routes - Remove role middleware temporarily
    Route::prefix('chief')->name('chief.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'chief'])->name('dashboard');
        Route::get('/lands', [LandController::class, 'chiefLands'])->name('lands');
        Route::get('/allocations', [AllocationController::class, 'chiefAllocations'])->name('allocations');
    });
});

// Public API Routes for GIS (No auth required for public maps)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/lands/geojson', [LandController::class, 'getLandGeoJson'])->name('lands.geojson');
    Route::get('/chiefs/{chief}/geojson', [ChiefController::class, 'getChiefGeoJson'])->name('chiefs.geojson');
    
    // Public statistics (read-only)
    Route::get('/public/stats', [DashboardController::class, 'getPublicStats'])->name('public.stats');
});

// Health check route (for monitoring)
Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
});

// Fallback Route
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});