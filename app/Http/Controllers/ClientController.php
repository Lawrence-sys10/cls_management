<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;

class ClientController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
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

    public function create(): \Illuminate\View\View
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

    public function show(Client $client): \Illuminate\View\View
    {
        $client->load(['allocations.land.chief', 'documents']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): \Illuminate\View\View
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
        return Excel::download(new ClientsExport($request), 'clients-' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new ClientsImport, $request->file('file'));

        return redirect()->route('clients.index')
            ->with('success', 'Clients imported successfully!');
    }
}