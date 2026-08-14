@props([
    'label',
    'name',
    'id' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autocomplete' => null,
    'error' => null,
    'size' => 'md',
])

@php
    $inputId = $id ?? $name;
    $inputValue = $value ?? old($name);

    $sizeClasses = match ($size) {
        'sm' => 'py-1.5 px-3 text-xs',
        'lg' => 'py-3 px-4 text-base',
        default => 'py-2 px-3.5 text-sm',
    };

    $baseInputClasses = "{$sizeClasses} block w-full rounded-xl border border-gray-200/90 bg-white text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15 shadow-2xs transition-all";
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed bg-gray-50' : '';
    $readonlyClass = $readonly ? 'bg-gray-50 cursor-not-allowed' : '';
    $inputClasses = implode(
        ' ',
        array_filter([$baseInputClasses, $disabledClass, $readonlyClass, $attributes->get('class')]),
    );

    $inputAttributes = $attributes->merge([
        'id' => $inputId,
        'type' => $type,
        'name' => $name,
        'value' => $inputValue,
        'placeholder' => $placeholder,
        'required' => $required ? true : null,
        'disabled' => $disabled ? true : null,
        'readonly' => $readonly ? true : null,
        'autocomplete' => $autocomplete,
        'class' => $inputClasses,
    ]);
@endphp

<div>
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input {{ $inputAttributes }}>

    @if ($error)
        <p class="text-xs text-red-600 mt-1">{{ $error }}</p>
    @endif
</div>
