<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Client::withCount('allocations');

        if ($request->has('search') && $request->search) {
            $query->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('id_number', 'like', '%' . $request->search . '%');
        }

        $clients = $query->latest()->paginate(20);
        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::create($request->validated());

        // Check if activity logging is available before using it
        if (function_exists('activity') && class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($client)
                ->log('created client: ' . $client->full_name);
        }

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client registered successfully!');
    }

    public function show(Client $client): View
    {
        $client->load(['allocations.land.chief', 'documents']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        // Check if activity logging is available before using it
        if (function_exists('activity') && class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($client)
                ->log('updated client: ' . $client->full_name);
        }

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client_name = $client->full_name;
        $client->delete();

        // Check if activity logging is available before using it
        if (function_exists('activity') && class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            activity()
                ->causedBy(auth()->user())
                ->log('deleted client: ' . $client_name);
        }

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully!');
    }

    public function export(Request $request)
    {
        try {
            logger()->info('Client export started', ['user_id' => auth()->id()]);
            
            $export = new \App\Exports\ClientsExport($request);
            return $export->download();
            
        } catch (\Exception $e) {
            logger()->error('Client export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mixes:xlsx,xls,csv'
        ]);

        Excel::import(new ClientsImport, $request->file('file'));

        return redirect()->route('clients.index')
            ->with('success', 'Clients imported successfully!');
    }

    /**
     * Show client's allocations
     */
    public function allocations(Client $client): View
    {
        $allocations = Allocation::with(['land', 'chief'])
            ->where('client_id', $client->id)
            ->latest()
            ->paginate(15);

        return view('clients.allocations', compact('client', 'allocations'));
    }

    /**
     * Show client's documents
     */
    public function documents(Client $client): View
    {
        $client->load('documents');
        return view('clients.documents', compact('client'));
    }

    /**
     * Bulk actions for clients
     */
    public function bulkActions(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|string|in:export,delete',
            'clients' => 'required|array',
            'clients.*' => 'exists:clients,id'
        ]);

        $clients = Client::whereIn('id', $request->clients)->get();

        switch ($request->action) {
            case 'export':
                // Handle export logic
                return $this->export($request);
            
            case 'delete':
                // Check if any client has allocations
                $clientsWithAllocations = Client::whereIn('id', $request->clients)
                    ->whereHas('allocations')
                    ->count();

                if ($clientsWithAllocations > 0) {
                    return redirect()->route('clients.index')
                        ->with('error', 'Cannot delete clients with existing allocations.');
                }

                $count = Client::whereIn('id', $request->clients)->delete();

                // Log activity
                if (function_exists('activity')) {
                    activity()
                        ->causedBy(auth()->user())
                        ->log('bulk deleted ' . $count . ' clients');
                }

                return redirect()->route('clients.index')
                    ->with('success', $count . ' clients deleted successfully!');
        }
    }

    /**
     * Bulk delete clients
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'clients' => 'required|array',
            'clients.*' => 'exists:clients,id'
        ]);

        // Check if any client has allocations
        $clientsWithAllocations = Client::whereIn('id', $request->clients)
            ->whereHas('allocations')
            ->count();

        if ($clientsWithAllocations > 0) {
            return redirect()->route('clients.index')
                ->with('error', 'Cannot delete clients with existing allocations.');
        }

        $count = Client::whereIn('id', $request->clients)->delete();

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->log('bulk deleted ' . $count . ' clients');
        }

        return redirect()->route('clients.index')
            ->with('success', $count . ' clients deleted successfully!');
    }

    /**
     * Download import template
     */
    public function downloadImportTemplate()
    {
        $templatePath = storage_path('app/templates/client-import-template.xlsx');
        
        if (!file_exists($templatePath)) {
            return redirect()->route('clients.index')
                ->with('error', 'Import template not found.');
        }

        return response()->download($templatePath, 'client-import-template.xlsx');
    }
}