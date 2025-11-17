@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Edit Land Plot: {{ $land->plot_number }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('lands.update', $land) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="plot_number" class="form-label">Plot Number *</label>
                                    <input type="text" class="form-control @error('plot_number') is-invalid @enderror" 
                                           id="plot_number" name="plot_number" value="{{ old('plot_number', $land->plot_number) }}" required>
                                    @error('plot_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location *</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location', $land->location) }}" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="chief_id" class="form-label">Chief *</label>
                                    <select class="form-control @error('chief_id') is-invalid @enderror" 
                                            id="chief_id" name="chief_id" required>
                                        <option value="">Select Chief</option>
                                        @foreach($chiefs as $chief)
                                            <option value="{{ $chief->id }}" {{ old('chief_id', $land->chief_id) == $chief->id ? 'selected' : '' }}>
                                                {{ $chief->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('chief_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="area_acres" class="form-label">Area (Acres) *</label>
                                    <input type="number" step="0.01" class="form-control @error('area_acres') is-invalid @enderror" 
                                           id="area_acres" name="area_acres" value="{{ old('area_acres', $land->area_acres) }}" required>
                                    @error('area_acres')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                           id="latitude" name="latitude" value="{{ old('latitude', $land->latitude) }}">
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude', $land->longitude) }}">
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ownership_status" class="form-label">Ownership Status *</label>
                            <select class="form-control @error('ownership_status') is-invalid @enderror" 
                                    id="ownership_status" name="ownership_status" required>
                                <option value="">Select Status</option>
                                <option value="available" {{ old('ownership_status', $land->ownership_status) == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="allocated" {{ old('ownership_status', $land->ownership_status) == 'allocated' ? 'selected' : '' }}>Allocated</option>
                                <option value="reserved" {{ old('ownership_status', $land->ownership_status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            </select>
                            @error('ownership_status')
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

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lands.show', $land) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Land Plot</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>Plot Information</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Current Status:</strong>
                            <span class="badge bg-{{ $land->ownership_status == 'available' ? 'success' : ($land->ownership_status == 'allocated' ? 'primary' : 'warning') }} ms-2">
                                {{ ucfirst($land->ownership_status) }}
                            </span>
                        </li>
                        <li class="mb-2">
                            <strong>Verification:</strong>
                            @if($land->is_verified)
                                <span class="badge bg-success ms-2">Verified</span>
                            @else
                                <span class="badge bg-warning ms-2">Unverified</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <strong>Allocations:</strong>
                            <span class="ms-2">{{ $land->allocations->count() }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Documents:</strong>
                            <span class="ms-2">{{ $land->documents->count() }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Last Updated:</strong>
                            <span class="ms-2">{{ $land->updated_at->format('M d, Y H:i') }}</span>
                        </li>
                    </ul>
                    
                    <div class="mt-3">
                        <a href="{{ route('lands.show', $land) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="{{ route('lands.documents', $land) }}" class="btn btn-outline-info btn-sm w-100">
                            <i class="fas fa-file"></i> Manage Documents
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection