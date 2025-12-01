@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New User</h4>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Staff
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_type" class="form-label">User Type</label>
                                    <select class="form-control @error('user_type') is-invalid @enderror" 
                                            id="user_type" name="user_type">
                                        <option value="staff" {{ old('user_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                        <option value="chief" {{ old('user_type') == 'chief' ? 'selected' : '' }}>Chief</option>
                                        <option value="cls_admin" {{ old('user_type') == 'cls_admin' ? 'selected' : '' }}>CLS Admin</option>
                                    </select>
                                    @error('user_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" id="staff-fields">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                           id="department" name="department" value="{{ old('department') }}">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_id" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                                           id="employee_id" name="employee_id" value="{{ old('employee_id') }}">
                                    @error('employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role *</label>
                            <div class="row">
                                @php
                                    $allowedRoles = ['admin', 'staff', 'chief', 'cls_admin'];
                                    $filteredRoles = $roles->whereIn('name', $allowedRoles);
                                @endphp
                                
                                @foreach($filteredRoles as $role)
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox" type="radio" 
                                                   name="roles[]" value="{{ $role->name }}" 
                                                   id="role_{{ $role->id }}"
                                                   {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userTypeSelect = document.getElementById('user_type');
        const staffFields = document.getElementById('staff-fields');
        const roleCheckboxes = document.querySelectorAll('.role-checkbox');
        
        function toggleStaffFields() {
            if (userTypeSelect.value === 'staff') {
                staffFields.style.display = 'flex';
                // Make staff fields required
                document.getElementById('department').required = true;
                document.getElementById('employee_id').required = true;
            } else {
                staffFields.style.display = 'none';
                // Remove required attribute
                document.getElementById('department').required = false;
                document.getElementById('employee_id').required = false;
            }
        }
        
        // Initial toggle
        toggleStaffFields();
        
        // Toggle on change
        userTypeSelect.addEventListener('change', toggleStaffFields);
        
        // Auto-select role based on user type
        userTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            roleCheckboxes.forEach(checkbox => {
                if (checkbox.value === selectedType) {
                    checkbox.checked = true;
                }
            });
        });
        
        // Auto-update user type based on role selection
        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    userTypeSelect.value = this.value;
                    toggleStaffFields();
                }
            });
        });
        
        // Ensure only one role can be selected (radio behavior)
        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    roleCheckboxes.forEach(otherCheckbox => {
                        if (otherCheckbox !== this) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
            });
        });
    });
</script>
@endsection