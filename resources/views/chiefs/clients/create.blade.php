@extends('layouts.app')

@section('title', 'Add New Client')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>Add New Client
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('chief.clients.store') }}" method="POST">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="section-header mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h6>
                            <div class="section-divider"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_type" class="form-label">ID Type *</label>
                                    <select class="form-control @error('id_type') is-invalid @enderror" 
                                            id="id_type" name="id_type" required>
                                        <option value="">Select ID Type</option>
                                        <option value="ghanacard" {{ old('id_type') == 'ghanacard' ? 'selected' : '' }}>Ghana Card</option>
                                        <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="drivers_license" {{ old('id_type') == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                                        <option value="voters_id" {{ old('id_type') == 'voters_id' ? 'selected' : '' }}>Voter's ID</option>
                                    </select>
                                    @error('id_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_number" class="form-label">ID Number *</label>
                                    <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                                           id="id_number" name="id_number" value="{{ old('id_number') }}" required>
                                    @error('id_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender *</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                           id="occupation" name="occupation" value="{{ old('occupation') }}">
                                    @error('occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="section-header mb-4 mt-4">
                            <h6 class="section-title">
                                <i class="fas fa-address-book me-2"></i>Contact Information
                            </h6>
                            <div class="section-divider"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="emergency_contact" class="form-label">Emergency Contact</label>
                            <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                   id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}"
                                   placeholder="Name and phone number of emergency contact">
                            @error('emergency_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Information -->
                        <div class="section-header mb-4 mt-4">
                            <h6 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>Additional Information
                            </h6>
                            <div class="section-divider"></div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="4" 
                                      placeholder="Any additional information about the client...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('chief.clients.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Clients
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Client
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar with Help Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Client Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-2">Required Information</h6>
                        <ul class="mb-0 small">
                            <li><strong>Full Name:</strong> Client's complete legal name</li>
                            <li><strong>ID Type & Number:</strong> Government-issued identification</li>
                            <li><strong>Phone Number:</strong> Primary contact number</li>
                            <li><strong>Date of Birth:</strong> Client's birth date</li>
                            <li><strong>Gender:</strong> Client's gender</li>
                            <li><strong>Address:</strong> Current residential address</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading mb-2">Important Notes</h6>
                        <ul class="mb-0 small">
                            <li>Ensure ID number is accurate and unique</li>
                            <li>Provide valid contact information</li>
                            <li>Emergency contact is recommended for safety</li>
                            <li>Notes can include special requirements or preferences</li>
                        </ul>
                    </div>

                    <div class="quick-stats mt-3">
                        <h6 class="text-muted mb-3">Your Client Statistics</h6>
                        <div class="d-flex justify-content-between text-center">
                            <div>
                                <div class="fw-bold text-primary">{{ Auth::user()->clients()->count() }}</div>
                                <small class="text-muted">Total Clients</small>
                            </div>
                            <div>
                                <div class="fw-bold text-success">{{ Auth::user()->clients()->has('allocations')->count() }}</div>
                                <small class="text-muted">With Allocations</small>
                            </div>
                            <div>
                                <div class="fw-bold text-info">{{ Auth::user()->clients()->whereDate('created_at', '>=', now()->subMonth())->count() }}</div>
                                <small class="text-muted">New This Month</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .section-header {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
    }
    
    .section-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        width: 50px;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .alert ul {
        margin-bottom: 0;
        padding-left: 1rem;
    }
    
    .alert ul li {
        margin-bottom: 0.25rem;
    }
    
    .quick-stats {
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate age from date of birth
        const dobInput = document.getElementById('date_of_birth');
        if (dobInput) {
            dobInput.addEventListener('change', function() {
                const dob = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                
                if (age > 0) {
                    // You could display the age somewhere if needed
                    console.log('Client age:', age);
                }
            });
        }

        // Form submission confirmation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const clientName = document.getElementById('full_name').value;
            if (!confirm(`Are you sure you want to create client "${clientName}"?`)) {
                e.preventDefault();
            }
        });

        // Real-time validation for ID number based on ID type
        const idTypeSelect = document.getElementById('id_type');
        const idNumberInput = document.getElementById('id_number');

        if (idTypeSelect && idNumberInput) {
            idTypeSelect.addEventListener('change', function() {
                const idType = this.value;
                
                // Set placeholder based on ID type
                switch(idType) {
                    case 'ghanacard':
                        idNumberInput.placeholder = 'GHA-XXXXXXXX-X';
                        break;
                    case 'passport':
                        idNumberInput.placeholder = 'Passport number';
                        break;
                    case 'drivers_license':
                        idNumberInput.placeholder = 'Driver\'s license number';
                        break;
                    case 'voters_id':
                        idNumberInput.placeholder = 'Voter\'s ID number';
                        break;
                    default:
                        idNumberInput.placeholder = 'ID Number';
                }
            });
        }
    });
</script>
@endpush