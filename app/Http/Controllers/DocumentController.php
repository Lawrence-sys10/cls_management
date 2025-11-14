<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Client;
use App\Models\Land;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'document_type' => 'required|string',
            'client_id' => 'nullable|exists:clients,id',
            'land_id' => 'nullable|exists:lands,id',
            'allocation_id' => 'nullable|exists:allocations,id',
            'description' => 'nullable|string'
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        $document = Document::create([
            'client_id' => $request->client_id,
            'land_id' => $request->land_id,
            'allocation_id' => $request->allocation_id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'document_type' => $request->document_type,
            'description' => $request->description,
            'uploaded_by' => auth()->id(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($document)
            ->log('uploaded document: ' . $document->file_name);

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }

    public function destroy(Document $document): RedirectResponse
    {
        // Delete physical file
        Storage::disk('public')->delete($document->file_path);

        $file_name = $document->file_name;
        $document->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('deleted document: ' . $file_name);

        return redirect()->back()->with('success', 'Document deleted successfully!');
    }

    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($document)
            ->log('downloaded document: ' . $document->file_name);

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function verify(Document $document): RedirectResponse
    {
        $document->update(['is_verified' => true]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($document)
            ->log('verified document: ' . $document->file_name);

        return redirect()->back()->with('success', 'Document verified successfully!');
    }
}
