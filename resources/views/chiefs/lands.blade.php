@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>My Land Plots</h1>
            <p class="text-muted">Manage lands under your jurisdiction</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_lands'] }}</h4>
                            <p class="mb-0">Total Lands</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-landmark fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['verified_lands'] }}</h4>
                            <p class="mb-0">Verified Lands</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['lands_with_allocations'] }}</h4>
                            <p class="mb-0">Lands with Allocations</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lands Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Land Plots</h5>
        </div>
        <div class="card-body">
            @if($lands->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Plot Number</th>
                                <th>Location</th>
                                <th>Area (Acres)</th>
                                <th>Status</th>
                                <th>Verified</th>
                                <th>Allocations</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lands as $land)
                                <tr>
                                    <td>
                                        <strong>{{ $land->plot_number }}</strong>
                                    </td>
                                    <td>{{ $land->location }}</td>
                                    <td>{{ number_format($land->area_acres, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $land->ownership_status == 'available' ? 'success' : ($land->ownership_status == 'allocated' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($land->ownership_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($land->is_verified)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-warning">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($land->allocations->count() > 0)
                                            <span class="badge bg-info">{{ $land->allocations->count() }} allocation(s)</span>
                                        @else
                                            <span class="text-muted">No allocations</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('lands.show', $land) }}" class="btn btn-sm btn-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('lands.edit', $land) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('lands.documents', $land) }}" class="btn btn-sm btn-secondary" title="Documents">
                                                <i class="fas fa-file"></i>
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
                    <div>
                        Showing {{ $lands->firstItem() }} to {{ $lands->lastItem() }} of {{ $lands->total() }} results
                    </div>
                    {{ $lands->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-landmark fa-4x text-muted mb-3"></i>
                    <h4>No Land Plots Found</h4>
                    <p class="text-muted">You haven't registered any land plots yet.</p>
                    <a href="{{ route('lands.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Register First Land Plot
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection