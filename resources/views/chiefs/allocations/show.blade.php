@extends('layouts.app')

@section('title', 'Allocation Details - ' . $allocation->client->full_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-8 col-lg-7">
            <!-- Allocation Information Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-handshake me-2"></i>Allocation Details
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('chief.allocations.edit', $allocation) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('chief.allocations.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row g-4">
                        <!-- Client Section -->
                        <div class="col-md-4 text-center">
                            <div class="client-avatar-large mb-3">
                                <div class="avatar-circle-large bg-primary text-white">
                                    {{ substr($allocation->client->full_name, 0, 1) }}
                                </div>
                            </div>
                            <h6 class="mb-1 fw-bold">{{ $allocation->client->full_name }}</h6>
                            <small class="text-muted d-block">{{ $allocation->client->id_number }}</small>
                            <span class="badge 
                                @if($allocation->status == 'active' && !$isExpired) bg-success
                                @elseif($isExpired) bg-danger
                                @elseif($allocation->status == 'terminated') bg-secondary
                                @else bg-warning
                                @endif mt-2 small">
                                @if($isExpired)
                                    Expired
                                @else
                                    {{ ucfirst($allocation->status) }}
                                @endif
                            </span>
                        </div>
                        
                        <!-- Allocation Details -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="detail-group">
                                        <label class="detail-label">Land Plot</label>
                                        <p class="detail-value">
                                            <a href="{{ route('chief.lands.show', $allocation->land) }}" class="text-decoration-none">
                                                Plot {{ $allocation->land->plot_number }}
                                            </a>
                                        </p>
                                    </div>
                                    <div class="detail-group">
                                        <label class="detail-label">Location</label>
                                        <p class="detail-value">{{ $allocation->land->location }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label class="detail-label">Land Size</label>
                                        <p class="detail-value">{{ $allocation->land->size }} acres</p>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="detail-group">
                                        <label class="detail-label">Allocation Date</label>
                                        <p class="detail-value">{{ $allocation->allocation_date->format('M j, Y') }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label class="detail-label">Duration</label>
                                        <p class="detail-value">{{ $allocation->duration_years }} years</p>
                                    </div>
                                    <div class="detail-group">
                                        <label class="detail-label">Expiry Date</label>
                                        <p class="detail-value">
                                            {{ $expiryDate->format('M j, Y') }}
                                            @if($isExpired)
                                                <br><small class="text-danger">Expired {{ $expiryDate->diffForHumans() }}</small>
                                            @elseif($daysUntilExpiry <= 180)
                                                <br><small class="text-warning">Expires in {{ $daysUntilExpiry }} days</small>
                                            @else
                                                <br><small class="text-muted">{{ $expiryDate->diffForHumans() }}</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Purpose -->
                                <div class="col-12">
                                    <div class="detail-group">
                                        <label class="detail-label">Purpose</label>
                                        <p class="detail-value">{{ $allocation->purpose }}</p>
                                    </div>
                                </div>
                                
                                <!-- Rent Information -->
                                @if($allocation->rent_amount)
                                <div class="col-12">
                                    <div class="detail-group">
                                        <label class="detail-label">Rent Information</label>
                                        <p class="detail-value">
                                            GHS {{ number_format($allocation->rent_amount, 2) }} 
                                            @if($allocation->payment_frequency)
                                                per {{ $allocation->payment_frequency }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Terms -->
                                @if($allocation->terms)
                                <div class="col-12">
                                    <div class="detail-group">
                                        <label class="detail-label">Terms & Conditions</label>
                                        <p class="detail-value text-muted">{{ $allocation->terms }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Timeline -->
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="detail-group">
                                                <label class="detail-label">Created</label>
                                                <p class="detail-value">{{ $allocation->created_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="detail-group">
                                                <label class="detail-label">Last Updated</label>
                                                <p class="detail-value">{{ $allocation->updated_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            @if($allocation->documents->count() > 0)
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>Related Documents
                        <span class="badge bg-primary ms-2">{{ $allocation->documents->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Document Name</th>
                                    <th>Type</th>
                                    <th>Uploaded</th>
                                    <th class="pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allocation->documents as $document)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold">{{ $document->name }}</div>
                                        <small class="text-muted">{{ $document->description }}</small>
                                    </td>
                                    <td>{{ $document->type }}</td>
                                    <td>{{ $document->created_at->format('M j, Y') }}</td>
                                    <td class="pe-3">
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('chief.allocations.generateAllocationLetter', $allocation) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-file-alt me-2"></i>Generate Allocation Letter
                        </a>
                        <a href="{{ route('chief.allocations.generateCertificate', $allocation) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-certificate me-2"></i>Generate Certificate
                        </a>
                        <a href="{{ route('chief.clients.show', $allocation->client) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user me-2"></i>View Client Profile
                        </a>
                        <a href="{{ route('chief.lands.show', $allocation->land) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-map me-2"></i>View Land Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Allocation Statistics -->
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Allocation Statistics
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="stats-grid">
                        <div class="stat-item text-center p-2">
                            <div class="stat-value text-primary">{{ $allocation->duration_years }}</div>
                            <div class="stat-label">Duration (Years)</div>
                        </div>
                        <div class="stat-item text-center p-2">
                            <div class="stat-value text-info">{{ $daysUntilExpiry }}</div>
                            <div class="stat-label">Days {{ $isExpired ? 'Expired' : 'Remaining' }}</div>
                        </div>
                        <div class="stat-item text-center p-2">
                            <div class="stat-value text-success">{{ $allocation->documents->count() }}</div>
                            <div class="stat-label">Documents</div>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="timeline-info">
                        <div class="timeline-item d-flex align-items-center mb-2">
                            <i class="fas fa-calendar text-muted me-2 fs-6"></i>
                            <div>
                                <small class="text-muted">Allocated</small>
                                <div class="fw-semibold">{{ $allocation->allocation_date->format('M j, Y') }}</div>
                            </div>
                        </div>
                        <div class="timeline-item d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-muted me-2 fs-6"></i>
                            <div>
                                <small class="text-muted">Expires</small>
                                <div class="fw-semibold">{{ $expiryDate->format('M j, Y') }}</div>
                            </div>
                        </div>
                        <div class="timeline-item d-flex align-items-center">
                            <i class="fas fa-sync text-muted me-2 fs-6"></i>
                            <div>
                                <small class="text-muted">Last Updated</small>
                                <div class="fw-semibold">{{ $allocation->updated_at->format('M j, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .client-avatar-large .avatar-circle-large {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.75rem;
        margin: 0 auto;
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
        word-break: break-word;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
    }
    
    .stat-item {
        background: #f8f9fa;
        border-radius: 0.5rem;
        border: 1px solid #e9ecef;
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
    }
    
    .timeline-info .timeline-item {
        padding: 0.25rem 0;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .table td {
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    @media (max-width: 768px) {
        .client-avatar-large .avatar-circle-large {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .stat-item {
            padding: 1rem 0.5rem;
        }
        
        .detail-group {
            margin-bottom: 0.75rem;
        }
    }
</style>
@endpush