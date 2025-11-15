@extends('layouts.app')

@section('title', 'Add New Chief')
@section('subtitle', 'Add New Chief')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Add New Chief
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('chiefs.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Personal Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" id="full_name" 
                                               value="{{ old('full_name') }}" 
                                               class="form-control @error('full_name') is-invalid @enderror" 
                                               placeholder="Enter chief's full name" required>
                                        @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title/Rank <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" 
                                               value="{{ old('title') }}" 
                                               class="form-control @error('title') is-invalid @enderror" 
                                               placeholder="e.g., Nana, Oba, Togbe, Chief" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" 
                                               value="{{ old('phone') }}" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               placeholder="Enter phone number" required>
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
                                    <h5 class="text-primary mb-3">Chief Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="traditional_area" class="form-label">Traditional Area <span class="text-danger">*</span></label>
                                        <input type="text" name="traditional_area" id="traditional_area" 
                                               value="{{ old('traditional_area') }}" 
                                               class="form-control @error('traditional_area') is-invalid @enderror" 
                                               placeholder="e.g., Ashanti, Ga, Dagbon, Ewe" required>
                                        @error('traditional_area')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="community" class="form-label">Community/Town <span class="text-danger">*</span></label>
                                        <input type="text" name="community" id="community" 
                                               value="{{ old('community') }}" 
                                               class="form-control @error('community') is-invalid @enderror" 
                                               placeholder="Enter community or town name" required>
                                        @error('community')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                                        <select name="region" id="region" class="form-select @error('region') is-invalid @enderror" required>
                                            <option value="">Select Region</option>
                                            <option value="Greater Accra" {{ old('region') == 'Greater Accra' ? 'selected' : '' }}>Greater Accra</option>
                                            <option value="Ashanti" {{ old('region') == 'Ashanti' ? 'selected' : '' }}>Ashanti</option>
                                            <option value="Western" {{ old('region') == 'Western' ? 'selected' : '' }}>Western</option>
                                            <option value="Western North" {{ old('region') == 'Western North' ? 'selected' : '' }}>Western North</option>
                                            <option value="Eastern" {{ old('region') == 'Eastern' ? 'selected' : '' }}>Eastern</option>
                                            <option value="Central" {{ old('region') == 'Central' ? 'selected' : '' }}>Central</option>
                                            <option value="Volta" {{ old('region') == 'Volta' ? 'selected' : '' }}>Volta</option>
                                            <option value="Oti" {{ old('region') == 'Oti' ? 'selected' : '' }}>Oti</option>
                                            <option value="Bono" {{ old('region') == 'Bono' ? 'selected' : '' }}>Bono</option>
                                            <option value="Bono East" {{ old('region') == 'Bono East' ? 'selected' : '' }}>Bono East</option>
                                            <option value="Ahafo" {{ old('region') == 'Ahafo' ? 'selected' : '' }}>Ahafo</option>
                                            <option value="Northern" {{ old('region') == 'Northern' ? 'selected' : '' }}>Northern</option>
                                            <option value="North East" {{ old('region') == 'North East' ? 'selected' : '' }}>North East</option>
                                            <option value="Savannah" {{ old('region') == 'Savannah' ? 'selected' : '' }}>Savannah</option>
                                            <option value="Upper East" {{ old('region') == 'Upper East' ? 'selected' : '' }}>Upper East</option>
                                            <option value="Upper West" {{ old('region') == 'Upper West' ? 'selected' : '' }}>Upper West</option>
                                        </select>
                                        @error('region')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="rank_level" class="form-label">Rank Level</label>
                                        <select name="rank_level" id="rank_level" class="form-select @error('rank_level') is-invalid @enderror">
                                            <option value="">Select Rank Level</option>
                                            <option value="paramount" {{ old('rank_level') == 'paramount' ? 'selected' : '' }}>Paramount Chief</option>
                                            <option value="divisional" {{ old('rank_level') == 'divisional' ? 'selected' : '' }}>Divisional Chief</option>
                                            <option value="sub-chief" {{ old('rank_level') == 'sub-chief' ? 'selected' : '' }}>Sub-Chief</option>
                                            <option value="queen_mother" {{ old('rank_level') == 'queen_mother' ? 'selected' : '' }}>Queen Mother</option>
                                            <option value="elder" {{ old('rank_level') == 'elder' ? 'selected' : '' }}>Elder</option>
                                            <option value="other" {{ old('rank_level') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('rank_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Address Information</h5>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Full Address <span class="text-danger">*</span></label>
                                <textarea name="address" id="address" rows="3" 
                                          class="form-control @error('address') is-invalid @enderror" 
                                          placeholder="Enter chief's palace address or residence..." required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city" class="form-label">City/Town</label>
                                        <input type="text" name="city" id="city" 
                                               value="{{ old('city') }}" 
                                               class="form-control @error('city') is-invalid @enderror" 
                                               placeholder="Enter city or town">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="landmarks" class="form-label">Landmarks</label>
                                        <input type="text" name="landmarks" id="landmarks" 
                                               value="{{ old('landmarks') }}" 
                                               class="form-control @error('landmarks') is-invalid @enderror" 
                                               placeholder="Enter nearby landmarks">
                                        @error('landmarks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Additional Information</h5>
                            
                            <div class="mb-3">
                                <label for="years_of_service" class="form-label">Years of Service</label>
                                <input type="number" name="years_of_service" id="years_of_service" 
                                       value="{{ old('years_of_service') }}" 
                                       class="form-control @error('years_of_service') is-invalid @enderror" 
                                       placeholder="Enter number of years as chief" min="0" max="100">
                                @error('years_of_service')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="Enter any additional notes about the chief...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Chief
                                </label>
                            </div>
                            <small class="text-muted">Uncheck to mark chief as inactive</small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('chiefs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Chiefs
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Create Chief
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
        // Phone number formatting (similar to your client form)
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('0')) {
                    value = '+233' + value.substring(1);
                }
                if (value.startsWith('233')) {
                    value = '+' + value;
                }
                e.target.value = value;
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const fullName = document.getElementById('full_name').value;
            const title = document.getElementById('title').value;
            const phone = document.getElementById('phone').value;
            const traditionalArea = document.getElementById('traditional_area').value;
            const community = document.getElementById('community').value;
            const region = document.getElementById('region').value;
            const address = document.getElementById('address').value;
            
            if (!fullName || !title || !phone || !traditionalArea || !community || !region || !address) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                return false;
            }
            
            // Validate phone number format
            const phoneRegex = /^\+233[0-9]{9}$/;
            if (phone && !phoneRegex.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid Ghanaian phone number (e.g., +233XXXXXXXXX)');
                return false;
            }
        });

        // Auto-fill city based on community
        const communityInput = document.getElementById('community');
        const cityInput = document.getElementById('city');
        
        if (communityInput && cityInput) {
            communityInput.addEventListener('blur', function() {
                if (communityInput.value && !cityInput.value) {
                    cityInput.value = communityInput.value;
                }
            });
        }
    });
</script>
@endpush