@extends('layouts.app')

@section('title', 'System Health')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">System Health Status</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3><i class="fas fa-database"></i></h3>
                                            <h5>Database</h5>
                                        </div>
                                        <div class="text-end">
                                            <h4>OK</h4>
                                            <small>Connected</small>
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
                                            <h3><i class="fas fa-hdd"></i></h3>
                                            <h5>Storage</h5>
                                        </div>
                                        <div class="text-end">
                                            <h4>OK</h4>
                                            <small>85% Free</small>
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
                                            <h3><i class="fas fa-server"></i></h3>
                                            <h5>Server</h5>
                                        </div>
                                        <div class="text-end">
                                            <h4>OK</h4>
                                            <small>Running</small>
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
                                            <h3><i class="fas fa-shield-alt"></i></h3>
                                            <h5>Security</h5>
                                        </div>
                                        <div class="text-end">
                                            <h4>OK</h4>
                                            <small>Protected</small>
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
                                    <h5 class="card-title">System Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>PHP Version</strong></td>
                                            <td>{{ phpversion() }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Laravel Version</strong></td>
                                            <td>{{ app()->version() }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Server Software</strong></td>
                                            <td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Database</strong></td>
                                            <td>MySQL</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Environment</strong></td>
                                            <td>{{ app()->environment() }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-sync me-2"></i>Clear Cache
                                        </button>
                                        <button class="btn btn-outline-warning">
                                            <i class="fas fa-redo me-2"></i>Restart Queue
                                        </button>
                                        <button class="btn btn-outline-info">
                                            <i class="fas fa-eye me-2"></i>View Detailed Logs
                                        </button>
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