@props([''client''])

<div class="bg-white border border-gray-200 rounded-lg p-4">
    <div class="flex items-center space-x-4">
        <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-blue-600"></i>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">
                {{ $client->full_name }}
            </p>
            <p class="text-sm text-gray-500 truncate">
                {{ $client->phone }}
            </p>
            <p class="text-xs text-gray-400 truncate">
                {{ ucfirst(str_replace(''_'', '' '', $client->id_type)) }}: {{ $client->id_number }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $client->allocations_count }} allocations
            </span>
        </div>
    </div>
</div>
