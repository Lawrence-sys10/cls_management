@extends('layouts.app')

@section('title', 'Edit Land - ' . $land->plot_number)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Land - {{ $land->plot_number }}
                    </h5>
                    <a href="{{ route('chief.lands.show', $land) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Details
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('chief.lands.update', $land) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="plot_number" class="form-label">Plot Number *</label>
                                    <input type="text" class="form-control @error('plot_number') is-invalid @enderror" 
                                           id="plot_number" name="plot_number" 
                                           value="{{ old('plot_number', $land->plot_number) }}" required>
                                    @error('plot_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location *</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" 
                                           value="{{ old('location', $land->location) }}" required>
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
                                           id="area_acres" name="area_acres" 
                                           value="{{ old('area_acres', $land->area_acres) }}" required>
                                    @error('area_acres')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="area_hectares" class="form-label">Area (Hectares) *</label>
                                    <input type="number" step="0.01" class="form-control @error('area_hectares') is-invalid @enderror" 
                                           id="area_hectares" name="area_hectares" 
                                           value="{{ old('area_hectares', $land->area_hectares) }}" required>
                                    @error('area_hectares')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="land_use" class="form-label">Land Use *</label>
                                    <select class="form-control @error('land_use') is-invalid @enderror" 
                                            id="land_use" name="land_use" required>
                                        <option value="">Select Land Use</option>
                                        <option value="residential" {{ old('land_use', $land->land_use) == 'residential' ? 'selected' : '' }}>Residential</option>
                                        <option value="commercial" {{ old('land_use', $land->land_use) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                        <option value="agricultural" {{ old('land_use', $land->land_use) == 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                                        <option value="industrial" {{ old('land_use', $land->land_use) == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                        <option value="recreational" {{ old('land_use', $land->land_use) == 'recreational' ? 'selected' : '' }}>Recreational</option>
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
                                           id="price" name="price" 
                                           value="{{ old('price', $land->price) }}">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="landmark" class="form-label">Landmark</label>
                            <input type="text" class="form-control @error('landmark') is-invalid @enderror" 
                                   id="landmark" name="landmark" 
                                   value="{{ old('landmark', $land->landmark) }}">
                            @error('landmark')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $land->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="coordinates" class="form-label">GPS Coordinates</label>
                            <input type="text" class="form-control @error('coordinates') is-invalid @enderror" 
                                   id="coordinates" name="coordinates" 
                                   value="{{ old('coordinates', $land->coordinates) }}" 
                                   placeholder="e.g., 5.6037, -0.1870">
                            @error('coordinates')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Status Display (Read-only) -->
                        <div class="mb-3">
                            <label class="form-label">Current Status</label>
                            <div>
                                @php
                                    $statusClass = match($land->ownership_status) {
                                        'vacant' => 'badge-success',
                                        'allocated' => 'badge-primary',
                                        'under_dispute' => 'badge-warning',
                                        default => 'badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} fs-6">
                                    {{ ucfirst(str_replace('_', ' ', $land->ownership_status)) }}
                                </span>
                                <small class="text-muted ms-2">
                                    @if($land->ownership_status === 'allocated')
                                        (Cannot change status while land is allocated)
                                    @elseif($land->ownership_status === 'under_dispute')
                                        (Cannot change status while land is under dispute)
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('chief.lands.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <a href="{{ route('chief.lands.show', $land) }}" class="btn btn-outline-primary ms-2">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Land
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar with Land Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Land Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Plot Number:</strong>
                        <div class="text-muted">{{ $land->plot_number }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Current Location:</strong>
                        <div class="text-muted">{{ $land->location }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Area:</strong>
                        <div class="text-muted">
                            {{ number_format($land->area_acres, 2) }} acres
                            ({{ number_format($land->area_hectares, 2) }} ha)
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Land Use:</strong>
                        <div class="text-muted text-capitalize">{{ str_replace('_', ' ', $land->land_use) }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created:</strong>
                        <div class="text-muted">{{ $land->created_at->format('M j, Y g:i A') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <div class="text-muted">{{ $land->updated_at->format('M j, Y g:i A') }}</div>
                    </div>

                    @if($land->allocations()->exists())
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> This land has existing allocations. 
                        Some changes may affect existing allocation records.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($land->ownership_status === 'vacant')
                        <a href="{{ route('chief.allocations.create') }}?land_id={{ $land->id }}" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-handshake me-2"></i>Allocate This Land
                        </a>
                        @endif
                        
                        <a href="{{ route('chief.lands.documents', $land) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-file-alt me-2"></i>Manage Documents
                        </a>

                        @if($land->ownership_status === 'allocated')
                        <button type="button" class="btn btn-warning btn-sm" disabled>
                            <i class="fas fa-lock me-2"></i>Land is Allocated
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-calculate hectares when acres changes
        const acresInput = document.getElementById('area_acres');
        const hectaresInput = document.getElementById('area_hectares');
        
        if (acresInput && hectaresInput) {
            acresInput.addEventListener('input', function() {
                const acres = parseFloat(this.value) || 0;
                const hectares = acres * 0.404686;
                hectaresInput.value = hectares.toFixed(2);
            });
            
            hectaresInput.addEventListener('input', function() {
                const hectares = parseFloat(this.value) || 0;
                const acres = hectares / 0.404686;
                acresInput.value = acres.toFixed(2);
            });
        }

        // Form submission confirmation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const plotNumber = document.getElementById('plot_number').value;
            if (!confirm(`Are you sure you want to update land "${plotNumber}"?`)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush