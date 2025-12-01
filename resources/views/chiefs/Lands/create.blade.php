@extends('layouts.app')

@section('title', 'Add New Land')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>Add New Land
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('chief.lands.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="plot_number" class="form-label">Plot Number *</label>
                                    <input type="text" class="form-control @error('plot_number') is-invalid @enderror" 
                                           id="plot_number" name="plot_number" value="{{ old('plot_number') }}" required>
                                    @error('plot_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location *</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location') }}" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="area_acres" class="form-label">Area (Acres) *</label>
                                    <input type="number" step="0.01" class="form-control @error('area_acres') is-invalid @enderror" 
                                           id="area_acres" name="area_acres" value="{{ old('area_acres') }}" required>
                                    @error('area_acres')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="area_hectares" class="form-label">Area (Hectares) *</label>
                                    <input type="number" step="0.01" class="form-control @error('area_hectares') is-invalid @enderror" 
                                           id="area_hectares" name="area_hectares" value="{{ old('area_hectares') }}" required>
                                    @error('area_hectares')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Add calculation buttons -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="button" id="calculateHectares" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-calculator me-1"></i>Calculate Hectares from Acres
                                    </button>
                                    <button type="button" id="calculateAcres" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-calculator me-1"></i>Calculate Acres from Hectares
                                    </button>
                                </div>
                                <small class="text-muted">Use buttons to calculate between units</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="land_use" class="form-label">Land Use *</label>
                                    <select class="form-control @error('land_use') is-invalid @enderror" 
                                            id="land_use" name="land_use" required>
                                        <option value="">Select Land Use</option>
                                        <option value="residential" {{ old('land_use') == 'residential' ? 'selected' : '' }}>Residential</option>
                                        <option value="commercial" {{ old('land_use') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                        <option value="agricultural" {{ old('land_use') == 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                                        <option value="industrial" {{ old('land_use') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                        <option value="recreational" {{ old('land_use') == 'recreational' ? 'selected' : '' }}>Recreational</option>
                                    </select>
                                    @error('land_use')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (₵)</label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Add Registration Date Field -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="registration_date" class="form-label">Registration Date *</label>
                                    <input type="date" class="form-control @error('registration_date') is-invalid @enderror" 
                                           id="registration_date" name="registration_date" 
                                           value="{{ old('registration_date', now()->format('Y-m-d')) }}" required>
                                    @error('registration_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="landmark" class="form-label">Landmark</label>
                                    <input type="text" class="form-control @error('landmark') is-invalid @enderror" 
                                           id="landmark" name="landmark" value="{{ old('landmark') }}">
                                    @error('landmark')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="coordinates" class="form-label">GPS Coordinates</label>
                            <input type="text" class="form-control @error('coordinates') is-invalid @enderror" 
                                   id="coordinates" name="coordinates" value="{{ old('coordinates') }}" 
                                   placeholder="e.g., 5.6037, -0.1870">
                            @error('coordinates')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('chief.lands.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Lands
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Land
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
                        <i class="fas fa-info-circle me-2"></i>Land Registration Guide
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-2">Required Information</h6>
                        <ul class="mb-0 small">
                            <li><strong>Plot Number:</strong> Unique identifier for the land</li>
                            <li><strong>Location:</strong> Physical location/address</li>
                            <li><strong>Area:</strong> Size in acres and hectares</li>
                            <li><strong>Land Use:</strong> Intended purpose of the land</li>
                            <li><strong>Registration Date:</strong> Date land was registered</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading mb-2">Important Notes</h6>
                        <ul class="mb-0 small">
                            <li>Plot number must be unique</li>
                            <li>Area should be accurate in both acres and hectares</li>
                            <li>Registration date defaults to today</li>
                            <li>GPS coordinates help with precise location</li>
                            <li>Landmark helps identify the location easily</li>
                        </ul>
                    </div>

                    <div class="quick-stats mt-3">
                        <h6 class="text-muted mb-3">Area Conversion</h6>
                        <div class="small text-muted">
                            <p><strong>1 Acre = 0.4047 Hectares</strong></p>
                            <p><strong>1 Hectare = 2.4711 Acres</strong></p>
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
        const acresInput = document.getElementById('area_acres');
        const hectaresInput = document.getElementById('area_hectares');
        const calculateHectaresBtn = document.getElementById('calculateHectares');
        const calculateAcresBtn = document.getElementById('calculateAcres');

        // Conversion constants
        const ACRES_TO_HECTARES = 0.4047;
        const HECTARES_TO_ACRES = 2.4711;

        // Calculate hectares from acres
        function calculateHectares() {
            const acres = parseFloat(acresInput.value);
            if (!isNaN(acres) && acres > 0) {
                const hectares = acres * ACRES_TO_HECTARES;
                hectaresInput.value = hectares.toFixed(4);
                clearFieldErrors();
            } else {
                showError('Please enter a valid number of acres');
            }
        }

        // Calculate acres from hectares
        function calculateAcres() {
            const hectares = parseFloat(hectaresInput.value);
            if (!isNaN(hectares) && hectares > 0) {
                const acres = hectares * HECTARES_TO_ACRES;
                acresInput.value = acres.toFixed(4);
                clearFieldErrors();
            } else {
                showError('Please enter a valid number of hectares');
            }
        }

        // Clear field errors
        function clearFieldErrors() {
            acresInput.classList.remove('is-invalid');
            hectaresInput.classList.remove('is-invalid');
        }

        // Show error message
        function showError(message) {
            // You can implement a toast or alert here
            console.error(message);
            alert(message);
        }

        // Event listeners for calculation buttons
        calculateHectaresBtn.addEventListener('click', calculateHectares);
        calculateAcresBtn.addEventListener('click', calculateAcres);

        // Optional: Auto-calculate on input (commented out to avoid conflicts)
        /*
        let isCalculating = false;
        
        acresInput.addEventListener('input', function() {
            if (!isCalculating && this.value) {
                isCalculating = true;
                calculateHectares();
                isCalculating = false;
            }
        });

        hectaresInput.addEventListener('input', function() {
            if (!isCalculating && this.value) {
                isCalculating = true;
                calculateAcres();
                isCalculating = false;
            }
        });
        */

        // Form submission confirmation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const plotNumber = document.getElementById('plot_number').value;
            
            // Validate that both area fields are filled
            const acres = parseFloat(acresInput.value);
            const hectares = parseFloat(hectaresInput.value);
            
            if (isNaN(acres) || acres <= 0) {
                e.preventDefault();
                acresInput.classList.add('is-invalid');
                alert('Please enter a valid area in acres');
                return;
            }
            
            if (isNaN(hectares) || hectares <= 0) {
                e.preventDefault();
                hectaresInput.classList.add('is-invalid');
                alert('Please enter a valid area in hectares');
                return;
            }

            if (!confirm(`Are you sure you want to create land with plot number "${plotNumber}"?`)) {
                e.preventDefault();
            }
        });

        // Set max date for registration date to today
        const registrationDateInput = document.getElementById('registration_date');
        if (registrationDateInput) {
            const today = new Date().toISOString().split('T')[0];
            registrationDateInput.setAttribute('max', today);
        }

        // Validate coordinates format
        const coordinatesInput = document.getElementById('coordinates');
        if (coordinatesInput) {
            coordinatesInput.addEventListener('blur', function() {
                const coordinates = this.value.trim();
                if (coordinates) {
                    // Basic coordinate validation (latitude,longitude format)
                    const coordRegex = /^-?\d+(\.\d+)?\s*,\s*-?\d+(\.\d+)?$/;
                    if (!coordRegex.test(coordinates)) {
                        this.classList.add('is-invalid');
                        // You could add a custom validation message here
                    } else {
                        this.classList.remove('is-invalid');
                    }
                }
            });
        }
    });
</script>
@endpush