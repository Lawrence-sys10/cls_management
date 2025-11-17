@extends('layouts.app')

@section('title', 'Allocations for ' . $client->full_name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Allocations for {{ $client->full_name }}</h1>
            <p class="text-muted">Client ID: {{ $client->id_number }} • Phone: {{ $client->phone }}</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('clients.show', $client) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Client
            </a>
            <a href="{{ route('allocations.create') }}?client_id={{ $client->id }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Allocation
            </a>
        </div>
    </div>

    <!-- Client Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $allocations->total() }}</h4>
                    <small>Total Allocations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $allocations->where('approval_status', 'approved')->count() }}</h4>
                    <small>Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $allocations->where('approval_status', 'pending')->count() }}</h4>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">GHS {{ number_format($allocations->sum('payment_amount'), 2) }}</h4>
                    <small>Total Payments</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Allocations Table -->
    <div class="card">
        <div class="card-body">
            @if($allocations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Allocation ID</th>
                                <th>Plot Number</th>
                                <th>Location</th>
                                <th>Allocation Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allocations as $allocation)
                                <tr>
                                    <td>#{{ $allocation->id }}</td>
                                    <td>
                                        <a href="{{ route('lands.show', $allocation->land) }}">
                                            {{ $allocation->land->plot_number }}
                                        </a>
                                    </td>
                                    <td>{{ $allocation->land->location }}</td>
                                    <td>{{ $allocation->allocation_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $allocation->approval_status == 'approved' ? 'success' : ($allocation->approval_status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($allocation->approval_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $allocation->payment_status == 'paid' ? 'success' : ($allocation->payment_status == 'partial' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($allocation->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($allocation->payment_amount)
                                            GHS {{ number_format($allocation->payment_amount, 2) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('allocations.show', $allocation) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('allocations.edit', $allocation) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
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
                        Showing {{ $allocations->firstItem() }} to {{ $allocations->lastItem() }} of {{ $allocations->total() }} allocations
                    </div>
                    {{ $allocations->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-landmark fa-4x text-muted mb-3"></i>
                    <h4>No Allocations Found</h4>
                    <p class="text-muted">This client doesn't have any land allocations yet.</p>
                    <a href="{{ route('allocations.create') }}?client_id={{ $client->id }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Allocation
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection