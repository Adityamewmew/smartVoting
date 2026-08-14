@props([
    'label',
    'name',
    'id' => null,
    'options' => [],
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'value' => null,
    'size' => 'md',
])
@php
    $selectId = $id ?? $name;

    $sizeClasses = match ($size) {
        'sm' => 'py-1.5 px-3 text-xs',
        'lg' => 'py-3 px-4 text-base',
        default => 'py-2 px-3.5 text-sm',
    };

    $baseClasses = "{$sizeClasses} block w-full rounded-xl border border-gray-200/90 bg-white text-gray-900 focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15 shadow-2xs transition-all cursor-pointer";
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed bg-gray-50' : '';
    $customClass = $attributes->get('class');
    $selectClass = trim(implode(' ', array_filter([$baseClasses, $disabledClass, $customClass])));
@endphp
<div>
    @if ($label)
        <label for="{{ $selectId }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    <select id="{{ $selectId }}" name="{{ $name }}" class="{{ $selectClass }}"
        {{ $disabled ? 'disabled' : '' }} {{ $attributes->except(['class', 'id', 'name', 'disabled']) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $text)
            <option value="{{ $optionValue }}" {{ (old($name) ?? $value) == $optionValue ? 'selected' : '' }}>
                {{ $text }}</option>
        @endforeach
    </select>
    @if ($error)
        <p class="text-xs text-red-600 mt-1">{{ $error }}</p>
    @endif
</div>
