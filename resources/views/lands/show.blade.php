@extends('layouts.app')

@section('title', 'Land Plot: ' . $land->plot_number)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Land Plot: {{ $land->plot_number }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="{{ route('lands.edit', $land) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('lands.documents', $land) }}" class="btn btn-info">
                    <i class="fas fa-file"></i> Documents
                </a>
                @if(!$land->is_verified)
                    <form action="{{ route('lands.verify', $land) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Verify
                        </button>
                    </form>
                @endif
                <a href="{{ route('lands.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Land Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Plot Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Plot Number:</th>
                            <td>
                                <strong>{{ $land->plot_number }}</strong>
                                @if($land->is_verified)
                                    <span class="badge bg-success ms-2">Verified</span>
                                @else
                                    <span class="badge bg-warning ms-2">Unverified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Location:</th>
                            <td>{{ $land->location }}</td>
                        </tr>
                        <tr>
                            <th>Chief:</th>
                            <td>{{ $land->chief->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Area:</th>
                            <td>{{ number_format($land->area_acres, 2) }} acres ({{ number_format($land->area_hectares, 2) }} hectares)</td>
                        </tr>
                        <tr>
                            <th>Ownership Status:</th>
                            <td>
                                <span class="badge bg-{{ $land->ownership_status == 'available' ? 'success' : ($land->ownership_status == 'allocated' ? 'primary' : 'warning') }}">
                                    {{ ucfirst($land->ownership_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Land Use:</th>
                            <td>{{ ucfirst($land->land_use ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                            <th>Price:</th>
                            <td>GHS {{ number_format($land->price, 2) }}</td>
                        </tr>
                        @if($land->latitude && $land->longitude)
                        <tr>
                            <th>Coordinates:</th>
                            <td>{{ $land->latitude }}, {{ $land->longitude }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Soil Type:</th>
                            <td>{{ $land->soil_type ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Topography:</th>
                            <td>{{ $land->topography ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Boundary Description:</th>
                            <td>{{ $land->boundary_description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $land->description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Registration Date:</th>
                            <td>{{ $land->registration_date ? $land->registration_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Allocations -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Allocations ({{ $land->allocations->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($land->allocations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($land->allocations as $allocation)
                                        <tr>
                                            <td>{{ $allocation->client->full_name ?? 'N/A' }}</td>
                                            <td>{{ $allocation->allocation_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $allocation->approval_status == 'approved' ? 'success' : ($allocation->approval_status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($allocation->approval_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $allocation->payment_status == 'paid' ? 'success' : ($allocation->payment_status == 'partial' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($allocation->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No allocations for this land plot.</p>
                    @endif
                </div>
            </div>

            <!-- Documents Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Documents ({{ $land->documents->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($land->documents->count() > 0)
                        <div class="list-group">
                            @foreach($land->documents->take(3) as $document)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $document->document_type }}</h6>
                                            <small class="text-muted">{{ $document->file_name }}</small>
                                        </div>
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($land->documents->count() > 3)
                            <div class="text-center mt-2">
                                <a href="{{ route('lands.documents', $land) }}" class="btn btn-sm btn-outline-primary">
                                    View All Documents
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">No documents uploaded.</p>
                        <a href="{{ route('lands.documents', $land) }}" class="btn btn-sm btn-primary">
                            Upload Documents
                        </a>
                    @endif
                </div>
            </div>

            <!-- Map Preview -->
            @if($land->latitude && $land->longitude)
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Location Map</h5>
                </div>
                <div class="card-body">
                    <div id="map-preview" style="height: 200px; width: 100%;"></div>
                    <div class="text-center mt-2">
                        <small class="text-muted">Coordinates: {{ $land->latitude }}, {{ $land->longitude }}</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@if($land->latitude && $land->longitude)
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-preview { 
        height: 200px; 
        border-radius: 0.375rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map-preview').setView([{{ $land->latitude }}, {{ $land->longitude }}], 15);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add marker for the land plot
    L.marker([{{ $land->latitude }}, {{ $land->longitude }}])
        .addTo(map)
        .bindPopup('<strong>{{ $land->plot_number }}</strong><br>{{ $land->location }}')
        .openPopup();
});
</script>
@endpush
@endif