@extends('layouts.app')

@section('title', isset($client) && $client->exists ? 'Edit Client' : 'Add New Client')
@section('subtitle', isset($client) && $client->exists ? 'Edit Client: ' . $client->full_name : 'Add New Client')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        {{ isset($client) && $client->exists ? 'Edit Client: ' . $client->full_name : 'Add New Client' }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($client) && $client->exists ? route('clients.update', $client) : route('clients.store') }}">
                        @csrf
                        @if(isset($client) && $client->exists)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Personal Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" id="full_name" 
                                               value="{{ old('full_name', $client->full_name ?? '') }}" 
                                               class="form-control @error('full_name') is-invalid @enderror" 
                                               placeholder="Enter full name" required>
                                        @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                                <input type="text" name="phone" id="phone" 
                                                       value="{{ old('phone', $client->phone ?? '') }}" 
                                                       class="form-control @error('phone') is-invalid @enderror" 
                                                       placeholder="Enter phone number" required>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email Address</label>
                                                <input type="email" name="email" id="email" 
                                                       value="{{ old('email', $client->email ?? '') }}" 
                                                       class="form-control @error('email') is-invalid @enderror" 
                                                       placeholder="Enter email address">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                                    <option value="">Select Gender...</option>
                                                    <option value="male" {{ old('gender', $client->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('gender', $client->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ old('gender', $client->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                                <input type="date" name="date_of_birth" id="date_of_birth" 
                                                       value="{{ old('date_of_birth', isset($client) && $client->date_of_birth ? $client->date_of_birth->format('Y-m-d') : '') }}" 
                                                       class="form-control @error('date_of_birth') is-invalid @enderror">
                                                @error('date_of_birth')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Identification Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="id_type" class="form-label">ID Type <span class="text-danger">*</span></label>
                                        <select name="id_type" id="id_type" class="form-select @error('id_type') is-invalid @enderror" required>
                                            <option value="">Select ID Type...</option>
                                            <option value="ghanacard" {{ old('id_type', $client->id_type ?? '') == 'ghanacard' ? 'selected' : '' }}>Ghana Card</option>
                                            <option value="passport" {{ old('id_type', $client->id_type ?? '') == 'passport' ? 'selected' : '' }}>Passport</option>
                                            <option value="voters_id" {{ old('id_type', $client->id_type ?? '') == 'voters_id' ? 'selected' : '' }}>Voter's ID</option>
                                            <option value="drivers_license" {{ old('id_type', $client->id_type ?? '') == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                                            <option value="other" {{ old('id_type', $client->id_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('id_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_number" class="form-label">ID Number <span class="text-danger">*</span></label>
                                        <input type="text" name="id_number" id="id_number" 
                                               value="{{ old('id_number', $client->id_number ?? '') }}" 
                                               class="form-control @error('id_number') is-invalid @enderror" 
                                               placeholder="Enter ID number" required>
                                        @error('id_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="occupation" class="form-label">Occupation</label>
                                        <input type="text" name="occupation" id="occupation" 
                                               value="{{ old('occupation', $client->occupation ?? '') }}" 
                                               class="form-control @error('occupation') is-invalid @enderror" 
                                               placeholder="Enter occupation">
                                        @error('occupation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="emergency_contact" class="form-label">Emergency Contact</label>
                                        <input type="text" name="emergency_contact" id="emergency_contact" 
                                               value="{{ old('emergency_contact', $client->emergency_contact ?? '') }}" 
                                               class="form-control @error('emergency_contact') is-invalid @enderror" 
                                               placeholder="Enter emergency contact">
                                        @error('emergency_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Contact Information</h5>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" id="address" rows="3" 
                                          class="form-control @error('address') is-invalid @enderror" 
                                          placeholder="Enter full address...">{{ old('address', $client->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Clients
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                {{ isset($client) && $client->exists ? 'Update Client' : 'Create Client' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set maximum date to today for date of birth
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date_of_birth').max = today;
        
        // Phone number validation
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                // Remove any non-digit characters except + at the beginning
                this.value = this.value.replace(/[^\d+]/g, '');
                
                // Ensure it starts with country code if international
                if (this.value.startsWith('+')) {
                    // International format validation
                    if (this.value.length > 15) {
                        this.value = this.value.slice(0, 15);
                    }
                } else {
                    // Local Ghanaian format (024, 020, 059, etc.)
                    if (this.value.length > 10) {
                        this.value = this.value.slice(0, 10);
                    }
                }
            });
        }
        
        // ID number validation based on ID type
        const idTypeSelect = document.getElementById('id_type');
        const idNumberInput = document.getElementById('id_number');
        
        if (idTypeSelect && idNumberInput) {
            idTypeSelect.addEventListener('change', function() {
                // Clear any previous validation
                idNumberInput.classList.remove('is-invalid');
                
                switch(this.value) {
                    case 'ghanacard':
                        idNumberInput.placeholder = 'GHA-XXXXXXXXX-X';
                        break;
                    case 'passport':
                        idNumberInput.placeholder = 'Enter passport number';
                        break;
                    case 'voters_id':
                        idNumberInput.placeholder = 'Enter voter ID number';
                        break;
                    case 'drivers_license':
                        idNumberInput.placeholder = 'Enter driver\'s license number';
                        break;
                    default:
                        idNumberInput.placeholder = 'Enter ID number';
                }
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const fullName = document.getElementById('full_name').value;
            const phone = document.getElementById('phone').value;
            const idType = document.getElementById('id_type').value;
            const idNumber = document.getElementById('id_number').value;
            const gender = document.getElementById('gender').value;
            
            if (!fullName || !phone || !idType || !idNumber || !gender) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                return false;
            }
            
            // Additional phone validation
            if (phone.length < 10) {
                e.preventDefault();
                alert('Please enter a valid phone number (at least 10 digits).');
                phoneInput.focus();
                return false;
            }
        });
    });
</script>
@endpush