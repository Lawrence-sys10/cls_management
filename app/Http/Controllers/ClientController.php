<?php

namespace App\Http\Controllers;

use App\Models\Chief;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChiefController extends Controller
{
    public function index(Request $request): View
    {
        $query = Chief::query();

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('jurisdiction', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $chiefs = $query->latest()->paginate(20);
        return view('chiefs.index', compact('chiefs'));
    }

    public function create(): View
    {
        return view('chiefs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jurisdiction' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'area_boundaries' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Chief::create(array_merge($validated, ['is_active' => $request->has('is_active')]));

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->log('created chief: ' . $validated['name']);
        }

        return redirect()->route('chiefs.index')
            ->with('success', 'Chief created successfully!');
    }

    public function show(Chief $chief): View
    {
        // Load chief's allocations with related data
        $allocations = Allocation::with(['client', 'land'])
            ->where('chief_id', $chief->id)
            ->latest()
            ->paginate(10);

        // Get allocation statistics
        $stats = [
            'total_allocations' => Allocation::where('chief_id', $chief->id)->count(),
            'approved_allocations' => Allocation::where('chief_id', $chief->id)
                ->where('approval_status', 'approved')->count(),
            'pending_allocations' => Allocation::where('chief_id', $chief->id)
                ->where('approval_status', 'pending')->count(),
            'rejected_allocations' => Allocation::where('chief_id', $chief->id)
                ->where('approval_status', 'rejected')->count(),
        ];

        return view('chiefs.show', compact('chief', 'allocations', 'stats'));
    }

    public function edit(Chief $chief): View
    {
        return view('chiefs.edit', compact('chief'));
    }

    public function update(Request $request, Chief $chief): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jurisdiction' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'area_boundaries' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $chief->update(array_merge($validated, ['is_active' => $request->has('is_active')]));

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($chief)
                ->log('updated chief: ' . $chief->name);
        }

        return redirect()->route('chiefs.show', $chief)
            ->with('success', 'Chief updated successfully!');
    }

    public function destroy(Chief $chief): RedirectResponse
    {
        // Check if chief has allocations
        if ($chief->allocations()->exists()) {
            return redirect()->route('chiefs.index')
                ->with('error', 'Cannot delete chief with existing allocations. Please reassign allocations first.');
        }

        $chiefName = $chief->name;
        $chief->delete();

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->log('deleted chief: ' . $chiefName);
        }

        return redirect()->route('chiefs.index')
            ->with('success', 'Chief deleted successfully!');
    }

    public function toggleStatus(Chief $chief): RedirectResponse
    {
        $chief->update([
            'is_active' => !$chief->is_active
        ]);

        $status = $chief->is_active ? 'activated' : 'deactivated';

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($chief)
                ->log("{$status} chief: " . $chief->name);
        }

        return redirect()->back()
            ->with('success', "Chief {$status} successfully!");
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Chief::query();

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('jurisdiction', 'like', '%' . $request->search . '%');
        }

        $chiefs = $query->get();

        $fileName = 'chiefs-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function() use ($chiefs) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Name', 'Jurisdiction', 'Phone', 'Email', 'Area Boundaries', 'Status', 'Created At']);
            
            // Data
            foreach ($chiefs as $chief) {
                fputcsv($file, [
                    $chief->id,
                    $chief->name,
                    $chief->jurisdiction,
                    $chief->phone,
                    $chief->email,
                    $chief->area_boundaries,
                    $chief->is_active ? 'Active' : 'Inactive',
                    $chief->created_at->format('Y-m-d')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk actions for chiefs
     */
    public function bulkActions(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|string|in:activate,deactivate,delete,export',
            'chiefs' => 'required|array',
            'chiefs.*' => 'exists:chiefs,id'
        ]);

        $chiefs = Chief::whereIn('id', $request->chiefs)->get();

        switch ($request->action) {
            case 'activate':
                $chiefs->each->update(['is_active' => true]);
                $count = $chiefs->count();
                
                if (function_exists('activity')) {
                    activity()
                        ->causedBy(auth()->user())
                        ->log("bulk activated {$count} chiefs");
                }
                
                return redirect()->route('chiefs.index')
                    ->with('success', $count . ' chiefs activated successfully!');

            case 'deactivate':
                $chiefs->each->update(['is_active' => false]);
                $count = $chiefs->count();
                
                if (function_exists('activity')) {
                    activity()
                        ->causedBy(auth()->user())
                        ->log("bulk deactivated {$count} chiefs");
                }
                
                return redirect()->route('chiefs.index')
                    ->with('success', $count . ' chiefs deactivated successfully!');

            case 'delete':
                // Check if any chief has allocations
                $chiefsWithAllocations = Chief::whereIn('id', $request->chiefs)
                    ->whereHas('allocations')
                    ->count();

                if ($chiefsWithAllocations > 0) {
                    return redirect()->route('chiefs.index')
                        ->with('error', 'Cannot delete chiefs with existing allocations.');
                }

                $count = Chief::whereIn('id', $request->chiefs)->delete();

                if (function_exists('activity')) {
                    activity()
                        ->causedBy(auth()->user())
                        ->log("bulk deleted {$count} chiefs");
                }

                return redirect()->route('chiefs.index')
                    ->with('success', $count . ' chiefs deleted successfully!');

            case 'export':
                return $this->export($request);
        }
    }

    /**
     * Get chief statistics for dashboard
     */
    public function getChiefStats(): \Illuminate\Http\JsonResponse
    {
        $totalChiefs = Chief::count();
        $activeChiefs = Chief::where('is_active', true)->count();
        $inactiveChiefs = Chief::where('is_active', false)->count();

        return response()->json([
            'total' => $totalChiefs,
            'active' => $activeChiefs,
            'inactive' => $inactiveChiefs,
        ]);
    }
}