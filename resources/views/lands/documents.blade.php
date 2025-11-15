@extends('layouts.app')

@section('title', 'Land Documents - ' . $land->plot_number)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">Documents for {{ $land->plot_number }}</h4>
                        <p class="card-subtitle">{{ $land->location }}</p>
                    </div>
                    <div class="card-tools">
                        <a href="{{ route('lands.show', $land) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Land
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Upload Document Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Upload New Document</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('lands.store-document', $land) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="document_type" class="form-label">Document Type</label>
                                            <select class="form-control" id="document_type" name="document_type" required>
                                                <option value="">Select Document Type</option>
                                                <option value="title_deed">Title Deed</option>
                                                <option value="survey_map">Survey Map</option>
                                                <option value="ownership_certificate">Ownership Certificate</option>
                                                <option value="approval_letter">Approval Letter</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="document" class="form-label">Document File</label>
                                            <input type="file" class="form-control" id="document" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                            <small class="form-text text-muted">Max file size: 10MB. Allowed types: PDF, DOC, DOCX, JPG, JPEG, PNG</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="1" placeholder="Optional description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i>Upload Document
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Documents List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Uploaded Documents</h5>
                        </div>
                        <div class="card-body">
                            @if($land->documents->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Document Type</th>
                                                <th>File Name</th>
                                                <th>File Size</th>
                                                <th>Uploaded By</th>
                                                <th>Upload Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($land->documents as $document)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</span>
                                                    </td>
                                                    <td>{{ $document->file_name }}</td>
                                                    <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                                    <td>{{ $document->uploader->name ?? 'N/A' }}</td>
                                                    <td>{{ $document->created_at->format('M j, Y g:i A') }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('documents.download', $document) }}" class="btn btn-sm btn-info" title="Download">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                            <a href="{{ route('documents.preview', $document) }}" class="btn btn-sm btn-secondary" title="Preview">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this document?')">
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
                                <div class="text-center py-4">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No documents uploaded yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection