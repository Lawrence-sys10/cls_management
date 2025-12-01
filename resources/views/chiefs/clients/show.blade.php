@extends('layouts.app')

@section('title', 'Client Details - ' . $client->full_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-8 col-lg-7">
            <!-- Client Information Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Client Information
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('chief.clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('chief.clients.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row g-4">
                        <!-- Avatar Section -->
                        <div class="col-md-3 text-center">
                            <div class="client-avatar-large mb-3">
                                <div class="avatar-circle-large bg-primary text-white">
                                    {{ substr($client->full_name, 0, 1) }}
                                </div>
                            </div>
                            <h6 class="mb-1 fw-bold">{{ $client->full_name }}</h6>
                            <small class="text-muted d-block">Client ID: {{ $client->id }}</small>
                            @if($client->allocations->count() > 0)
                                <span class="badge bg-success mt-2 small">
                                    <i class="fas fa-handshake me-1"></i>Active
                                </span>
                            @else
                                <span class="badge bg-secondary mt-2 small">
                                    <i class="fas fa-user me-1"></i>No Allocations
                                </span>
                            @endif
                        </div>
                        
                        <!-- Client Details -->
                        <div class="col-md-9">
                            <div class="row g-3">
                                <!-- Personal Info -->
                                <div class="col-sm-6">
                                    <div class="detail-group">
                                        <label class="detail-label">ID Number</label>
                                        <p class="detail-value">{{ $client->id_number }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label class="detail-label">ID Type</label>
                                        <p class="detail-value">{{ $client->id_type_formatted }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label class="detail-label">Date of Birth</label>
                                        <p class="detail-value">
                                            @if($client->date_of_birth)
                                                {{ \Carbon\Carbon::parse($client->date_of_birth)->format('M j, Y') }}
                                                <small class="text-muted d-block">({{ $client->age }} years)</small>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Contact Info -->
                                <div class="col-sm-6">
                                    <div class="detail-group">
                                        <label class="detail-label">Phone</label>
                                        <p class="detail-value">
                                            @if($client->phone)
                                                <a href="tel:{{ $client->phone }}" class="text-decoration-none">
                                                    <i class="fas fa-phone me-1 text-muted"></i>{{ $client->phone }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="detail-group">
                                        <label class="detail-label">Email</label>
                                        <p class="detail-value">
                                            @if($client->email)
                                                <a href="mailto:{{ $client->email }}" class="text-decoration-none">
                                                    <i class="fas fa-envelope me-1 text-muted"></i>{{ $client->email }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="detail-group">
                                        <label class="detail-label">Gender</label>
                                        <p class="detail-value">
                                            @if($client->gender)
                                                {{ ucfirst($client->gender) }}
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Additional Info -->
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="detail-group">
                                                <label class="detail-label">Occupation</label>
                                                <p class="detail-value">
                                                    @if($client->occupation)
                                                        {{ $client->occupation }}
                                                    @else
                                                        <span class="text-muted">Not provided</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="detail-group">
                                                <label class="detail-label">Emergency Contact</label>
                                                <p class="detail-value">
                                                    @if($client->emergency_contact)
                                                        {{ $client->emergency_contact }}
                                                    @else
                                                        <span class="text-muted">Not provided</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Address -->
                                @if($client->address)
                                <div class="col-12">
                                    <div class="detail-group">
                                        <label class="detail-label">Address</label>
                                        <p class="detail-value">{{ $client->address }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Notes -->
                                @if($client->notes)
                                <div class="col-12">
                                    <div class="detail-group">
                                        <label class="detail-label">Notes</label>
                                        <p class="detail-value text-muted">{{ $client->notes }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Registration Info -->
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="detail-group">
                                                <label class="detail-label">Registered On</label>
                                                <p class="detail-value">{{ $client->created_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="detail-group">
                                                <label class="detail-label">Last Updated</label>
                                                <p class="detail-value">{{ $client->updated_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Allocations Summary -->
            @if($client->allocations->count() > 0)
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-handshake me-2"></i>Current Allocations
                        <span class="badge bg-primary ms-2">{{ $client->allocations->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Plot Number</th>
                                    <th>Location</th>
                                    <th>Allocation Date</th>
                                    <th>Duration</th>
                                    <th class="pe-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->allocations as $allocation)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('chief.lands.show', $allocation->land) }}" class="text-decoration-none fw-semibold">
                                            {{ $allocation->land->plot_number }}
                                        </a>
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;" title="{{ $allocation->land->location }}">
                                        {{ $allocation->land->location }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($allocation->allocation_date)->format('M j, Y') }}</td>
                                    <td>{{ $allocation->duration_years }} years</td>
                                    <td class="pe-3">
                                        <span class="badge {{ $allocation->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($allocation->status) }}
                                        </span>
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
                        <a href="{{ route('chief.clients.allocations', $client) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-handshake me-2"></i>View All Allocations
                        </a>
                        <a href="{{ route('chief.allocations.create') }}?client_id={{ $client->id }}" class="btn btn-success btn-sm">
                            <i class="fas fa-map-marked-alt me-2"></i>Allocate New Land
                        </a>
                        <a href="{{ route('chief.clients.documents', $client) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file-alt me-2"></i>Manage Documents
                        </a>
                    </div>
                </div>
            </div>

            <!-- Client Statistics -->
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Client Statistics
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="stats-grid">
                        <div class="stat-item text-center p-2">
                            <div class="stat-value text-primary">{{ $client->allocations->count() }}</div>
                            <div class="stat-label">Total Allocations</div>
                        </div>
                        <div class="stat-item text-center p-2">
                            <div class="stat-value text-success">{{ $client->allocations->where('status', 'active')->count() }}</div>
                            <div class="stat-label">Active</div>
                        </div>
                        <div class="stat-item text-center p-2">
                            <div class="stat-value text-info">
                                @php
                                    $minutes = (int)$client->created_at->diffInMinutes(now());
                                    $hours = (int)$client->created_at->diffInHours(now());
                                    $days = (int)$client->created_at->diffInDays(now());
                                    $months = (int)$client->created_at->diffInMonths(now());
                                    $years = (int)$client->created_at->diffInYears(now());
                                    
                                    if ($minutes < 1) {
                                        echo 'Recently';
                                    } elseif ($minutes < 60) {
                                        echo $minutes . ($minutes == 1 ? ' min' : ' mins');
                                    } elseif ($hours < 24) {
                                        echo $hours . ($hours == 1 ? ' hour' : ' hours');
                                    } elseif ($days < 30) {
                                        echo $days . ($days == 1 ? ' day' : ' days');
                                    } elseif ($months < 12) {
                                        echo $months . ($months == 1 ? ' month' : ' months');
                                    } else {
                                        echo $years . ($years == 1 ? ' year' : ' years');
                                    }
                                @endphp
                            </div>
                            <div class="stat-label">Registered</div>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="timeline-info">
                        <div class="timeline-item d-flex align-items-center mb-2">
                            <i class="fas fa-calendar text-muted me-2 fs-6"></i>
                            <div>
                                <small class="text-muted">Created</small>
                                <div class="fw-semibold">{{ $client->created_at->format('M j, Y') }}</div>
                            </div>
                        </div>
                        <div class="timeline-item d-flex align-items-center">
                            <i class="fas fa-sync text-muted me-2 fs-6"></i>
                            <div>
                                <small class="text-muted">Last Updated</small>
                                <div class="fw-semibold">{{ $client->updated_at->format('M j, Y') }}</div>
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
    
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }
        
        .btn-group .btn {
            font-size: 0.8rem;
            padding: 0.375rem 0.75rem;
        }
    }
</style>
@endpush