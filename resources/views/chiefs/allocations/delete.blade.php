@extends('layouts.app')

@section('title', 'Delete Allocation - ' . $allocation->client->full_name)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Allocation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="alert-icon mb-3">
                            <i class="fas fa-exclamation-circle fa-3x text-danger"></i>
                        </div>
                        <h4 class="text-danger mb-3">Confirm Deletion</h4>
                    </div>

                    <!-- Allocation Summary -->
                    <div class="allocation-summary mb-4 p-3 border rounded">
                        <h6 class="mb-3">Allocation to be Deleted:</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-group">
                                    <label class="detail-label">Client</label>
                                    <p class="detail-value">{{ $allocation->client->full_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-group">
                                    <label class="detail-label">Land Plot</label>
                                    <p class="detail-value">Plot {{ $allocation->land->plot_number }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-group">
                                    <label class="detail-label">Allocation Date</label>
                                    <p class="detail-value">{{ $allocation->allocation_date->format('M j, Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-group">
                                    <label class="detail-label">Duration</label>
                                    <p class="detail-value">{{ $allocation->duration_years }} years</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-group">
                            <label class="detail-label">Purpose</label>
                            <p class="detail-value">{{ Str::limit($allocation->purpose, 100) }}</p>
                        </div>
                    </div>

                    <!-- Warning Messages -->
                    <div class="alert alert-danger">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>Important Notice
                        </h6>
                        <ul class="mb-0">
                            <li>This action cannot be undone</li>
                            <li>The land plot will be marked as "vacant" and available for reallocation</li>
                            <li>All allocation records will be permanently deleted</li>
                            <li>Related documents will remain in the system</li>
                            <li>This may affect client records and reporting</li>
                        </ul>
                    </div>

                    <!-- Impact Assessment -->
                    <div class="impact-assessment mb-4">
                        <h6 class="text-muted mb-3">Impact of This Action:</h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="impact-item">
                                    <div class="impact-value text-danger">1</div>
                                    <div class="impact-label">Allocation Removed</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="impact-item">
                                    <div class="impact-value text-success">1</div>
                                    <div class="impact-label">Land Available</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="impact-item">
                                    <div class="impact-value text-info">{{ $allocation->documents->count() }}</div>
                                    <div class="impact-label">Documents Kept</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Form -->
                    <form action="{{ route('chief.allocations.destroy', $allocation) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="mb-3">
                            <label for="confirmation" class="form-label">
                                Type "DELETE" to confirm this action
                            </label>
                            <input type="text" class="form-control" id="confirmation" name="confirmation" 
                                   placeholder="Type DELETE here" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('chief.allocations.show', $allocation) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-danger" id="deleteButton" disabled>
                                <i class="fas fa-trash me-2"></i>Permanently Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        color: white;
    }
    
    .alert-icon {
        color: #dc3545;
    }
    
    .detail-group {
        margin-bottom: 1rem;
    }
    
    .detail-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .detail-value {
        color: #6c757d;
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    
    .allocation-summary {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    
    .impact-item {
        padding: 0.5rem;
    }
    
    .impact-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    
    .impact-label {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    #deleteButton:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmationInput = document.getElementById('confirmation');
        const deleteButton = document.getElementById('deleteButton');
        const form = document.querySelector('form');

        confirmationInput.addEventListener('input', function() {
            deleteButton.disabled = this.value !== 'DELETE';
        });

        form.addEventListener('submit', function(e) {
            if (!confirm('FINAL WARNING: This will permanently delete the allocation. Are you absolutely sure?')) {
                e.preventDefault();
            }
        });

        // Focus on confirmation input
        confirmationInput.focus();
    });
</script>
@endpush