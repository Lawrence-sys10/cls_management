@extends('layouts.app')

@section('title', 'My Lands')
@section('subtitle', 'Manage your land records and allocations')

@section('actions')
    <a href="{{ route('chief.lands.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Land
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Lands</h3>
                    <div class="stat-value">{{ $lands->total() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Your registered lands</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Vacant Lands</h3>
                    <div class="stat-value">{{ $lands->where('ownership_status', 'vacant')->count() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-landmark"></i>
                        <span>Available for allocation</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-landmark"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Allocated</h3>
                    <div class="stat-value">{{ $lands->where('ownership_status', 'allocated')->count() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-handshake"></i>
                        <span>Currently allocated</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Under Dispute</h3>
                    <div class="stat-value">{{ $lands->where('ownership_status', 'under_dispute')->count() }}</div>
                    <div class="stat-trend trend-down">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Lands with disputes</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Lands Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Land Records</h5>
            <div class="header-actions">
                <a href="{{ route('chief.lands.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Land
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="form-control"
                               placeholder="Plot number or location...">
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="vacant" {{ request('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                            <option value="allocated" {{ request('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                            <option value="under_dispute" {{ request('status') == 'under_dispute' ? 'selected' : '' }}>Under Dispute</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            @if($lands->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="landsTable">
                    <thead>
                        <tr>
                            <th>Plot Number</th>
                            <th>Location</th>
                            <th>Area (Acres)</th>
                            <th>Land Use</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lands as $land)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $land->plot_number }}</div>
                                <small class="text-muted">ID: {{ $land->id }}</small>
                            </td>
                            <td>
                                <div class="text-dark">{{ $land->location }}</div>
                                @if($land->landmark)
                                <small class="text-muted">Near: {{ $land->landmark }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="text-dark">{{ number_format($land->area_acres, 2) }} acres</div>
                                <small class="text-muted">{{ number_format($land->area_hectares, 2) }} ha</small>
                            </td>
                            <td>
                                <div class="text-dark text-capitalize">{{ str_replace('_', ' ', $land->land_use) }}</div>
                                @if($land->price)
                                <small class="text-muted">₵{{ number_format($land->price) }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($land->ownership_status) {
                                        'vacant' => 'badge-success',
                                        'allocated' => 'badge-primary',
                                        'under_dispute' => 'badge-warning',
                                        default => 'badge-secondary'
                                    };
                                    
                                    $statusText = match($land->ownership_status) {
                                        'vacant' => 'Vacant',
                                        'allocated' => 'Allocated',
                                        'under_dispute' => 'Under Dispute',
                                        default => ucfirst(str_replace('_', ' ', $land->ownership_status))
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                                @if($land->ownership_status === 'allocated' && $land->allocations->count() > 0)
                                <br>
                                <small class="text-muted">Allocations: {{ $land->allocations->count() }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="text-dark">{{ $land->updated_at->format('M j, Y') }}</div>
                                <small class="text-muted">{{ $land->updated_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('chief.lands.show', $land) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('chief.lands.edit', $land) }}" class="btn btn-sm btn-outline-secondary" title="Edit Land">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($land->ownership_status === 'vacant')
                                    <a href="{{ route('chief.allocations.create') }}?land_id={{ $land->id }}" class="btn btn-sm btn-outline-success" title="Allocate Land">
                                        <i class="fas fa-handshake"></i>
                                    </a>
                                    @endif
                                    @if($land->ownership_status === 'under_dispute')
                                    <a href="{{ route('chief.disputes.index') }}?land_id={{ $land->id }}" class="btn btn-sm btn-outline-warning" title="View Dispute">
                                        <i class="fas fa-gavel"></i>
                                    </a>
                                    @endif
                                    <!-- Updated: Use delete confirmation page instead of inline form -->
                                    <a href="{{ route('chief.lands.delete', $land) }}" class="btn btn-sm btn-outline-danger" title="Delete Land">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $lands->firstItem() }} to {{ $lands->lastItem() }} of {{ $lands->total() }} entries
                </div>
                {{ $lands->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No land records found</h5>
                <p class="text-muted">Get started by adding your first land record.</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('chief.lands.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Your First Land
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid var(--primary);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .stat-card:hover::before {
        transform: scaleX(1);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .stat-info h3 {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-card:nth-child(1) .stat-icon {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .stat-card:nth-child(4) .stat-icon {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: var(--gray-600);
    }
    
    .trend-up {
        color: #10b981;
    }
    
    .trend-down {
        color: var(--danger);
    }
    
    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    
    .badge-primary {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
        border: 1px solid rgba(67, 97, 238, 0.2);
    }
    
    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    
    .badge-secondary {
        background: rgba(108, 117, 125, 0.1);
        color: var(--gray-600);
        border: 1px solid rgba(108, 117, 125, 0.2);
    }
    
    .btn-group {
        display: flex;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    
    .header-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    /* Custom styling for chief-specific elements */
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white;
    }
    
    .card-header h5 {
        color: white;
        margin-bottom: 0;
    }
    
    .stat-card {
        border-left-color: #667eea;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#landsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [[5, 'desc']], // Default sort by last updated
            language: {
                emptyTable: "No land records found"
            },
            columnDefs: [
                { orderable: false, targets: [6] } // Disable sorting for actions column
            ]
        });

        // Auto-submit form when filters change
        $('#status').change(function() {
            if($(this).val()) {
                $(this).closest('form').submit();
            }
        });

        // Quick status filter buttons
        $('.quick-filter').click(function() {
            const status = $(this).data('status');
            $('#status').val(status).closest('form').submit();
        });
    });

    // Quick actions for land management
    function quickAllocate(landId) {
        if(confirm('Are you sure you want to allocate this land?')) {
            window.location.href = "{{ route('chief.allocations.create') }}?land_id=" + landId;
        }
    }

    function viewDisputes(landId) {
        window.location.href = "{{ route('chief.disputes.index') }}?land_id=" + landId;
    }

    // Enhanced delete confirmation
    function confirmDelete(landId, plotNumber) {
        Swal.fire({
            title: 'Are you sure?',
            html: `
                <div class="text-start">
                    <p>You are about to permanently delete:</p>
                    <div class="alert alert-danger">
                        <strong>${plotNumber}</strong>
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
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById(`delete-form-${landId}`).submit();
            }
        });
    }
</script>
@endpush