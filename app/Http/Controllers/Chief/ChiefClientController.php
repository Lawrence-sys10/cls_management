<?php

namespace App\Http\Controllers\Chief;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChiefClientController extends Controller
{
    /**
     * Display a listing of the chief's clients.
     */
    public function index(Request $request)
    {
        // Get clients directly through chief_id relationship
        $clients = Client::where('chief_id', Auth::id())
            ->withCount('allocations')
            ->with(['allocations.land'])
            ->latest();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $clients->where(function($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%')
                      ->orWhere('phone', 'like', '%' . $request->search . '%')
                      ->orWhere('id_number', 'like', '%' . $request->search . '%');
            });
        }

        // Apply gender filter
        if ($request->has('gender') && $request->gender) {
            $clients->where('gender', $request->gender);
        }

        // Apply has allocations filter
        if ($request->has('has_allocations') && $request->has_allocations !== '') {
            if ($request->has_allocations) {
                $clients->has('allocations');
            } else {
                $clients->doesntHave('allocations');
            }
        }

        $clients = $clients->paginate(10);

        // Add additional computed properties for the view
        $clients->getCollection()->transform(function ($client) {
            $client->has_disputes = $client->allocations->contains('has_disputes', true);
            $client->created_this_month = $client->created_at->greaterThanOrEqualTo(Carbon::now()->startOfMonth());
            return $client;
        });

        return view('chiefs.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('chiefs.clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email',
            'phone' => 'required|string|max:20|unique:clients,phone',
            'id_type' => 'required|string|in:ghanacard,passport,drivers_license,voters_id',
            'id_number' => 'required|string|max:50|unique:clients,id_number',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'occupation' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            // Add chief_id to the validated data and create client
            $validated['chief_id'] = Auth::id();
            $client = Client::create($validated);

            return redirect()->route('chief.clients.index')
                ->with('success', 'Client added successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating client: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        // Ensure the chief can only view their own clients
        if ($client->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $client->load(['allocations.land', 'documents']);

        return view('chiefs.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        // Ensure the chief can only edit their own clients
        if ($client->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('chiefs.clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        // Ensure the chief can only update their own clients
        if ($client->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email,' . $client->id,
            'phone' => 'required|string|max:20|unique:clients,phone,' . $client->id,
            'id_type' => 'required|string|in:ghanacard,passport,drivers_license,voters_id',
            'id_number' => 'required|string|max:50|unique:clients,id_number,' . $client->id,
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'occupation' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('chief.clients.show', $client)
            ->with('success', 'Client updated successfully!');
    }

    /**
     * Show delete confirmation page.
     */
    public function delete(Client $client)
    {
        // Ensure the chief can only delete their own clients
        if ($client->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $allocationCount = $client->allocations()->count();
        $documentCount = $client->documents()->count();

        return view('chiefs.clients.delete', compact('client', 'allocationCount', 'documentCount'));
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        // Ensure the chief can only delete their own clients
        if ($client->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if client has allocations
        if ($client->allocations()->exists()) {
            return redirect()->route('chief.clients.delete', $client)
                ->with('error', 'Cannot delete client that has land allocations. Please delete all allocations first.');
        }

        // Delete associated documents first
        $client->documents()->delete();

        $clientName = $client->full_name;
        $client->delete();

        return redirect()->route('chief.clients.index')
            ->with('success', "Client '$clientName' has been permanently deleted.");
    }

    /**
     * Show client allocations.
     */
    public function allocations(Client $client)
    {
        // Ensure the chief can only view allocations for their own clients
        if ($client->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $allocations = $client->allocations()
            ->with(['land'])
            ->latest()
            ->paginate(15);

        // Calculate statistics for the client's allocations
        $stats = [
            'total_allocations' => $client->allocations()->count(),
            'approved_allocations' => $client->allocations()
                ->where('approval_status', 'approved')
                ->count(),
            'pending_allocations' => $client->allocations()
                ->where('approval_status', 'pending')
                ->count(),
            'rejected_allocations' => $client->allocations()
                ->where('approval_status', 'rejected')
                ->count(),
            'active_allocations' => $client->allocations()
                ->where('approval_status', 'approved')
                ->count(),
        ];

        return view('clients.allocations', compact('client', 'allocations', 'stats'));
    }

    /**
     * Show client documents.
     */
    public function documents(Client $client)
    {
        // Ensure the chief can only view documents for their own clients
        if ($client->chief_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $documents = $client->documents()->latest()->paginate(10);

        return view('chiefs.clients.documents', compact('client', 'documents'));
    }

    /**
     * Get client statistics for dashboard
     */
    public function statistics()
    {
        $totalClients = Client::where('chief_id', Auth::id())->count();
        $clientsWithAllocations = Client::where('chief_id', Auth::id())->has('allocations')->count();
        $newClientsThisMonth = Client::where('chief_id', Auth::id())
            ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        return response()->json([
            'total_clients' => $totalClients,
            'clients_with_allocations' => $clientsWithAllocations,
            'new_clients_this_month' => $newClientsThisMonth,
        ]);
    }

    /**
     * Search clients for autocomplete (Select2 format)
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q');
            
            \Log::info('Client search query:', ['query' => $query, 'chief_id' => Auth::id()]);
            
            $clients = Client::where('chief_id', Auth::id())
                ->where(function($q) use ($query) {
                    $q->where('full_name', 'like', "%{$query}%")
                      ->orWhere('id_number', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->select('id', 'full_name', 'id_number', 'phone', 'email')
                ->limit(10)
                ->get();

            \Log::info('Client search results:', ['count' => $clients->count()]);

            return response()->json([
                'success' => true,
                'clients' => $clients
            ]);
        } catch (\Exception $e) {
            \Log::error('Client search error:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error searching clients'
            ], 500);
        }
    }

    /**
     * Quick client creation for allocation form
     */
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:50|unique:clients,id_number',
            'phone' => 'required|string|max:20|unique:clients,phone',
            'email' => 'nullable|email|max:255|unique:clients,email',
        ]);

        try {
            // Add chief_id and default values
            $validated['chief_id'] = Auth::id();
            $validated['id_type'] = 'ghanacard';
            $validated['address'] = 'Address to be updated';
            $validated['date_of_birth'] = now()->subYears(25)->format('Y-m-d');
            $validated['gender'] = 'other';

            $client = Client::create($validated);

            return response()->json([
                'success' => true,
                'client' => $client,
                'message' => 'Client created successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating client: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client details for allocation form
     */
    public function getClientDetails(Client $client)
    {
        // Ensure the chief can only access their own clients
        if ($client->chief_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'client' => [
                'id' => $client->id,
                'full_name' => $client->full_name,
                'id_number' => $client->id_number,
                'phone' => $client->phone,
                'email' => $client->email,
                'address' => $client->address,
            ]
        ]);
    }

    /**
     * Check if client exists by ID number or phone
     */
    public function checkExisting(Request $request)
    {
        $request->validate([
            'id_number' => 'required_without:phone|string|max:50',
            'phone' => 'required_without:id_number|string|max:20',
        ]);

        $client = Client::where('chief_id', Auth::id())
            ->where(function($query) use ($request) {
                if ($request->id_number) {
                    $query->where('id_number', $request->id_number);
                }
                if ($request->phone) {
                    $query->orWhere('phone', $request->phone);
                }
            })
            ->first();

        if ($client) {
            return response()->json([
                'exists' => true,
                'client' => $client
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
    }
}