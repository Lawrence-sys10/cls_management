@extends('layouts.app')

@section('title', 'Chiefs Report')
@section('subtitle', 'Comprehensive Chiefs Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-crown me-2"></i>Chiefs Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filters</h5>
                                    <form method="GET" action="{{ route('reports.chiefs') }}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="region" class="form-label">Region</label>
                                                <select name="region" id="region" class="form-select">
                                                    <option value="">All Regions</option>
                                                    <option value="Greater Accra" {{ request('region') == 'Greater Accra' ? 'selected' : '' }}>Greater Accra</option>
                                                    <option value="Ashanti" {{ request('region') == 'Ashanti' ? 'selected' : '' }}>Ashanti</option>
                                                    <option value="Western" {{ request('region') == 'Western' ? 'selected' : '' }}>Western</option>
                                                    <option value="Eastern" {{ request('region') == 'Eastern' ? 'selected' : '' }}>Eastern</option>
                                                    <option value="Central" {{ request('region') == 'Central' ? 'selected' : '' }}>Central</option>
                                                    <option value="Volta" {{ request('region') == 'Volta' ? 'selected' : '' }}>Volta</option>
                                                    <option value="Northern" {{ request('region') == 'Northern' ? 'selected' : '' }}>Northern</option>
                                                    <option value="Upper East" {{ request('region') == 'Upper East' ? 'selected' : '' }}>Upper East</option>
                                                    <option value="Upper West" {{ request('region') == 'Upper West' ? 'selected' : '' }}>Upper West</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" name="start_date" id="start_date" 
                                                       value="{{ request('start_date') }}" class="form-control">
                                            </div>
                                            <div class="col-md-4">
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
                                                <a href="{{ route('reports.chiefs') }}" class="btn btn-secondary">
                                                    <i class="fas fa-redo me-2"></i>Reset
                                                </a>
                                                
                                                <!-- Download Buttons -->
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download me-2"></i>Export
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="{{ route('reports.chiefs.generate') }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                                                <input type="hidden" name="region" value="{{ request('region') }}">
                                                                <input type="hidden" name="format" value="pdf">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-file-pdf me-2"></i>Export as PDF
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('reports.chiefs.generate') }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                                                <input type="hidden" name="region" value="{{ request('region') }}">
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
                                            <h4 class="mb-0">{{ $chiefs->count() }}</h4>
                                            <p class="mb-0">Total Chiefs</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-crown fa-2x"></i>
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
                                            <h4 class="mb-0">{{ $chiefs->where('is_active', true)->count() }}</h4>
                                            <p class="mb-0">Active Chiefs</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
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
                                            <h4 class="mb-0">{{ $chiefs->sum('lands_count') }}</h4>
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
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $chiefs->sum(function($chief) { return $chief->lands->where('ownership_status', 'allocated')->count(); }) }}</h4>
                                            <p class="mb-0">Allocated Lands</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-list-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chiefs Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Chiefs Details</h5>
                        </div>
                        <div class="card-body">
                            @if($chiefs->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="chiefsTable">
                                        <thead>
                                            <tr>
                                                <th>Chief ID</th>
                                                <th>Full Name</th>
                                                <th>Title</th>
                                                <th>Traditional Area</th>
                                                <th>Community</th>
                                                <th>Region</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Total Lands</th>
                                                <th>Years of Service</th>
                                                <th>Registered Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($chiefs as $chief)
                                                <tr>
                                                    <td>#{{ $chief->id }}</td>
                                                    <td>
                                                        <strong>{{ $chief->full_name }}</strong>
                                                        @if($chief->email)
                                                            <br><small class="text-muted">{{ $chief->email }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $chief->title }}</td>
                                                    <td>{{ $chief->traditional_area }}</td>
                                                    <td>{{ $chief->community }}</td>
                                                    <td>{{ $chief->region }}</td>
                                                    <td>{{ $chief->phone }}</td>
                                                    <td>
                                                        <span class="badge {{ $chief->is_active ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $chief->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            {{ $chief->lands_count ?? $chief->lands->count() }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $chief->years_of_service ?? 'N/A' }}</td>
                                                    <td>{{ $chief->created_at->format('M d, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-crown fa-3x text-muted mb-3"></i>
                                    <h5>No chiefs found</h5>
                                    <p class="text-muted">No chief records match your current filters.</p>
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
        if (document.getElementById('end_date')) {
            document.getElementById('end_date').max = today;
        }
    });
</script>
@endpush