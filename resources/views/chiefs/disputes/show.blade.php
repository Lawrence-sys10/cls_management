@extends('layouts.app')

@section('title', 'Dispute Details - ' . $dispute->case_number)
@section('subtitle', 'View dispute information and management actions')

@section('actions')
    <div class="btn-group">
        <a href="{{ route('chief.disputes.edit', $dispute) }}" class="btn btn-outline-secondary">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        @if($dispute->status != 'resolved' && $dispute->status != 'closed')
        <a href="{{ route('chief.disputes.resolve', $dispute) }}" class="btn btn-success">
            <i class="fas fa-check me-2"></i>Resolve
        </a>
        @endif
        @if($dispute->status == 'resolved')
        <form action="{{ route('chief.disputes.close', $dispute) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-info">
                <i class="fas fa-lock me-2"></i>Close Case
            </button>
        </form>
        @endif
        @if($dispute->status == 'closed')
        <form action="{{ route('chief.disputes.reopen', $dispute) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-undo me-2"></i>Reopen
            </button>
        </form>
        @endif
        <form action="{{ route('chief.disputes.destroy', $dispute) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this dispute? This action cannot be undone.')">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Case Overview -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Case Overview</h5>
                    <div>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'investigation' => 'info', 
                                'hearing' => 'primary',
                                'resolved' => 'success',
                                'closed' => 'secondary'
                            ];
                            $priorityColors = [
                                'low' => 'success',
                                'medium' => 'info',
                                'high' => 'warning',
                                'critical' => 'danger'
                            ];
                        @endphp
                        <span class="badge badge-{{ $priorityColors[$dispute->severity] }} me-2">
                            <i class="fas fa-flag me-1"></i>{{ ucfirst($dispute->severity) }} Priority
                        </span>
                        <span class="badge badge-{{ $statusColors[$dispute->status] }}">
                            {{ ucfirst($dispute->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Case Number:</th>
                                    <td><strong>{{ $dispute->case_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Dispute Type:</th>
                                    <td class="text-capitalize">{{ str_replace('_', ' ', $dispute->dispute_type) }}</td>
                                </tr>
                                <tr>
                                    <th>Filed Date:</th>
                                    <td>{{ $dispute->filing_date->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Duration:</th>
                                    <td>{{ $dispute->filing_date->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Land Plot:</th>
                                    <td>
                                        @if($dispute->land)
                                        <strong>Plot {{ $dispute->land->plot_number }}</strong>
                                        @else
                                        <span class="text-danger">Land record deleted</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Location:</th>
                                    <td>{{ $dispute->land->location ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Land Size:</th>
                                    <td>{{ $dispute->land->size ?? 'N/A' }} acres</td>
                                </tr>
                                <tr>
                                    <th>Land Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $dispute->land->ownership_status == 'under_dispute' ? 'warning' : 'success' }}">
                                            {{ str_replace('_', ' ', $dispute->land->ownership_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Dispute Description -->
                    <div class="mt-4">
                        <h6>Dispute Description</h6>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($dispute->description)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parties Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Parties Involved</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Complainant -->
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Complainant</h6>
                                </div>
                                <div class="card-body">
                                    @if($dispute->complainant)
                                    <h6 class="text-primary">{{ $dispute->complainant->full_name }}</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>ID Number:</strong></td>
                                            <td>{{ $dispute->complainant->id_number }}</td>
                                        </tr>
                                        @if($dispute->complainant->phone)
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $dispute->complainant->phone }}</td>
                                        </tr>
                                        @endif
                                        @if($dispute->complainant->email)
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $dispute->complainant->email }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                    <a href="{{ route('chief.clients.show', $dispute->complainant) }}" class="btn btn-sm btn-outline-primary">
                                        View Client Details
                                    </a>
                                    @else
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                        <p>Complainant record not found</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Respondent -->
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Respondent</h6>
                                </div>
                                <div class="card-body">
                                    @if($dispute->respondent)
                                    <h6 class="text-info">{{ $dispute->respondent->full_name }}</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>ID Number:</strong></td>
                                            <td>{{ $dispute->respondent->id_number }}</td>
                                        </tr>
                                        @if($dispute->respondent->phone)
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $dispute->respondent->phone }}</td>
                                        </tr>
                                        @endif
                                        @if($dispute->respondent->email)
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $dispute->respondent->email }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                    <a href="{{ route('chief.clients.show', $dispute->respondent) }}" class="btn btn-sm btn-outline-info">
                                        View Client Details
                                    </a>
                                    @else
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-minus-circle fa-2x mb-2"></i>
                                        <p>No respondent specified</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resolutions History -->
            @if($dispute->resolutions->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Resolution History</h5>
                </div>
                <div class="card-body">
                    @foreach($dispute->resolutions as $resolution)
                    <div class="timeline-item mb-4">
                        <div class="d-flex">
                            <div class="timeline-marker bg-success rounded-circle me-3" style="width: 12px; height: 12px; margin-top: 5px;"></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="mb-1 text-success">Case Resolved</h6>
                                    <small class="text-muted">{{ $resolution->created_at->format('M j, Y g:i A') }}</small>
                                </div>
                                <p class="mb-1"><strong>Outcome:</strong> 
                                    <span class="text-capitalize">{{ str_replace('_', ' ', $resolution->outcome) }}</span>
                                </p>
                                <p class="mb-1"><strong>Resolution:</strong></p>
                                <div class="border rounded p-3 bg-light mb-2">
                                    {!! nl2br(e($resolution->resolution)) !!}
                                </div>
                                @if($resolution->notes)
                                <p class="mb-1"><strong>Notes:</strong></p>
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($resolution->notes)) !!}
                                </div>
                                @endif
                                <small class="text-muted">Resolved by: {{ $resolution->resolved_by_name ?? 'System' }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Case Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Case Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('chief.disputes.edit', $dispute) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Edit Dispute
                        </a>
                        
                        @if($dispute->status != 'resolved' && $dispute->status != 'closed')
                        <a href="{{ route('chief.disputes.resolve', $dispute) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-check me-2"></i>Resolve Dispute
                        </a>
                        @endif

                        @if($dispute->status == 'resolved')
                        <form action="{{ route('chief.disputes.close', $dispute) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm">
                                <i class="fas fa-lock me-2"></i>Close Case
                            </button>
                        </form>
                        @endif

                        @if($dispute->status == 'closed')
                        <form action="{{ route('chief.disputes.reopen', $dispute) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-undo me-2"></i>Reopen Case
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('chief.disputes.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-list me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Case Timeline -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Case Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-primary rounded-circle" style="width: 10px; height: 10px;"></div>
                            <div class="timeline-content ms-4">
                                <h6 class="mb-0">Case Filed</h6>
                                <small class="text-muted">{{ $dispute->filing_date->format('M j, Y') }}</small>
                                <p class="mb-0 small">Dispute registered in the system</p>
                            </div>
                        </div>

                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-{{ $statusColors[$dispute->status] }} rounded-circle" style="width: 10px; height: 10px;"></div>
                            <div class="timeline-content ms-4">
                                <h6 class="mb-0">Current Status</h6>
                                <small class="text-muted">{{ $dispute->updated_at->format('M j, Y') }}</small>
                                <p class="mb-0 small text-capitalize">{{ $dispute->status }}</p>
                            </div>
                        </div>

                        @if($dispute->resolved_at)
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-success rounded-circle" style="width: 10px; height: 10px;"></div>
                            <div class="timeline-content ms-4">
                                <h6 class="mb-0">Case Resolved</h6>
                                <small class="text-muted">{{ $dispute->resolved_at->format('M j, Y') }}</small>
                                <p class="mb-0 small">Dispute successfully resolved</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
    }
    
    .timeline-item {
        position: relative;
    }
    
    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
    }
    
    .timeline-content {
        padding-bottom: 1rem;
    }
    
    .badge-success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .badge-danger { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.2); }
    .badge-info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
    .badge-primary { background: rgba(102, 126, 234, 0.1); color: #667eea; border: 1px solid rgba(102, 126, 234, 0.2); }
    .badge-secondary { background: rgba(108, 117, 125, 0.1); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.2); }
</style>
@endpush