@extends('layouts.app')

@section('title', 'Create New Allocation')
@section('subtitle', 'Create a new land allocation')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Create New Allocation</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('allocations.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Land Selection</h5>
                                    
                                    <div class="mb-3">
                                        <label for="land_id" class="form-label">Select Land Plot <span class="text-danger">*</span></label>
                                        <select name="land_id" id="land_id" class="form-select select2 @error('land_id') is-invalid @enderror" required>
                                            <option value="">Choose Land Plot...</option>
                                            @foreach($lands as $land)
                                            <option value="{{ $land->id }}" {{ old('land_id', request('land_id')) == $land->id ? 'selected' : '' }}>
                                                {{ $land->plot_number }} - {{ $land->location }} ({{ number_format($land->area_acres, 2) }} acres)
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('land_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div id="land-details" class="card bg-light border-0 p-3" style="display: none;">
                                        <h6 class="card-title text-dark">Land Details</h6>
                                        <div class="row text-sm text-muted">
                                            <div class="col-6">
                                                <strong>Chief:</strong> <span id="land-chief">-</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Price:</strong> GHS <span id="land-price">0.00</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Land Use:</strong> <span id="land-use">-</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Status:</strong> <span id="land-status">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Client Selection</h5>
                                    
                                    <div class="mb-3">
                                        <label for="client_id" class="form-label">Select Client <span class="text-danger">*</span></label>
                                        <select name="client_id" id="client_id" class="form-select select2 @error('client_id') is-invalid @enderror" required>
                                            <option value="">Choose Client...</option>
                                            @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', request('client_id')) == $client->id ? 'selected' : '' }}>
                                                {{ $client->full_name }} - {{ $client->phone }} ({{ $client->id_number }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div id="client-details" class="card bg-light border-0 p-3" style="display: none;">
                                        <h6 class="card-title text-dark">Client Details</h6>
                                        <div class="row text-sm text-muted">
                                            <div class="col-6">
                                                <strong>Occupation:</strong> <span id="client-occupation">-</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>ID Type:</strong> <span id="client-id-type">-</span>
                                            </div>
                                            <div class="col-12">
                                                <strong>Address:</strong> <span id="client-address">-</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Email:</strong> <span id="client-email">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Allocation Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="chief_id" class="form-label">Approving Chief <span class="text-danger">*</span></label>
                                        <select name="chief_id" id="chief_id" class="form-select select2 @error('chief_id') is-invalid @enderror" required>
                                            <option value="">Select Chief...</option>
                                            @foreach($chiefs as $chief)
                                            <option value="{{ $chief->id }}" {{ old('chief_id') == $chief->id ? 'selected' : '' }}>
                                                {{ $chief->name }} - {{ $chief->jurisdiction }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('chief_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="allocation_date" class="form-label">Allocation Date <span class="text-danger">*</span></label>
                                        <input type="date" name="allocation_date" id="allocation_date" 
                                               value="{{ old('allocation_date', now()->format('Y-m-d')) }}" 
                                               class="form-control @error('allocation_date') is-invalid @enderror" required>
                                        @error('allocation_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Payment Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                                        <select name="payment_status" id="payment_status" class="form-select select2 @error('payment_status') is-invalid @enderror" required>
                                            <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Partial Payment</option>
                                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="overdue" {{ old('payment_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                        </select>
                                        @error('payment_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_amount" class="form-label">Payment Amount (GHS)</label>
                                        <input type="number" step="0.01" name="payment_amount" id="payment_amount" 
                                               value="{{ old('payment_amount') }}" 
                                               class="form-control @error('payment_amount') is-invalid @enderror">
                                        @error('payment_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                        <select name="processed_by" id="processed_by" class="form-select select2 @error('processed_by') is-invalid @enderror" required>
                                            <option value="">Select Staff...</option>
                                            @foreach($staff as $staffMember)
                                            <option value="{{ $staffMember->id }}" {{ old('processed_by') == $staffMember->id ? 'selected' : '' }}>
                                                {{ $staffMember->user->name }} - {{ $staffMember->department }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('processed_by')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Additional Information</h5>
                            
                            <div class="mb-3">
                                <label for="purpose" class="form-label">Purpose of Allocation</label>
                                <textarea name="purpose" id="purpose" rows="3" 
                                          class="form-control @error('purpose') is-invalid @enderror" 
                                          placeholder="Describe the purpose of this land allocation...">{{ old('purpose') }}</textarea>
                                @error('purpose')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="Any additional notes or comments...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('allocations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Allocations
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Allocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        padding: 4px 12px;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for all dropdowns
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option...';
            },
            allowClear: true,
            width: '100%'
        });

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('allocation_date').min = today;
        
        // Land selection details
        const landSelect = document.getElementById('land_id');
        const landDetails = document.getElementById('land-details');
        
        if (landSelect) {
            // Initial trigger for pre-selected values
            if (landSelect.value) {
                updateLandDetails(landSelect.value);
            }

            // Listen for Select2 change events
            $(landSelect).on('change', function() {
                const landId = this.value;
                updateLandDetails(landId);
            });
        }

        // Client selection details
        const clientSelect = document.getElementById('client_id');
        const clientDetails = document.getElementById('client-details');
        
        if (clientSelect) {
            // Initial trigger for pre-selected values
            if (clientSelect.value) {
                updateClientDetails(clientSelect.value);
            }

            // Listen for Select2 change events
            $(clientSelect).on('change', function() {
                const clientId = this.value;
                updateClientDetails(clientId);
            });
        }

        function updateLandDetails(landId) {
            if (landId) {
                // Show loading state
                document.getElementById('land-chief').textContent = 'Loading...';
                document.getElementById('land-price').textContent = '0.00';
                document.getElementById('land-use').textContent = 'Loading...';
                document.getElementById('land-status').textContent = 'Loading...';
                
                // In a real application, you would fetch land details via AJAX
                // For now, we'll simulate with sample data
                setTimeout(() => {
                    document.getElementById('land-chief').textContent = 'Chief Kwame';
                    document.getElementById('land-price').textContent = '15,000.00';
                    document.getElementById('land-use').textContent = 'Residential';
                    document.getElementById('land-status').textContent = 'Available';
                    
                    landDetails.style.display = 'block';
                }, 500);
            } else {
                landDetails.style.display = 'none';
            }
        }

        function updateClientDetails(clientId) {
            if (clientId) {
                // Show loading state
                document.getElementById('client-occupation').textContent = 'Loading...';
                document.getElementById('client-id-type').textContent = 'Loading...';
                document.getElementById('client-address').textContent = 'Loading...';
                document.getElementById('client-email').textContent = 'Loading...';
                
                // Similar to land details, you would fetch client details via AJAX
                setTimeout(() => {
                    document.getElementById('client-occupation').textContent = 'Business Owner';
                    document.getElementById('client-id-type').textContent = 'Ghana Card';
                    document.getElementById('client-address').textContent = 'Accra, Ghana';
                    document.getElementById('client-email').textContent = 'client@example.com';
                    
                    clientDetails.style.display = 'block';
                }, 500);
            } else {
                clientDetails.style.display = 'none';
            }
        }

        // Form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const landId = document.getElementById('land_id').value;
                const clientId = document.getElementById('client_id').value;
                const chiefId = document.getElementById('chief_id').value;
                const processedBy = document.getElementById('processed_by').value;
                
                if (!landId || !clientId || !chiefId || !processedBy) {
                    e.preventDefault();
                    alert('Please fill in all required fields marked with *.');
                    return false;
                }
            });
        }

        // Ensure Select2 works properly with Bootstrap validation
        $('.select2').on('change', function() {
            if ($(this).hasClass('is-invalid')) {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
        });
    });
</script>
@endpush