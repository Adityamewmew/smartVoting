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
        'active', 'success', 'emerald' => 'bg-emerald-50 text-emerald-900 border-emerald-200/90 shadow-2xs',
        'primary', 'blue' => 'bg-blue-50 text-blue-900 border-blue-200/90 shadow-2xs',
        'solid-primary' => 'bg-blue-600 text-white border-blue-600 shadow-2xs',
        'closed', 'inactive', 'danger', 'red' => 'bg-rose-50 text-rose-900 border-rose-200/90 shadow-2xs',
        'warning', 'amber', 'scheduled', 'pending' => 'bg-amber-50 text-amber-900 border-amber-200/90 shadow-2xs',
        'purple' => 'bg-purple-50 text-purple-900 border-purple-200/90 shadow-2xs',
        'gray', 'draft' => 'bg-slate-100 text-slate-900 border-slate-200/90 shadow-2xs',
        default => 'bg-slate-100 text-slate-900 border-slate-200/90 shadow-2xs',
    };

    $dotClasses = match ($resolvedType) {
        'active', 'success', 'emerald' => 'bg-emerald-500',
        'primary', 'blue' => 'bg-blue-500',
        'solid-primary' => 'bg-white',
        'closed', 'inactive', 'danger', 'red' => 'bg-rose-500',
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
