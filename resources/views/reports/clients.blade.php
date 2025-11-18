@extends('layouts.app')

@section('title', 'Clients Report')
@section('subtitle', 'Comprehensive Clients Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Clients Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filters</h5>
                                    <form method="GET" action="{{ route('reports.clients') }}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">All Statuses</option>
                                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                            <div class="col-md-3">
                                                <label for="id_type" class="form-label">ID Type</label>
                                                <select name="id_type" id="id_type" class="form-select">
                                                    <option value="">All ID Types</option>
                                                    <option value="ghanacard" {{ request('id_type') == 'ghanacard' ? 'selected' : '' }}>Ghana Card</option>
                                                    <option value="passport" {{ request('id_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                                                    <option value="drivers_license" {{ request('id_type') == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                                                    <option value="voters_id" {{ request('id_type') == 'voters_id' ? 'selected' : '' }}>Voter's ID</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                                </button>
                                                <a href="{{ route('reports.clients') }}" class="btn btn-secondary">
                                                    <i class="fas fa-redo me-2"></i>Reset
                                                </a>
                                                
                                                <!-- Download Buttons -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download me-2"></i>Export
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="{{ route('reports.clients.generate') }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                                                <input type="hidden" name="status" value="{{ request('status') }}">
                                                                <input type="hidden" name="id_type" value="{{ request('id_type') }}">
                                                                <input type="hidden" name="format" value="pdf">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-file-pdf me-2"></i>Export as PDF
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('reports.clients.generate') }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                                                <input type="hidden" name="status" value="{{ request('status') }}">
                                                                <input type="hidden" name="id_type" value="{{ request('id_type') }}">
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
                                            <h4 class="mb-0">{{ $clients->count() }}</h4>
                                            <p class="mb-0">Total Clients</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-users fa-2x"></i>
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
                                            <h4 class="mb-0">{{ $clients->where('is_active', true)->count() }}</h4>
                                            <p class="mb-0">Active Clients</p>
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
                                            <h4 class="mb-0">{{ $clients->where('is_active', false)->count() }}</h4>
                                            <p class="mb-0">Inactive Clients</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-user-slash fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $clients->whereNotNull('allocations')->count() }}</h4>
                                            <p class="mb-0">With Allocations</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-list-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Clients Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Clients Details</h5>
                        </div>
                        <div class="card-body p-0">
                            @if($clients->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-striped mb-0" id="clientsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="70" class="small fw-bold px-2 py-1">ID</th>
                                                <th width="150" class="small fw-bold px-2 py-1">Full Name</th>
                                                <th width="110" class="small fw-bold px-2 py-1">Phone</th>
                                                <th width="140" class="small fw-bold px-2 py-1">Email</th>
                                                <th width="90" class="small fw-bold px-2 py-1">ID Type</th>
                                                <th width="120" class="small fw-bold px-2 py-1">ID Number</th>
                                                <th width="120" class="small fw-bold px-2 py-1">Occupation</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Status</th>
                                                <th width="80" class="small fw-bold px-2 py-1">Allocations</th>
                                                <th width="100" class="small fw-bold px-2 py-1">Registered</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($clients as $client)
                                                <tr>
                                                    <td class="small px-2 py-1">#{{ $client->id }}</td>
                                                    <td class="small px-2 py-1">
                                                        <div class="fw-semibold text-truncate" title="{{ $client->full_name }}">
                                                            {{ $client->full_name }}
                                                        </div>
                                                        @if($client->date_of_birth)
                                                            <small class="text-muted">Age: {{ \Carbon\Carbon::parse($client->date_of_birth)->age }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="small px-2 py-1">{{ $client->phone }}</td>
                                                    <td class="small px-2 py-1 text-truncate" title="{{ $client->email ?? 'N/A' }}">
                                                        {{ $client->email ?? 'N/A' }}
                                                    </td>
                                                    <td class="small px-2 py-1">
                                                        <span class="badge bg-secondary">
                                                            @php
                                                                $idType = $client->id_type;
                                                                if($idType == 'ghanacard') echo 'Ghana Card';
                                                                elseif($idType == 'passport') echo 'Passport';
                                                                elseif($idType == 'drivers_license') echo 'Driver License';
                                                                elseif($idType == 'voters_id') echo 'Voter ID';
                                                                else echo ucfirst($idType);
                                                            @endphp
                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1 text-truncate" title="{{ $client->id_number }}">
                                                        {{ $client->id_number }}
                                                    </td>
                                                    <td class="small px-2 py-1 text-truncate" title="{{ $client->occupation ?? 'N/A' }}">
                                                        {{ $client->occupation ?? 'N/A' }}
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <span class="badge {{ $client->is_active ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $client->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1 text-center">
                                                        <span class="badge bg-primary">
                                                            {{ $client->allocations_count ?? $client->allocations->count() }}
                                                        </span>
                                                    </td>
                                                    <td class="small px-2 py-1">{{ $client->created_at->format('M d, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No clients found</h5>
                                    <p class="text-muted">No client records match your current filters.</p>
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
<style>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
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