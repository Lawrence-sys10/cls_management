@extends('layouts.app')

@section('title', 'Delete Land - ' . $land->plot_number)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Land
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-circle me-2"></i>Warning: This action cannot be undone!
                        </h6>
                        <p class="mb-0">You are about to permanently delete this land record and all associated data.</p>
                    </div>

                    <!-- Land Details -->
                    <div class="land-details mb-4">
                        <h6 class="text-muted mb-3">Land to be deleted:</h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Plot Number:</strong>
                                        <div class="text-dark">{{ $land->plot_number }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Location:</strong>
                                        <div class="text-dark">{{ $land->location }}</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Area:</strong>
                                        <div class="text-dark">
                                            {{ number_format($land->area_acres, 2) }} acres
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Status:</strong>
                                        <div class="text-dark text-capitalize">
                                            {{ str_replace('_', ' ', $land->ownership_status) }}
                                        </div>
                                    </div>
                                </div>
                                @if($land->description)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <strong>Description:</strong>
                                        <div class="text-dark">{{ $land->description }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dependencies Check -->
                    @if($land->allocations()->exists() || $land->documents()->exists())
                    <div class="dependencies-check mb-4">
                        <h6 class="text-muted mb-3">Associated Data:</h6>
                        <div class="card border-warning">
                            <div class="card-body">
                                @if($land->allocations()->exists())
                                <div class="alert alert-warning mb-3">
                                    <i class="fas fa-handshake me-2"></i>
                                    <strong>Allocations Found:</strong> 
                                    This land has {{ $land->allocations()->count() }} allocation record(s).
                                    <br>
                                    <small class="text-muted">
                                        You must delete all allocations before you can delete this land.
                                    </small>
                                </div>
                                @endif

                                @if($land->documents()->exists())
                                <div class="alert alert-info">
                                    <i class="fas fa-file-alt me-2"></i>
                                    <strong>Documents Found:</strong> 
                                    This land has {{ $land->documents()->count() }} document(s) attached.
                                    <br>
                                    <small class="text-muted">
                                        All documents will be permanently deleted along with the land record.
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('chief.lands.show', $land) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Details
                            </a>
                            <a href="{{ route('chief.lands.index') }}" class="btn btn-outline-primary ms-2">
                                <i class="fas fa-list me-2"></i>All Lands
                            </a>
                        </div>
                        
                        <div>
                            @if($land->allocations()->exists())
                                <!-- Disabled delete button if allocations exist -->
                                <button type="button" class="btn btn-danger" disabled 
                                        data-bs-toggle="tooltip" 
                                        title="Cannot delete land with existing allocations">
                                    <i class="fas fa-trash me-2"></i>Delete Land
                                </button>
                                <div class="text-danger small mt-1">
                                    Remove allocations first
                                </div>
                            @else
                                <!-- Delete confirmation form -->
                                <form action="{{ route('chief.lands.destroy', $land) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" 
                                            onclick="confirmDelete(this)">
                                        <i class="fas fa-trash me-2"></i>Delete Land
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Warning for Allocated Lands -->
                    @if($land->ownership_status === 'allocated')
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-lock me-2"></i>
                        <strong>Status Restriction:</strong> 
                        This land is currently allocated and cannot be deleted. 
                        You must first change the land status to vacant.
                    </div>
                    @endif

                    <!-- Alternative Actions -->
                    @if($land->allocations()->exists() || $land->ownership_status === 'allocated')
                    <div class="alternative-actions mt-4">
                        <h6 class="text-muted mb-3">Alternative Actions:</h6>
                        <div class="d-grid gap-2">
                            @if($land->allocations()->exists())
                            <a href="{{ route('chief.allocations.index') }}?land_id={{ $land->id }}" 
                               class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-handshake me-2"></i>View Allocations for This Land
                            </a>
                            @endif
                            
                            @if($land->ownership_status === 'allocated')
                            <a href="{{ route('chief.lands.edit', $land) }}" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-edit me-2"></i>Edit Land Details Instead
                            </a>
                            @endif
                            
                            <a href="{{ route('chief.lands.documents', $land) }}" 
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-file-alt me-2"></i>Manage Documents
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card.border-danger {
        border-width: 2px;
    }
    
    .land-details .card {
        border-left: 4px solid #dc3545;
    }
    
    .dependencies-check .card {
        border-left: 4px solid #ffc107;
    }
    
    .btn:disabled {
        cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(button) {
        const landPlotNumber = "{{ $land->plot_number }}";
        const landLocation = "{{ $land->location }}";
        
        Swal.fire({
            title: 'Are you sure?',
            html: `
                <div class="text-start">
                    <p>You are about to permanently delete:</p>
                    <div class="alert alert-danger">
                        <strong>${landPlotNumber}</strong><br>
                        <small>${landLocation}</small>
                    </div>
                    <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                button.closest('form').submit();
            }
        });
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush