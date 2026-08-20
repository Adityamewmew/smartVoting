@props([
    'text' => null,
    'status' => null,
    'color' => null,
    'size' => 'md',
    'pulse' => false,
])

@php
    $resolvedType = $color ?? $status ?? 'default';

    $colorClasses = match ($resolvedType) {
        'active', 'success', 'emerald' => 'bg-gradient-to-r from-emerald-50 to-emerald-100/70 text-emerald-800 border-emerald-200/90 shadow-2xs',
        'primary', 'blue' => 'bg-gradient-to-r from-blue-50 to-blue-100/70 text-blue-800 border-blue-200/90 shadow-2xs',
        'closed', 'inactive', 'danger', 'red' => 'bg-gradient-to-r from-red-50 to-red-100/70 text-red-800 border-red-200/90 shadow-2xs',
        'warning', 'amber', 'scheduled', 'pending' => 'bg-gradient-to-r from-amber-50 to-amber-100/70 text-amber-800 border-amber-200/90 shadow-2xs',
        'purple' => 'bg-gradient-to-r from-purple-50 to-purple-100/70 text-purple-800 border-purple-200/90 shadow-2xs',
        'gray', 'draft' => 'bg-gradient-to-r from-slate-50 to-gray-100/70 text-slate-700 border-gray-200/90 shadow-2xs',
        default => 'bg-gradient-to-r from-slate-50 to-gray-100/70 text-slate-700 border-gray-200/90 shadow-2xs',
    };

    $dotClasses = match ($resolvedType) {
        'active', 'success', 'emerald' => 'bg-emerald-500',
        'primary', 'blue' => 'bg-blue-500',
        'closed', 'inactive', 'danger', 'red' => 'bg-red-500',
        'warning', 'amber', 'scheduled', 'pending' => 'bg-amber-500',
        'purple' => 'bg-purple-500',
        default => 'bg-slate-400',
    };

    $sizeClasses = match ($size) {
        'sm' => 'py-0.5 px-2 text-[10px]',
        'lg' => 'py-1 px-3 text-xs',
        default => 'py-0.5 px-2.5 text-xs',
    };

    $showPulse = $pulse || in_array($resolvedType, ['active', 'live']);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 font-bold rounded-full border shadow-2xs $sizeClasses $colorClasses transition-all duration-150"]) }}>
    @if ($showPulse)
        <span class="size-1.5 rounded-full {{ $dotClasses }} animate-pulse"></span>
    @endif
    {{ $text ?? $slot }}
</span>
