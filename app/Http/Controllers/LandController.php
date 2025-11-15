<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\Chief;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreLandRequest;
use App\Http\Requests\UpdateLandRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LandsExport;
use App\Imports\LandsImport;
use Illuminate\Support\Facades\Storage;

class LandController extends Controller
{
    public function index(Request $request): View
    {
        $query = Land::with(['chief', 'allocations.client']);

        // Search filters
        if ($request->has('search') && $request->search) {
            $query->where('plot_number', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%')
                  ->orWhereHas('chief', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
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

    public function create(): View
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

        // Log activity (with check for activity log package)
        if (function_exists('activity') && class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($land)
                ->log('created land plot: ' . $land->plot_number);
        }

        return redirect()->route('lands.show', $land)
            ->with('success', 'Land plot registered successfully!');
    }

    public function show(Land $land): View
    {
        $land->load(['chief', 'allocations.client', 'documents']);
        return view('lands.show', compact('land'));
    }

    public function edit(Land $land): View
    {
        $chiefs = Chief::where('is_active', true)->get();
        return view('lands.edit', compact('land', 'chiefs'));
    }

    public function update(UpdateLandRequest $request, Land $land): RedirectResponse
    {
        $land->update($request->validated());

        // Handle polygon boundaries if provided
        if ($request->has('polygon_boundaries')) {
            $land->update(['polygon_boundaries' => $request->polygon_boundaries]);
        }

        // Log activity (with check for activity log package)
        if (function_exists('activity') && class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($land)
                ->log('updated land plot: ' . $land->plot_number);
        }

        return redirect()->route('lands.show', $land)
            ->with('success', 'Land plot updated successfully!');
    }

    public function destroy(Land $land): RedirectResponse
    {
        // Check if land has allocations
        if ($land->allocations()->exists()) {
            return redirect()->route('lands.index')
                ->with('error', 'Cannot delete land plot. It has existing allocations.');
        }

        $plot_number = $land->plot_number;
        $land->delete();

        // Log activity (with check for activity log package)
        if (function_exists('activity') && class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            activity()
                ->causedBy(auth()->user())
                ->log('deleted land plot: ' . $plot_number);
        }

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

        try {
            Excel::import(new LandsImport, $request->file('file'));
            
            return redirect()->route('lands.index')
                ->with('success', 'Lands imported successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('lands.index')
                ->with('error', 'Error importing lands: ' . $e->getMessage());
        }
    }

    public function downloadImportTemplate()
    {
        $templatePath = storage_path('app/templates/land-import-template.xlsx');
        
        if (!file_exists($templatePath)) {
            return redirect()->route('lands.index')
                ->with('error', 'Import template not found.');
        }

        return response()->download($templatePath, 'land-import-template.xlsx');
    }

    public function map(): View
    {
        $lands = Land::with('chief')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where(function ($query) {
                $query->where('latitude', '!=', 0)
                      ->where('longitude', '!=', 0);
            })
            ->get();

        return view('lands.map', compact('lands'));
    }

    public function getLandGeoJson(): JsonResponse
    {
        try {
            $lands = Land::with('chief')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where(function ($query) {
                    $query->where('latitude', '!=', 0)
                          ->where('longitude', '!=', 0);
                })
                ->get();

            $features = [];

            foreach ($lands as $land) {
                // Skip if coordinates are invalid
                if (empty($land->latitude) || empty($land->longitude)) {
                    continue;
                }

                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            floatval($land->longitude),
                            floatval($land->latitude)
                        ]
                    ],
                    'properties' => [
                        'id' => $land->id,
                        'plot_number' => $land->plot_number,
                        'location' => $land->location,
                        'status' => $land->ownership_status ?? 'unknown',
                        'area_acres' => $land->area_acres ?? 0,
                        'chief' => $land->chief->name ?? 'Unknown Chief',
                        'popupContent' => $this->getPopupContent($land)
                    ]
                ];
            }

            return response()->json([
                'type' => 'FeatureCollection',
                'features' => $features
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'type' => 'FeatureCollection',
                'features' => [],
                'error' => 'Failed to load land data'
            ], 500);
        }
    }

    public function bulkActions(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|string|in:verify,unverify,delete',
            'lands' => 'required|array',
            'lands.*' => 'exists:lands,id'
        ]);

        $lands = Land::whereIn('id', $request->lands)->get();

        switch ($request->action) {
            case 'verify':
                Land::whereIn('id', $request->lands)->update(['is_verified' => true]);
                $message = 'Selected lands verified successfully!';
                break;
            
            case 'unverify':
                Land::whereIn('id', $request->lands)->update(['is_verified' => false]);
                $message = 'Selected lands unverified successfully!';
                break;
            
            case 'delete':
                // Check if any land has allocations
                $landsWithAllocations = Land::whereIn('id', $request->lands)
                    ->whereHas('allocations')
                    ->count();

                if ($landsWithAllocations > 0) {
                    return redirect()->route('lands.index')
                        ->with('error', 'Cannot delete lands with existing allocations.');
                }

                Land::whereIn('id', $request->lands)->delete();
                $message = 'Selected lands deleted successfully!';
                break;
        }

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->log('performed bulk action: ' . $request->action . ' on ' . count($request->lands) . ' lands');
        }

        return redirect()->route('lands.index')->with('success', $message);
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'lands' => 'required|array',
            'lands.*' => 'exists:lands,id'
        ]);

        // Check if any land has allocations
        $landsWithAllocations = Land::whereIn('id', $request->lands)
            ->whereHas('allocations')
            ->count();

        if ($landsWithAllocations > 0) {
            return redirect()->route('lands.index')
                ->with('error', 'Cannot delete lands with existing allocations.');
        }

        $count = Land::whereIn('id', $request->lands)->delete();

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->log('bulk deleted ' . $count . ' lands');
        }

        return redirect()->route('lands.index')
            ->with('success', $count . ' lands deleted successfully!');
    }

    public function documents(Land $land): View
    {
        $land->load('documents');
        return view('lands.documents', compact('land'));
    }

    public function storeDocument(Request $request, Land $land): RedirectResponse
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_type' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        try {
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('land_documents', $fileName, 'public');

            $document = Document::create([
                'land_id' => $land->id,
                'document_type' => $request->document_type,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'description' => $request->description,
                'uploaded_by' => auth()->id(),
            ]);

            // Log activity
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($land)
                    ->log('uploaded document for land: ' . $land->plot_number);
            }

            return redirect()->route('lands.documents', $land)
                ->with('success', 'Document uploaded successfully!');

        } catch (\Exception $e) {
            return redirect()->route('lands.documents', $land)
                ->with('error', 'Error uploading document: ' . $e->getMessage());
        }
    }

    public function verify(Request $request, Land $land): RedirectResponse
    {
        $land->update(['is_verified' => true]);

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($land)
                ->log('verified land: ' . $land->plot_number);
        }

        return redirect()->route('lands.show', $land)
            ->with('success', 'Land verified successfully!');
    }

    /**
     * Chief's lands view
     */
    public function chiefLands(): View
    {
        $chiefId = auth()->id();
        $lands = Land::with(['allocations.client'])
            ->where('chief_id', $chiefId)
            ->latest()
            ->paginate(15);

        $stats = [
            'total_lands' => Land::where('chief_id', $chiefId)->count(),
            'verified_lands' => Land::where('chief_id', $chiefId)->where('is_verified', true)->count(),
            'lands_with_allocations' => Land::where('chief_id', $chiefId)->whereHas('allocations')->count(),
        ];

        return view('chief.lands', compact('lands', 'stats'));
    }

    /**
     * Generate popup content for map
     */
    private function getPopupContent(Land $land): string
    {
        return '
            <div class="p-2">
                <h4 class="font-bold">' . e($land->plot_number) . '</h4>
                <p><strong>Location:</strong> ' . e($land->location) . '</p>
                <p><strong>Status:</strong> ' . e($land->ownership_status ?? 'Unknown') . '</p>
                <p><strong>Area:</strong> ' . e($land->area_acres ?? 0) . ' acres</p>
                <p><strong>Chief:</strong> ' . e($land->chief->name ?? 'Unknown') . '</p>
                <a href="' . route('lands.show', $land) . '" class="text-blue-600 hover:text-blue-800 text-sm">
                    View Details
                </a>
            </div>
        ';
    }

    /**
     * Get land statistics for dashboard
     */
    public function getLandStats(): JsonResponse
    {
        $totalLands = Land::count();
        $availableLands = Land::where('ownership_status', 'available')->count();
        $allocatedLands = Land::where('ownership_status', 'allocated')->count();
        $reservedLands = Land::where('ownership_status', 'reserved')->count();

        return response()->json([
            'total' => $totalLands,
            'available' => $availableLands,
            'allocated' => $allocatedLands,
            'reserved' => $reservedLands,
        ]);
    }
}