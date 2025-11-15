@extends('layouts.app')

@section('title', 'System Report')
@section('subtitle', 'Comprehensive System Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>System Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- System Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                                    <p class="mb-0">Total Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $stats['active_users'] }}</h3>
                                    <p class="mb-0">Active Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $stats['total_lands'] }}</h3>
                                    <p class="mb-0">Total Lands</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $stats['total_clients'] }}</h3>
                                    <p class="mb-0">Total Clients</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-dark text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $stats['total_chiefs'] }}</h3>
                                    <p class="mb-0">Total Chiefs</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $stats['total_allocations'] }}</h3>
                                    <p class="mb-0">Total Allocations</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $stats['pending_allocations'] }}</h3>
                                    <p class="mb-0">Pending Allocations</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-purple text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $stats['storage_usage']['formatted'] ?? '0 B' }}</h3>
                                    <p class="mb-0">Storage Usage</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">System Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>PHP Version:</strong>
                                        </div>
                                        <div class="col-6">
                                            {{ PHP_VERSION }}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <strong>Laravel Version:</strong>
                                        </div>
                                        <div class="col-6">
                                            {{ app()->version() }}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <strong>Server Software:</strong>
                                        </div>
                                        <div class="col-6">
                                            {{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <strong>Database Driver:</strong>
                                        </div>
                                        <div class="col-6">
                                            {{ config('database.default') }}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <strong>Environment:</strong>
                                        </div>
                                        <div class="col-6">
                                            {{ app()->environment() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('reports.lands') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-map me-2"></i>View Lands Report
                                        </a>
                                        <a href="{{ route('reports.allocations') }}" class="btn btn-outline-success">
                                            <i class="fas fa-list-check me-2"></i>View Allocations Report
                                        </a>
                                        <a href="{{ route('reports.clients') }}" class="btn btn-outline-info">
                                            <i class="fas fa-users me-2"></i>View Clients Report
                                        </a>
                                        <a href="{{ route('reports.chiefs') }}" class="btn btn-outline-warning">
                                            <i class="fas fa-crown me-2"></i>View Chiefs Report
                                        </a>
                                    </div>
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

<style>
.bg-purple {
    background-color: #6f42c1 !important;
}
</style>