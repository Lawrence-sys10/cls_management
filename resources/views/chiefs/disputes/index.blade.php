@extends('layouts.app')

@section('title', 'Disputes')
@section('subtitle', 'Manage land allocation disputes and resolutions')

@section('actions')
    <a href="{{ route('chief.disputes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>New Dispute
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Disputes</h3>
                    <div class="stat-value">{{ $disputes->total() }}</div>
                    <div class="stat-trend">
                        <i class="fas fa-gavel"></i>
                        <span>All disputes</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-gavel"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Pending</h3>
                    @php
                        $pendingDisputes = $disputes->where('status', 'pending')->count();
                    @endphp
                    <div class="stat-value">{{ $pendingDisputes }}</div>
                    <div class="stat-trend">
                        <i class="fas fa-clock"></i>
                        <span>Require attention</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>In Progress</h3>
                    @php
                        $inProgressDisputes = $disputes->whereIn('status', ['investigation', 'hearing'])->count();
                    @endphp
                    <div class="stat-value">{{ $inProgressDisputes }}</div>
                    <div class="stat-trend">
                        <i class="fas fa-spinner"></i>
                        <span>Under investigation</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Resolved</h3>
                    @php
                        $resolvedDisputes = $disputes->where('status', 'resolved')->count();
                    @endphp
                    <div class="stat-value">{{ $resolvedDisputes }}</div>
                    <div class="stat-trend">
                        <i class="fas fa-check-circle"></i>
                        <span>Successfully closed</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Disputes Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Dispute Records</h5>
            <div class="header-actions">
                <a href="{{ route('chief.disputes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>New Dispute
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="form-control"
                               placeholder="Case number, description, plot number, client name...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="investigation" {{ request('status') == 'investigation' ? 'selected' : '' }}>Investigation</option>
                            <option value="hearing" {{ request('status') == 'hearing' ? 'selected' : '' }}>Hearing</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            @if($disputes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="disputesTable">
                    <thead>
                        <tr>
                            <th>Case Details</th>
                            <th>Parties</th>
                            <th>Land Details</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Filed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disputes as $dispute)
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
                            
                            $statusColor = $statusColors[$dispute->status] ?? 'secondary';
                            $priorityColor = $priorityColors[$dispute->severity] ?? 'secondary';
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="dispute-avatar me-3">
                                        <div class="avatar-circle bg-{{ $priorityColor }} text-white">
                                            {{ substr($dispute->case_number, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">
                                            {{ $dispute->case_number }}
                                        </div>
                                        <div class="text-muted small text-capitalize">
                                            {{ str_replace('_', ' ', $dispute->dispute_type) }}
                                        </div>
                                        @if($dispute->description)
                                        <small class="text-muted">{{ Str::limit($dispute->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark small">Complainant:</div>
                                <div class="text-dark">{{ $dispute->complainant->full_name ?? 'N/A' }}</div>
                                
                                @if($dispute->respondent)
                                <div class="fw-semibold text-dark small mt-1">Respondent:</div>
                                <div class="text-dark">{{ $dispute->respondent->full_name }}</div>
                                @endif
                            </td>
                            <td>
                                @if($dispute->land)
                                <div class="fw-semibold text-dark">
                                    Plot {{ $dispute->land->plot_number }}
                                </div>
                                <small class="text-muted">{{ Str::limit($dispute->land->location, 25) }}</small>
                                @if($dispute->land->size)
                                <br>
                                <small class="text-muted">{{ $dispute->land->size }} acres</small>
                                @endif
                                @else
                                <span class="text-muted">Land not found</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $priorityColor }}">
                                    <i class="fas fa-flag me-1"></i>{{ ucfirst($dispute->severity) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $statusColor }}">
                                    @if($dispute->status == 'pending')
                                        <i class="fas fa-clock me-1"></i>
                                    @elseif($dispute->status == 'investigation')
                                        <i class="fas fa-search me-1"></i>
                                    @elseif($dispute->status == 'hearing')
                                        <i class="fas fa-gavel me-1"></i>
                                    @elseif($dispute->status == 'resolved')
                                        <i class="fas fa-check me-1"></i>
                                    @else
                                        <i class="fas fa-times me-1"></i>
                                    @endif
                                    {{ ucfirst($dispute->status) }}
                                </span>
                                
                                @if($dispute->status == 'pending' && $dispute->filing_date->diffInDays(now()) > 7)
                                <br>
                                <small class="text-warning mt-1">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                </small>
                                @endif
                            </td>
                            <td>
                                <div class="text-dark">{{ $dispute->filing_date->format('M j, Y') }}</div>
                                <small class="text-muted">{{ $dispute->filing_date->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('chief.disputes.show', $dispute) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('chief.disputes.edit', $dispute) }}" class="btn btn-sm btn-outline-secondary" title="Edit Dispute">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($dispute->status != 'resolved' && $dispute->status != 'closed')
                                    <a href="{{ route('chief.disputes.resolve', $dispute) }}" class="btn btn-sm btn-outline-success" title="Resolve Dispute">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    @endif
                                    @if($dispute->status == 'resolved')
                                    <form action="{{ route('chief.disputes.close', $dispute) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-info" title="Close Dispute">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($dispute->status == 'closed')
                                    <form action="{{ route('chief.disputes.reopen', $dispute) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Reopen Dispute">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('chief.disputes.destroy', $dispute) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Dispute" onclick="return confirm('Are you sure you want to delete this dispute?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $disputes->firstItem() }} to {{ $disputes->lastItem() }} of {{ $disputes->total() }} entries
                </div>
                {{ $disputes->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No dispute records found</h5>
                <p class="text-muted">
                    @if(request('search') || request('status') || request('priority'))
                        No disputes match your search criteria.
                    @else
                        No disputes have been recorded yet.
                    @endif
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('chief.disputes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Dispute
                    </a>
                    @if(request('search') || request('status') || request('priority'))
                    <a href="{{ route('chief.disputes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        border-left: 4px solid #667eea;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .stat-card:hover::before {
        transform: scaleX(1);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .stat-info h3 {
        font-size: 0.9rem;
        font-weight: 500;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #343a40;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-card:nth-child(1) .stat-icon {
        background: rgba(67, 97, 238, 0.1);
        color: #667eea;
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .stat-card:nth-child(4) .stat-icon {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: #6c757d;
    }
    
    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    
    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    
    .badge-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .badge-info {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }
    
    .badge-primary {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }
    
    .badge-secondary {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.2);
    }
    
    .btn-group {
        display: flex;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    
    .header-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .dispute-avatar .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white;
    }
    
    .card-header h5 {
        color: white;
        margin-bottom: 0;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.04);
    }
    
    .avatar-circle.bg-danger { background: linear-gradient(135deg, #dc3545, #c82333) !important; }
    .avatar-circle.bg-warning { background: linear-gradient(135deg, #ffc107, #e0a800) !important; }
    .avatar-circle.bg-info { background: linear-gradient(135deg, #17a2b8, #138496) !important; }
    .avatar-circle.bg-success { background: linear-gradient(135deg, #28a745, #218838) !important; }
    .avatar-circle.bg-primary { background: linear-gradient(135deg, #667eea, #764ba2) !important; }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#disputesTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [[5, 'desc']],
            language: {
                emptyTable: "No dispute records found"
            },
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });

        // Auto-submit form when filters change
        $('#status, #priority').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>
@endpush