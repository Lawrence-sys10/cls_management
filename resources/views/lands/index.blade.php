@extends('layouts.app')

@section('title', 'Land Management')
@section('subtitle', 'Manage land records and allocations')

@section('actions')
    <a href="{{ route('lands.export') }}" class="btn btn-success">
        <i class="fas fa-file-export me-2"></i>Export
    </a>
    <a href="{{ route('lands.create') }}" class="btn btn-primary">
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
                        <span>Registered lands</span>
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
    </div>

    <!-- Lands Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Land Records</h5>
            <div class="header-actions">
                <a href="{{ route('lands.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export me-1"></i>Export
                </a>
                <a href="{{ route('lands.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Land
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="form-control"
                               placeholder="Plot number or location...">
                    </div>
                    <div class="col-md-3">
                        <label for="chief_id" class="form-label">Chief</label>
                        <select name="chief_id" id="chief_id" class="form-control">
                            <option value="">All Chiefs</option>
                            @foreach($chiefs as $chief)
                            <option value="{{ $chief->id }}" {{ request('chief_id') == $chief->id ? 'selected' : '' }}>
                                {{ $chief->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="vacant" {{ request('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                            <option value="allocated" {{ request('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                            <option value="under_dispute" {{ request('status') == 'under_dispute' ? 'selected' : '' }}>Under Dispute</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
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
                            <th>Chief</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lands as $land)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $land->plot_number }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $land->location }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ number_format($land->area_acres, 2) }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $land->chief->name }}</div>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($land->ownership_status) {
                                        'vacant' => 'badge-success',
                                        'allocated' => 'badge-primary',
                                        'under_dispute' => 'badge-warning',
                                        default => 'badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $land->ownership_status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('lands.show', $land) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lands.edit', $land) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('lands.destroy', $land) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this land?')">
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
                <a href="{{ route('lands.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Land
                </a>
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
        background: rgba(76, 201, 240, 0.1);
        color: var(--success);
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: rgba(247, 37, 133, 0.1);
        color: var(--warning);
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
        background: rgba(247, 37, 133, 0.1);
        color: var(--warning);
        border: 1px solid rgba(247, 37, 133, 0.2);
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
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#landsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [],
            language: {
                emptyTable: "No land records found"
            },
            columnDefs: [
                { orderable: false, targets: [5] } // Disable sorting for actions column
            ]
        });
    });
</script>
@endpush