@extends('layouts.app')

@section('title', 'My Allocations')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">My Allocations</h4>
                    <p class="card-subtitle">Allocations under your jurisdiction</p>
                </div>
                <div class="card-body">
                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $stats['total_allocations'] }}</h3>
                                            <p>Total Allocations</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-file-contract fa-2x"></i>
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
                                            <h3>{{ $stats['approved_allocations'] }}</h3>
                                            <p>Approved</p>
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
                                            <h3>{{ $stats['pending_allocations'] }}</h3>
                                            <p>Pending</p>
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
                                            <h3>{{ $stats['rejected_allocations'] }}</h3>
                                            <p>Rejected</p>
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
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Land Plot</th>
                                    <th>Allocation Date</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocations as $allocation)
                                    <tr>
                                        <td>
                                            <strong>{{ $allocation->client->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $allocation->client->email }}</small>
                                        </td>
                                        <td>
                                            {{ $allocation->land->plot_number }}
                                            <br>
                                            <small class="text-muted">{{ $allocation->land->location }}</small>
                                        </td>
                                        <td>{{ $allocation->allocation_date->format('M j, Y') }}</td>
                                        <td>{{ Str::limit($allocation->purpose, 50) }}</td>
                                        <td>
                                            @if($allocation->approval_status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($allocation->approval_status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('allocations.show', $allocation) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($allocation->approval_status == 'pending')
                                                    <form action="{{ route('allocations.approve', $allocation) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('allocations.reject', $allocation) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-file-contract fa-2x mb-3"></i>
                                                <p>No allocations found under your jurisdiction</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($allocations->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $allocations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection