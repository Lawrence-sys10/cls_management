@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Land Plots Map</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('lands.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div id="map" style="height: 70vh; width: 100%;"></div>
        </div>
    </div>

    <!-- Map Legend -->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Map Legend</h6>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <div class="legend-color available me-2" style="width: 20px; height: 20px; background-color: #28a745; border-radius: 50%;"></div>
                            <span>Available Lands</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-color allocated me-2" style="width: 20px; height: 20px; background-color: #007bff; border-radius: 50%;"></div>
                            <span>Allocated Lands</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-color reserved me-2" style="width: 20px; height: 20px; background-color: #ffc107; border-radius: 50%;"></div>
                            <span>Reserved Lands</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-color unknown me-2" style="width: 20px; height: 20px; background-color: #6c757d; border-radius: 50%;"></div>
                            <span>Unknown Status</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { 
        height: 70vh; 
        border-radius: 0.375rem;
    }
    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    .leaflet-popup-content {
        width: 250px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map').setView([0, 0], 2);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Custom icons based on land status
    const getIcon = (status) => {
        const color = {
            'available': '#28a745',
            'allocated': '#007bff', 
            'reserved': '#ffc107',
            'unknown': '#6c757d'
        }[status] || '#6c757d';

        return L.divIcon({
            className: 'custom-marker',
            html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
    };

    // Load land data
    fetch('{{ route("lands.geojson") }}')
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                const bounds = [];

                data.features.forEach(feature => {
                    const { coordinates } = feature.geometry;
                    const properties = feature.properties;
                    
                    const marker = L.marker([coordinates[1], coordinates[0]], {
                        icon: getIcon(properties.status)
                    }).addTo(map);

                    // Add popup
                    marker.bindPopup(properties.popupContent);

                    bounds.push([coordinates[1], coordinates[0]]);
                });

                // Fit map to show all markers
                if (bounds.length > 0) {
                    map.fitBounds(bounds);
                }
            } else {
                // Default view if no coordinates
                map.setView([0, 0], 2);
                
                // Add a message for no data
                L.popup()
                    .setLatLng([0, 0])
                    .setContent('<div class="text-center p-3"><i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i><p>No land plots with coordinates found.</p><p>Add coordinates to lands to see them on the map.</p></div>')
                    .openOn(map);
            }
        })
        .catch(error => {
            console.error('Error loading land data:', error);
            
            L.popup()
                .setLatLng([0, 0])
                .setContent('<div class="text-center p-3"><i class="fas fa-exclamation-circle fa-2x text-danger mb-2"></i><p>Error loading map data.</p><p>Please try again later.</p></div>')
                .openOn(map);
        });
});
</script>
@endpush