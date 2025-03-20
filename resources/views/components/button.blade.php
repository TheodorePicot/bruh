@props([
    'variant' => 'primary'
])

@php
    $buttonClasses = [
        'primary' => 'primary-button',
        'secondary' => 'secondary-button',
    ]

@endphp

<button {{ $attributes->merge(['class' => $buttonClasses[$variant]]) }}>
    {{ $slot }}
</button>
