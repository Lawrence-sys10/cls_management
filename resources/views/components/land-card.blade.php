@props([''land''])

<div class="bg-white border border-gray-200 rounded-lg p-4">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $land->plot_number }}</h4>
            <div class="space-y-1 text-sm text-gray-600">
                <p><strong>Location:</strong> {{ $land->location }}</p>
                <p><strong>Area:</strong> {{ number_format($land->area_acres, 2) }} acres</p>
                <p><strong>Chief:</strong> {{ $land->chief->name }}</p>
                <p>
                    <strong>Status:</strong> 
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $land->ownership_status == ''vacant'' ? ''bg-green-100 text-green-800'' : 
                           ($land->ownership_status == ''allocated'' ? ''bg-blue-100 text-blue-800'' : ''bg-orange-100 text-orange-800'') }}">
                        {{ ucfirst(str_replace(''_'', '' '', $land->ownership_status)) }}
                    </span>
                </p>
            </div>
        </div>
        <div class="ml-4 flex-shrink-0">
            <a href="{{ route(''lands.show'', $land) }}" 
               class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                View
            </a>
        </div>
    </div>
</div>
