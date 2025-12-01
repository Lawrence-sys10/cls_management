@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Edit User: {{ $user->name }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info me-2">
                            <i class="fas fa-eye me-2"></i>View
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
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
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active User
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        When inactive, user cannot log into the system.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Password Reset Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-key me-2"></i>Password Reset
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Note:</strong> Leave these fields empty if you don't want to change the password.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" 
                                                   placeholder="Enter new password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" 
                                                   id="password_confirmation" name="password_confirmation" 
                                                   placeholder="Confirm new password">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="generate_password">
                                        <label class="form-check-label" for="generate_password">
                                            Generate random password
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        A strong random password will be generated automatically.
                                    </small>
                                </div>
                                
                                <div id="generated_password_section" class="alert alert-info d-none">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Generated Password:</strong>
                                            <span id="generated_password" class="font-monospace ms-2"></span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyGeneratedPassword()">
                                            <i class="fas fa-copy me-1"></i>Copy
                                        </button>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        Please copy this password and provide it to the user securely.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Roles *</label>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="roles[]" value="{{ $role->name }}" 
                                                   id="role_{{ $role->id }}"
                                                   {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
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
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">Cancel</a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update User
                                </button>
                            </div>
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
        const generatePasswordCheckbox = document.getElementById('generate_password');
        const passwordField = document.getElementById('password');
        const passwordConfirmField = document.getElementById('password_confirmation');
        const generatedPasswordSection = document.getElementById('generated_password_section');
        const generatedPasswordSpan = document.getElementById('generated_password');

        // Function to generate a random password
        function generateRandomPassword(length = 12) {
            const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < length; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            return password;
        }

        // Toggle password generation
        generatePasswordCheckbox.addEventListener('change', function() {
            if (this.checked) {
                const randomPassword = generateRandomPassword();
                passwordField.value = randomPassword;
                passwordConfirmField.value = randomPassword;
                generatedPasswordSpan.textContent = randomPassword;
                generatedPasswordSection.classList.remove('d-none');
                
                // Make fields readonly
                passwordField.readOnly = true;
                passwordConfirmField.readOnly = true;
            } else {
                passwordField.value = '';
                passwordConfirmField.value = '';
                generatedPasswordSection.classList.add('d-none');
                
                // Make fields editable again
                passwordField.readOnly = false;
                passwordConfirmField.readOnly = false;
            }
        });

        // Manual password input should uncheck generate checkbox
        passwordField.addEventListener('input', function() {
            if (this.value && generatePasswordCheckbox.checked) {
                generatePasswordCheckbox.checked = false;
                generatedPasswordSection.classList.add('d-none');
                passwordConfirmField.readOnly = false;
            }
        });
    });

    // Copy generated password to clipboard
    function copyGeneratedPassword() {
        const password = document.getElementById('generated_password').textContent;
        navigator.clipboard.writeText(password).then(function() {
            // Show success feedback
            const button = event.target.closest('button');
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');
            
            setTimeout(function() {
                button.innerHTML = originalHtml;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            alert('Failed to copy password: ' + err);
        });
    }
</script>

<style>
    .font-monospace {
        font-family: 'Courier New', monospace;
        background-color: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
    }
    
    .card .card-header.bg-light {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endsection