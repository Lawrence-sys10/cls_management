@extends('layouts.app')

@section('title', 'Chiefs Map')
@section('subtitle', 'Geographical distribution of traditional chiefs')

@section('actions')
    <a href="{{ route('chiefs.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-list me-2"></i>List View
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Chiefs Geographical Distribution</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Filters</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select id="statusFilter" class="form-select">
                                    <option value="all">All Chiefs</option>
                                    <option value="active">Active Only</option>
                                    <option value="inactive">Inactive Only</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="regionFilter" class="form-label">Region</label>
                                <select id="regionFilter" class="form-select">
                                    <option value="all">All Regions</option>
                                    @foreach($chiefs->pluck('region')->unique()->filter() as $region)
                                        <option value="{{ $region }}">{{ $region }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button id="resetFilters" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-refresh me-2"></i>Reset Filters
                            </button>
                        </div>
                    </div>

                    <!-- Chiefs List -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Chiefs List</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush" id="chiefsList">
                                @foreach($chiefs as $chief)
                                <div class="list-group-item chief-item" data-chief-id="{{ $chief->id }}" data-status="{{ $chief->is_active ? 'active' : 'inactive' }}" data-region="{{ $chief->region ?? '' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $chief->name }}</h6>
                                            <small class="text-muted">{{ $chief->jurisdiction }}</small>
                                            <br>
                                            <small>
                                                <span class="badge bg-{{ $chief->is_active ? 'success' : 'secondary' }}">
                                                    {{ $chief->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                @if($chief->region)
                                                <span class="badge bg-info">{{ $chief->region }}</span>
                                                @endif
                                            </small>
                                        </div>
                                        <span class="text-muted">{{ $chief->allocations_count }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Map Container -->
                    <div class="card">
                        <div class="card-body p-0">
                            <div id="map" style="height: 600px; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Map Legend -->
                    <div class="mt-3">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="legend-marker bg-success me-2" style="width: 16px; height: 16px; border-radius: 50%;"></div>
                                <small>Active Chief</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="legend-marker bg-secondary me-2" style="width: 16px; height: 16px; border-radius: 50%;"></div>
                                <small>Inactive Chief</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="legend-marker bg-warning me-2" style="width: 16px; height: 16px; border-radius: 50%;"></div>
                                <small>Multiple Allocations</small>
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
    .chief-item {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .chief-item:hover {
        background-color: #f8f9fa;
    }
    
    .chief-item.active {
        background-color: #e7f1ff;
        border-left: 3px solid #0d6efd;
    }
    
    .legend-marker {
        border: 2px solid white;
        box-shadow: 0 0 2px rgba(0,0,0,0.3);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map (this would typically use Leaflet or Google Maps)
        initializeMap();
        
        // Filter functionality
        const statusFilter = document.getElementById('statusFilter');
        const regionFilter = document.getElementById('regionFilter');
        const resetFilters = document.getElementById('resetFilters');
        const chiefItems = document.querySelectorAll('.chief-item');
        
        function filterChiefs() {
            const statusValue = statusFilter.value;
            const regionValue = regionFilter.value;
            
            chiefItems.forEach(item => {
                const status = item.dataset.status;
                const region = item.dataset.region;
                
                const statusMatch = statusValue === 'all' || status === statusValue;
                const regionMatch = regionValue === 'all' || region === regionValue;
                
                if (statusMatch && regionMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        statusFilter.addEventListener('change', filterChiefs);
        regionFilter.addEventListener('change', filterChiefs);
        
        resetFilters.addEventListener('click', function() {
            statusFilter.value = 'all';
            regionFilter.value = 'all';
            filterChiefs();
        });
        
        // Chief item click handler
        chiefItems.forEach(item => {
            item.addEventListener('click', function() {
                chiefItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                
                const chiefId = this.dataset.chiefId;
                highlightChiefOnMap(chiefId);
            });
        });
        
        function initializeMap() {
            // This is a placeholder for map initialization
            // In a real implementation, you would use Leaflet, Google Maps, or similar
            const mapElement = document.getElementById('map');
            mapElement.innerHTML = `
                <div style="height: 100%; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 0.375rem;">
                    <div class="text-center text-muted">
                        <i class="fas fa-map fa-3x mb-3"></i>
                        <h5>Map View</h5>
                        <p>Chiefs geographical distribution map would be displayed here.</p>
                        <small>Integration with mapping service required.</small>
                    </div>
                </div>
            `;
        }
        
        function highlightChiefOnMap(chiefId) {
            // This would highlight the chief's location on the map
            console.log('Highlighting chief:', chiefId);
        }
    });
</script>
@endpush