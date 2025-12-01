@extends('layouts.app')

@section('title', 'Delete Client - ' . $client->name)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Client - {{ $client->name }}
                    </h5>
                    <a href="{{ route('chief.clients.show', $client) }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left me-1"></i>Back to Details
                    </a>
                </div>
                <div class="card-body">
                    <!-- Warning Alert -->
                    <div class="alert alert-danger mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="alert-heading">Warning: This action cannot be undone!</h5>
                                <p class="mb-0">You are about to permanently delete this client and all associated data. This action is irreversible.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Client Information Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Client Details</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th class="text-muted">Name:</th>
                                            <td>{{ $client->name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">ID Number:</th>
                                            <td>{{ $client->id_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Phone:</th>
                                            <td>{{ $client->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Email:</th>
                                            <td>{{ $client->email ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Created:</th>
                                            <td>{{ $client->created_at->format('M j, Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning">
                                    <h6 class="mb-0">Impact Analysis</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $allocationCount = $client->allocations()->count();
                                        $documentCount = $client->documents()->count();
                                    @endphp
                                    
                                    @if($allocationCount > 0)
                                        <div class="alert alert-warning p-2 mb-2">
                                            <i class="fas fa-handshake me-1"></i>
                                            <strong>{{ $allocationCount }} Land Allocation(s)</strong> will be affected
                                        </div>
                                    @endif
                                    
                                    @if($documentCount > 0)
                                        <div class="alert alert-warning p-2 mb-2">
                                            <i class="fas fa-file-alt me-1"></i>
                                            <strong>{{ $documentCount }} Document(s)</strong> will be deleted
                                        </div>
                                    @endif
                                    
                                    @if($allocationCount == 0 && $documentCount == 0)
                                        <div class="alert alert-info p-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            No associated records found
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Steps -->
                    <div class="card border-warning mb-4">
                        <div class="card-header bg-warning">
                            <h6 class="mb-0">Confirm Deletion</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p>To confirm deletion, please type the client's name below:</p>
                                <input type="text" class="form-control" id="confirmation_name" 
                                       placeholder="Enter client name to confirm" 
                                       oninput="toggleDeleteButton()">
                                <div class="form-text text-muted">
                                    Type: <code>{{ $client->name }}</code>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="confirm_irreversible" 
                                       onchange="toggleDeleteButton()">
                                <label class="form-check-label text-danger" for="confirm_irreversible">
                                    I understand that this action cannot be undone and all associated data will be permanently deleted.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('chief.clients.show', $client) }}" class="btn btn-success">
                                <i class="fas fa-arrow-left me-2"></i>Go Back Safely
                            </a>
                            
                            <a href="{{ route('chief.clients.edit', $client) }}" class="btn btn-outline-primary ms-2">
                                <i class="fas fa-edit me-2"></i>Edit Instead
                            </a>
                        </div>
                        
                        <div>
                            <form action="{{ route('chief.clients.destroy', $client) }}" method="POST" id="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" id="deleteButton" disabled>
                                    <i class="fas fa-trash-alt me-2"></i>Permanently Delete Client
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Statistics -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Client Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="h4 text-primary mb-1">{{ $client->allocations()->count() }}</div>
                                <small class="text-muted">Land Allocations</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="h4 text-info mb-1">{{ $client->documents()->count() }}</div>
                                <small class="text-muted">Documents</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($client->allocations()->exists())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> Deleting this client will also remove all land allocation records.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Alternative Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Alternative Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('chief.clients.edit', $client) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Edit Client Information
                        </a>
                        
                        <a href="{{ route('chief.clients.allocations', $client) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-handshake me-2"></i>Manage Allocations
                        </a>

                        <a href="{{ route('chief.clients.documents', $client) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-file-alt me-2"></i>Manage Documents
                        </a>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Consider these alternatives before deletion
                        </small>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-database me-2"></i>System Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Client ID:</strong>
                        <span class="text-muted">{{ $client->id }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Created:</strong>
                        <span class="text-muted">{{ $client->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong>
                        <span class="text-muted">{{ $client->updated_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Last Login:</strong>
                        <span class="text-muted">
                            @if($client->last_login_at)
                                {{ $client->last_login_at->format('M j, Y g:i A') }}
                            @else
                                Never logged in
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        font-weight: 600;
    }
    
    .border-danger {
        border-width: 2px !important;
    }
    
    .alert-warning {
        border-left: 4px solid #ffc107;
    }
    
    .alert-danger {
        border-left: 4px solid #dc3545;
    }
    
    .form-check-input:checked {
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleDeleteButton() {
        const clientName = "{{ $client->name }}";
        const inputName = document.getElementById('confirmation_name').value;
        const confirmCheckbox = document.getElementById('confirm_irreversible');
        const deleteButton = document.getElementById('deleteButton');
        
        if (inputName === clientName && confirmCheckbox.checked) {
            deleteButton.disabled = false;
        } else {
            deleteButton.disabled = true;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Form submission confirmation
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.addEventListener('submit', function(e) {
            if (!confirm('Final confirmation: Are you absolutely sure you want to permanently delete this client and all associated data?')) {
                e.preventDefault();
            }
        });
        
        // Prevent accidental submission
        document.getElementById('confirmation_name').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    });
</script>
@endpush