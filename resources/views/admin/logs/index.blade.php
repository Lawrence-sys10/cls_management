@extends('layouts.app')

@section('title', 'System Logs')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">System Logs</h4>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash me-2"></i>Clear Logs
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Level</th>
                                    <th>Message</th>
                                    <th>User</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ now()->format('Y-m-d H:i:s') }}</td>
                                    <td><span class="badge bg-info">INFO</span></td>
                                    <td>System logs page accessed</td>
                                    <td>{{ auth()->user()->name }}</td>
                                    <td>127.0.0.1</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No additional log entries found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection