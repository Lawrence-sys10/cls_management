@extends('layouts.app')

@section('title', isset($land) && $land->exists ? 'Edit Land' : 'Add New Land')
@section('subtitle', isset($land) && $land->exists ? 'Edit Land: ' . $land->plot_number : 'Add New Land')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        {{ isset($land) && $land->exists ? 'Edit Land: ' . $land->plot_number : 'Add New Land' }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($land) && $land->exists ? route('lands.update', $land) : route('lands.store') }}">
                        @csrf
                        @if(isset($land) && $land->exists)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Plot Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="plot_number" class="form-label">Plot Number <span class="text-danger">*</span></label>
                                        <input type="text" name="plot_number" id="plot_number" 
                                               value="{{ old('plot_number', $land->plot_number ?? '') }}" 
                                               class="form-control @error('plot_number') is-invalid @enderror" 
                                               placeholder="Enter plot number" required>
                                        @error('plot_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                        <input type="text" name="location" id="location" 
                                               value="{{ old('location', $land->location ?? '') }}" 
                                               class="form-control @error('location') is-invalid @enderror" 
                                               placeholder="Enter location" required>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="area_acres" class="form-label">Area (Acres) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="area_acres" id="area_acres" 
                                                       value="{{ old('area_acres', $land->area_acres ?? '') }}" 
                                                       class="form-control @error('area_acres') is-invalid @enderror" 
                                                       placeholder="0.00" min="0.01" required>
                                                @error('area_acres')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="area_hectares" class="form-label">Area (Hectares) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="area_hectares" id="area_hectares" 
                                                       value="{{ old('area_hectares', $land->area_hectares ?? '') }}" 
                                                       class="form-control @error('area_hectares') is-invalid @enderror" 
                                                       placeholder="0.00" min="0.01" required>
                                                @error('area_hectares')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="chief_id" class="form-label">Chief <span class="text-danger">*</span></label>
                                        <select name="chief_id" id="chief_id" class="form-select @error('chief_id') is-invalid @enderror" required>
                                            <option value="">Select Chief...</option>
                                            @foreach($chiefs as $chief)
                                            <option value="{{ $chief->id }}" {{ old('chief_id', $land->chief_id ?? '') == $chief->id ? 'selected' : '' }}>
                                                {{ $chief->name }} - {{ $chief->jurisdiction }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('chief_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Land Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="ownership_status" class="form-label">Ownership Status <span class="text-danger">*</span></label>
                                        <select name="ownership_status" id="ownership_status" class="form-select @error('ownership_status') is-invalid @enderror" required>
                                            <option value="vacant" {{ old('ownership_status', $land->ownership_status ?? '') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                                            <option value="allocated" {{ old('ownership_status', $land->ownership_status ?? '') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                                            <option value="under_dispute" {{ old('ownership_status', $land->ownership_status ?? '') == 'under_dispute' ? 'selected' : '' }}>Under Dispute</option>
                                            <option value="reserved" {{ old('ownership_status', $land->ownership_status ?? '') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                        </select>
                                        @error('ownership_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="land_use" class="form-label">Land Use <span class="text-danger">*</span></label>
                                        <select name="land_use" id="land_use" class="form-select @error('land_use') is-invalid @enderror" required>
                                            <option value="residential" {{ old('land_use', $land->land_use ?? '') == 'residential' ? 'selected' : '' }}>Residential</option>
                                            <option value="commercial" {{ old('land_use', $land->land_use ?? '') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                            <option value="agricultural" {{ old('land_use', $land->land_use ?? '') == 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                                            <option value="industrial" {{ old('land_use', $land->land_use ?? '') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                            <option value="mixed" {{ old('land_use', $land->land_use ?? '') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                        </select>
                                        @error('land_use')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="latitude" class="form-label">Latitude</label>
                                                <input type="number" step="0.00000001" name="latitude" id="latitude" 
                                                       value="{{ old('latitude', $land->latitude ?? '') }}" 
                                                       class="form-control @error('latitude') is-invalid @enderror" 
                                                       placeholder="0.000000">
                                                @error('latitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="longitude" class="form-label">Longitude</label>
                                                <input type="number" step="0.00000001" name="longitude" id="longitude" 
                                                       value="{{ old('longitude', $land->longitude ?? '') }}" 
                                                       class="form-control @error('longitude') is-invalid @enderror" 
                                                       placeholder="0.000000">
                                                @error('longitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price (GHS)</label>
                                        <input type="number" step="0.01" name="price" id="price" 
                                               value="{{ old('price', $land->price ?? '') }}" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               placeholder="0.00" min="0">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Additional Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="boundary_description" class="form-label">Boundary Description</label>
                                        <textarea name="boundary_description" id="boundary_description" rows="3" 
                                                  class="form-control @error('boundary_description') is-invalid @enderror" 
                                                  placeholder="Describe the land boundaries...">{{ old('boundary_description', $land->boundary_description ?? '') }}</textarea>
                                        @error('boundary_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="registration_date" class="form-label">Registration Date <span class="text-danger">*</span></label>
                                        <input type="date" name="registration_date" id="registration_date" 
                                               value="{{ old('registration_date', isset($land) && $land->registration_date ? $land->registration_date->format('Y-m-d') : now()->format('Y-m-d')) }}" 
                                               class="form-control @error('registration_date') is-invalid @enderror" required>
                                        @error('registration_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('lands.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Lands
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                {{ isset($land) && $land->exists ? 'Update Land' : 'Create Land' }}
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
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('registration_date').min = today;
        
        // Auto-convert between acres and hectares
        const acresInput = document.getElementById('area_acres');
        const hectaresInput = document.getElementById('area_hectares');
        
        function convertAcresToHectares(acres) {
            return acres * 0.404686;
        }
        
        function convertHectaresToAcres(hectares) {
            return hectares * 2.47105;
        }
        
        if (acresInput && hectaresInput) {
            acresInput.addEventListener('input', function() {
                const acres = parseFloat(this.value) || 0;
                if (acres > 0) {
                    hectaresInput.value = convertAcresToHectares(acres).toFixed(2);
                }
            });
            
            hectaresInput.addEventListener('input', function() {
                const hectares = parseFloat(this.value) || 0;
                if (hectares > 0) {
                    acresInput.value = convertHectaresToAcres(hectares).toFixed(2);
                }
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const plotNumber = document.getElementById('plot_number').value;
            const location = document.getElementById('location').value;
            const areaAcres = document.getElementById('area_acres').value;
            const areaHectares = document.getElementById('area_hectares').value;
            const chiefId = document.getElementById('chief_id').value;
            
            if (!plotNumber || !location || !areaAcres || !areaHectares || !chiefId) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                return false;
            }
        });
    });
</script>
@endpush