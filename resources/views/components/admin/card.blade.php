@props(['fit' => false])

@php
    $heightClass = $fit ? 'h-auto' : 'h-full';
@endphp

<div {{ $attributes->merge(['class' => "bg-white rounded-2xl border border-gray-200/75 shadow-xs hover:shadow-sm transition-all duration-200 p-6 $heightClass text-gray-900"]) }}>
    {{ $slot }}
</div>
