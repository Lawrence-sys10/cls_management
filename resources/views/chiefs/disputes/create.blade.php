@extends('layouts.app')

@section('title', 'Register New Dispute')
@section('subtitle', 'Record a new land allocation dispute')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Register New Dispute</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('chief.disputes.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Case Information -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Case Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="case_number" class="form-label">Case Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('case_number') is-invalid @enderror" 
                                                   id="case_number" name="case_number" value="{{ old('case_number') }}" 
                                                   placeholder="Enter unique case number" required>
                                            @error('case_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="dispute_type" class="form-label">Dispute Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('dispute_type') is-invalid @enderror" 
                                                    id="dispute_type" name="dispute_type" required>
                                                <option value="">Select dispute type</option>
                                                <option value="boundary" {{ old('dispute_type') == 'boundary' ? 'selected' : '' }}>Boundary Dispute</option>
                                                <option value="ownership" {{ old('dispute_type') == 'ownership' ? 'selected' : '' }}>Ownership Dispute</option>
                                                <option value="inheritance" {{ old('inheritance') == 'inheritance' ? 'selected' : '' }}>Inheritance Dispute</option>
                                                <option value="other" {{ old('dispute_type') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('dispute_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="filing_date" class="form-label">Filing Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('filing_date') is-invalid @enderror" 
                                                   id="filing_date" name="filing_date" value="{{ old('filing_date', date('Y-m-d')) }}" 
                                                   required>
                                            @error('filing_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="severity" class="form-label">Priority Level <span class="text-danger">*</span></label>
                                            <select class="form-control @error('severity') is-invalid @enderror" 
                                                    id="severity" name="severity" required>
                                                <option value="">Select priority level</option>
                                                <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Low</option>
                                                <option value="medium" {{ old('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                                                <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>High</option>
                                                <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                                            </select>
                                            @error('severity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Land & Parties Information -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Land & Parties Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="land_id" class="form-label">Land Plot <span class="text-danger">*</span></label>
                                            <select class="form-control @error('land_id') is-invalid @enderror" 
                                                    id="land_id" name="land_id" required>
                                                <option value="">Select land plot</option>
                                                @foreach($lands as $land)
                                                    <option value="{{ $land->id }}" {{ old('land_id') == $land->id ? 'selected' : '' }}>
                                                        Plot {{ $land->plot_number }} - {{ $land->location }} ({{ $land->size }} acres)
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('land_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="complainant_id" class="form-label">Complainant <span class="text-danger">*</span></label>
                                            <select class="form-control @error('complainant_id') is-invalid @enderror" 
                                                    id="complainant_id" name="complainant_id" required>
                                                <option value="">Select complainant</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" {{ old('complainant_id') == $client->id ? 'selected' : '' }}>
                                                        {{ $client->full_name }} - {{ $client->id_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('complainant_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="respondent_id" class="form-label">Respondent (Optional)</label>
                                            <select class="form-control @error('respondent_id') is-invalid @enderror" 
                                                    id="respondent_id" name="respondent_id">
                                                <option value="">Select respondent (if any)</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" {{ old('respondent_id') == $client->id ? 'selected' : '' }}>
                                                        {{ $client->full_name }} - {{ $client->id_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('respondent_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Dispute Description</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Detailed Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="5" 
                                              placeholder="Provide detailed description of the dispute, including background, issues, and any supporting information..."
                                              required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('chief.disputes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Register Dispute
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
        // Auto-generate case number if empty
        const caseNumberField = document.getElementById('case_number');
        if (!caseNumberField.value) {
            const timestamp = new Date().getTime();
            caseNumberField.value = 'DSP-' + timestamp;
        }

        // Enhance form functionality
        const landSelect = document.getElementById('land_id');
        const complainantSelect = document.getElementById('complainant_id');
        const respondentSelect = document.getElementById('respondent_id');

        // Prevent selecting same client as complainant and respondent
        complainantSelect.addEventListener('change', function() {
            const complainantId = this.value;
            if (complainantId && respondentSelect.value === complainantId) {
                respondentSelect.value = '';
            }
        });

        respondentSelect.addEventListener('change', function() {
            const respondentId = this.value;
            if (respondentId && complainantSelect.value === respondentId) {
                alert('Complainant and Respondent cannot be the same person.');
                this.value = '';
            }
        });
    });
</script>
@endpush