# Step 3: Generate Laravel Controllers for CLS Management System
# Save this as generate-controllers.ps1 and run from project root

# Create controllers directory if it doesn't exist
$controllersPath = "app/Http/Controllers"
if (!(Test-Path $controllersPath)) {
    New-Item -ItemType Directory -Path $controllersPath -Force
}

# Create Admin namespace
$adminPath = "$controllersPath/Admin"
if (!(Test-Path $adminPath)) {
    New-Item -ItemType Directory -Path $adminPath -Force
}

# Create API namespace
$apiPath = "$controllersPath/API"
if (!(Test-Path $apiPath)) {
    New-Item -ItemType Directory -Path $apiPath -Force
}

# 1. Main Dashboard Controller
@'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Land;
use App\Models\Client;
use App\Models\Allocation;
use App\Models\Chief;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $stats = [
            'total_lands' => Land::count(),
            'total_clients' => Client::count(),
            'total_chiefs' => Chief::count(),
            'total_allocations' => Allocation::count(),
            'pending_approvals' => Allocation::where('approval_status', 'pending')->count(),
            'verified_lands' => Land::where('is_verified', true)->count(),
        ];

        // Recent activities
        $recent_allocations = Allocation::with(['land', 'client', 'chief'])
            ->latest()
            ->take(10)
            ->get();

        // Land distribution by status
        $land_distribution = Land::select('ownership_status', DB::raw('count(*) as total'))
            ->groupBy('ownership_status')
            ->get();

        // Monthly allocation trends
        $allocation_trends = Allocation::select(
                DB::raw('MONTH(allocation_date) as month'),
                DB::raw('YEAR(allocation_date) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('allocation_date', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'recent_allocations',
            'land_distribution',
            'allocation_trends'
        ));
    }

    public function getChartData(): JsonResponse
    {
        $data = [
            'land_status' => Land::select('ownership_status', DB::raw('count(*) as total'))
                ->groupBy('ownership_status')
                ->get(),
            'monthly_allocations' => Allocation::select(
                    DB::raw('MONTHNAME(allocation_date) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('allocation_date', date('Y'))
                ->groupBy('month')
                ->orderBy(DB::raw('MONTH(allocation_date)'))
                ->get(),
            'chief_lands' => Chief::withCount('lands')->get()
        ];

        return response()->json($data);
    }
}
'@ | Out-File -FilePath "$controllersPath/DashboardController.php" -Encoding UTF8

# 2. Land Controller
@'
<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\Chief;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreLandRequest;
use App\Http\Requests\UpdateLandRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LandsExport;
use App\Imports\LandsImport;

class LandController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Land::with(['chief', 'allocation.client']);

        // Search filters
        if ($request->has('search') && $request->search) {
            $query->where('plot_number', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        if ($request->has('chief_id') && $request->chief_id) {
            $query->where('chief_id', $request->chief_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('ownership_status', $request->status);
        }

        $lands = $query->latest()->paginate(20);
        $chiefs = Chief::where('is_active', true)->get();

        return view('lands.index', compact('lands', 'chiefs'));
    }

    public function create(): \Illuminate\View\View
    {
        $chiefs = Chief::where('is_active', true)->get();
        return view('lands.create', compact('chiefs'));
    }

    public function store(StoreLandRequest $request): RedirectResponse
    {
        $land = Land::create($request->validated());

        // Handle polygon boundaries if provided
        if ($request->has('polygon_boundaries')) {
            $land->update(['polygon_boundaries' => $request->polygon_boundaries]);
        }

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($land)
            ->log('created land plot: ' . $land->plot_number);

        return redirect()->route('lands.show', $land)
            ->with('success', 'Land plot registered successfully!');
    }

    public function show(Land $land): \Illuminate\View\View
    {
        $land->load(['chief', 'allocation.client', 'documents']);
        return view('lands.show', compact('land'));
    }

    public function edit(Land $land): \Illuminate\View\View
    {
        $chiefs = Chief::where('is_active', true)->get();
        return view('lands.edit', compact('land', 'chiefs'));
    }

    public function update(UpdateLandRequest $request, Land $land): RedirectResponse
    {
        $land->update($request->validated());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($land)
            ->log('updated land plot: ' . $land->plot_number);

        return redirect()->route('lands.show', $land)
            ->with('success', 'Land plot updated successfully!');
    }

    public function destroy(Land $land): RedirectResponse
    {
        $plot_number = $land->plot_number;
        $land->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('deleted land plot: ' . $plot_number);

        return redirect()->route('lands.index')
            ->with('success', 'Land plot deleted successfully!');
    }

    public function export(Request $request)
    {
        return Excel::download(new LandsExport($request), 'lands-' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new LandsImport, $request->file('file'));

        return redirect()->route('lands.index')
            ->with('success', 'Lands imported successfully!');
    }

    public function getLandGeoJson(): JsonResponse
    {
        $lands = Land::with('chief')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $features = [];

        foreach ($lands as $land) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$land->longitude, $land->latitude]
                ],
                'properties' => [
                    'id' => $land->id,
                    'plot_number' => $land->plot_number,
                    'location' => $land->location,
                    'status' => $land->ownership_status,
                    'area_acres' => $land->area_acres,
                    'chief' => $land->chief->name,
                    'popupContent' => view('lands.partials.map-popup', compact('land'))->render()
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }
}
'@ | Out-File -FilePath "$controllersPath/LandController.php" -Encoding UTF8

# 3. Client Controller
@'
<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;

class ClientController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Client::withCount('allocations');

        if ($request->has('search') && $request->search) {
            $query->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('id_number', 'like', '%' . $request->search . '%');
        }

        $clients = $query->latest()->paginate(20);
        return view('clients.index', compact('clients'));
    }

    public function create(): \Illuminate\View\View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::create($request->validated());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->log('created client: ' . $client->full_name);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client registered successfully!');
    }

    public function show(Client $client): \Illuminate\View\View
    {
        $client->load(['allocations.land.chief', 'documents']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): \Illuminate\View\View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->log('updated client: ' . $client->full_name);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client_name = $client->full_name;
        $client->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('deleted client: ' . $client_name);

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully!');
    }

    public function export(Request $request)
    {
        return Excel::download(new ClientsExport($request), 'clients-' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new ClientsImport, $request->file('file'));

        return redirect()->route('clients.index')
            ->with('success', 'Clients imported successfully!');
    }
}
'@ | Out-File -FilePath "$controllersPath/ClientController.php" -Encoding UTF8

# 4. Allocation Controller
@'
<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Land;
use App\Models\Client;
use App\Models\Chief;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreAllocationRequest;
use App\Http\Requests\UpdateAllocationRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class AllocationController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Allocation::with(['land', 'client', 'chief', 'processedBy']);

        if ($request->has('status') && $request->status) {
            $query->where('approval_status', $request->status);
        }

        if ($request->has('chief_id') && $request->chief_id) {
            $query->where('chief_id', $request->chief_id);
        }

        $allocations = $query->latest()->paginate(20);
        $chiefs = Chief::where('is_active', true)->get();

        return view('allocations.index', compact('allocations', 'chiefs'));
    }

    public function create(): \Illuminate\View\View
    {
        $lands = Land::where('ownership_status', 'vacant')->get();
        $clients = Client::all();
        $chiefs = Chief::where('is_active', true)->get();
        $staff = Staff::all();

        return view('allocations.create', compact('lands', 'clients', 'chiefs', 'staff'));
    }

    public function store(StoreAllocationRequest $request): RedirectResponse
    {
        $allocation = Allocation::create($request->validated());

        // Update land status
        Land::where('id', $request->land_id)->update(['ownership_status' => 'allocated']);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($allocation)
            ->log('created allocation for land: ' . $allocation->land->plot_number);

        return redirect()->route('allocations.show', $allocation)
            ->with('success', 'Allocation created successfully!');
    }

    public function show(Allocation $allocation): \Illuminate\View\View
    {
        $allocation->load(['land', 'client', 'chief', 'processedBy', 'documents']);
        return view('allocations.show', compact('allocation'));
    }

    public function edit(Allocation $allocation): \Illuminate\View\View
    {
        $lands = Land::all();
        $clients = Client::all();
        $chiefs = Chief::where('is_active', true)->get();
        $staff = Staff::all();

        return view('allocations.edit', compact('allocation', 'lands', 'clients', 'chiefs', 'staff'));
    }

    public function update(UpdateAllocationRequest $request, Allocation $allocation): RedirectResponse
    {
        $allocation->update($request->validated());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($allocation)
            ->log('updated allocation: ' . $allocation->id);

        return redirect()->route('allocations.show', $allocation)
            ->with('success', 'Allocation updated successfully!');
    }

    public function approve(Allocation $allocation): RedirectResponse
    {
        $allocation->update([
            'approval_status' => 'approved',
            'chief_approval_date' => now()
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($allocation)
            ->log('approved allocation: ' . $allocation->id);

        return redirect()->back()->with('success', 'Allocation approved successfully!');
    }

    public function reject(Allocation $allocation): RedirectResponse
    {
        $allocation->update([
            'approval_status' => 'rejected'
        ]);

        // Free up the land
        Land::where('id', $allocation->land_id)->update(['ownership_status' => 'vacant']);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($allocation)
            ->log('rejected allocation: ' . $allocation->id);

        return redirect()->back()->with('success', 'Allocation rejected successfully!');
    }

    public function generateAllocationLetter(Allocation $allocation)
    {
        $allocation->load(['land', 'client', 'chief']);
        
        $pdf = PDF::loadView('allocations.allocation-letter', compact('allocation'));
        
        return $pdf->download('allocation-letter-' . $allocation->land->plot_number . '.pdf');
    }
}
'@ | Out-File -FilePath "$controllersPath/AllocationController.php" -Encoding UTF8

# 5. Chief Controller
@'
<?php

namespace App\Http\Controllers;

use App\Models\Chief;
use App\Models\Land;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreChiefRequest;
use App\Http\Requests\UpdateChiefRequest;

class ChiefController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Chief::withCount(['lands', 'allocations']);

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('jurisdiction', 'like', '%' . $request->search . '%');
        }

        $chiefs = $query->latest()->paginate(20);
        return view('chiefs.index', compact('chiefs'));
    }

    public function create(): \Illuminate\View\View
    {
        return view('chiefs.create');
    }

    public function store(StoreChiefRequest $request): RedirectResponse
    {
        $chief = Chief::create($request->validated());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($chief)
            ->log('created chief: ' . $chief->name);

        return redirect()->route('chiefs.show', $chief)
            ->with('success', 'Chief registered successfully!');
    }

    public function show(Chief $chief): \Illuminate\View\View
    {
        $chief->load(['lands', 'allocations.client', 'user']);
        
        $land_stats = [
            'total' => $chief->lands->count(),
            'vacant' => $chief->lands->where('ownership_status', 'vacant')->count(),
            'allocated' => $chief->lands->where('ownership_status', 'allocated')->count(),
            'disputed' => $chief->lands->where('ownership_status', 'under_dispute')->count(),
        ];

        return view('chiefs.show', compact('chief', 'land_stats'));
    }

    public function edit(Chief $chief): \Illuminate\View\View
    {
        return view('chiefs.edit', compact('chief'));
    }

    public function update(UpdateChiefRequest $request, Chief $chief): RedirectResponse
    {
        $chief->update($request->validated());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($chief)
            ->log('updated chief: ' . $chief->name);

        return redirect()->route('chiefs.show', $chief)
            ->with('success', 'Chief updated successfully!');
    }

    public function destroy(Chief $chief): RedirectResponse
    {
        $chief_name = $chief->name;
        $chief->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('deleted chief: ' . $chief_name);

        return redirect()->route('chiefs.index')
            ->with('success', 'Chief deleted successfully!');
    }

    public function getChiefGeoJson(Chief $chief): \Illuminate\Http\JsonResponse
    {
        $lands = $chief->lands()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $features = [];

        foreach ($lands as $land) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$land->longitude, $land->latitude]
                ],
                'properties' => [
                    'plot_number' => $land->plot_number,
                    'status' => $land->ownership_status,
                    'area_acres' => $land->area_acres
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }
}
'@ | Out-File -FilePath "$controllersPath/ChiefController.php" -Encoding UTF8

# 6. Document Controller
@'
<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Client;
use App\Models\Land;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'document_type' => 'required|string',
            'client_id' => 'nullable|exists:clients,id',
            'land_id' => 'nullable|exists:lands,id',
            'allocation_id' => 'nullable|exists:allocations,id',
            'description' => 'nullable|string'
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        $document = Document::create([
            'client_id' => $request->client_id,
            'land_id' => $request->land_id,
            'allocation_id' => $request->allocation_id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'document_type' => $request->document_type,
            'description' => $request->description,
            'uploaded_by' => auth()->id(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($document)
            ->log('uploaded document: ' . $document->file_name);

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }

    public function destroy(Document $document): RedirectResponse
    {
        // Delete physical file
        Storage::disk('public')->delete($document->file_path);

        $file_name = $document->file_name;
        $document->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('deleted document: ' . $file_name);

        return redirect()->back()->with('success', 'Document deleted successfully!');
    }

    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($document)
            ->log('downloaded document: ' . $document->file_name);

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function verify(Document $document): RedirectResponse
    {
        $document->update(['is_verified' => true]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($document)
            ->log('verified document: ' . $document->file_name);

        return redirect()->back()->with('success', 'Document verified successfully!');
    }
}
'@ | Out-File -FilePath "$controllersPath/DocumentController.php" -Encoding UTF8

# 7. Report Controller
@'
<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LandsReportExport;
use App\Exports\AllocationsReportExport;
use App\Exports\ClientsReportExport;

class ReportController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $reports = Report::with('generatedBy')->latest()->paginate(15);
        return view('reports.index', compact('reports'));
    }

    public function generateLandReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'chief_id' => 'nullable|exists:chiefs,id',
            'status' => 'nullable|string'
        ]);

        if ($request->format === 'excel') {
            return Excel::download(
                new LandsReportExport($request), 
                'lands-report-' . date('Y-m-d') . '.xlsx'
            );
        }

        // PDF report
        $lands = \App\Models\Land::with(['chief', 'allocation.client'])
            ->when($request->start_date, function($query) use ($request) {
                $query->where('registration_date', '>=', $request->start_date);
            })
            ->when($request->end_date, function($query) use ($request) {
                $query->where('registration_date', '<=', $request->end_date);
            })
            ->when($request->chief_id, function($query) use ($request) {
                $query->where('chief_id', $request->chief_id);
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('ownership_status', $request->status);
            })
            ->get();

        $pdf = PDF::loadView('reports.lands-pdf', compact('lands'));

        // Save report record
        Report::create([
            'name' => 'Lands Report - ' . date('Y-m-d'),
            'type' => 'lands',
            'date_range' => [
                'start' => $request->start_date,
                'end' => $request->end_date
            ],
            'generated_by' => auth()->id(),
            'file_path' => 'reports/lands-report-' . date('Y-m-d') . '.pdf',
            'parameters' => $request->all()
        ]);

        return $pdf->download('lands-report-' . date('Y-m-d') . '.pdf');
    }

    public function generateAllocationReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string'
        ]);

        if ($request->format === 'excel') {
            return Excel::download(
                new AllocationsReportExport($request), 
                'allocations-report-' . date('Y-m-d') . '.xlsx'
            );
        }

        // Similar implementation for allocation report...
        return Excel::download(new AllocationsReportExport($request), 'allocations-report.xlsx');
    }

    public function destroy(Report $report): RedirectResponse
    {
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Report deleted successfully!');
    }
}
'@ | Out-File -FilePath "$controllersPath/ReportController.php" -Encoding UTF8

# 8. Admin User Controller
@'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with(['roles', 'staff'])->latest()->paginate(20);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'department' => 'required_if:user_type,staff|string|max:255',
            'employee_id' => 'required_if:user_type,staff|string|max:50',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type ?? 'staff',
        ]);

        // Assign roles
        $user->syncRoles($request->roles);

        // Create staff record if user type is staff
        if ($request->user_type === 'staff') {
            Staff::create([
                'user_id' => $user->id,
                'department' => $request->department,
                'phone' => $request->phone,
                'assigned_area' => $request->assigned_area,
                'employee_id' => $request->employee_id,
                'date_joined' => now(),
            ]);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('created user: ' . $user->name);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15',
            'roles' => 'required|array',
            'is_active' => 'boolean'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? true,
        ]);

        $user->syncRoles($request->roles);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('updated user: ' . $user->name);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user_name = $user->name;
        $user->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('deleted user: ' . $user_name);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
'@ | Out-File -FilePath "$adminPath/UserController.php" -Encoding UTF8

Write-Host "✅ All controllers generated successfully!" -ForegroundColor Green
Write-Host "📁 Controllers created: 8" -ForegroundColor Cyan
Write-Host "📍 Locations:" -ForegroundColor Yellow
Write-Host "   - app/Http/Controllers/DashboardController.php" -ForegroundColor White
Write-Host "   - app/Http/Controllers/LandController.php" -ForegroundColor White
Write-Host "   - app/Http/Controllers/ClientController.php" -ForegroundColor White
Write-Host "   - app/Http/Controllers/AllocationController.php" -ForegroundColor White
Write-Host "   - app/Http/Controllers/ChiefController.php" -ForegroundColor White
Write-Host "   - app/Http/Controllers/DocumentController.php" -ForegroundColor White
Write-Host "   - app/Http/Controllers/ReportController.php" -ForegroundColor White
Write-Host "   - app/Http/Controllers/Admin/UserController.php" -ForegroundColor White
Write-Host "🚀 Next step: We'll create routes and form requests" -ForegroundColor Yellow