@extends('layouts.app')

@section('title', 'Allocations Report')
@section('subtitle', 'Comprehensive Allocations Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list-check me-2"></i>Allocations Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filters</h5>
                                    <form method="GET" action="{{ route('reports.allocations') }}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">All Statuses</option>
                                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
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
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                                </button>
                                                <a href="{{ route('reports.allocations') }}" class="btn btn-secondary">
                                                    <i class="fas fa-redo me-2"></i>Reset
                                                </a>
                                                
                                                <!-- Download Buttons -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download me-2"></i>Export
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="{{ route('reports.allocations.generate') }}" method="POST" class="d-inline">
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
                                                            <form action="{{ route('reports.allocations.generate') }}" method="POST" class="d-inline">
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
                                            <h4 class="mb-0">{{ $allocations->count() }}</h4>
                                            <p class="mb-0">Total Allocations</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-list-check fa-2x"></i>
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
                                            <h4 class="mb-0">{{ $allocations->where('status', 'approved')->count() }}</h4>
                                            <p class="mb-0">Approved</p>
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
                                            <h4 class="mb-0">{{ $allocations->where('status', 'pending')->count() }}</h4>
                                            <p class="mb-0">Pending</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
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
                                            <h4 class="mb-0">{{ $allocations->where('status', 'rejected')->count() }}</h4>
                                            <p class="mb-0">Rejected</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-times-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allocations Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Allocations Details</h5>
                        </div>
                        <div class="card-body">
                            @if($allocations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="allocationsTable">
                                        <thead>
                                            <tr>
                                                <th>Allocation ID</th>
                                                <th>Client</th>
                                                <th>Land Details</th>
                                                <th>Chief</th>
                                                <th>Allocation Date</th>
                                                <th>Status</th>
                                                <th>Duration (Years)</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allocations as $allocation)
                                                <tr>
                                                    <td>#{{ $allocation->id }}</td>
                                                    <td>
                                                        @if($allocation->client)
                                                            <strong>{{ $allocation->client->full_name }}</strong>
                                                            <br><small class="text-muted">{{ $allocation->client->phone }}</small>
                                                        @else
                                                            <span class="text-muted">No Client</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($allocation->land)
                                                            <strong>{{ $allocation->land->location }}</strong>
                                                            <br><small class="text-muted">Plot: {{ $allocation->land->plot_number }}</small>
                                                            <br><small class="text-muted">{{ number_format($allocation->land->area_acres, 2) }} acres</small>
                                                        @else
                                                            <span class="text-muted">No Land</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($allocation->chief)
                                                            {{ $allocation->chief->full_name }}
                                                            <br><small class="text-muted">{{ $allocation->chief->community }}</small>
                                                        @else
                                                            <span class="text-muted">No Chief</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $allocation->allocation_date ? \Carbon\Carbon::parse($allocation->allocation_date)->format('M d, Y') : 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($allocation->status == 'approved') bg-success
                                                            @elseif($allocation->status == 'pending') bg-warning
                                                            @elseif($allocation->status == 'rejected') bg-danger
                                                            @elseif($allocation->status == 'completed') bg-info
                                                            @else bg-secondary @endif">
                                                            {{ ucfirst($allocation->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $allocation->duration_years ?? 'N/A' }}</td>
                                                    <td>{{ $allocation->created_at->format('M d, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-list-check fa-3x text-muted mb-3"></i>
                                    <h5>No allocations found</h5>
                                    <p class="text-muted">No allocation records match your current filters.</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date inputs
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('end_date').max = today;
    });
</script>
@endpush