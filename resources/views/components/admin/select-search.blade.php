@props([
    'label',
    'name',
    'id' => null,
    'options' => [],
    'readonlyValue' => null,
    'readonlyText' => null,
    'readonlySubtext' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'value' => null,
    'searchPlaceholder' => 'Cari...',
    'size' => 'md',
    'class' => '',
])
@php
    $selectId = $id ?? $name;
    $isReadonly = !empty($readonlyValue);

    $sizeClasses = match ($size) {
        'sm' => 'py-1.5 px-3 pe-9 text-sm',
        'lg' => 'p-3.5 sm:p-5 pe-9 sm:pe-9 sm:text-sm',
        default => 'py-2.5 sm:py-3 px-4 pe-9 sm:pe-9 sm:text-sm',
    };

    $hsSelectConfig =
        '{
        "hasSearch": true,
        "searchPlaceholder": "' .
        $searchPlaceholder .
        '",
        "searchClasses": "block w-full text-xs bg-transparent border-gray-200 rounded-lg text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 py-2 px-3",
        "searchWrapperClasses": "bg-white p-2 sticky top-0 border-b border-gray-100",
        "placeholder": "' .
        ($placeholder ?: 'Pilih...') .
        '",
        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"><span class=\"me-2\" data-icon></span><span class=\"text-gray-800 font-medium\" data-title></span></button>",
        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative ' .
        $sizeClasses .
        ' flex items-center text-nowrap w-full cursor-pointer bg-white border border-gray-200 text-gray-800 rounded-xl text-start hover:bg-gray-50 focus:outline-hidden focus:border-blue-500 shadow-2xs transition-colors",
        "dropdownClasses": "mt-2 max-h-72 pb-1 px-1 space-y-0.5 z-20 w-full bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:size-2 [&::-webkit-scrollbar-thumb]:bg-gray-200",
        "optionClasses": "hs-selected:bg-blue-600 hs-selected:text-white py-2 px-4 w-full text-xs text-gray-700 font-medium cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 transition-colors",
        "optionTemplate": "<div><div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div class=\"text-gray-800 font-medium\" data-title></div></div></div>",
        "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-400\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
    }';
@endphp
<div class="space-y-1.5 {{ $class }}">
    @if ($label)
        <label for="{{ $selectId }}" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @if ($isReadonly)
        <input type="hidden" name="{{ $name }}" value="{{ $readonlyValue }}">
        <div
            class="py-2.5 px-3.5 block w-full bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800">
            {{ $readonlyText }}
            @if ($readonlySubtext)
                <span class="ms-1 text-xs font-normal text-gray-500 uppercase">{{ $readonlySubtext }}</span>
            @endif
        </div>
    @else
        <select id="{{ $selectId }}" name="{{ $name }}" data-hs-select="{{ $hsSelectConfig }}"
            class="hidden">
            @if ($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach ($options as $optValue => $text)
                <option value="{{ $optValue }}" {{ (old($name) ?? $value) == $optValue ? 'selected' : '' }}>
                    {{ $text }}</option>
            @endforeach
        </select>
    @endif

    @if ($error)
        <p class="text-xs text-red-600 mt-1">{{ $error }}</p>
    @endif
</div>
