@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">User Management</h4>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Add New User
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters and Search -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form id="bulk-action-form" action="{{ route('admin.users.bulk-actions') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <select name="action" class="form-select form-select-sm" style="min-width: 150px;" required>
                                    <option value="">Bulk Actions</option>
                                    <option value="activate">Activate</option>
                                    <option value="deactivate">Deactivate</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button type="submit" class="btn btn-outline-primary btn-sm">Apply</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="40" class="align-middle">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th class="align-middle">User</th>
                                    <th class="align-middle">Contact</th>
                                    <th class="align-middle">Role</th>
                                    <th class="align-middle">Status</th>
                                    <th class="align-middle">Last Login</th>
                                    <th class="align-middle text-center" width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="align-middle">
                                            <input type="checkbox" name="users[]" value="{{ $user->id }}" class="form-check-input user-checkbox">
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-primary rounded-circle text-white fw-bold small">
                                                        {{ $user->initials }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1" style="min-width: 0;">
                                                    <div class="fw-semibold text-truncate" style="max-width: 150px;" title="{{ $user->name }}">
                                                        {{ $user->name }}
                                                    </div>
                                                    <small class="text-muted text-truncate d-block" style="max-width: 150px;" title="{{ $user->user_type }}">
                                                        {{ $user->user_type }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div style="min-width: 0;">
                                                <div class="text-truncate" style="max-width: 180px;" title="{{ $user->email }}">
                                                    {{ $user->email }}
                                                </div>
                                                @if($user->phone)
                                                    <small class="text-muted text-truncate d-block" style="max-width: 180px;" title="{{ $user->phone }}">
                                                        {{ $user->phone }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex flex-wrap gap-1" style="max-width: 120px;">
                                                @foreach($user->roles as $role)
                                                    <span class="badge bg-secondary badge-sm text-truncate" style="max-width: 100px; font-size: 0.7rem;" title="{{ $role->name }}">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @if($user->is_active)
                                                <span class="badge bg-success badge-sm">Active</span>
                                            @else
                                                <span class="badge bg-danger badge-sm">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <small class="text-muted">
                                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                            </small>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info px-2" title="View" data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye fa-xs"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning px-2" title="Edit" data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit fa-xs"></i>
                                                </a>
                                                @if($user->is_active)
                                                    <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary px-2" title="Deactivate" data-bs-toggle="tooltip">
                                                            <i class="fas fa-toggle-off fa-xs"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success px-2" title="Activate" data-bs-toggle="tooltip">
                                                            <i class="fas fa-toggle-on fa-xs"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2" title="Delete" data-bs-toggle="tooltip" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fas fa-trash fa-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-2x mb-3"></i>
                                                <p class="mb-0">No users found</p>
                                                @if(request('search'))
                                                    <small>Try adjusting your search criteria</small>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                            </div>
                            <div>
                                {{ $users->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-title {
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .badge-sm {
        font-size: 0.7rem;
        padding: 0.25em 0.4em;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .form-check-input {
        margin: 0;
    }
    
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Remove scroll bar and ensure proper table display */
    .table-responsive {
        overflow: visible;
        max-height: none;
    }
    
    .card-body {
        overflow: visible;
    }
    
    /* Ensure table fits within container */
    .table {
        margin-bottom: 0;
        width: 100%;
    }
    
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .card-tools {
            width: 100%;
        }
        
        .card-tools .btn {
            width: 100%;
        }
        
        .table-responsive {
            font-size: 0.8rem;
        }
        
        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 0.7rem;
        }
        
        /* Ensure horizontal scrolling on mobile if needed */
        .table-responsive {
            overflow-x: auto;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .row.mb-3 {
            margin-left: -5px;
            margin-right: -5px;
        }
        
        .row.mb-3 > div {
            padding-left: 5px;
            padding-right: 5px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Select all checkbox
        const selectAll = document.getElementById('select-all');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        
        selectAll.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Bulk action form submission
        const bulkForm = document.getElementById('bulk-action-form');
        bulkForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one user.');
                return false;
            }
            
            const action = this.querySelector('select[name="action"]').value;
            if (!action) {
                e.preventDefault();
                alert('Please select an action.');
                return false;
            }
            
            if (action === 'delete' && !confirm('Are you sure you want to delete ' + checkedBoxes.length + ' user(s)?')) {
                e.preventDefault();
                return false;
            }
            
            // Add selected users to form
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'users[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
        });

        // Update select all checkbox state when individual checkboxes change
        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.user-checkbox:checked').length === userCheckboxes.length;
                selectAll.checked = allChecked;
                selectAll.indeterminate = !allChecked && document.querySelectorAll('.user-checkbox:checked').length > 0;
            });
        });
    });
</script>
@endsection