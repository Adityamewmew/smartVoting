@props([
    'size' => 'md',
    'color' => 'primary',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'class' => '',
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'py-1.5 px-3 text-sm',
        'md' => 'py-2.5 px-4 text-sm',
        'lg' => 'py-3 px-5 text-base',
        'xl' => 'py-3.5 px-6 text-lg',
        default => 'py-2.5 px-4 text-sm',
    };

    $colorClasses = match ($color) {
        'primary' => 'glass-button text-white font-semibold',
        'secondary' => 'glass-button text-white font-semibold',
        'danger' => 'bg-red-500/80 text-white hover:bg-red-600/90 backdrop-blur-md shadow-lg', 
        'success' => 'bg-green-500/80 text-white hover:bg-green-600/90 backdrop-blur-md shadow-lg',
        'outline-primary' => 'glass-button text-white font-semibold',
        'outline-secondary' => 'bg-transparent text-ink hover:underline px-0 py-0', /* Ghost text link */
        'outline-danger' => 'glass-button text-red-600 border-red-500/30 font-semibold',
        'outline-success' => 'glass-button text-green-600 border-green-500/30 font-semibold',
        default => 'glass-button text-white font-semibold',
    };

    $baseClasses =
        'inline-flex items-center justify-center gap-x-2 font-normal rounded-full transition-all duration-200 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none';
    $classes = implode(' ', array_filter([$class, $baseClasses, $sizeClasses, $colorClasses]));

    $attributes = $attributes->merge([
        'type' => $href ? null : $type,
        'href' => $href,
        'disabled' => $disabled ? true : null,
        'class' => $classes . ' cursor-pointer',
    ]);
@endphp

@if ($href)
    <a navigate {{ $attributes }}>
        @if ($icon && $iconPosition === 'left')
            {!! $icon !!}
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right')
            {!! $icon !!}
        @endif
    </a>
@else
    <button {{ $attributes }}>
        @if ($icon && $iconPosition === 'left')
            {!! $icon !!}
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right')
            {!! $icon !!}
        @endif
    </button>
@endif
