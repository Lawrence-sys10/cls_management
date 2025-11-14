# Step 4: Generate Laravel Routes and Form Requests for CLS Management System
# Save this as generate-routes-requests.ps1 and run from project root

# Create Form Requests directory
$requestsPath = "app/Http/Requests"
if (!(Test-Path $requestsPath)) {
    New-Item -ItemType Directory -Path $requestsPath -Force
}

# 1. Store Land Request
@'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'plot_number' => 'required|string|max:50|unique:lands,plot_number',
            'area_acres' => 'required|numeric|min:0.01',
            'area_hectares' => 'required|numeric|min:0.01',
            'location' => 'required|string|max:255',
            'boundary_description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'polygon_boundaries' => 'nullable|json',
            'ownership_status' => 'required|in:vacant,allocated,under_dispute,reserved',
            'chief_id' => 'required|exists:chiefs,id',
            'price' => 'nullable|numeric|min:0',
            'land_use' => 'required|in:residential,commercial,agricultural,industrial,mixed',
            'soil_type' => 'nullable|string|max:100',
            'topography' => 'nullable|string|max:100',
            'access_roads' => 'nullable|array',
            'utilities' => 'nullable|array',
            'registration_date' => 'required|date',
            'is_verified' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'plot_number.unique' => 'This plot number already exists in the system.',
            'chief_id.exists' => 'The selected chief does not exist.',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
        ];
    }
}
'@ | Out-File -FilePath "$requestsPath/StoreLandRequest.php" -Encoding UTF8

# 2. Update Land Request
@'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'plot_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('lands')->ignore($this->route('land'))
            ],
            'area_acres' => 'required|numeric|min:0.01',
            'area_hectares' => 'required|numeric|min:0.01',
            'location' => 'required|string|max:255',
            'boundary_description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'polygon_boundaries' => 'nullable|json',
            'ownership_status' => 'required|in:vacant,allocated,under_dispute,reserved',
            'chief_id' => 'required|exists:chiefs,id',
            'price' => 'nullable|numeric|min:0',
            'land_use' => 'required|in:residential,commercial,agricultural,industrial,mixed',
            'soil_type' => 'nullable|string|max:100',
            'topography' => 'nullable|string|max:100',
            'access_roads' => 'nullable|array',
            'utilities' => 'nullable|array',
            'registration_date' => 'required|date',
            'is_verified' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'plot_number.unique' => 'This plot number already exists in the system.',
            'chief_id.exists' => 'The selected chief does not exist.',
        ];
    }
}
'@ | Out-File -FilePath "$requestsPath/UpdateLandRequest.php" -Encoding UTF8

# 3. Store Client Request
@'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:clients,phone',
            'email' => 'nullable|email|unique:clients,email',
            'id_type' => 'required|in:ghanacard,passport,drivers_license,voters_id',
            'id_number' => 'required|string|max:50|unique:clients,id_number',
            'address' => 'required|string|max:500',
            'occupation' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact' => 'nullable|string|max:15',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email address is already registered.',
            'id_number.unique' => 'This ID number is already registered.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
        ];
    }
}
'@ | Out-File -FilePath "$requestsPath/StoreClientRequest.php" -Encoding UTF8

# 4. Update Client Request
@'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('clients')->ignore($this->route('client'))
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('clients')->ignore($this->route('client'))
            ],
            'id_type' => 'required|in:ghanacard,passport,drivers_license,voters_id',
            'id_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('clients')->ignore($this->route('client'))
            ],
            'address' => 'required|string|max:500',
            'occupation' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact' => 'nullable|string|max:15',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email address is already registered.',
            'id_number.unique' => 'This ID number is already registered.',
        ];
    }
}
'@ | Out-File -FilePath "$requestsPath/UpdateClientRequest.php" -Encoding UTF8

# 5. Store Chief Request
@'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChiefRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'jurisdiction' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:chiefs,phone',
            'email' => 'nullable|email|unique:chiefs,email',
            'area_boundaries' => 'nullable|json',
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'This phone number is already registered for another chief.',
            'email.unique' => 'This email address is already registered.',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }
}
'@ | Out-File -FilePath "$requestsPath/StoreChiefRequest.php" -Encoding UTF8

# 6. Update Chief Request
@'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChiefRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'jurisdiction' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('chiefs')->ignore($this->route('chief'))
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('chiefs')->ignore($this->route('chief'))
            ],
            'area_boundaries' => 'nullable|json',
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'This phone number is already registered for another chief.',
            'email.unique' => 'This email address is already registered.',
        ];
    }
}
'@ | Out-File -FilePath "$requestsPath/UpdateChiefRequest.php" -Encoding UTF8

# 7. Store Allocation Request
@'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'land_id' => [
                'required',
                'exists:lands,id',
                Rule::unique('allocations')->where(function ($query) {
                    return $query->where('approval_status', '!=', 'rejected');
                })
            ],
            'client_id' => 'required|exists:clients,id',
            'chief_id' => 'required|exists:chiefs,id',
            'processed_by' => 'required|exists:staff,id',
            'allocation_date' => 'required|date',
            'approval_status' => 'required|in:pending,approved,rejected,finalized',
            'payment_status' => 'required|in:pending,partial,paid,overdue',
            'payment_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'land_id.unique' => 'This land plot is already allocated to another client.',
            'land_id.exists' => 'The selected land plot does not exist.',
            'client_id.exists' => 'The selected client does not exist.',
            'chief_id.exists' => 'The selected chief does not exist.',
            'processed_by.exists' => 'The selected staff member does not exist.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->land_id) {
                $land = \App\Models\Land::find($this->land_id);
                if ($land && $land->ownership_status !== 'vacant') {
                    $validator->errors()->add('land_id', 'This land plot is not available for allocation.');
                }
            }
        });
    }
}
'@ | Out-File -FilePath "$requestsPath/StoreAllocationRequest.php" -Encoding UTF8

# 8. Update Allocation Request
@'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'staff']);
    }

    public function rules(): array
    {
        $allocation = $this->route('allocation');
        
        return [
            'land_id' => [
                'required',
                'exists:lands,id',
                Rule::unique('allocations')->ignore($allocation->id)->where(function ($query) {
                    return $query->where('approval_status', '!=', 'rejected');
                })
            ],
            'client_id' => 'required|exists:clients,id',
            'chief_id' => 'required|exists:chiefs,id',
            'processed_by' => 'required|exists:staff,id',
            'allocation_date' => 'required|date',
            'approval_status' => 'required|in:pending,approved,rejected,finalized',
            'payment_status' => 'required|in:pending,partial,paid,overdue',
            'payment_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'land_id.unique' => 'This land plot is already allocated to another client.',
            'land_id.exists' => 'The selected land plot does not exist.',
        ];
    }
}
'@ | Out-File -FilePath "$requestsPath/UpdateAllocationRequest.php" -Encoding UTF8

# Now generate the web routes
@'
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

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // Lands Management
    Route::prefix('lands')->name('lands.')->group(function () {
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
        
        // GIS Routes
        Route::get('/api/geojson', [LandController::class, 'getLandGeoJson'])->name('api.geojson');
    });

    // Clients Management
    Route::prefix('clients')->name('clients.')->group(function () {
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
    });

    // Allocations Management
    Route::prefix('allocations')->name('allocations.')->group(function () {
        Route::get('/', [AllocationController::class, 'index'])->name('index');
        Route::get('/create', [AllocationController::class, 'create'])->name('create');
        Route::post('/', [AllocationController::class, 'store'])->name('store');
        Route::get('/{allocation}', [AllocationController::class, 'show'])->name('show');
        Route::get('/{allocation}/edit', [AllocationController::class, 'edit'])->name('edit');
        Route::put('/{allocation}', [AllocationController::class, 'update'])->name('update');
        Route::delete('/{allocation}', [AllocationController::class, 'destroy'])->name('destroy');
        
        // Approval Workflow
        Route::post('/{allocation}/approve', [AllocationController::class, 'approve'])->name('approve');
        Route::post('/{allocation}/reject', [AllocationController::class, 'reject'])->name('reject');
        Route::get('/{allocation}/allocation-letter', [AllocationController::class, 'generateAllocationLetter'])->name('allocation-letter');
    });

    // Chiefs Management
    Route::prefix('chiefs')->name('chiefs.')->group(function () {
        Route::get('/', [ChiefController::class, 'index'])->name('index');
        Route::get('/create', [ChiefController::class, 'create'])->name('create');
        Route::post('/', [ChiefController::class, 'store'])->name('store');
        Route::get('/{chief}', [ChiefController::class, 'show'])->name('show');
        Route::get('/{chief}/edit', [ChiefController::class, 'edit'])->name('edit');
        Route::put('/{chief}', [ChiefController::class, 'update'])->name('update');
        Route::delete('/{chief}', [ChiefController::class, 'destroy'])->name('destroy');
        
        // GIS Routes
        Route::get('/{chief}/geojson', [ChiefController::class, 'getChiefGeoJson'])->name('geojson');
    });

    // Documents Management
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::post('/{document}/verify', [DocumentController::class, 'verify'])->name('verify');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/lands', [ReportController::class, 'generateLandReport'])->name('lands.generate');
        Route::post('/allocations', [ReportController::class, 'generateAllocationReport'])->name('allocations.generate');
        Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
    });

    // Admin Management (Restricted to Admin only)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
        
        // System Settings (placeholder for future development)
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
    });
});

// Public API Routes for GIS (if needed)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/lands/geojson', [LandController::class, 'getLandGeoJson'])->name('lands.geojson');
});

// Fallback Route
Route::fallback(function () {
    return redirect()->route('dashboard');
});
'@ | Out-File -FilePath "routes/web.php" -Encoding UTF8

# Generate API Routes
@'
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
'@ | Out-File -FilePath "routes/api.php" -Encoding UTF8

# Generate Auth Routes (for Laravel Breeze)
@'
<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
'@ | Out-File -FilePath "routes/auth.php" -Encoding UTF8

Write-Host "✅ All routes and form requests generated successfully!" -ForegroundColor Green
Write-Host "📁 Files created:" -ForegroundColor Cyan
Write-Host "   - app/Http/Requests/StoreLandRequest.php" -ForegroundColor White
Write-Host "   - app/Http/Requests/UpdateLandRequest.php" -ForegroundColor White
Write-Host "   - app/Http/Requests/StoreClientRequest.php" -ForegroundColor White
Write-Host "   - app/Http/Requests/UpdateClientRequest.php" -ForegroundColor White
Write-Host "   - app/Http/Requests/StoreChiefRequest.php" -ForegroundColor White
Write-Host "   - app/Http/Requests/UpdateChiefRequest.php" -ForegroundColor White
Write-Host "   - app/Http/Requests/StoreAllocationRequest.php" -ForegroundColor White
Write-Host "   - app/Http/Requests/UpdateAllocationRequest.php" -ForegroundColor White
Write-Host "   - routes/web.php" -ForegroundColor White
Write-Host "   - routes/api.php" -ForegroundColor White
Write-Host "   - routes/auth.php" -ForegroundColor White
Write-Host "🚀 Next step: We'll create views and Blade templates" -ForegroundColor Yellow