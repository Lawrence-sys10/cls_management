<?php

namespace App\Http\Controllers\Chief;

use App\Http\Controllers\Controller;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ChiefLandController extends Controller
{
    /**
     * Get the chief ID safely
     */
    private function getChiefId()
    {
        return Auth::user()->getChiefId();
    }

    /**
     * Ensure chief profile exists
     */
    private function ensureChiefProfile()
    {
        return Auth::user()->ensureChiefProfile();
    }

    /**
     * Display a listing of the chief's lands.
     */
    public function index(Request $request)
    {
        // Get the chief ID safely
        $chiefId = $this->getChiefId();
        
        // Get lands for this chief
        $lands = Land::where('chief_id', $chiefId)
            ->with(['chief', 'allocations.client'])
            ->latest();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $lands->where(function($query) use ($request) {
                $query->where('plot_number', 'like', '%' . $request->search . '%')
                      ->orWhere('location', 'like', '%' . $request->search . '%')
                      ->orWhere('landmark', 'like', '%' . $request->search . '%');
            });
        }

        // Apply status filter
        if ($request->has('status') && $request->status) {
            $lands->where('ownership_status', $request->status);
        }

        $lands = $lands->paginate(10);

        return view('chiefs.lands.index', compact('lands'));
    }

    /**
     * Show the form for creating a new land.
     */
    public function create()
    {
        return view('chiefs.lands.create');
    }

    /**
     * Store a newly created land in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plot_number' => 'required|string|max:100|unique:lands,plot_number',
            'location' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'area_acres' => 'required|numeric|min:0.01',
            'area_hectares' => 'required|numeric|min:0.01',
            'land_use' => 'required|string|in:residential,commercial,agricultural,industrial,recreational',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'coordinates' => 'nullable|string',
            'registration_date' => 'required|date',
        ]);

        // Get the chief ID safely
        $validated['chief_id'] = $this->getChiefId();
        $validated['ownership_status'] = 'vacant'; // Default status

        Land::create($validated);

        return redirect()->route('chief.lands.index')
            ->with('success', 'Land added successfully!');
    }

    /**
     * Display the specified land.
     */
    public function show(Land $land)
    {
        // Ensure the chief can only view their own lands
        if ($land->chief_id !== $this->getChiefId()) {
            abort(403, 'Unauthorized action.');
        }

        $land->load(['chief', 'allocations.client', 'documents']);

        return view('chiefs.lands.show', compact('land'));
    }

    /**
     * Show the form for editing the specified land.
     */
    public function edit(Land $land)
    {
        // Ensure the chief can only edit their own lands
        if ($land->chief_id !== $this->getChiefId()) {
            abort(403, 'Unauthorized action.');
        }

        return view('chiefs.lands.edit', compact('land'));
    }

    /**
     * Update the specified land in storage.
     */
    public function update(Request $request, Land $land)
    {
        // Ensure the chief can only update their own lands
        if ($land->chief_id !== $this->getChiefId()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'plot_number' => 'required|string|max:100|unique:lands,plot_number,' . $land->id,
            'location' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'area_acres' => 'required|numeric|min:0.01',
            'area_hectares' => 'required|numeric|min:0.01',
            'land_use' => 'required|string|in:residential,commercial,agricultural,industrial,recreational',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'coordinates' => 'nullable|string',
            'registration_date' => 'required|date',
        ]);

        $land->update($validated);

        return redirect()->route('chief.lands.show', $land)
            ->with('success', 'Land updated successfully!');
    }

    /**
     * Show the delete confirmation page.
     */
    public function delete(Land $land)
    {
        // Ensure the chief can only delete their own lands
        if ($land->chief_id !== $this->getChiefId()) {
            abort(403, 'Unauthorized action.');
        }

        // Load relationships for the delete confirmation page
        $land->load(['allocations', 'documents']);

        return view('chiefs.lands.delete', compact('land'));
    }

    /**
     * Remove the specified land from storage.
     */
    public function destroy(Land $land)
    {
        // Ensure the chief can only delete their own lands
        if ($land->chief_id !== $this->getChiefId()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if land has allocations
        if ($land->allocations()->exists()) {
            return redirect()->route('chief.lands.delete', $land)
                ->with('error', 'Cannot delete land that has allocations. Please delete allocations first.');
        }

        // Check if land is allocated (additional safety check)
        if ($land->ownership_status === 'allocated') {
            return redirect()->route('chief.lands.delete', $land)
                ->with('error', 'Cannot delete land that is currently allocated. Please change the land status first.');
        }

        // Delete associated documents first
        $land->documents()->delete();

        // Delete the land
        $land->delete();

        return redirect()->route('chief.lands.index')
            ->with('success', 'Land deleted successfully!');
    }

    /**
     * Get land statistics for the chief.
     */
    public function getLandStats()
    {
        $chiefId = $this->getChiefId();
        
        $stats = [
            'total_lands' => Land::where('chief_id', $chiefId)->count(),
            'vacant_lands' => Land::where('chief_id', $chiefId)->where('ownership_status', 'vacant')->count(),
            'allocated_lands' => Land::where('chief_id', $chiefId)->where('ownership_status', 'allocated')->count(),
            'disputed_lands' => Land::where('chief_id', $chiefId)->where('ownership_status', 'under_dispute')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Show land documents.
     */
    public function documents(Land $land)
    {
        // Ensure the chief can only view their own land documents
        if ($land->chief_id !== $this->getChiefId()) {
            abort(403, 'Unauthorized action.');
        }

        $documents = $land->documents()->latest()->get();

        return view('chiefs.lands.documents', compact('land', 'documents'));
    }

    /**
     * Store land document.
     */
    public function storeDocument(Request $request, Land $land)
    {
        // Ensure the chief can only add documents to their own lands
        if ($land->chief_id !== $this->getChiefId()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'document_type' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        // Handle document upload
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('land-documents', 'public');
            
            $land->documents()->create([
                'file_path' => $documentPath,
                'document_type' => $request->document_type,
                'description' => $request->description,
                'uploaded_by' => Auth::id(),
            ]);
        }

        return redirect()->route('chief.lands.documents', $land)
            ->with('success', 'Document uploaded successfully!');
    }

    /**
     * Update land status (for vacant/allocated/dispute)
     */
    public function updateStatus(Request $request, Land $land)
    {
        // Ensure the chief can only update their own lands
        if ($land->chief_id !== $this->getChiefId()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'ownership_status' => 'required|in:vacant,allocated,under_dispute'
        ]);

        $land->update([
            'ownership_status' => $request->ownership_status
        ]);

        return redirect()->back()
            ->with('success', 'Land status updated successfully!');
    }

    /**
     * Quick allocate action
     */
    public function quickAllocate(Land $land)
    {
        // Ensure the chief can only allocate their own lands
        if ($land->chief_id !== $this->getChiefId()) {
            abort(403, 'Unauthorized action.');
        }

        if ($land->ownership_status !== 'vacant') {
            return redirect()->back()
                ->with('error', 'Only vacant lands can be allocated.');
        }

        return redirect()->route('chief.allocations.create', ['land_id' => $land->id]);
    }

    /**
     * Search available lands for allocation (Select2 format)
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q');
            $chiefId = $this->getChiefId();
            
            Log::info('Land search initiated', [
                'query' => $query,
                'chief_id' => $chiefId,
                'request_params' => $request->all()
            ]);

            // Build the query for available lands
            $landsQuery = Land::where('chief_id', $chiefId)
                ->where(function($q) {
                    $q->where('ownership_status', 'vacant')
                      ->orWhere('ownership_status', 'available')
                      ->orWhere('status', 'vacant')
                      ->orWhere('status', 'available')
                      ->orWhereNull('ownership_status')
                      ->orWhereNull('status');
                });

            // Apply search filter if query is provided
            if ($query) {
                $landsQuery->where(function($q) use ($query) {
                    $q->where('plot_number', 'like', "%{$query}%")
                      ->orWhere('location', 'like', "%{$query}%")
                      ->orWhere('landmark', 'like', "%{$query}%")
                      ->orWhere('size', 'like', "%{$query}%");
                });
            }

            $lands = $landsQuery->select('id', 'plot_number', 'location', 'landmark', 'size', 'ownership_status', 'status')
                ->limit(10)
                ->get();

            Log::info('Land search results', [
                'query' => $query,
                'results_count' => $lands->count(),
                'lands' => $lands->toArray()
            ]);

            return response()->json([
                'success' => true,
                'lands' => $lands
            ]);

        } catch (\Exception $e) {
            Log::error('Land search error', [
                'error' => $e->getMessage(),
                'query' => $query ?? 'none',
                'chief_id' => $chiefId ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error searching lands: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available lands for allocation (AJAX endpoint)
     */
    public function getAvailableLands()
    {
        try {
            $chiefId = $this->getChiefId();
            
            $lands = Land::where('chief_id', $chiefId)
                ->where(function($query) {
                    $query->where('ownership_status', 'vacant')
                          ->orWhere('ownership_status', 'available')
                          ->orWhere('status', 'vacant')
                          ->orWhere('status', 'available')
                          ->orWhereNull('ownership_status')
                          ->orWhereNull('status');
                })
                ->select('id', 'plot_number', 'location', 'landmark', 'size', 'ownership_status', 'status')
                ->get();

            Log::info('Available lands fetched', [
                'chief_id' => $chiefId,
                'count' => $lands->count()
            ]);

            return response()->json([
                'success' => true,
                'lands' => $lands,
                'count' => $lands->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching available lands: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching available lands'
            ], 500);
        }
    }

    /**
     * Get land details for allocation form
     */
    public function getLandDetails(Land $land)
    {
        // Ensure the chief can only access their own lands
        if ($land->chief_id !== $this->getChiefId()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'land' => [
                'id' => $land->id,
                'plot_number' => $land->plot_number,
                'location' => $land->location,
                'landmark' => $land->landmark,
                'size' => $land->size,
                'ownership_status' => $land->ownership_status,
                'status' => $land->status,
            ]
        ]);
    }

    /**
     * Check if land exists by plot number
     */
    public function checkExisting(Request $request)
    {
        $request->validate([
            'plot_number' => 'required|string|max:100',
        ]);

        $chiefId = $this->getChiefId();
        
        $land = Land::where('chief_id', $chiefId)
            ->where('plot_number', $request->plot_number)
            ->first();

        if ($land) {
            return response()->json([
                'exists' => true,
                'land' => $land
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
    }

    /**
     * Quick land creation for allocation form
     */
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'plot_number' => 'required|string|max:100|unique:lands,plot_number',
            'location' => 'required|string|max:255',
            'size' => 'required|numeric|min:0.01',
        ]);

        try {
            // Add chief_id and default values
            $validated['chief_id'] = $this->getChiefId();
            $validated['ownership_status'] = 'vacant';
            $validated['status'] = 'vacant';
            $validated['land_use'] = 'agricultural';
            $validated['registration_date'] = now()->format('Y-m-d');
            $validated['area_acres'] = $validated['size'];
            $validated['area_hectares'] = $validated['size'] * 0.404686; // Convert acres to hectares

            $land = Land::create($validated);

            Log::info('Quick land created', [
                'land_id' => $land->id,
                'plot_number' => $land->plot_number,
                'chief_id' => $this->getChiefId()
            ]);

            return response()->json([
                'success' => true,
                'land' => $land,
                'message' => 'Land created successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Quick land creation error', [
                'error' => $e->getMessage(),
                'plot_number' => $validated['plot_number'],
                'chief_id' => $this->getChiefId()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating land: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug method to check available lands
     */
    public function debugAvailableLands()
    {
        try {
            $chiefId = $this->getChiefId();
            
            $allLands = Land::where('chief_id', $chiefId)->get();
            $availableLands = Land::where('chief_id', $chiefId)
                ->where(function($query) {
                    $query->where('ownership_status', 'vacant')
                          ->orWhere('ownership_status', 'available')
                          ->orWhere('status', 'vacant')
                          ->orWhere('status', 'available')
                          ->orWhereNull('ownership_status')
                          ->orWhereNull('status');
                })
                ->get();

            return response()->json([
                'success' => true,
                'debug_info' => [
                    'chief_id' => $chiefId,
                    'total_lands' => $allLands->count(),
                    'available_lands' => $availableLands->count(),
                    'all_lands' => $allLands->map(function($land) {
                        return [
                            'id' => $land->id,
                            'plot_number' => $land->plot_number,
                            'location' => $land->location,
                            'ownership_status' => $land->ownership_status,
                            'status' => $land->status,
                            'size' => $land->size
                        ];
                    }),
                    'available_lands_details' => $availableLands->map(function($land) {
                        return [
                            'id' => $land->id,
                            'plot_number' => $land->plot_number,
                            'location' => $land->location,
                            'ownership_status' => $land->ownership_status,
                            'status' => $land->status,
                            'size' => $land->size
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Debug error: ' . $e->getMessage()
            ], 500);
        }
    }
}