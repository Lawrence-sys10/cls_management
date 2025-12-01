@extends('layouts.app')

@section('title', 'Edit Client - ' . $client->name)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Edit Client - {{ $client->name }}
                    </h5>
                    <a href="{{ route('chief.clients.show', $client) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Details
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('chief.clients.update', $client) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $client->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', $client->date_of_birth ? $client->date_of_birth->format('Y-m-d') : '') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender">
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
                            <div class="col-md-4">
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
                                           id="email" name="email" value="{{ old('email', $client->email) }}">
                                    @error('email')
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
                                   id="emergency_contact" name="emergency_contact" 
                                   value="{{ old('emergency_contact', $client->emergency_contact) }}"
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
                                      placeholder="Any additional information about the client...">{{ old('notes', $client->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('chief.clients.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <a href="{{ route('chief.clients.show', $client) }}" class="btn btn-outline-primary ms-2">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Client
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Client Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Name:</strong>
                        <div class="text-muted">{{ $client->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>ID Number:</strong>
                        <div class="text-muted">{{ $client->id_number }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Phone:</strong>
                        <div class="text-muted">{{ $client->phone }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <div class="text-muted">{{ $client->email ?? 'Not provided' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Created:</strong>
                        <div class="text-muted">{{ $client->created_at->format('M j, Y g:i A') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <div class="text-muted">{{ $client->updated_at->format('M j, Y g:i A') }}</div>
                    </div>

                    @if($client->allocations()->exists())
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> This client has {{ $client->allocations()->count() }} allocation(s). 
                        Some changes may affect allocation records.
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
                        <a href="{{ route('chief.clients.allocations', $client) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-handshake me-2"></i>View Allocations
                        </a>
                        
                        <a href="{{ route('chief.allocations.create') }}?client_id={{ $client->id }}" class="btn btn-success btn-sm">
                            <i class="fas fa-map-marked-alt me-2"></i>Allocate Land
                        </a>

                        <a href="{{ route('chief.clients.documents', $client) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file-alt me-2"></i>Manage Documents
                        </a>
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission confirmation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const clientName = document.getElementById('name').value;
            if (!confirm(`Are you sure you want to update client "${clientName}"?`)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush