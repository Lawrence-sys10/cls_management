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
