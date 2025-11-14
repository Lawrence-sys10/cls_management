# Step 5: Generate Laravel Views and Blade Templates for CLS Management System
# Save this as generate-views.ps1 and run from project root

# Create main views directories
$viewsPath = "resources/views"
$layoutsPath = "$viewsPath/layouts"
$componentsPath = "$viewsPath/components"
$partialsPath = "$viewsPath/partials"
$landsPath = "$viewsPath/lands"
$clientsPath = "$viewsPath/clients"
$allocationsPath = "$viewsPath/allocations"
$chiefsPath = "$viewsPath/chiefs"
$reportsPath = "$viewsPath/reports"
$adminPath = "$viewsPath/admin"
$authPath = "$viewsPath/auth"

# Create directories
@($layoutsPath, $componentsPath, $partialsPath, $landsPath, $clientsPath, $allocationsPath, $chiefsPath, $reportsPath, "$adminPath/users", $authPath) | ForEach-Object {
    if (!(Test-Path $_)) {
        New-Item -ItemType Directory -Path $_ -Force
    }
}

# 1. Main Layout with modern UI
@'
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CLS Management System') - Techiman Customary Lands</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    
    @stack('styles')
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-green-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-8" src="/images/logo.png" alt="CLS Logo">
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </x-nav-link>
                                <x-nav-link href="{{ route('lands.index') }}" :active="request()->routeIs('lands.*')">
                                    <i class="fas fa-map-marked-alt mr-2"></i>Lands
                                </x-nav-link>
                                <x-nav-link href="{{ route('clients.index') }}" :active="request()->routeIs('clients.*')">
                                    <i class="fas fa-users mr-2"></i>Clients
                                </x-nav-link>
                                <x-nav-link href="{{ route('allocations.index') }}" :active="request()->routeIs('allocations.*')">
                                    <i class="fas fa-handshake mr-2"></i>Allocations
                                </x-nav-link>
                                <x-nav-link href="{{ route('chiefs.index') }}" :active="request()->routeIs('chiefs.*')">
                                    <i class="fas fa-crown mr-2"></i>Chiefs
                                </x-nav-link>
                                @can('admin')
                                <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.*')">
                                    <i class="fas fa-cog mr-2"></i>Admin
                                </x-nav-link>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <!-- User dropdown -->
                            <x-user-dropdown />
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <!-- Page Heading -->
            @if(isset($header))
            <header class="bg-white shadow">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                            {{ $header }}
                        </h1>
                        @yield('actions')
                    </div>
                </div>
            </header>
            @endif

            <!-- Page Content -->
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <!-- Notifications -->
                @if(session('success'))
                <x-alert type="success" message="{{ session('success') }}" />
                @endif
                @if(session('error'))
                <x-alert type="error" message="{{ session('error') }}" />
                @endif
                @if($errors->any())
                <x-alert type="error" message="Please check the form for errors." />
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    @stack('scripts')
</body>
</html>
'@ | Out-File -FilePath "$layoutsPath/app.blade.php" -Encoding UTF8

# 2. Dashboard View
@'
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
'@ | Out-File -FilePath "$viewsPath/dashboard.blade.php" -Encoding UTF8

# 3. Lands Index View
@'
@extends('layouts.app')

@section('title', 'Land Management')
@section('header', 'Land Management')

@section('actions')
<div class="flex space-x-2">
    <a href="{{ route('lands.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-file-export mr-2"></i>Export
    </a>
    <a href="{{ route('lands.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-plus mr-2"></i>Add Land
    </a>
</div>
@endsection

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <!-- Search and Filters -->
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="Plot number or location...">
                </div>
                <div>
                    <label for="chief_id" class="block text-sm font-medium text-gray-700">Chief</label>
                    <select name="chief_id" id="chief_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">All Chiefs</option>
                        @foreach($chiefs as $chief)
                        <option value="{{ $chief->id }}" {{ request('chief_id') == $chief->id ? 'selected' : '' }}>
                            {{ $chief->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="vacant" {{ request('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                        <option value="allocated" {{ request('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                        <option value="under_dispute" {{ request('status') == 'under_dispute' ? 'selected' : '' }}>Under Dispute</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Lands Table -->
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200" id="landsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plot Number</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area (Acres)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chief</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($lands as $land)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $land->plot_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $land->location }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($land->area_acres, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $land->chief->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $land->ownership_status == 'vacant' ? 'bg-green-100 text-green-800' : 
                                   ($land->ownership_status == 'allocated' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $land->ownership_status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('lands.show', $land) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('lands.edit', $land) }}" class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('lands.destroy', $land) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $lands->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#landsTable').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: []
        });
    });
</script>
@endpush
'@ | Out-File -FilePath "$landsPath/index.blade.php" -Encoding UTF8

# 4. Land Create/Edit Form
@'
@extends('layouts.app')

@section('title', $land->exists ? 'Edit Land' : 'Add New Land')
@section('header', $land->exists ? 'Edit Land: ' . $land->plot_number : 'Add New Land')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ $land->exists ? route('lands.update', $land) : route('lands.store') }}">
                @csrf
                @if($land->exists)
                @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Plot Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Plot Information</h3>
                        
                        <div>
                            <label for="plot_number" class="block text-sm font-medium text-gray-700">Plot Number *</label>
                            <input type="text" name="plot_number" id="plot_number" value="{{ old('plot_number', $land->plot_number) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('plot_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $land->location) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="area_acres" class="block text-sm font-medium text-gray-700">Area (Acres) *</label>
                                <input type="number" step="0.01" name="area_acres" id="area_acres" value="{{ old('area_acres', $land->area_acres) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('area_acres')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="area_hectares" class="block text-sm font-medium text-gray-700">Area (Hectares) *</label>
                                <input type="number" step="0.01" name="area_hectares" id="area_hectares" value="{{ old('area_hectares', $land->area_hectares) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('area_hectares')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="chief_id" class="block text-sm font-medium text-gray-700">Chief *</label>
                            <select name="chief_id" id="chief_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select Chief</option>
                                @foreach($chiefs as $chief)
                                <option value="{{ $chief->id }}" {{ old('chief_id', $land->chief_id) == $chief->id ? 'selected' : '' }}>
                                    {{ $chief->name }} - {{ $chief->jurisdiction }}
                                </option>
                                @endforeach
                            </select>
                            @error('chief_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Land Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Land Details</h3>
                        
                        <div>
                            <label for="ownership_status" class="block text-sm font-medium text-gray-700">Ownership Status *</label>
                            <select name="ownership_status" id="ownership_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="vacant" {{ old('ownership_status', $land->ownership_status) == 'vacant' ? 'selected' : '' }}>Vacant</option>
                                <option value="allocated" {{ old('ownership_status', $land->ownership_status) == 'allocated' ? 'selected' : '' }}>Allocated</option>
                                <option value="under_dispute" {{ old('ownership_status', $land->ownership_status) == 'under_dispute' ? 'selected' : '' }}>Under Dispute</option>
                                <option value="reserved" {{ old('ownership_status', $land->ownership_status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            </select>
                            @error('ownership_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="land_use" class="block text-sm font-medium text-gray-700">Land Use *</label>
                            <select name="land_use" id="land_use" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="residential" {{ old('land_use', $land->land_use) == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('land_use', $land->land_use) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="agricultural" {{ old('land_use', $land->land_use) == 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                                <option value="industrial" {{ old('land_use', $land->land_use) == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                <option value="mixed" {{ old('land_use', $land->land_use) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                            @error('land_use')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="number" step="0.00000001" name="latitude" id="latitude" value="{{ old('latitude', $land->latitude) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="number" step="0.00000001" name="longitude" id="longitude" value="{{ old('longitude', $land->longitude) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (GHS)</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $land->price) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="boundary_description" class="block text-sm font-medium text-gray-700">Boundary Description</label>
                            <textarea name="boundary_description" id="boundary_description" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('boundary_description', $land->boundary_description) }}</textarea>
                        </div>
                        <div>
                            <label for="registration_date" class="block text-sm font-medium text-gray-700">Registration Date *</label>
                            <input type="date" name="registration_date" id="registration_date" value="{{ old('registration_date', $land->registration_date?->format('Y-m-d')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('registration_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('lands.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ $land->exists ? 'Update Land' : 'Create Land' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
'@ | Out-File -FilePath "$landsPath/create.blade.php" -Encoding UTF8

# 5. Components (Alert, Nav Link, Stat Card)
@'
@props(['type' => 'info', 'message'])

@php
    $colors = [
        'success' => 'bg-green-50 border-green-400 text-green-700',
        'error' => 'bg-red-50 border-red-400 text-red-700',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-700',
        'info' => 'bg-blue-50 border-blue-400 text-blue-700',
    ];
    
    $icons = [
        'success' => 'fas fa-check-circle',
        'error' => 'fas fa-exclamation-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'info' => 'fas fa-info-circle',
    ];
@endphp

<div class="rounded-md p-4 mb-4 border {{ $colors[$type] }}">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="{{ $icons[$type] }}"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium">{{ $message }}</p>
        </div>
    </div>
</div>
'@ | Out-File -FilePath "$componentsPath/alert.blade.php" -Encoding UTF8

@'
@props(['active' => false, 'href'])

@php
    $classes = $active
        ? 'bg-green-900 text-white px-3 py-2 rounded-md text-sm font-medium'
        : 'text-green-300 hover:bg-green-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
'@ | Out-File -FilePath "$componentsPath/nav-link.blade.php" -Encoding UTF8

@'
@props(['title', 'value', 'icon', 'color' => 'blue', 'trend' => null])

@php
    $colors = [
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
        'red' => 'bg-red-500',
        'purple' => 'bg-purple-500',
        'orange' => 'bg-orange-500',
    ];
@endphp

<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 {{ $colors[$color] }} rounded-md flex items-center justify-center">
                    <i class="fas fa-{{ $icon }} text-white text-sm"></i>
                </div>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">{{ $title }}</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ $value }}</dd>
                    @if($trend)
                    <dd class="text-xs text-green-600">{{ $trend }} from last month</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
'@ | Out-File -FilePath "$componentsPath/stat-card.blade.php" -Encoding UTF8

# 6. User Dropdown Component
@'
<div class="relative ml-3" x-data="{ open: false }">
    <div>
        <button @click="open = !open" type="button" class="flex max-w-xs items-center rounded-full bg-green-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-green-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
            <span class="sr-only">Open user menu</span>
            <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center">
                <span class="text-white text-sm font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            </div>
        </button>
    </div>

    <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-0">
            <i class="fas fa-user mr-2"></i>Your Profile
        </a>
        <form method="POST" action="{{ route('logout') }}" class="block">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                <i class="fas fa-sign-out-alt mr-2"></i>Sign out
            </button>
        </form>
    </div>
</div>
'@ | Out-File -FilePath "$componentsPath/user-dropdown.blade.php" -Encoding UTF8

Write-Host "✅ Core views and components generated successfully!" -ForegroundColor Green
Write-Host "📁 Files created:" -ForegroundColor Cyan
Write-Host "   - resources/views/layouts/app.blade.php" -ForegroundColor White
Write-Host "   - resources/views/dashboard.blade.php" -ForegroundColor White
Write-Host "   - resources/views/lands/index.blade.php" -ForegroundColor White
Write-Host "   - resources/views/lands/create.blade.php" -ForegroundColor White
Write-Host "   - resources/views/components/alert.blade.php" -ForegroundColor White
Write-Host "   - resources/views/components/nav-link.blade.php" -ForegroundColor White
Write-Host "   - resources/views/components/stat-card.blade.php" -ForegroundColor White
Write-Host "   - resources/views/components/user-dropdown.blade.php" -ForegroundColor White
Write-Host "🚀 Next step: We'll create the remaining views and GIS integration" -ForegroundColor Yellow