<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Land;
use App\Models\Client;
use App\Models\Chief;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreAllocationRequest;
use App\Http\Requests\UpdateAllocationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AllocationsExport;
use App\Imports\AllocationsImport;

class AllocationController extends Controller
{
    public function index(Request $request): View
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

    public function create(): View
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

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($allocation)
                ->log('created allocation for land: ' . $allocation->land->plot_number);
        }

        return redirect()->route('allocations.show', $allocation)
            ->with('success', 'Allocation created successfully!');
    }

    public function show(Allocation $allocation): View
    {
        $allocation->load(['land', 'client', 'chief', 'processedBy', 'documents']);
        return view('allocations.show', compact('allocation'));
    }

    public function edit(Allocation $allocation): View
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

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($allocation)
                ->log('updated allocation: ' . $allocation->id);
        }

        return redirect()->route('allocations.show', $allocation)
            ->with('success', 'Allocation updated successfully!');
    }

    public function destroy(Allocation $allocation): RedirectResponse
    {
        // Free up the land
        Land::where('id', $allocation->land_id)->update(['ownership_status' => 'vacant']);

        $allocationId = $allocation->id;
        $allocation->delete();

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->log('deleted allocation: ' . $allocationId);
        }

        return redirect()->route('allocations.index')
            ->with('success', 'Allocation deleted successfully!');
    }

    public function approve(Allocation $allocation): RedirectResponse
    {
        $allocation->update([
            'approval_status' => 'approved',
            'chief_approval_date' => now()
        ]);

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($allocation)
                ->log('approved allocation: ' . $allocation->id);
        }

        return redirect()->back()->with('success', 'Allocation approved successfully!');
    }

    public function reject(Allocation $allocation): RedirectResponse
    {
        $allocation->update([
            'approval_status' => 'rejected'
        ]);

        // Free up the land
        Land::where('id', $allocation->land_id)->update(['ownership_status' => 'vacant']);

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($allocation)
                ->log('rejected allocation: ' . $allocation->id);
        }

        return redirect()->back()->with('success', 'Allocation rejected successfully!');
    }

    public function markPending(Allocation $allocation): RedirectResponse
    {
        $allocation->update([
            'approval_status' => 'pending',
            'chief_approval_date' => null
        ]);

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($allocation)
                ->log('marked allocation as pending: ' . $allocation->id);
        }

        return redirect()->back()->with('success', 'Allocation marked as pending successfully!');
    }

    public function generateAllocationLetter(Allocation $allocation)
{
    try {
        $allocation->load(['land', 'client', 'chief']);
        
        // Hardcoded safe filename
        $filename = "allocation_letter.pdf";
        
        $pdf = PDF::loadView('allocations.allocation-letter', compact('allocation'));
        
        return $pdf->download($filename);
        
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to generate allocation letter: ' . $e->getMessage());
    }
}

public function generateCertificate(Allocation $allocation)
{
    try {
        $allocation->load(['land', 'client', 'chief']);
        
        // Hardcoded safe filename
        $filename = "certificate.pdf";
        
        $pdf = PDF::loadView('allocations.certificate', compact('allocation'));
        
        return $pdf->download($filename);
        
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to generate certificate: ' . $e->getMessage());
    }
}

    public function export(Request $request)
    {
        try {
            $filename = "allocations_" . date('Y-m-d') . ".xlsx";
            return Excel::download(new AllocationsExport($request), $filename);
        } catch (\Exception $e) {
            logger()->error('Allocations export failed', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new AllocationsImport, $request->file('file'));
            
            // Log activity
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->log('imported allocations from file');
            }
            
            return redirect()->route('allocations.index')
                ->with('success', 'Allocations imported successfully!');
                
        } catch (\Exception $e) {
            logger()->error('Allocations import failed', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('allocations.index')
                ->with('error', 'Error importing allocations: ' . $e->getMessage());
        }
    }

    public function bulkApprove(Request $request): RedirectResponse
    {
        $request->validate([
            'allocations' => 'required|array',
            'allocations.*' => 'exists:allocations,id'
        ]);

        try {
            $allocations = Allocation::whereIn('id', $request->allocations)->get();
            
            foreach ($allocations as $allocation) {
                $allocation->update([
                    'approval_status' => 'approved',
                    'chief_approval_date' => now()
                ]);
            }

            $count = $allocations->count();

            // Log activity
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->log('bulk approved ' . $count . ' allocations');
            }

            return redirect()->route('allocations.index')
                ->with('success', $count . ' allocations approved successfully!');
                
        } catch (\Exception $e) {
            logger()->error('Bulk approve failed', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Bulk approval failed: ' . $e->getMessage());
        }
    }

    public function bulkReject(Request $request): RedirectResponse
    {
        $request->validate([
            'allocations' => 'required|array',
            'allocations.*' => 'exists:allocations,id'
        ]);

        try {
            $allocations = Allocation::whereIn('id', $request->allocations)->get();
            
            foreach ($allocations as $allocation) {
                $allocation->update(['approval_status' => 'rejected']);
                // Free up the land
                Land::where('id', $allocation->land_id)->update(['ownership_status' => 'vacant']);
            }

            $count = $allocations->count();

            // Log activity
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->log('bulk rejected ' . $count . ' allocations');
            }

            return redirect()->route('allocations.index')
                ->with('success', $count . ' allocations rejected successfully!');
                
        } catch (\Exception $e) {
            logger()->error('Bulk reject failed', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Bulk rejection failed: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'allocations' => 'required|array',
            'allocations.*' => 'exists:allocations,id'
        ]);

        try {
            $allocations = Allocation::whereIn('id', $request->allocations)->get();
            
            foreach ($allocations as $allocation) {
                // Free up the land
                Land::where('id', $allocation->land_id)->update(['ownership_status' => 'vacant']);
                $allocation->delete();
            }

            $count = $allocations->count();

            // Log activity
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->log('bulk deleted ' . $count . ' allocations');
            }

            return redirect()->route('allocations.index')
                ->with('success', $count . ' allocations deleted successfully!');
                
        } catch (\Exception $e) {
            logger()->error('Bulk delete failed', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Bulk deletion failed: ' . $e->getMessage());
        }
    }

    /**
     * Chief's allocations view
     */
    public function chiefAllocations(): View
    {
        $chiefId = auth()->id();
        $allocations = Allocation::with(['land', 'client'])
            ->where('chief_id', $chiefId)
            ->latest()
            ->paginate(15);

        $stats = [
            'total_allocations' => Allocation::where('chief_id', $chiefId)->count(),
            'approved_allocations' => Allocation::where('chief_id', $chiefId)->where('approval_status', 'approved')->count(),
            'pending_allocations' => Allocation::where('chief_id', $chiefId)->where('approval_status', 'pending')->count(),
            'rejected_allocations' => Allocation::where('chief_id', $chiefId)->where('approval_status', 'rejected')->count(),
        ];

        return view('chief.allocations', compact('allocations', 'stats'));
    }

    /**
     * Get allocation statistics for dashboard
     */
    public function getAllocationStats(): JsonResponse
    {
        $totalAllocations = Allocation::count();
        $pendingAllocations = Allocation::where('approval_status', 'pending')->count();
        $approvedAllocations = Allocation::where('approval_status', 'approved')->count();
        $rejectedAllocations = Allocation::where('approval_status', 'rejected')->count();

        return response()->json([
            'total' => $totalAllocations,
            'pending' => $pendingAllocations,
            'approved' => $approvedAllocations,
            'rejected' => $rejectedAllocations,
        ]);
    }
}