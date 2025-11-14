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
