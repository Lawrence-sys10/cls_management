@extends('layouts.app')

@section('title', 'Edit Allocation: #' . $allocation->id)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Edit Allocation #{{ $allocation->id }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('allocations.update', $allocation) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Allocation Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="land_id" class="form-label">Land Plot <span class="text-danger">*</span></label>
                                        <select name="land_id" id="land_id" class="form-select select2 @error('land_id') is-invalid @enderror" required>
                                            <option value="">Select Land Plot...</option>
                                            @foreach($lands as $land)
                                            <option value="{{ $land->id }}" {{ old('land_id', $allocation->land_id) == $land->id ? 'selected' : '' }}>
                                                {{ $land->plot_number }} - {{ $land->location }} ({{ number_format($land->area_acres, 2) }} acres)
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('land_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                        <select name="client_id" id="client_id" class="form-select select2 @error('client_id') is-invalid @enderror" required>
                                            <option value="">Select Client...</option>
                                            @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', $allocation->client_id) == $client->id ? 'selected' : '' }}>
                                                {{ $client->full_name }} - {{ $client->phone }} ({{ $client->id_number }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="allocation_date" class="form-label">Allocation Date <span class="text-danger">*</span></label>
                                        <input type="date" name="allocation_date" id="allocation_date" 
                                               value="{{ old('allocation_date', $allocation->allocation_date->format('Y-m-d')) }}" 
                                               class="form-control @error('allocation_date') is-invalid @enderror" required>
                                        @error('allocation_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="purpose" class="form-label">Purpose</label>
                                        <textarea name="purpose" id="purpose" rows="3" 
                                                  class="form-control @error('purpose') is-invalid @enderror" 
                                                  placeholder="Describe the purpose of this allocation...">{{ old('purpose', $allocation->purpose) }}</textarea>
                                        @error('purpose')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">Approval & Payment</h5>
                                    
                                    <div class="mb-3">
                                        <label for="chief_id" class="form-label">Chief <span class="text-danger">*</span></label>
                                        <select name="chief_id" id="chief_id" class="form-select select2 @error('chief_id') is-invalid @enderror" required>
                                            <option value="">Select Chief...</option>
                                            @foreach($chiefs as $chief)
                                            <option value="{{ $chief->id }}" {{ old('chief_id', $allocation->chief_id) == $chief->id ? 'selected' : '' }}>
                                                {{ $chief->name }} - {{ $chief->jurisdiction }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('chief_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="approval_status" class="form-label">Approval Status <span class="text-danger">*</span></label>
                                        <select name="approval_status" id="approval_status" class="form-select @error('approval_status') is-invalid @enderror" required>
                                            <option value="pending" {{ old('approval_status', $allocation->approval_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ old('approval_status', $allocation->approval_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ old('approval_status', $allocation->approval_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        @error('approval_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                                        <select name="payment_status" id="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                                            <option value="unpaid" {{ old('payment_status', $allocation->payment_status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            <option value="partial" {{ old('payment_status', $allocation->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                                            <option value="paid" {{ old('payment_status', $allocation->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                        @error('payment_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="payment_amount" class="form-label">Payment Amount (GHS)</label>
                                                <input type="number" step="0.01" name="payment_amount" id="payment_amount" 
                                                       value="{{ old('payment_amount', $allocation->payment_amount) }}" 
                                                       class="form-control @error('payment_amount') is-invalid @enderror" 
                                                       placeholder="0.00" min="0">
                                                @error('payment_amount')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="payment_date" class="form-label">Payment Date</label>
                                                <input type="date" name="payment_date" id="payment_date" 
                                                       value="{{ old('payment_date', $allocation->payment_date ? $allocation->payment_date->format('Y-m-d') : '') }}" 
                                                       class="form-control @error('payment_date') is-invalid @enderror">
                                                @error('payment_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="processed_by" class="form-label">Processed By</label>
                                        <select name="processed_by" id="processed_by" class="form-select select2 @error('processed_by') is-invalid @enderror">
                                            <option value="">Select Staff...</option>
                                            @foreach($staff as $staffMember)
                                            <option value="{{ $staffMember->id }}" {{ old('processed_by', $allocation->processed_by) == $staffMember->id ? 'selected' : '' }}>
                                                {{ $staffMember->user->name ?? 'N/A' }} - {{ $staffMember->department }}
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

                        <hr class="my-4">

                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Additional Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="chief_approval_date" class="form-label">Chief Approval Date</label>
                                        <input type="date" name="chief_approval_date" id="chief_approval_date" 
                                               value="{{ old('chief_approval_date', $allocation->chief_approval_date ? $allocation->chief_approval_date->format('Y-m-d') : '') }}" 
                                               class="form-control @error('chief_approval_date') is-invalid @enderror">
                                        @error('chief_approval_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="registrar_approval_date" class="form-label">Registrar Approval Date</label>
                                        <input type="date" name="registrar_approval_date" id="registrar_approval_date" 
                                               value="{{ old('registrar_approval_date', $allocation->registrar_approval_date ? $allocation->registrar_approval_date->format('Y-m-d') : '') }}" 
                                               class="form-control @error('registrar_approval_date') is-invalid @enderror">
                                        @error('registrar_approval_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="Additional notes or comments...">{{ old('notes', $allocation->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" name="is_finalized" id="is_finalized" value="1" 
                                       class="form-check-input @error('is_finalized') is-invalid @enderror"
                                       {{ old('is_finalized', $allocation->is_finalized) ? 'checked' : '' }}>
                                <label for="is_finalized" class="form-check-label">
                                    Mark as Finalized
                                </label>
                                @error('is_finalized')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Once finalized, this allocation cannot be modified.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('allocations.show', $allocation) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Allocation
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Update Allocation
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
        // Initialize Select2 for dropdowns with select2 class
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option...';
            },
            allowClear: true,
            width: '100%'
        });

        // Set maximum date to today for all date fields
        const today = new Date().toISOString().split('T')[0];
        const dateFields = ['allocation_date', 'payment_date', 'chief_approval_date', 'registrar_approval_date'];
        
        dateFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.max = today;
            }
        });

        // Auto-fill chief approval date when status is set to approved
        const approvalStatus = document.getElementById('approval_status');
        const chiefApprovalDate = document.getElementById('chief_approval_date');
        
        if (approvalStatus && chiefApprovalDate) {
            approvalStatus.addEventListener('change', function() {
                if (this.value === 'approved' && !chiefApprovalDate.value) {
                    chiefApprovalDate.value = today;
                }
            });
        }

        // Auto-fill payment date when payment status is set to paid
        const paymentStatus = document.getElementById('payment_status');
        const paymentDate = document.getElementById('payment_date');
        
        if (paymentStatus && paymentDate) {
            paymentStatus.addEventListener('change', function() {
                if (this.value === 'paid' && !paymentDate.value) {
                    paymentDate.value = today;
                }
            });
        }

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const landId = document.getElementById('land_id').value;
            const clientId = document.getElementById('client_id').value;
            const allocationDate = document.getElementById('allocation_date').value;
            const chiefId = document.getElementById('chief_id').value;
            const approvalStatus = document.getElementById('approval_status').value;
            const paymentStatus = document.getElementById('payment_status').value;
            
            if (!landId || !clientId || !allocationDate || !chiefId || !approvalStatus || !paymentStatus) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                return false;
            }
        });

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