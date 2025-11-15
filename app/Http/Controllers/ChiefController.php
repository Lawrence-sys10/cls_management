<?php

namespace App\Http\Controllers;

use App\Models\Chief;
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
            $query->where('full_name', 'like', '%' . $request->search . '%');
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
            'full_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'traditional_area' => 'required|string',
            'community' => 'required|string',
            'region' => 'required|string',
        ]);

        Chief::create($validated);

        return redirect()->route('chiefs.index')
            ->with('success', 'Chief created successfully!');
    }

    public function show(Chief $chief): View
    {
        return view('chiefs.show', compact('chief'));
    }

    public function edit(Chief $chief): View
    {
        return view('chiefs.edit', compact('chief'));
    }

    public function update(Request $request, Chief $chief): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'traditional_area' => 'required|string',
            'community' => 'required|string',
            'region' => 'required|string',
        ]);

        $chief->update($validated);

        return redirect()->route('chiefs.index')
            ->with('success', 'Chief updated successfully!');
    }

    public function destroy(Chief $chief): RedirectResponse
    {
        $chief->delete();
        return redirect()->route('chiefs.index')
            ->with('success', 'Chief deleted successfully!');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Chief::query();

        if ($request->has('search') && $request->search) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
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
            fputcsv($file, ['ID', 'Full Name', 'Title', 'Phone', 'Email', 'Status']);
            
            // Data
            foreach ($chiefs as $chief) {
                fputcsv($file, [
                    $chief->id,
                    $chief->full_name,
                    $chief->title,
                    $chief->phone,
                    $chief->email,
                    $chief->is_active ? 'Active' : 'Inactive'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}