@props(['fit' => false])

@php
    $heightClass = $fit ? 'h-auto' : 'h-full';
@endphp

<div {{ $attributes->merge(['class' => "glass-card p-6 $heightClass text-ink"]) }}>
    {{ $slot }}
</div>
