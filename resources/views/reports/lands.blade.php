@extends('layouts.app')

@section('title', 'Lands Report')
@section('subtitle', 'Comprehensive Lands Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-map-marked-alt me-2"></i>Lands Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filters</h5>
                                    <form method="GET" action="{{ route('reports.lands') }}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">All Statuses</option>
                                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                                    <option value="disputed" {{ request('status') == 'disputed' ? 'selected' : '' }}>Disputed</option>
                                                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="chief_id" class="form-label">Chief</label>
                                                <select name="chief_id" id="chief_id" class="form-select select2">
                                                    <option value="">All Chiefs</option>
                                                    @foreach($chiefs as $chief)
                                                        <option value="{{ $chief->id }}" {{ request('chief_id') == $chief->id ? 'selected' : '' }}>
                                                            {{ $chief->full_name }} - {{ $chief->community }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" name="start_date" id="start_date" 
                                                       value="{{ request('start_date') }}" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="end_date" class="form-label">End Date</label>
                                                <input type="date" name="end_date" id="end_date" 
                                                       value="{{ request('end_date') }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                                </button>
                                                <a href="{{ route('reports.lands') }}" class="btn btn-secondary">
                                                    <i class="fas fa-redo me-2"></i>Reset
                                                </a>
                                                
                                                <!-- Download Buttons -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download me-2"></i>Export
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="{{ route('reports.lands.generate') }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                                                <input type="hidden" name="status" value="{{ request('status') }}">
                                                                <input type="hidden" name="chief_id" value="{{ request('chief_id') }}">
                                                                <input type="hidden" name="format" value="pdf">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-file-pdf me-2"></i>Export as PDF
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('reports.lands.generate') }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                                                <input type="hidden" name="status" value="{{ request('status') }}">
                                                                <input type="hidden" name="chief_id" value="{{ request('chief_id') }}">
                                                                <input type="hidden" name="format" value="excel">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-file-excel me-2"></i>Export as Excel
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $lands->count() }}</h4>
                                            <p class="mb-0">Total Lands</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-map fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $lands->where('status', 'available')->count() }}</h4>
                                            <p class="mb-0">Available</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $lands->where('status', 'occupied')->count() }}</h4>
                                            <p class="mb-0">Occupied</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-home fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $lands->where('status', 'disputed')->count() }}</h4>
                                            <p class="mb-0">Disputed</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lands Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lands Details</h5>
                        </div>
                        <div class="card-body p-0">
                            @if($lands->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped mb-0" id="landsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="70" class="small fw-bold px-2 py-1">ID</th>
                                                <th width="150" class="small fw-bold px-2 py-1">Location</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Size</th>
                                                <th width="90" class="small fw-bold px-2 py-1">Status</th>
                                                <th width="120" class="small fw-bold px-2 py-1">Client</th>
                                                <th width="120" class="small fw-bold px-2 py-1">Chief</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Price</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Date Added</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lands as $land)
                                                <tr>
                                                    <td class="small px-2 py-1">#{{ $land->id }}</td>
                                                    <td class="small px-2 py-1">
                                                        <div class="fw-semibold text-truncate" title="{{ $land->location }}">
                                                            {{ $land->location }}
                                                        </div>
                                                        @if($land->landmarks)
                                                            <small class="text-muted text-truncate d-block" title="{{ $land->landmarks }}">
                                                                {{ $land->landmarks }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        {{ number_format($land->size, 1) }}
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <span class="badge 
                                                            @if($land->status == 'available') bg-success
                                                            @elseif($land->status == 'occupied') bg-warning
                                                            @elseif($land->status == 'disputed') bg-danger
                                                            @elseif($land->status == 'sold') bg-info
                                                            @else bg-secondary @endif">
                                                            {{ ucfirst($land->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        @if($land->client)
                                                            <div class="text-truncate" title="{{ $land->client->full_name }}">
                                                                {{ $land->client->full_name }}
                                                            </div>
                                                        @else
                                                            <span class="text-muted">No Client</span>
                                                        @endif
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        @if($land->chief)
                                                            <div class="text-truncate" title="{{ $land->chief->full_name }}">
                                                                {{ $land->chief->full_name }}
                                                            </div>
                                                        @else
                                                            <span class="text-muted">No Chief</span>
                                                        @endif
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        @if($land->price)
                                                            {{ number_format($land->price, 0) }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        {{ $land->created_at->format('M d, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                    <h5>No lands found</h5>
                                    <p class="text-muted">No land records match your current filters.</p>
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    /* Select2 Styling */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        padding: 4px 12px;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
    }

    /* Compact table styling */
    .table-sm {
        font-size: 0.8rem;
    }
    
    .table-sm th,
    .table-sm td {
        padding: 0.4rem 0.5rem;
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.7rem;
        font-weight: 500;
        padding: 0.25em 0.4em;
    }
    
    .text-truncate {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Ensure table fits within container */
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    /* Compact card body for table */
    .card-body.p-0 {
        padding: 0 !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-sm {
            font-size: 0.75rem;
        }
        
        .table-sm th,
        .table-sm td {
            padding: 0.3rem 0.4rem;
        }
        
        .badge {
            font-size: 0.65rem;
            padding: 0.2em 0.3em;
        }
    }
    
    @media (max-width: 576px) {
        .table-responsive {
            font-size: 0.7rem;
        }
        
        .table-sm th,
        .table-sm td {
            padding: 0.25rem 0.3rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for chief dropdown only
        $('#chief_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select Chief...',
            allowClear: true,
            width: '100%'
        });

        // Initialize date inputs
        const today = new Date().toISOString().split('T')[0];
        if (document.getElementById('end_date')) {
            document.getElementById('end_date').max = today;
        }
        
        // Add tooltips for truncated text
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush