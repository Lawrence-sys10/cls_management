@extends('layouts.app')

@section('title', 'Add New Chief')
@section('subtitle', 'Register a new traditional chief')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-crown me-2"></i>Add New Chief
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('chiefs.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user me-2"></i>Personal Information
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" 
                                               value="{{ old('name') }}" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               placeholder="Enter chief's full name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" 
                                               value="{{ old('phone') }}" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               placeholder="e.g., 0201234567" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" name="email" id="email" 
                                               value="{{ old('email') }}" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               placeholder="Enter email address">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-map-marked-alt me-2"></i>Jurisdiction Details
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="jurisdiction" class="form-label">Jurisdiction <span class="text-danger">*</span></label>
                                        <input type="text" name="jurisdiction" id="jurisdiction" 
                                               value="{{ old('jurisdiction') }}" 
                                               class="form-control @error('jurisdiction') is-invalid @enderror" 
                                               placeholder="e.g., Techiman Central, Kumasi Metro" required>
                                        @error('jurisdiction')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="area_boundaries" class="form-label">Area Boundaries</label>
                                        <textarea name="area_boundaries" id="area_boundaries" rows="3" 
                                                  class="form-control @error('area_boundaries') is-invalid @enderror" 
                                                  placeholder="Describe the geographical boundaries of the chief's jurisdiction...">{{ old('area_boundaries') }}</textarea>
                                        @error('area_boundaries')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Chief Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active Chief
                                            </label>
                                        </div>
                                        <small class="text-muted">Chief will be able to approve allocations when active</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Additional Information
                            </h5>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes & Comments</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="Enter any additional notes about the chief...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('chiefs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Chiefs
                            </a>
                            
                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Create Chief
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

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.5rem;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.5rem 0.5rem 0 0 !important;
        padding: 1.5rem;
    }
    
    .card-title {
        margin: 0;
        font-weight: 600;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        padding: 0.75rem;
        transition: all 0.2s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .text-primary {
        color: #3b82f6 !important;
    }
    
    .btn {
        border-radius: 0.375rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }
    
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }
    
    h5 {
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.5rem;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                // Format as Ghanaian phone number (0201234567)
                if (value.length <= 10) {
                    e.target.value = value;
                } else {
                    e.target.value = value.substring(0, 10);
                }
            });
            
            // Add placeholder text dynamically
            phoneInput.addEventListener('focus', function() {
                if (!this.value) {
                    this.placeholder = 'e.g., 0201234567 or 0241234567';
                }
            });
            
            phoneInput.addEventListener('blur', function() {
                this.placeholder = 'e.g., 0201234567';
            });
        }
        
        // Auto-suggest jurisdiction based on name
        const nameInput = document.getElementById('name');
        const jurisdictionInput = document.getElementById('jurisdiction');
        
        if (nameInput && jurisdictionInput) {
            nameInput.addEventListener('blur', function() {
                if (nameInput.value && !jurisdictionInput.value) {
                    // Extract potential jurisdiction from name
                    const name = nameInput.value.trim();
                    const words = name.split(' ');
                    if (words.length > 1) {
                        // Use the last word as potential area name
                        const area = words[words.length - 1];
                        jurisdictionInput.value = area + ' Traditional Area';
                    }
                }
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const jurisdiction = document.getElementById('jurisdiction').value;
            
            // Basic required field validation
            if (!name || !phone || !jurisdiction) {
                e.preventDefault();
                showAlert('Please fill in all required fields marked with *.', 'error');
                return false;
            }
            
            // Validate phone number format (Ghanaian numbers)
            const phoneRegex = /^(020|024|054|055|059|026|027|028|050|057)[0-9]{7}$/;
            if (phone && !phoneRegex.test(phone.replace(/\D/g, ''))) {
                e.preventDefault();
                showAlert('Please enter a valid Ghanaian phone number (e.g., 0201234567)', 'error');
                return false;
            }
            
            // Validate email if provided
            const email = document.getElementById('email').value;
            if (email && !isValidEmail(email)) {
                e.preventDefault();
                showAlert('Please enter a valid email address.', 'error');
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
            submitBtn.disabled = true;
        });
        
        // Reset form button
        const resetBtn = form.querySelector('button[type="reset"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
                    form.reset();
                    // Reset the switch to checked
                    document.getElementById('is_active').checked = true;
                }
            });
        }
        
        // Helper functions
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        function showAlert(message, type = 'info') {
            // Remove existing alerts
            const existingAlert = document.querySelector('.custom-alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            // Create alert element
            const alert = document.createElement('div');
            alert.className = `custom-alert alert alert-${type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert at the top of the form
            form.insertBefore(alert, form.firstChild);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }
        
        // Character counter for textareas
        const textareas = form.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            const counter = document.createElement('div');
            counter.className = 'form-text text-end character-counter';
            counter.textContent = `0 characters`;
            textarea.parentNode.appendChild(counter);
            
            textarea.addEventListener('input', function() {
                const count = this.value.length;
                counter.textContent = `${count} characters`;
                counter.className = `form-text text-end character-counter ${count > 500 ? 'text-warning' : 'text-muted'}`;
            });
            
            // Trigger initial count
            textarea.dispatchEvent(new Event('input'));
        });
    });
</script>
@endpush