@extends('layouts.app')

@section('title', 'Edit Allocation - ' . $allocation->client->full_name)
@section('subtitle', 'Update land allocation details')

@section('actions')
    <a href="{{ route('chief.allocations.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Allocations
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Allocation
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('chief.allocations.update', $allocation) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Client Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Client *</label>
                                    <select class="form-control @error('client_id') is-invalid @enderror" 
                                            id="client_id" name="client_id" required>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" 
                                                    {{ (old('client_id', $allocation->client_id) == $client->id) ? 'selected' : '' }}>
                                                {{ $client->full_name }} - {{ $client->id_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Land Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="land_id" class="form-label">Land Plot *</label>
                                    <select class="form-control @error('land_id') is-invalid @enderror" 
                                            id="land_id" name="land_id" required>
                                        @foreach($lands as $land)
                                            <option value="{{ $land->id }}" 
                                                    {{ (old('land_id', $allocation->land_id) == $land->id) ? 'selected' : '' }}>
                                                Plot {{ $land->plot_number }} - {{ $land->location }}
                                                ({{ $land->size }} acres)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('land_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Allocation Date -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="allocation_date" class="form-label">Allocation Date *</label>
                                    <input type="date" class="form-control @error('allocation_date') is-invalid @enderror" 
                                           id="allocation_date" name="allocation_date" 
                                           value="{{ old('allocation_date', $allocation->allocation_date->format('Y-m-d')) }}" required>
                                    @error('allocation_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Duration -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration_years" class="form-label">Duration (Years) *</label>
                                    <input type="number" class="form-control @error('duration_years') is-invalid @enderror" 
                                           id="duration_years" name="duration_years" 
                                           value="{{ old('duration_years', $allocation->duration_years) }}" min="1" max="99" required>
                                    @error('duration_years')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Purpose -->
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose of Allocation *</label>
                            <textarea class="form-control @error('purpose') is-invalid @enderror" 
                                      id="purpose" name="purpose" rows="3" required>{{ old('purpose', $allocation->purpose) }}</textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rent Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rent_amount" class="form-label">Rent Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">GHS</span>
                                        <input type="number" class="form-control @error('rent_amount') is-invalid @enderror" 
                                               id="rent_amount" name="rent_amount" 
                                               value="{{ old('rent_amount', $allocation->rent_amount) }}" min="0" step="0.01">
                                        @error('rent_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_frequency" class="form-label">Payment Frequency</label>
                                    <select class="form-control @error('payment_frequency') is-invalid @enderror" 
                                            id="payment_frequency" name="payment_frequency">
                                        <option value="">Select Frequency</option>
                                        <option value="monthly" {{ old('payment_frequency', $allocation->payment_frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('payment_frequency', $allocation->payment_frequency) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ old('payment_frequency', $allocation->payment_frequency) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                    @error('payment_frequency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-3">
                            <label for="terms" class="form-label">Terms and Conditions</label>
                            <textarea class="form-control @error('terms') is-invalid @enderror" 
                                      id="terms" name="terms" rows="4">{{ old('terms', $allocation->terms) }}</textarea>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', $allocation->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $allocation->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="terminated" {{ old('status', $allocation->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Expiry Information -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-calendar-alt me-2"></i>Allocation Period
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Original Start:</strong> 
                                    <br>{{ $allocation->allocation_date->format('M j, Y') }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Current Expiry:</strong>
                                    <br>{{ $allocation->allocation_date->addYears($allocation->duration_years)->format('M j, Y') }}
                                </div>
                                <div class="col-md-4">
                                    <strong>New Expiry:</strong>
                                    <br><span id="newExpiryDate">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('chief.allocations.show', $allocation) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <a href="{{ route('chief.allocations.delete', $allocation) }}" class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Allocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Allocation Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="allocation-summary">
                        <h6 class="text-muted mb-3">Current Allocation</h6>
                        
                        <div class="detail-group mb-3">
                            <label class="detail-label">Client</label>
                            <p class="detail-value">{{ $allocation->client->full_name }}</p>
                        </div>
                        
                        <div class="detail-group mb-3">
                            <label class="detail-label">Land Plot</label>
                            <p class="detail-value">Plot {{ $allocation->land->plot_number }}</p>
                        </div>
                        
                        <div class="detail-group mb-3">
                            <label class="detail-label">Current Status</label>
                            <p class="detail-value">
                                <span class="badge 
                                    @if($allocation->status == 'active') bg-success
                                    @elseif($allocation->status == 'terminated') bg-danger
                                    @else bg-warning
                                    @endif">
                                    {{ ucfirst($allocation->status) }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="detail-group mb-3">
                            <label class="detail-label">Created</label>
                            <p class="detail-value">{{ $allocation->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                        
                        <div class="detail-group">
                            <label class="detail-label">Last Updated</label>
                            <p class="detail-value">{{ $allocation->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading mb-2">Update Notes</h6>
                        <ul class="mb-0 small">
                            <li>Changing land plot will update land statuses</li>
                            <li>Status changes affect allocation validity</li>
                            <li>Duration changes update expiry date</li>
                            <li>All changes are logged</li>
                        </ul>
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
    
    .detail-group {
        margin-bottom: 1rem;
    }
    
    .detail-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .detail-value {
        color: #6c757d;
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    
    .allocation-summary h6 {
        font-size: 0.9rem;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allocationDate = document.getElementById('allocation_date');
        const durationYears = document.getElementById('duration_years');
        const newExpiryDate = document.getElementById('newExpiryDate');

        function calculateNewExpiryDate() {
            const startDate = new Date(allocationDate.value);
            const years = parseInt(durationYears.value) || 0;
            
            if (startDate && years > 0) {
                const expiryDate = new Date(startDate);
                expiryDate.setFullYear(expiryDate.getFullYear() + years);
                
                newExpiryDate.textContent = expiryDate.toLocaleDateString('en-US', { 
                    year: 'numeric', month: 'short', day: 'numeric' 
                });
            } else {
                newExpiryDate.textContent = '-';
            }
        }

        allocationDate.addEventListener('change', calculateNewExpiryDate);
        durationYears.addEventListener('input', calculateNewExpiryDate);

        // Initialize on page load
        calculateNewExpiryDate();

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to update this allocation? Changes will take effect immediately.')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush