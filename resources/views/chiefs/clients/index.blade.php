@extends('layouts.app')

@section('title', 'My Clients')
@section('subtitle', 'Manage your client records and information')

@section('actions')
    <a href="{{ route('chief.clients.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Client
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Total Clients</h3>
                    <div class="stat-value">{{ $clients->total() }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-users"></i>
                        <span>Your registered clients</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>Active Allocations</h3>
                    <div class="stat-value">{{ $clients->sum('allocations_count') }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-handshake"></i>
                        <span>Current land allocations</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>With Disputes</h3>
                    @php
                        $clientsWithDisputes = $clients->filter(function($client) {
                            return $client->has_disputes ?? false;
                        })->count();
                    @endphp
                    <div class="stat-value">{{ $clientsWithDisputes }}</div>
                    <div class="stat-trend trend-down">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Clients involved in disputes</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <h3>New This Month</h3>
                    @php
                        $newThisMonth = $clients->filter(function($client) {
                            return $client->created_this_month ?? false;
                        })->count();
                    @endphp
                    <div class="stat-value">{{ $newThisMonth }}</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-chart-line"></i>
                        <span>Recent registrations</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Client Records</h5>
            <div class="header-actions">
                <a href="{{ route('chief.clients.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Client
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="form-control"
                               placeholder="Name, email, phone, or ID number...">
                    </div>
                    <div class="col-md-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>

            @if($clients->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="clientsTable">
                    <thead>
                        <tr>
                            <th>Client Info</th>
                            <th>Contact</th>
                            <th>ID Number</th>
                            <th>Allocations</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="client-avatar me-3">
                                        <div class="avatar-circle bg-primary text-white">
                                            {{ substr($client->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $client->name }}</div>
                                        @if($client->occupation)
                                        <small class="text-muted">{{ $client->occupation }}</small>
                                        @endif
                                        @if($client->date_of_birth)
                                        <br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($client->date_of_birth)->age }} years
                                            @if($client->gender)
                                            • {{ ucfirst($client->gender) }}
                                            @endif
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($client->email)
                                <div class="text-dark">
                                    <i class="fas fa-envelope me-1 text-muted"></i>
                                    {{ $client->email }}
                                </div>
                                @endif
                                @if($client->phone)
                                <div class="text-dark">
                                    <i class="fas fa-phone me-1 text-muted"></i>
                                    {{ $client->phone }}
                                </div>
                                @endif
                                @if($client->emergency_contact)
                                <small class="text-muted">
                                    <i class="fas fa-first-aid me-1"></i>
                                    {{ $client->emergency_contact }}
                                </small>
                                @endif
                            </td>
                            <td>
                                <div class="text-dark">{{ $client->id_number }}</div>
                                @if($client->address)
                                <small class="text-muted">{{ Str::limit($client->address, 30) }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="fw-bold text-dark">{{ $client->allocations_count ?? 0 }}</div>
                                    <small class="text-muted">Active</small>
                                </div>
                                @if($client->allocations_count > 0)
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar bg-success" style="width: {{ min($client->allocations_count * 20, 100) }}%"></div>
                                </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = ($client->allocations_count ?? 0) > 0 ? 'badge-success' : 'badge-secondary';
                                    $statusText = ($client->allocations_count ?? 0) > 0 ? 'Active' : 'No Allocations';
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                                @if($client->has_disputes ?? false)
                                <br>
                                <span class="badge badge-warning mt-1">In Dispute</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-dark">{{ $client->created_at->format('M j, Y') }}</div>
                                <small class="text-muted">{{ $client->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('chief.clients.show', $client) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('chief.clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary" title="Edit Client">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('chief.clients.allocations', $client) }}" class="btn btn-sm btn-outline-info" title="View Allocations">
                                        <i class="fas fa-handshake"></i>
                                    </a>
                                    @if(($client->allocations_count ?? 0) == 0)
                                    <a href="{{ route('chief.allocations.create') }}?client_id={{ $client->id }}" class="btn btn-sm btn-outline-success" title="Allocate Land">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('chief.clients.delete', $client) }}" class="btn btn-sm btn-outline-danger" title="Delete Client">
                                        <i class="fas fa-trash"></i>
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
                <div class="text-muted">
                    Showing {{ $clients->firstItem() }} to {{ $clients->lastItem() }} of {{ $clients->total() }} entries
                </div>
                {{ $clients->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No client records found</h5>
                <p class="text-muted">Get started by adding your first client record.</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('chief.clients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Your First Client
                    </a>
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
    
    .trend-up {
        color: #10b981;
    }
    
    .trend-down {
        color: #dc3545;
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
    
    /* Client avatar styles */
    .client-avatar .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
    }
    
    /* Progress bar styles */
    .progress {
        background-color: #e9ecef;
        border-radius: 2px;
    }
    
    .progress-bar {
        border-radius: 2px;
    }
    
    /* Custom styling for chief-specific elements */
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white;
    }
    
    .card-header h5 {
        color: white;
        margin-bottom: 0;
    }
    
    .stat-card {
        border-left-color: #667eea;
    }
    
    /* Table row hover effect */
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.04);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#clientsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: [[5, 'desc']], // Default sort by registration date
            language: {
                emptyTable: "No client records found"
            },
            columnDefs: [
                { orderable: false, targets: [6] } // Disable sorting for actions column
            ]
        });

        // Auto-submit form when filters change
        $('#gender').change(function() {
            if($(this).val()) {
                $(this).closest('form').submit();
            }
        });

        // Quick allocation function
        function quickAllocate(clientId, clientName) {
            if(confirm(`Allocate land to ${clientName}?`)) {
                window.location.href = "{{ route('chief.allocations.create') }}?client_id=" + clientId;
            }
        }
    });

    // Enhanced delete confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('a[href*="delete"]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const clientName = this.closest('tr').querySelector('.fw-semibold').textContent;
                const href = this.getAttribute('href');
                
                if(confirm(`Are you sure you want to delete client "${clientName}"? This action cannot be undone.`)) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
@endpush