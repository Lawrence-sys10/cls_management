<?php

namespace App\Http\Controllers\Chief;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChiefDisputeController extends Controller
{
    /**
     * Display a listing of the chief's disputes.
     */
    public function index(Request $request)
    {
        // Get only the chief's disputes with related data
        $disputes = Auth::user()->disputes()
            ->with(['land', 'complainant', 'respondent'])
            ->latest();

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $disputes->where(function($query) use ($request) {
                $query->where('case_number', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%')
                      ->orWhereHas('land', function($q) use ($request) {
                          $q->where('plot_number', 'like', '%' . $request->search . '%');
                      })
                      ->orWhereHas('complainant', function($q) use ($request) {
                          $q->where('full_name', 'like', '%' . $request->search . '%');
                      });
            });
        }

        // Apply status filter
        if ($request->has('status') && $request->status && $request->status !== 'all') {
            $disputes->where('status', $request->status);
        }

        // Apply priority filter
        if ($request->has('priority') && $request->priority) {
            $disputes->where('severity', $request->priority);
        }

        $disputes = $disputes->paginate(10);

        return view('chiefs.disputes.index', compact('disputes'));
    }

    /**
     * Show the form for creating a new dispute.
     */
    public function create(Request $request)
    {
        $lands = Auth::user()->lands()->get();
        $clients = Auth::user()->clients()->get();

        $selectedLand = null;
        if ($request->has('land_id')) {
            $selectedLand = Auth::user()->lands()->find($request->land_id);
        }

        return view('chiefs.disputes.create', compact('lands', 'clients', 'selectedLand'));
    }

    /**
     * Store a newly created dispute in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'land_id' => 'required|exists:lands,id',
            'complainant_id' => 'required|exists:clients,id',
            'respondent_id' => 'nullable|exists:clients,id',
            'case_number' => 'required|string|max:100|unique:disputes,case_number',
            'dispute_type' => 'required|in:boundary,ownership,inheritance,other',
            'description' => 'required|string',
            'filing_date' => 'required|date',
            'severity' => 'required|in:low,medium,high,critical',
        ]);

        // Verify the chief owns the land and clients
        Auth::user()->lands()->findOrFail($validated['land_id']);
        Auth::user()->clients()->findOrFail($validated['complainant_id']);
        
        if ($validated['respondent_id']) {
            Auth::user()->clients()->findOrFail($validated['respondent_id']);
        }

        // Automatically assign the current chief
        $validated['chief_id'] = Auth::id();
        $validated['status'] = 'pending';

        $dispute = Dispute::create($validated);

        // Update land status to under dispute
        $land = Land::find($validated['land_id']);
        $land->update(['ownership_status' => 'under_dispute']);

        return redirect()->route('chief.disputes.index')
            ->with('success', 'Dispute registered successfully!');
    }

    /**
     * Display the specified dispute.
     */
    public function show(Dispute $dispute)
    {
        // Ensure the chief can only view their own disputes
        $this->authorize('view', $dispute);

        $dispute->load(['land', 'complainant', 'respondent', 'chief', 'resolutions']);

        return view('chiefs.disputes.show', compact('dispute'));
    }

    /**
     * Show the form for editing the specified dispute.
     */
    public function edit(Dispute $dispute)
    {
        // Ensure the chief can only edit their own disputes
        $this->authorize('update', $dispute);

        $lands = Auth::user()->lands()->get();
        $clients = Auth::user()->clients()->get();

        return view('chiefs.disputes.edit', compact('dispute', 'lands', 'clients'));
    }

    /**
     * Update the specified dispute in storage.
     */
    public function update(Request $request, Dispute $dispute)
    {
        // Ensure the chief can only update their own disputes
        $this->authorize('update', $dispute);

        $validated = $request->validate([
            'land_id' => 'required|exists:lands,id',
            'complainant_id' => 'required|exists:clients,id',
            'respondent_id' => 'nullable|exists:clients,id',
            'case_number' => 'required|string|max:100|unique:disputes,case_number,' . $dispute->id,
            'dispute_type' => 'required|in:boundary,ownership,inheritance,other',
            'description' => 'required|string',
            'filing_date' => 'required|date',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:pending,investigation,hearing,resolved,closed',
        ]);

        // Verify the chief owns the land and clients
        Auth::user()->lands()->findOrFail($validated['land_id']);
        Auth::user()->clients()->findOrFail($validated['complainant_id']);
        
        if ($validated['respondent_id']) {
            Auth::user()->clients()->findOrFail($validated['respondent_id']);
        }

        $dispute->update($validated);

        return redirect()->route('chief.disputes.index')
            ->with('success', 'Dispute updated successfully!');
    }

    /**
     * Show resolve dispute form.
     */
    public function showResolveForm(Dispute $dispute)
    {
        $this->authorize('update', $dispute);
        
        return view('chiefs.disputes.resolve', compact('dispute'));
    }

    /**
     * Resolve a dispute.
     */
    public function resolve(Request $request, Dispute $dispute)
    {
        // Ensure the chief can only resolve their own disputes
        $this->authorize('update', $dispute);

        $validated = $request->validate([
            'resolution' => 'required|string',
            'resolution_date' => 'required|date',
            'outcome' => 'required|in:resolved_in_favor_complainant,resolved_in_favor_respondent,compromise,withdrawn',
            'notes' => 'nullable|string',
        ]);

        $dispute->update([
            'status' => 'resolved',
            'resolved_at' => $validated['resolution_date'],
        ]);

        // Create resolution record
        $dispute->resolutions()->create([
            'resolution' => $validated['resolution'],
            'outcome' => $validated['outcome'],
            'resolved_by' => Auth::id(),
            'notes' => $validated['notes'],
        ]);

        // Update land status based on outcome
        $land = $dispute->land;
        if (in_array($validated['outcome'], ['resolved_in_favor_complainant', 'resolved_in_favor_respondent', 'compromise'])) {
            $land->update(['ownership_status' => 'allocated']);
        } else {
            $land->update(['ownership_status' => 'vacant']);
        }

        return redirect()->route('chief.disputes.show', $dispute)
            ->with('success', 'Dispute resolved successfully!');
    }

    /**
     * Close a dispute.
     */
    public function close(Dispute $dispute)
    {
        // Ensure the chief can only close their own disputes
        $this->authorize('update', $dispute);

        $dispute->update(['status' => 'closed']);

        return redirect()->back()
            ->with('success', 'Dispute closed successfully!');
    }

    /**
     * Reopen a dispute.
     */
    public function reopen(Dispute $dispute)
    {
        // Ensure the chief can only reopen their own disputes
        $this->authorize('update', $dispute);

        $dispute->update(['status' => 'pending']);

        // Update land status back to under dispute
        $dispute->land->update(['ownership_status' => 'under_dispute']);

        return redirect()->back()
            ->with('success', 'Dispute reopened successfully!');
    }

    /**
     * Remove the specified dispute from storage.
     */
    public function destroy(Dispute $dispute)
    {
        // Ensure the chief can only delete their own disputes
        $this->authorize('delete', $dispute);

        $dispute->delete();

        return redirect()->route('chief.disputes.index')
            ->with('success', 'Dispute deleted successfully!');
    }

    /**
     * Get dispute statistics for the chief.
     */
    public function getDisputeStats()
    {
        $chief = Auth::user();
        
        $stats = [
            'total_disputes' => $chief->disputes()->count(),
            'pending_disputes' => $chief->disputes()->where('status', 'pending')->count(),
            'investigation_disputes' => $chief->disputes()->where('status', 'investigation')->count(),
            'hearing_disputes' => $chief->disputes()->where('status', 'hearing')->count(),
            'resolved_disputes' => $chief->disputes()->where('status', 'resolved')->count(),
            'closed_disputes' => $chief->disputes()->where('status', 'closed')->count(),
        ];

        return response()->json($stats);
    }
}