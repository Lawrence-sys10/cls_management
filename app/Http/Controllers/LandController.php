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

    public function show(Land $land): \Illuminate\View\View
    {
        $land->load(['chief', 'allocations.client', 'documents']);
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