# Step 6: Generate Remaining Views with GIS Integration and Advanced Features
# Save this as generate-views-advanced.ps1 and run from project root

# Define paths
$viewsPath = "resources/views"
$landsPath = "$viewsPath/lands"
$clientsPath = "$viewsPath/clients"
$allocationsPath = "$viewsPath/allocations"
$chiefsPath = "$viewsPath/chiefs"

# Create directories if they don't exist
@($landsPath, $clientsPath, $allocationsPath, $chiefsPath) | ForEach-Object {
    if (!(Test-Path $_)) {
        New-Item -ItemType Directory -Path $_ -Force
    }
}

# 1. Land Show View with GIS Map
@'
@extends(''layouts.app'')

@section(''title'', ''Land Details - '' . $land->plot_number)
@section(''header'', ''Land Details: '' . $land->plot_number)

@section(''actions'')
<div class="flex space-x-2">
    <a href="{{ route(''lands.edit'', $land) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-edit mr-2"></i>Edit
    </a>
    <a href="{{ route(''allocations.create'') }}?land_id={{ $land->id }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-handshake mr-2"></i>Allocate
    </a>
</div>
@endsection

@section(''content'')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <!-- Land Details -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Plot Information</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Plot Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $land->plot_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $land->location }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Area (Acres)</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($land->area_acres, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Area (Hectares)</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($land->area_hectares, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Chief</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $land->chief->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jurisdiction</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $land->chief->jurisdiction }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Ownership Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $land->ownership_status == ''vacant'' ? ''bg-green-100 text-green-800'' : 
                               ($land->ownership_status == ''allocated'' ? ''bg-blue-100 text-blue-800'' : ''bg-orange-100 text-orange-800'') }}">
                            {{ ucfirst(str_replace(''_'', '' '', $land->ownership_status)) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Land Use</dt>
                    <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $land->land_use }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Price (GHS)</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($land->price, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Registration Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $land->registration_date->format(''M d, Y'') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Verification Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $land->is_verified ? ''bg-green-100 text-green-800'' : ''bg-yellow-100 text-yellow-800'' }}">
                            {{ $land->is_verified ? ''Verified'' : ''Pending Verification'' }}
                        </span>
                    </dd>
                </div>
            </dl>

            <!-- Boundary Description -->
            @if($land->boundary_description)
            <div class="mt-6">
                <dt class="text-sm font-medium text-gray-500">Boundary Description</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $land->boundary_description }}</dd>
            </div>
            @endif

            <!-- Coordinates -->
            @if($land->latitude && $land->longitude)
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Latitude</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $land->latitude }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Longitude</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $land->longitude }}</dd>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Map and Allocation Info -->
    <div class="space-y-6">
        <!-- Map -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Location Map</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($land->latitude && $land->longitude)
                <div id="landMap" style="height: 300px; width: 100%;" class="rounded-lg"></div>
                @else
                <p class="text-sm text-gray-500 text-center py-8">No coordinates available for this land plot.</p>
                @endif
            </div>
        </div>

        <!-- Current Allocation -->
        @if($land->allocation)
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Current Allocation</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-gray-900">{{ $land->allocation->client->full_name }}</h4>
                        <p class="text-sm text-gray-500">Allocated on {{ $land->allocation->allocation_date->format(''M d, Y'') }}</p>
                        <p class="text-sm text-gray-500">Status: 
                            <span class="font-medium {{ $land->allocation->approval_status == ''approved'' ? ''text-green-600'' : ''text-yellow-600'' }}">
                                {{ ucfirst($land->allocation->approval_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Documents Section -->
<div class="mt-6 bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Documents</h3>
        <button type="button" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 transition ease-in-out duration-150">
            <i class="fas fa-plus mr-1"></i> Upload Document
        </button>
    </div>
    <div class="px-4 py-5 sm:p-6">
        @if($land->documents->count() > 0)
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($land->documents as $document)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $document->file_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst(str_replace(''_'', '' '', $document->document_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $document->created_at->format(''M d, Y'') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route(''documents.download'', $document) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-download"></i>
                            </a>
                            @if($document->is_verified)
                            <span class="text-green-600" title="Verified">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            @else
                            <button class="text-yellow-600 hover:text-yellow-900" title="Verify">
                                <i class="fas fa-question-circle"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm text-gray-500 text-center py-4">No documents uploaded for this land plot.</p>
        @endif
    </div>
</div>
@endsection

@push(''scripts'')
@if($land->latitude && $land->longitude)
<script>
    // Initialize map for land location
    var landMap = L.map(''landMap'').setView([{{ $land->latitude }}, {{ $land->longitude }}], 15);

    L.tileLayer(''https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'', {
        attribution: ''© OpenStreetMap contributors''
    }).addTo(landMap);

    // Add marker for the land
    var marker = L.marker([{{ $land->latitude }}, {{ $land->longitude }}]).addTo(landMap);
    marker.bindPopup(`
        <strong>{{ $land->plot_number }}</strong><br>
        {{ $land->location }}<br>
        {{ number_format($land->area_acres, 2) }} acres<br>
        Status: {{ ucfirst(str_replace(''_'', '' '', $land->ownership_status)) }}
    `).openPopup();
</script>
@endif
@endpush
'@ | Out-File -FilePath "$landsPath/show.blade.php" -Encoding UTF8

# 2. Clients Index View
@'
@extends(''layouts.app'')

@section(''title'', ''Client Management'')
@section(''header'', ''Client Management'')

@section(''actions'')
<div class="flex space-x-2">
    <a href="{{ route(''clients.export'') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-file-export mr-2"></i>Export
    </a>
    <a href="{{ route(''clients.create'') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-plus mr-2"></i>Add Client
    </a>
</div>
@endsection

@section(''content'')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <!-- Search -->
        <form method="GET" class="mb-6">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request(''search'') }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="Search by name, phone, or ID number...">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>

        <!-- Clients Table -->
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200" id="clientsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Information</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($clients as $client)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $client->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $client->occupation }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $client->phone }}</div>
                            @if($client->email)
                            <div class="text-sm text-gray-500">{{ $client->email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 capitalize">{{ str_replace(''_'', '' '', $client->id_type) }}</div>
                            <div class="text-sm text-gray-500">{{ $client->id_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $client->allocations_count }} allocation(s)
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route(''clients.show'', $client) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route(''clients.edit'', $client) }}" class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route(''clients.destroy'', $client) }}" method="POST" class="inline">
                                @csrf
                                @method(''DELETE'')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm(''Are you sure?'')">
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
            {{ $clients->links() }}
        </div>
    </div>
</div>
@endsection

@push(''scripts'')
<script>
    $(document).ready(function() {
        $(''#clientsTable'').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: []
        });
    });
</script>
@endpush
'@ | Out-File -FilePath "$clientsPath/index.blade.php" -Encoding UTF8

# 3. Client Show View
@'
@extends(''layouts.app'')

@section(''title'', ''Client Details - '' . $client->full_name)
@section(''header'', ''Client Details: '' . $client->full_name)

@section(''actions'')
<div class="flex space-x-2">
    <a href="{{ route(''clients.edit'', $client) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-edit mr-2"></i>Edit
    </a>
    <a href="{{ route(''allocations.create'') }}?client_id={{ $client->id }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fas fa-handshake mr-2"></i>Allocate Land
    </a>
</div>
@endsection

@section(''content'')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Client Information -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Information</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->phone }}</dd>
                    </div>
                    @if($client->email)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->email }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Occupation</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->occupation }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ str_replace(''_'', '' '', $client->id_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->id_number }}</dd>
                    </div>
                    @if($client->date_of_birth)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $client->date_of_birth->format(''M d, Y'') }}</dd>
                    </div>
                    @endif
                    @if($client->gender)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $client->gender }}</dd>
                    </div>
                    @endif
                </dl>

                <!-- Address -->
                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->address }}</dd>
                </div>

                <!-- Emergency Contact -->
                @if($client->emergency_contact)
                <div class="mt-4">
                    <dt class="text-sm font-medium text-gray-500">Emergency Contact</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->emergency_contact }}</dd>
                </div>
                @endif
            </div>
        </div>

        <!-- Land Allocations -->
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Land Allocations</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($client->allocations->count() > 0)
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plot Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocation Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($client->allocations as $allocation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $allocation->land->plot_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $allocation->land->location }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $allocation->allocation_date->format(''M d, Y'') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $allocation->approval_status == ''approved'' ? ''bg-green-100 text-green-800'' : 
                                           ($allocation->approval_status == ''pending'' ? ''bg-yellow-100 text-yellow-800'' : ''bg-red-100 text-red-800'') }}">
                                        {{ ucfirst($allocation->approval_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route(''allocations.show'', $allocation) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-gray-500 text-center py-4">No land allocations found for this client.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Documents and Quick Actions -->
    <div class="space-y-6">
        <!-- Documents -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Documents</h3>
                <button type="button" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-1"></i> Upload
                </button>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if($client->documents->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($client->documents as $document)
                    <li class="py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $document->file_name }}</p>
                                    <p class="text-sm text-gray-500 capitalize">{{ str_replace(''_'', '' '', $document->document_type) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($document->is_verified)
                                <i class="fas fa-check-circle text-green-500" title="Verified"></i>
                                @endif
                                <a href="{{ route(''documents.download'', $document) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-gray-500 text-center py-4">No documents uploaded.</p>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Allocation Summary</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Total Allocations</dt>
                        <dd class="text-sm text-gray-900">{{ $client->allocations->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Approved</dt>
                        <dd class="text-sm text-green-600">{{ $client->allocations->where(''approval_status'', ''approved'')->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Pending</dt>
                        <dd class="text-sm text-yellow-600">{{ $client->allocations->where(''approval_status'', ''pending'')->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
'@ | Out-File -FilePath "$clientsPath/show.blade.php" -Encoding UTF8

# 4. Allocations Index View
@'
@extends(''layouts.app'')

@section(''title'', ''Allocation Management'')
@section(''header'', ''Allocation Management'')

@section(''actions'')
<a href="{{ route(''allocations.create'') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
    <i class="fas fa-plus mr-2"></i>New Allocation
</a>
@endsection

@section(''content'')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <!-- Filters -->
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request(''status'') == ''pending'' ? ''selected'' : '' }}>Pending</option>
                        <option value="approved" {{ request(''status'') == ''approved'' ? ''selected'' : '' }}>Approved</option>
                        <option value="rejected" {{ request(''status'') == ''rejected'' ? ''selected'' : '' }}>Rejected</option>
                        <option value="finalized" {{ request(''status'') == ''finalized'' ? ''selected'' : '' }}>Finalized</option>
                    </select>
                </div>
                <div>
                    <label for="chief_id" class="block text-sm font-medium text-gray-700">Chief</label>
                    <select name="chief_id" id="chief_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">All Chiefs</option>
                        @foreach($chiefs as $chief)
                        <option value="{{ $chief->id }}" {{ request(''chief_id'') == $chief->id ? ''selected'' : '' }}>
                            {{ $chief->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Allocations Table -->
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200" id="allocationsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocation Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Land Information</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Information</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($allocations as $allocation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $allocation->id }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->allocation_date->format(''M d, Y'') }}</div>
                            <div class="text-sm text-gray-500">By: {{ $allocation->processedBy->user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $allocation->land->plot_number }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->land->location }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->chief->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $allocation->client->full_name }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->client->phone }}</div>
                            <div class="text-sm text-gray-500">{{ $allocation->client->id_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $allocation->approval_status == ''approved'' ? ''bg-green-100 text-green-800'' : 
                                   ($allocation->approval_status == ''pending'' ? ''bg-yellow-100 text-yellow-800'' : 
                                   ($allocation->approval_status == ''rejected'' ? ''bg-red-100 text-red-800'' : ''bg-blue-100 text-blue-800'')) }}">
                                {{ ucfirst($allocation->approval_status) }}
                            </span>
                            @if($allocation->payment_status != ''paid'')
                            <div class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    {{ ucfirst($allocation->payment_status) }}
                                </span>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route(''allocations.show'', $allocation) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($allocation->approval_status == ''pending'')
                            <form action="{{ route(''allocations.approve'', $allocation) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3" onclick="return confirm(''Approve this allocation?'')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route(''allocations.reject'', $allocation) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm(''Reject this allocation?'')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $allocations->links() }}
        </div>
    </div>
</div>
@endsection

@push(''scripts'')
<script>
    $(document).ready(function() {
        $(''#allocationsTable'').DataTable({
            paging: false,
            info: false,
            searching: false,
            order: []
        });
    });
</script>
@endpush
'@ | Out-File -FilePath "$allocationsPath/index.blade.php" -Encoding UTF8

# 5. Chiefs Index View
@'
@extends(''layouts.app'')

@section(''title'', ''Chief Management'')
@section(''header'', ''Chief Management'')

@section(''actions'')
@can(''admin'')
<a href="{{ route(''chiefs.create'') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
    <i class="fas fa-plus mr-2"></i>Add Chief
</a>
@endcan
@endsection

@section(''content'')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <!-- Search -->
        <form method="GET" class="mb-6">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request(''search'') }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="Search by name or jurisdiction...">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>

        <!-- Chiefs Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($chiefs as $chief)
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-crown text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $chief->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $chief->jurisdiction }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Lands</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $chief->lands_count }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Allocations</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $chief->allocations_count }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-phone mr-2"></i>{{ $chief->phone }}
                        </p>
                        @if($chief->email)
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-envelope mr-2"></i>{{ $chief->email }}
                        </p>
                        @endif
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $chief->is_active ? ''bg-green-100 text-green-800'' : ''bg-red-100 text-red-800'' }}">
                            {{ $chief->is_active ? ''Active'' : ''Inactive'' }}
                        </span>
                        <div class="flex space-x-2">
                            <a href="{{ route(''chiefs.show'', $chief) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can(''admin'')
                            <a href="{{ route(''chiefs.edit'', $chief) }}" class="text-green-600 hover:text-green-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $chiefs->links() }}
        </div>
    </div>
</div>
@endsection
'@ | Out-File -FilePath "$chiefsPath/index.blade.php" -Encoding UTF8

Write-Host "✅ Advanced views generated successfully!" -ForegroundColor Green
Write-Host "📁 Files created:" -ForegroundColor Cyan
Write-Host "   - resources/views/lands/show.blade.php" -ForegroundColor White
Write-Host "   - resources/views/clients/index.blade.php" -ForegroundColor White
Write-Host "   - resources/views/clients/show.blade.php" -ForegroundColor White
Write-Host "   - resources/views/allocations/index.blade.php" -ForegroundColor White
Write-Host "   - resources/views/chiefs/index.blade.php" -ForegroundColor White
Write-Host "🚀 Next step: We'll create form views, reports, and admin panels" -ForegroundColor Yellow