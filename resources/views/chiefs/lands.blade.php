@extends('layouts.app')

@section('title', 'My Lands')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">My Lands</h4>
                    <p class="card-subtitle">Lands under your jurisdiction</p>
                </div>
                <div class="card-body">
                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $stats['total_lands'] }}</h3>
                                            <p>Total Lands</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-map fa-2x"></i>
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
                                            <h3>{{ $stats['verified_lands'] }}</h3>
                                            <p>Verified Lands</p>
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
                                            <h3>{{ $stats['lands_with_allocations'] }}</h3>
                                            <p>Allocated Lands</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-file-contract fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lands Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Plot Number</th>
                                    <th>Location</th>
                                    <th>Area (Acres)</th>
                                    <th>Status</th>
                                    <th>Verification</th>
                                    <th>Allocations</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lands as $land)
                                    <tr>
                                        <td>
                                            <strong>{{ $land->plot_number }}</strong>
                                        </td>
                                        <td>{{ $land->location }}</td>
                                        <td>{{ $land->area_acres ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($land->ownership_status) }}</span>
                                        </td>
                                        <td>
                                            @if($land->is_verified)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $land->allocations_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('lands.show', $land) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('lands.documents', $land) }}" class="btn btn-sm btn-secondary" title="Documents">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-map fa-2x mb-3"></i>
                                                <p>No lands registered under your jurisdiction</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($lands->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $lands->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection