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
