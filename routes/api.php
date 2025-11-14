<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AllocationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API endpoints for GIS integration
Route::prefix('v1')->group(function () {
    Route::get('/lands/geojson', [LandController::class, 'getLandGeoJson']);
    Route::get('/chiefs/{chief}/geojson', [ChiefController::class, 'getChiefGeoJson']);
});

// Protected API endpoints (require authentication)
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('lands', LandController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('allocations', AllocationController::class);
    Route::apiResource('chiefs', ChiefController::class);
    
    // Additional API endpoints
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::post('/documents/upload', [DocumentController::class, 'store']);
});
