@props(['text', 'type'])

@php
$classes = ($type == 'success')
            ? 'mb-2 border border-green-500 p-2 text-green-700 bg-green-100 rounded text-center text-xl uppercase font-medium'
            : 'mb-2 border border-red-500 p-2 text-red-500 bg-red-100 rounded text-center text-xl uppercase font-medium';
@endphp

<p class="{{ $classes }}">
    {{ $text }}
</p>