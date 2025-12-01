<?php

namespace App\Http\Controllers\Chief;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Land;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChiefAllocationController extends Controller
{
    /**
     * Display a listing of the chief's allocations.
     */
    public function index(Request $request)
    {
        // Get only the chief's allocations
        $allocations = Auth::user()->allocations()
            ->with(['land', 'client', 'chief'])
            ->latest();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $allocations->where(function($query) use ($request) {
                $query->whereHas('land', function($q) use ($request) {
                    $q->where('plot_number', 'like', '%' . $request->search . '%')
                      ->orWhere('location', 'like', '%' . $request->search . '%');
                })->orWhereHas('client', function($q) use ($request) {
                    $q->where('full_name', 'like', '%' . $request->search . '%')
                      ->orWhere('id_number', 'like', '%' . $request->search . '%')
                      ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            });
        }

        // Apply status filter
        if ($request->has('status') && $request->status && $request->status !== 'all') {
            if ($request->status === 'active') {
                $allocations->where('status', 'active');
            } elseif ($request->status === 'expired') {
                // Filter for expired allocations (allocation_date + duration_years is in the past)
                $allocations->whereRaw('DATE_ADD(allocation_date, INTERVAL duration_years YEAR) < NOW()');
            } elseif ($request->status === 'terminated') {
                $allocations->where('status', 'terminated');
            } elseif ($request->status === 'inactive') {
                $allocations->where('status', 'inactive');
            }
        }

        $allocations = $allocations->paginate(15);

        return view('chiefs.allocations.index', compact('allocations'));
    }

    /**
     * Show the form for creating a new allocation.
     */
    public function create(Request $request)
    {
        try {
            \Log::info('Allocation Create - Starting', ['user_id' => Auth::id()]);

            // Get ONLY lands that belong to this chief
            $allLands = Land::where('chief_id', Auth::id())->get();
            
            \Log::info('Allocation Create - All chief lands count', [
                'chief_id' => Auth::id(),
                'count' => $allLands->count()
            ]);

            // If no lands found, show a warning but proceed
            if ($allLands->isEmpty()) {
                \Log::warning('Allocation Create - No lands assigned to chief', [
                    'chief_id' => Auth::id(),
                    'all_lands_in_db' => Land::count()
                ]);
                
                $lands = collect(); // Empty collection
            } else {
                // Query for available lands - ONLY from this chief's lands
                $lands = Land::where('chief_id', Auth::id())
                    ->where(function($query) {
                        $query->where('ownership_status', 'vacant')
                              ->orWhere('ownership_status', 'available')
                              ->orWhere('ownership_status', 'free')
                              ->orWhere('ownership_status', 'unallocated')
                              ->orWhere('status', 'vacant')
                              ->orWhere('status', 'available')
                              ->orWhere('status', 'free')
                              ->orWhere('status', 'unallocated')
                              ->orWhereNull('ownership_status')
                              ->orWhereNull('status');
                    })
                    ->get();

                \Log::info('Allocation Create - Available lands count', [
                    'available_count' => $lands->count()
                ]);

                // If no available lands, show all chief's lands for debugging but mark them as unavailable
                if ($lands->isEmpty()) {
                    $lands = $allLands;
                    \Log::warning('Allocation Create - No lands with vacant status, showing all chief lands for debugging');
                }
            }
                    
            // Get only clients that belong to this chief
            $clients = Client::where('chief_id', Auth::id())->get();

            \Log::info('Allocation Create - Final counts', [
                'lands_count' => $lands->count(),
                'clients_count' => $clients->count()
            ]);

            $selectedLand = null;
            if ($request->has('land_id')) {
                $selectedLand = Land::where('chief_id', Auth::id())
                    ->where('id', $request->land_id)
                    ->first();
            }

            $selectedClient = null;
            if ($request->has('client_id')) {
                $selectedClient = Client::where('chief_id', Auth::id())
                    ->where('id', $request->client_id)
                    ->first();
            }

            return view('chiefs.allocations.create', compact('lands', 'clients', 'selectedLand', 'selectedClient'));

        } catch (\Exception $e) {
            \Log::error('Error in allocation create: ' . $e->getMessage());
            return redirect()->route('chief.allocations.index')
                ->with('error', 'Error loading allocation form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created allocation in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'land_id' => 'required|exists:lands,id',
            'client_id' => 'required|exists:clients,id',
            'allocation_date' => 'required|date',
            'duration_years' => 'required|integer|min:1|max:99',
            'purpose' => 'required|string|max:500',
            'terms' => 'nullable|string',
            'rent_amount' => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|in:monthly,quarterly,yearly',
        ]);

        try {
            // Verify the chief owns both the land and client
            $land = Land::where('chief_id', Auth::id())->findOrFail($validated['land_id']);
            $client = Client::where('chief_id', Auth::id())->findOrFail($validated['client_id']);

            // Verify land is available (check multiple possible status values)
            $availableStatuses = ['vacant', 'available', 'free', 'unallocated', null];
            $landStatus = $land->ownership_status ?? $land->status;
            if (!in_array($landStatus, $availableStatuses)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Selected land is not available for allocation. Current status: ' . ($landStatus ?? 'unknown'));
            }

            // Check if client already has an active allocation for this land
            $existingAllocation = Allocation::where('client_id', $validated['client_id'])
                ->where('land_id', $validated['land_id'])
                ->where('status', 'active')
                ->first();

            if ($existingAllocation) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This client already has an active allocation for the selected land.');
            }

            // Check if land is already allocated to someone else
            $landAllocated = Allocation::where('land_id', $validated['land_id'])
                ->where('status', 'active')
                ->where('client_id', '!=', $validated['client_id'])
                ->first();

            if ($landAllocated) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This land is already allocated to another client.');
            }

            // Automatically assign the current chief
            $validated['chief_id'] = Auth::id();
            $validated['status'] = 'active';

            $allocation = Allocation::create($validated);

            // Update land status
            $land->update([
                'ownership_status' => 'allocated',
                'status' => 'allocated'
            ]);

            // Log the allocation creation
            \Log::info('Allocation created', [
                'allocation_id' => $allocation->id,
                'client_id' => $client->id,
                'land_id' => $land->id,
                'chief_id' => Auth::id()
            ]);

            return redirect()->route('chief.allocations.index')
                ->with('success', 'Land allocated successfully to ' . $client->full_name . '!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Land or client not found for chief: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Selected land or client not found in your records.');
        } catch (\Exception $e) {
            \Log::error('Error creating allocation: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating allocation: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified allocation.
     */
    public function show(Allocation $allocation)
    {
        // Ensure the chief can only view their own allocations
        if ($allocation->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $allocation->load(['land', 'client', 'chief', 'documents']);

        // Calculate expiry date
        $expiryDate = $allocation->allocation_date->addYears($allocation->duration_years);
        $isExpired = $expiryDate->isPast();
        $daysUntilExpiry = $expiryDate->isFuture() ? $expiryDate->diffInDays(now()) : 0;

        return view('chiefs.allocations.show', compact('allocation', 'expiryDate', 'isExpired', 'daysUntilExpiry'));
    }

    /**
     * Show the form for editing the specified allocation.
     */
    public function edit(Allocation $allocation)
    {
        // Ensure the chief can only edit their own allocations
        if ($allocation->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Get ONLY lands that belong to this chief
            $lands = Land::where('chief_id', Auth::id())->get();
            
            // Get ONLY clients that belong to this chief
            $clients = Client::where('chief_id', Auth::id())->get();

            return view('chiefs.allocations.edit', compact('allocation', 'lands', 'clients'));

        } catch (\Exception $e) {
            \Log::error('Error loading allocation edit: ' . $e->getMessage());
            return redirect()->route('chief.allocations.index')
                ->with('error', 'Error loading allocation edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified allocation in storage.
     */
    public function update(Request $request, Allocation $allocation)
    {
        // Ensure the chief can only update their own allocations
        if ($allocation->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'land_id' => 'required|exists:lands,id',
            'client_id' => 'required|exists:clients,id',
            'allocation_date' => 'required|date',
            'duration_years' => 'required|integer|min:1|max:99',
            'purpose' => 'required|string|max:500',
            'terms' => 'nullable|string',
            'rent_amount' => 'nullable|numeric|min:0',
            'payment_frequency' => 'nullable|in:monthly,quarterly,yearly',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        try {
            // Verify the chief owns both the land and client
            $newLand = Land::where('chief_id', Auth::id())->findOrFail($validated['land_id']);
            $client = Client::where('chief_id', Auth::id())->findOrFail($validated['client_id']);

            // If land is being changed, update the old land status
            if ($allocation->land_id != $validated['land_id']) {
                $oldLand = $allocation->land;
                $oldLand->update([
                    'ownership_status' => 'vacant',
                    'status' => 'vacant'
                ]);

                // Verify new land is available
                $availableStatuses = ['vacant', 'available', 'free', 'unallocated', null];
                $newLandStatus = $newLand->ownership_status ?? $newLand->status;
                if (!in_array($newLandStatus, $availableStatuses)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'The selected land is not available for allocation. Current status: ' . $newLandStatus);
                }

                // Update new land status
                $newLand->update([
                    'ownership_status' => 'allocated',
                    'status' => 'allocated'
                ]);
            }

            $allocation->update($validated);

            // Log the allocation update
            \Log::info('Allocation updated', [
                'allocation_id' => $allocation->id,
                'client_id' => $client->id,
                'land_id' => $newLand->id,
                'chief_id' => Auth::id()
            ]);

            return redirect()->route('chief.allocations.show', $allocation)
                ->with('success', 'Allocation updated successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Land or client not found for chief during update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Selected land or client not found in your records.');
        } catch (\Exception $e) {
            \Log::error('Error updating allocation: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating allocation: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified allocation from storage.
     */
    public function destroy(Allocation $allocation)
    {
        // Ensure the chief can only delete their own allocations
        if ($allocation->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Store information for logging before deletion
            $allocationId = $allocation->id;
            $landId = $allocation->land_id;
            $clientId = $allocation->client_id;

            // Update land status back to vacant
            $allocation->land->update([
                'ownership_status' => 'vacant',
                'status' => 'vacant'
            ]);

            $allocation->delete();

            // Log the allocation deletion
            \Log::info('Allocation deleted', [
                'allocation_id' => $allocationId,
                'client_id' => $clientId,
                'land_id' => $landId,
                'chief_id' => Auth::id()
            ]);

            return redirect()->route('chief.allocations.index')
                ->with('success', 'Allocation deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Error deleting allocation: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error deleting allocation: ' . $e->getMessage());
        }
    }

    /**
     * Show delete confirmation page.
     */
    public function delete(Allocation $allocation)
    {
        // Ensure the chief can only delete their own allocations
        if ($allocation->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('chiefs.allocations.delete', compact('allocation'));
    }

    /**
     * Generate allocation letter.
     */
    public function generateAllocationLetter(Allocation $allocation)
    {
        // Ensure the chief can only generate letters for their own allocations
        if ($allocation->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $allocation->load(['land', 'client', 'chief']);
        $expiryDate = $allocation->allocation_date->addYears($allocation->duration_years);

        return view('chiefs.allocations.allocation-letter', compact('allocation', 'expiryDate'));
    }

    /**
     * Generate certificate.
     */
    public function generateCertificate(Allocation $allocation)
    {
        // Ensure the chief can only generate certificates for their own allocations
        if ($allocation->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $allocation->load(['land', 'client', 'chief']);
        $expiryDate = $allocation->allocation_date->addYears($allocation->duration_years);

        return view('chiefs.allocations.certificate', compact('allocation', 'expiryDate'));
    }

    /**
     * Get allocation statistics for the chief.
     */
    public function getAllocationStats()
    {
        $chief = Auth::user();
        
        $totalAllocations = $chief->allocations()->count();
        $activeAllocations = $chief->allocations()->where('status', 'active')->count();
        $inactiveAllocations = $chief->allocations()->where('status', 'inactive')->count();
        $terminatedAllocations = $chief->allocations()->where('status', 'terminated')->count();

        // Calculate expired allocations
        $expiredAllocations = $chief->allocations()
            ->where('status', 'active')
            ->whereRaw('DATE_ADD(allocation_date, INTERVAL duration_years YEAR) < NOW()')
            ->count();

        $stats = [
            'total_allocations' => $totalAllocations,
            'active_allocations' => $activeAllocations,
            'expired_allocations' => $expiredAllocations,
            'inactive_allocations' => $inactiveAllocations,
            'terminated_allocations' => $terminatedAllocations,
        ];

        return response()->json($stats);
    }

    /**
     * Renew an allocation (extend duration)
     */
    public function renew(Request $request, Allocation $allocation)
    {
        // Ensure the chief can only renew their own allocations
        if ($allocation->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'extension_years' => 'required|integer|min:1|max:20',
            'renewal_date' => 'required|date',
        ]);

        try {
            // Update the allocation duration
            $allocation->update([
                'duration_years' => $allocation->duration_years + $validated['extension_years'],
                'allocation_date' => $validated['renewal_date'],
                'status' => 'active'
            ]);

            // Log the allocation renewal
            \Log::info('Allocation renewed', [
                'allocation_id' => $allocation->id,
                'extension_years' => $validated['extension_years'],
                'chief_id' => Auth::id()
            ]);

            return redirect()->route('chief.allocations.show', $allocation)
                ->with('success', 'Allocation renewed successfully for ' . $validated['extension_years'] . ' additional years!');

        } catch (\Exception $e) {
            \Log::error('Error renewing allocation: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error renewing allocation: ' . $e->getMessage());
        }
    }

    /**
     * Get available lands for allocation (AJAX endpoint)
     */
    public function getAvailableLands()
    {
        try {
            $lands = Land::where('chief_id', Auth::id())
                ->where(function($query) {
                    $query->where('ownership_status', 'vacant')
                          ->orWhere('ownership_status', 'available')
                          ->orWhere('ownership_status', 'free')
                          ->orWhere('ownership_status', 'unallocated')
                          ->orWhere('status', 'vacant')
                          ->orWhere('status', 'available')
                          ->orWhere('status', 'free')
                          ->orWhere('status', 'unallocated')
                          ->orWhereNull('ownership_status')
                          ->orWhereNull('status');
                })
                ->select('id', 'plot_number', 'location', 'size', 'ownership_status', 'status')
                ->get();

            return response()->json([
                'success' => true,
                'lands' => $lands,
                'count' => $lands->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching available lands: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching available lands'
            ], 500);
        }
    }

    /**
     * Get available clients for allocation (AJAX endpoint)
     */
    public function getAvailableClients()
    {
        try {
            $clients = Client::where('chief_id', Auth::id())
                ->select('id', 'full_name', 'id_number', 'phone')
                ->get();

            return response()->json([
                'success' => true,
                'clients' => $clients,
                'count' => $clients->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching available clients: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching available clients'
            ], 500);
        }
    }
}