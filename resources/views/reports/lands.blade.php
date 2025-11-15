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
                                                <select name="chief_id" id="chief_id" class="form-select">
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
                                                <button type="button" class="btn btn-success" onclick="exportToExcel()">
                                                    <i class="fas fa-file-excel me-2"></i>Export to Excel
                                                </button>
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
                        <div class="card-body">
                            @if($lands->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="landsTable">
                                        <thead>
                                            <tr>
                                                <th>Land ID</th>
                                                <th>Location</th>
                                                <th>Size (Acres)</th>
                                                <th>Status</th>
                                                <th>Client</th>
                                                <th>Chief</th>
                                                <th>Price (GHS)</th>
                                                <th>Date Added</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lands as $land)
                                                <tr>
                                                    <td>#{{ $land->id }}</td>
                                                    <td>
                                                        <strong>{{ $land->location }}</strong>
                                                        @if($land->landmarks)
                                                            <br><small class="text-muted">{{ $land->landmarks }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($land->size, 2) }}</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($land->status == 'available') bg-success
                                                            @elseif($land->status == 'occupied') bg-warning
                                                            @elseif($land->status == 'disputed') bg-danger
                                                            @elseif($land->status == 'sold') bg-info
                                                            @else bg-secondary @endif">
                                                            {{ ucfirst($land->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($land->client)
                                                            {{ $land->client->full_name }}
                                                        @else
                                                            <span class="text-muted">No Client</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($land->chief)
                                                            {{ $land->chief->full_name }}
                                                        @else
                                                            <span class="text-muted">No Chief</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($land->price)
                                                            {{ number_format($land->price, 2) }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $land->created_at->format('M d, Y') }}</td>
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

@push('scripts')
<script>
    function exportToExcel() {
        // Simple Excel export implementation
        const table = document.getElementById('landsTable');
        const html = table.outerHTML;
        
        // Create a blob and download link
        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'lands-report-' + new Date().toISOString().split('T')[0] + '.xls';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date inputs
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('end_date').max = today;
        
        // Add some interactivity
        const statusFilter = document.getElementById('status');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                // You can add auto-submit functionality here if desired
            });
        }
    });
</script>
@endpush