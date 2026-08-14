@props([
    'label',
    'name',
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'rows' => 3,
    'error' => null,
])

@php
    $textareaId = $id ?? $name;
    $textareaValue = $value ?? old($name);

    $baseClasses = "py-2.5 px-3.5 block w-full rounded-lg border border-gray-200 bg-white text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs transition-colors text-sm";
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed bg-gray-50' : '';
    $readonlyClass = $readonly ? 'bg-gray-50 cursor-not-allowed' : '';
    $classes = implode(' ', array_filter([$baseClasses, $disabledClass, $readonlyClass, $attributes->get('class')]));
@endphp

<div>
    @if ($label)
        <label for="{{ $textareaId }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    <textarea
        id="{{ $textareaId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        class="{{ $classes }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {!! $attributes->except('class') !!}>{{ $textareaValue }}</textarea>
    
    @error($name)
        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>
