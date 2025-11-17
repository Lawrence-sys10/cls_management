@extends('layouts.app')

@section('title', 'Documents for ' . $client->full_name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Documents for {{ $client->full_name }}</h1>
            <p class="text-muted">Client ID: {{ $client->id_number }}</p>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                <i class="fas fa-upload"></i> Upload Document
            </button>
            <a href="{{ route('clients.show', $client) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Client
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($client->documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>File Name</th>
                                <th>File Size</th>
                                <th>Description</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->documents as $document)
                                <tr>
                                    <td>
                                        <strong>{{ $document->document_type }}</strong>
                                    </td>
                                    <td>{{ $document->file_name }}</td>
                                    <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                    <td>{{ $document->description ?? 'N/A' }}</td>
                                    <td>{{ $document->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ Storage::url($document->file_path) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ Storage::url($document->file_path) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-info"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('documents.destroy', $document) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this document?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                    <h4>No Documents Found</h4>
                    <p class="text-muted">No documents have been uploaded for this client yet.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="fas fa-upload"></i> Upload First Document
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document_type" class="form-label">Document Type *</label>
                        <select class="form-control" id="document_type" name="document_type" required>
                            <option value="">Select Document Type</option>
                            <option value="ID Copy">ID Copy</option>
                            <option value="Proof of Address">Proof of Address</option>
                            <option value="Passport Photo">Passport Photo</option>
                            <option value="Application Form">Application Form</option>
                            <option value="Payment Receipt">Payment Receipt</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="document" class="form-label">Document File *</label>
                        <input type="file" class="form-control" id="document" name="document" 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        <div class="form-text">
                            Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3" placeholder="Optional description of the document"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Document</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File size validation
    const fileInput = document.getElementById('document');
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        
        if (file && file.size > maxSize) {
            alert('File size must be less than 10MB');
            this.value = '';
        }
    });
});
</script>
@endpush