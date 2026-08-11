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

    $baseClasses = "py-3 px-4 block w-full rounded-[8px] focus:ring-2 focus:ring-white/50 dark:text-neutral-400 placeholder-ink/60 font-normal glass-input";
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : '';
    $readonlyClass = $readonly ? 'bg-gray-50 dark:bg-neutral-800/50' : '';
    $classes = implode(' ', array_filter([$baseClasses, $disabledClass, $readonlyClass, $attributes->get('class')]));
@endphp

<div class="space-y-2">
    @if ($label)
        <label for="{{ $textareaId }}" class="text-sm text-ink font-normal pb-3">
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
