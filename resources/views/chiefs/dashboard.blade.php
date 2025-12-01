@extends('layouts.app')

@section('title', 'Chief Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Chief Dashboard</h4>
                    <p class="card-subtitle">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Lands Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Lands</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $landCount }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-map fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Clients Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Clients</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $clientCount }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Allocations Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total Allocations</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $allocationCount }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Available Lands Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Available Lands</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableLands }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-landmark fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('chief.lands.index') }}" class="btn btn-primary">
                                            <i class="fas fa-map me-2"></i>Manage Lands
                                        </a>
                                        <a href="{{ route('chief.clients.index') }}" class="btn btn-success">
                                            <i class="fas fa-users me-2"></i>Manage Clients
                                        </a>
                                        <a href="{{ route('chief.allocations.create') }}" class="btn btn-info">
                                            <i class="fas fa-plus-circle me-2"></i>Create Allocation
                                        </a>
                                        <a href="{{ route('chief.allocations.index') }}" class="btn btn-warning">
                                            <i class="fas fa-file-contract me-2"></i>View Allocations
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Recent Allocations</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentAllocations->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($recentAllocations as $allocation)
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                    <div>
                                                        <h6 class="mb-1">{{ $allocation->client->full_name }}</h6>
                                                        <small class="text-muted">
                                                            Plot: {{ $allocation->land->plot_number }} | 
                                                            {{ $allocation->allocation_date->format('M d, Y') }}
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-success rounded-pill">Active</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3 text-center">
                                            <a href="{{ route('chief.allocations.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                                        </div>
                                    @else
                                        <p class="text-muted text-center">No allocations yet</p>
                                        <div class="text-center">
                                            <a href="{{ route('chief.allocations.create') }}" class="btn btn-sm btn-primary">Create First Allocation</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Stats Row -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Land Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h4>{{ $activeAllocations }}</h4>
                                            <small class="text-muted">Active Allocations</small>
                                        </div>
                                        <div class="col-6">
                                            <h4>{{ $disputeCount }}</h4>
                                            <small class="text-muted">Pending Disputes</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">System Status</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Account:</strong> <span class="badge bg-success">Active</span></p>
                                    <p><strong>Last Login:</strong> {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y H:i') : 'Never' }}</p>
                                    <p><strong>Member Since:</strong> {{ auth()->user()->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection