<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Allocations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
        }
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: 1px solid #e3e6f0;
        }
        .bg-primary, .bg-success, .bg-warning, .bg-danger {
            color: white !important;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #6e707e;
        }
        .btn-group .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('title', 'Client Allocations')

    @section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Allocations for {{ $client->full_name }}</h4>
                            <p class="card-subtitle">All land allocations for this client</p>
                        </div>
                        <div>
                            @php
                                // Simple role-based navigation - ONLY use chief routes for chiefs
                                if (auth()->user()->hasRole('chief')) {
                                    $backUrl = route('chief.clients.index');
                                    $backText = 'Back to Clients';
                                } else {
                                    // For non-chiefs, use browser back as fallback
                                    $backUrl = 'javascript:history.back()';
                                    $backText = 'Back';
                                }
                            @endphp
                            
                            <a href="{{ $backUrl }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> {{ $backText }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Client Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Client Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-1"><strong>Name:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-1">{{ $client->full_name }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-1"><strong>Email:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-1">{{ $client->email }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-1"><strong>Phone:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-1">{{ $client->phone ?? 'Not provided' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-1"><strong>ID Number:</strong></p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="mb-1">{{ $client->id_number ?? 'Not provided' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Allocation Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Total Allocations:</strong></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1">{{ $stats['total_allocations'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Active Allocations:</strong></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1">{{ $stats['active_allocations'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Pending Approval:</strong></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1">{{ $stats['pending_allocations'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Rejected/Cancelled:</strong></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1">{{ $stats['rejected_allocations'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h3>{{ $stats['total_allocations'] ?? 0 }}</h3>
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
                                                <h3>{{ $stats['approved_allocations'] ?? 0 }}</h3>
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
                                                <h3>{{ $stats['pending_allocations'] ?? 0 }}</h3>
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
                                                <h3>{{ $stats['rejected_allocations'] ?? 0 }}</h3>
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
                                                <strong>{{ $allocation->land->plot_number }}</strong>
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
                                                    @if(auth()->user()->hasRole('chief'))
                                                        <!-- Chief-specific routes -->
                                                        <a href="{{ route('chief.allocations.show', $allocation) }}" class="btn btn-sm btn-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if($allocation->approval_status == 'pending')
                                                            <a href="{{ route('chief.allocations.edit', $allocation) }}" class="btn btn-sm btn-primary" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                        @if(auth()->user()->can('delete_allocations'))
                                                            <form action="{{ route('chief.allocations.destroy', $allocation) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this allocation?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <!-- Regular user routes -->
                                                        <a href="{{ route('allocations.show', $allocation) }}" class="btn btn-sm btn-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if(auth()->user()->can('edit_allocations') && $allocation->approval_status == 'pending')
                                                            <a href="{{ route('allocations.edit', $allocation) }}" class="btn btn-sm btn-primary" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                        @if(auth()->user()->can('delete_allocations'))
                                                            <form action="{{ route('allocations.destroy', $allocation) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this allocation?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-file-contract fa-2x mb-3"></i>
                                                    <p>No allocations found for this client</p>
                                                    @if(auth()->user()->hasRole('chief'))
                                                        <a href="{{ route('chief.allocations.create', ['client_id' => $client->id]) }}" class="btn btn-primary mt-2">
                                                            <i class="fas fa-plus me-1"></i> Create New Allocation
                                                        </a>
                                                    @elseif(auth()->user()->can('create_allocations'))
                                                        <a href="{{ route('allocations.create', ['client_id' => $client->id]) }}" class="btn btn-primary mt-2">
                                                            <i class="fas fa-plus me-1"></i> Create New Allocation
                                                        </a>
                                                    @endif
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

                        <!-- Create New Allocation Button -->
                        @if(auth()->user()->hasRole('chief') && $allocations->count() > 0)
                            <div class="mt-4 text-center">
                                <a href="{{ route('chief.allocations.create', ['client_id' => $client->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create New Allocation for This Client
                                </a>
                            </div>
                        @elseif(auth()->user()->can('create_allocations') && $allocations->count() > 0)
                            <div class="mt-4 text-center">
                                <a href="{{ route('allocations.create', ['client_id' => $client->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create New Allocation for This Client
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>