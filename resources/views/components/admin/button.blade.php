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
        'icon-sm' => 'size-8 p-0 text-xs rounded-lg',
        'icon-md' => 'size-9 p-0 text-sm rounded-xl',
        'sm' => 'py-1.5 px-3 text-xs rounded-lg',
        'md' => 'py-2 px-4 text-sm rounded-xl',
        'lg' => 'py-2.5 px-5 text-sm sm:text-base rounded-xl',
        'xl' => 'py-3.5 px-6 text-base rounded-xl',
        default => 'py-2 px-4 text-sm rounded-xl',
    };

    $colorClasses = match ($color) {
        'primary' => 'bg-gradient-to-b from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 border-t border-white/25 font-bold active:translate-y-0.5 active:scale-[0.98] active:shadow-inner',
        'secondary' => 'bg-gradient-to-b from-white to-gray-50/90 hover:from-gray-50 hover:to-gray-100 text-gray-700 border border-gray-200/90 shadow-2xs hover:shadow-xs hover:-translate-y-0.5 font-semibold active:translate-y-0.5 active:scale-[0.98] active:shadow-inner',
        'danger' => 'bg-gradient-to-b from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white shadow-md shadow-red-500/25 hover:shadow-lg hover:shadow-red-500/30 hover:-translate-y-0.5 border-t border-white/25 font-bold active:translate-y-0.5 active:scale-[0.98] active:shadow-inner', 
        'success' => 'bg-gradient-to-b from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white shadow-md shadow-emerald-500/25 hover:shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-0.5 border-t border-white/25 font-bold active:translate-y-0.5 active:scale-[0.98] active:shadow-inner',
        'outline-primary' => 'bg-gradient-to-b from-white to-blue-50/40 border border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-300 hover:-translate-y-0.5 shadow-2xs font-semibold active:translate-y-0.5 active:scale-[0.98]',
        'outline-secondary' => 'bg-gradient-to-b from-white to-gray-50/50 border border-gray-200/90 text-gray-600 hover:text-gray-900 hover:border-gray-300 hover:bg-gray-50 hover:-translate-y-0.5 shadow-2xs font-semibold active:translate-y-0.5 active:scale-[0.98]',
        'outline-danger' => 'bg-gradient-to-b from-white to-red-50/40 border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 hover:-translate-y-0.5 shadow-2xs font-semibold active:translate-y-0.5 active:scale-[0.98]',
        'outline-warning' => 'bg-gradient-to-b from-white to-amber-50/40 border border-amber-200 text-amber-600 hover:bg-amber-50 hover:border-amber-300 hover:-translate-y-0.5 shadow-2xs font-semibold active:translate-y-0.5 active:scale-[0.98]',
        'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-600 hover:text-gray-900 font-semibold active:translate-y-0.5 active:scale-[0.98]',
        default => 'bg-gradient-to-b from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 border-t border-white/25 font-bold active:translate-y-0.5 active:scale-[0.98] active:shadow-inner',
    };

    $baseClasses =
        'inline-flex items-center justify-center gap-x-2 transition-all duration-150 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none cursor-pointer';
    $classes = implode(' ', array_filter([$class, $baseClasses, $sizeClasses, $colorClasses]));

    $attributes = $attributes->merge([
        'type' => $href ? null : $type,
        'href' => $href,
        'disabled' => $disabled ? true : null,
        'class' => $classes,
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
