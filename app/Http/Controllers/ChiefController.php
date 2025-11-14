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
