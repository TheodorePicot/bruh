@props([
    'variant' => 'primary'
])

@php
    $buttonClasses = [
        'primary' => 'primary-button',
    ]

@endphp

<button {{ $attributes->merge(['class' => $buttonClasses[$variant]]) }}>
    {{ $slot }}
</button>
