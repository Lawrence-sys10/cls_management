@extends('layouts.app')

@section('title', 'Edit Client: ' . $client->full_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Edit Client: {{ $client->full_name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('clients.update', $client) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" name="full_name" value="{{ old('full_name', $client->full_name) }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $client->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $client->email) }}">
                                    @error('email')
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
                                        <option value="ghanacard" {{ old('id_type', $client->id_type) == 'ghanacard' ? 'selected' : '' }}>Ghana Card</option>
                                        <option value="passport" {{ old('id_type', $client->id_type) == 'passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="voters_id" {{ old('id_type', $client->id_type) == 'voters_id' ? 'selected' : '' }}>Voter's ID</option>
                                        <option value="drivers_license" {{ old('id_type', $client->id_type) == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                                        <option value="other" {{ old('id_type', $client->id_type) == 'other' ? 'selected' : '' }}>Other</option>
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
                                           id="id_number" name="id_number" value="{{ old('id_number', $client->id_number) }}" required>
                                    @error('id_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender *</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $client->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $client->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $client->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $client->date_of_birth ? $client->date_of_birth->format('Y-m-d') : '') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                           id="occupation" name="occupation" value="{{ old('occupation', $client->occupation) }}">
                                    @error('occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $client->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="emergency_contact" class="form-label">Emergency Contact</label>
                            <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                   id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $client->emergency_contact) }}">
                            @error('emergency_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Client</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>Client Information</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Current ID:</strong>
                            <span class="ms-2">{{ $client->id_type }} - {{ $client->id_number }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Allocations:</strong>
                            <span class="ms-2">{{ $client->allocations->count() }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Documents:</strong>
                            <span class="ms-2">{{ $client->documents->count() }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Last Updated:</strong>
                            <span class="ms-2">{{ $client->updated_at->format('M d, Y H:i') }}</span>
                        </li>
                    </ul>
                    
                    <div class="mt-3">
                        <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="{{ route('clients.allocations', $client) }}" class="btn btn-outline-info btn-sm w-100 mb-2">
                            <i class="fas fa-landmark"></i> View Allocations
                        </a>
                        <a href="{{ route('clients.documents', $client) }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-file"></i> Manage Documents
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection