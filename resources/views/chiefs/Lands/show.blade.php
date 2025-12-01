@extends('layouts.app')

@section('title', 'Land Details - ' . $land->plot_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marked-alt me-2"></i>Land Details
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('chief.lands.edit', $land) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('chief.lands.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Plot Number:</th>
                                    <td>{{ $land->plot_number }}</td>
                                </tr>
                                <tr>
                                    <th>Location:</th>
                                    <td>{{ $land->location }}</td>
                                </tr>
                                <tr>
                                    <th>Landmark:</th>
                                    <td>{{ $land->landmark ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Area:</th>
                                    <td>{{ number_format($land->area_acres, 2) }} acres ({{ number_format($land->area_hectares, 2) }} ha)</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Land Use:</th>
                                    <td class="text-capitalize">{{ str_replace('_', ' ', $land->land_use) }}</td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td>{{ $land->price ? '₵' . number_format($land->price) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @php
                                            $statusClass = match($land->ownership_status) {
                                                'vacant' => 'badge-success',
                                                'allocated' => 'badge-primary',
                                                'under_dispute' => 'badge-warning',
                                                default => 'badge-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $land->ownership_status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Coordinates:</th>
                                    <td>{{ $land->coordinates ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($land->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Description</h6>
                            <p class="text-muted">{{ $land->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($land->ownership_status === 'vacant')
                        <a href="{{ route('chief.allocations.create') }}?land_id={{ $land->id }}" 
                           class="btn btn-success">
                            <i class="fas fa-handshake me-2"></i>Allocate Land
                        </a>
                        @endif
                        
                        <a href="{{ route('chief.lands.documents', $land) }}" class="btn btn-info">
                            <i class="fas fa-file-alt me-2"></i>View Documents
                        </a>

                        @if($land->ownership_status === 'under_dispute')
                        <a href="{{ route('chief.disputes.index') }}?land_id={{ $land->id }}" class="btn btn-warning">
                            <i class="fas fa-gavel me-2"></i>View Dispute
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Land Information Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Land Information</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block mb-2">
                        <i class="fas fa-calendar me-1"></i>
                        Created: {{ $land->created_at->format('M j, Y') }}
                    </small>
                    <small class="text-muted d-block">
                        <i class="fas fa-sync me-1"></i>
                        Updated: {{ $land->updated_at->format('M j, Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection