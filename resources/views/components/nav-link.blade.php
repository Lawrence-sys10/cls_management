@props(['active' => false, 'href'])

@php
    $classes = $active
        ? 'bg-green-900 text-white px-3 py-2 rounded-md text-sm font-medium'
        : 'text-green-300 hover:bg-green-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
