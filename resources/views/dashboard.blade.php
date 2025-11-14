@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-4">
    <!-- Stats Cards -->
    <x-stat-card 
        title="Total Lands" 
        value="{{ $stats['total_lands'] }}" 
        icon="map-marked-alt" 
        color="blue" 
        trend="+5%"
    />
    <x-stat-card 
        title="Total Clients" 
        value="{{ $stats['total_clients'] }}" 
        icon="users" 
        color="green" 
        trend="+12%"
    />
    <x-stat-card 
        title="Total Chiefs" 
        value="{{ $stats['total_chiefs'] }}" 
        icon="crown" 
        color="purple" 
        trend="+2%"
    />
    <x-stat-card 
        title="Pending Approvals" 
        value="{{ $stats['pending_approvals'] }}" 
        icon="handshake" 
        color="orange" 
        trend="+3"
    />
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- Recent Allocations -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Allocations</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="flow-root">
                <ul class="-mb-8">
                    @foreach($recent_allocations as $allocation)
                    <li class="relative pb-8">
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                    <i class="fas fa-handshake text-white text-sm"></i>
                                </span>
                            </div>
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        Allocated <span class="font-medium text-gray-900">{{ $allocation->land->plot_number }}</span> to 
                                        <span class="font-medium text-gray-900">{{ $allocation->client->full_name }}</span>
                                    </p>
                                </div>
                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                    <time>{{ $allocation->created_at->diffForHumans() }}</time>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Land Distribution -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Land Distribution</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="space-y-4">
                @foreach($land_distribution as $distribution)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $distribution->ownership_status) }}</span>
                    <span class="text-sm text-gray-500">{{ $distribution->total }} lands</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-{{ $distribution->ownership_status == 'vacant' ? 'green' : ($distribution->ownership_status == 'allocated' ? 'blue' : 'orange') }}-500 h-2.5 rounded-full" 
                         style="width: {{ ($distribution->total / $stats['total_lands']) * 100 }}%"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Land Map Overview</h3>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <div id="map" style="height: 400px; width: 100%;" class="rounded-lg"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize map
    var map = L.map('map').setView([7.585, -1.955], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Load land data
    fetch('{{ route("lands.api.geojson") }}')
        .then(response => response.json())
        .then(data => {
            L.geoJSON(data, {
                pointToLayer: function (feature, latlng) {
                    return L.marker(latlng).bindPopup(feature.properties.popupContent);
                }
            }).addTo(map);
        });
</script>
@endpush
