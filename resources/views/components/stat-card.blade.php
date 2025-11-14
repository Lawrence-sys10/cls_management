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
