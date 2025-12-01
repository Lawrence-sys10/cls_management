@extends('layouts.app')

@section('title', 'Resolve Dispute - ' . $dispute->case_number)
@section('subtitle', 'Record dispute resolution and outcome')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Resolve Dispute - {{ $dispute->case_number }}</h5>
                </div>
                <div class="card-body">
                    <!-- Dispute Summary -->
                    <div class="card border-light mb-4">
                        <div class="card-body">
                            <h6>Dispute Summary</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="30%">Case Number:</th>
                                    <td>{{ $dispute->case_number }}</td>
                                </tr>
                                <tr>
                                    <th>Dispute Type:</th>
                                    <td class="text-capitalize">{{ str_replace('_', ' ', $dispute->dispute_type) }}</td>
                                </tr>
                                <tr>
                                    <th>Land Plot:</th>
                                    <td>Plot {{ $dispute->land->plot_number }} - {{ $dispute->land->location }}</td>
                                </tr>
                                <tr>
                                    <th>Complainant:</th>
                                    <td>{{ $dispute->complainant->full_name }}</td>
                                </tr>
                                @if($dispute->respondent)
                                <tr>
                                    <th>Respondent:</th>
                                    <td>{{ $dispute->respondent->full_name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <form action="{{ route('chief.disputes.resolve', $dispute) }}" method="POST">
                        @csrf
                        
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Resolution Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="outcome" class="form-label">Resolution Outcome <span class="text-danger">*</span></label>
                                    <select class="form-control @error('outcome') is-invalid @enderror" 
                                            id="outcome" name="outcome" required>
                                        <option value="">Select resolution outcome</option>
                                        <option value="resolved_in_favor_complainant" {{ old('outcome') == 'resolved_in_favor_complainant' ? 'selected' : '' }}>
                                            Resolved in Favor of Complainant
                                        </option>
                                        <option value="resolved_in_favor_respondent" {{ old('outcome') == 'resolved_in_favor_respondent' ? 'selected' : '' }}>
                                            Resolved in Favor of Respondent
                                        </option>
                                        <option value="compromise" {{ old('outcome') == 'compromise' ? 'selected' : '' }}>
                                            Compromise/Agreement Reached
                                        </option>
                                        <option value="withdrawn" {{ old('outcome') == 'withdrawn' ? 'selected' : '' }}>
                                            Case Withdrawn
                                        </option>
                                    </select>
                                    @error('outcome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="resolution_date" class="form-label">Resolution Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('resolution_date') is-invalid @enderror" 
                                           id="resolution_date" name="resolution_date" value="{{ old('resolution_date', date('Y-m-d')) }}" 
                                           required>
                                    @error('resolution_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="resolution" class="form-label">Resolution Details <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('resolution') is-invalid @enderror" 
                                              id="resolution" name="resolution" rows="6" 
                                              placeholder="Provide detailed description of the resolution, including terms, conditions, and any agreements reached..."
                                              required>{{ old('resolution') }}</textarea>
                                    @error('resolution')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="Any additional notes or observations...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Impact Warning -->
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notice</h6>
                            <p class="mb-0">
                                Resolving this dispute will update the land status and close the case. 
                                This action cannot be easily reversed. Please ensure all resolution details are accurate.
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('chief.disputes.show', $dispute) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dispute
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Resolve Dispute
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
        // Add confirmation for resolution
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to resolve this dispute? This action will update the land status and cannot be easily undone.')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush