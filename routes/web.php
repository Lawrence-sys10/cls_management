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

    // Lands Management - Accessible to staff and admin
    Route::prefix('lands')->name('lands.')->middleware(['role:admin|staff'])->group(function () {
        // Standard CRUD routes
        Route::get('/', [LandController::class, 'index'])->name('index');
        Route::get('/create', [LandController::class, 'create'])->name('create');
        Route::post('/', [LandController::class, 'store'])->name('store');
        Route::get('/{land}', [LandController::class, 'show'])->name('show');
        Route::get('/{land}/edit', [LandController::class, 'edit'])->name('edit');
        Route::put('/{land}', [LandController::class, 'update'])->name('update');
        Route::delete('/{land}', [LandController::class, 'destroy'])->name('destroy');
        
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

    // Clients Management - Accessible to staff and admin
    Route::prefix('clients')->name('clients.')->middleware(['role:admin|staff'])->group(function () {
        // Standard CRUD routes
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        
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

    // Allocations Management - Accessible to staff and admin
    Route::prefix('allocations')->name('allocations.')->middleware(['role:admin|staff'])->group(function () {
        // Standard CRUD routes
        Route::get('/', [AllocationController::class, 'index'])->name('index');
        Route::get('/create', [AllocationController::class, 'create'])->name('create');
        Route::post('/', [AllocationController::class, 'store'])->name('store');
        Route::get('/{allocation}', [AllocationController::class, 'show'])->name('show');
        Route::get('/{allocation}/edit', [AllocationController::class, 'edit'])->name('edit');
        Route::put('/{allocation}', [AllocationController::class, 'update'])->name('update');
        Route::delete('/{allocation}', [AllocationController::class, 'destroy'])->name('destroy');
        
        // Export/Import
        Route::get('/export', [AllocationController::class, 'export'])->name('export');
        Route::post('/import', [AllocationController::class, 'import'])->name('import');
        
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

    // Chiefs Management - Accessible to staff and admin
    Route::prefix('chiefs')->name('chiefs.')->middleware(['role:admin|staff'])->group(function () {
        // Standard CRUD routes
        Route::get('/', [ChiefController::class, 'index'])->name('index');
        Route::get('/create', [ChiefController::class, 'create'])->name('create');
        Route::post('/', [ChiefController::class, 'store'])->name('store');
        Route::get('/{chief}', [ChiefController::class, 'show'])->name('show');
        Route::get('/{chief}/edit', [ChiefController::class, 'edit'])->name('edit');
        Route::put('/{chief}', [ChiefController::class, 'update'])->name('update');
        Route::delete('/{chief}', [ChiefController::class, 'destroy'])->name('destroy');
        
        // Export/Import
        Route::get('/export', [ChiefController::class, 'export'])->name('export');
        
        // Bulk actions
        Route::post('/bulk-actions', [ChiefController::class, 'bulkActions'])->name('bulk-actions');
        
        // Chief-specific actions
        Route::patch('/{chief}/toggle-status', [ChiefController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{chief}/lands', [ChiefController::class, 'lands'])->name('lands');
        Route::get('/{chief}/allocations', [ChiefController::class, 'allocations'])->name('allocations');
        
        // Statistics
        Route::get('/stats/chief-stats', [ChiefController::class, 'getChiefStats'])->name('stats');
    });

    // Documents Management - Accessible to staff and admin
    Route::prefix('documents')->name('documents.')->middleware(['role:admin|staff'])->group(function () {
        // Standard CRUD routes
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
        
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

    // Reports - Accessible to staff and admin
    Route::prefix('reports')->name('reports.')->middleware(['role:admin|staff'])->group(function () {
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
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            
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

    // Chief-specific routes (Restricted to Chief role only)
    Route::middleware(['role:chief'])->prefix('chief')->name('chief.')->group(function () {
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
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});