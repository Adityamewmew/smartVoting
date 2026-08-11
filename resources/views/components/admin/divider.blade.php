@props([
    'label' => null,
])

<div class="relative py-16">
    <div class="absolute inset-0 flex items-center" aria-hidden="true">
        <div class="w-full border-t border-graphite-hairline"></div>
    </div>
    @if($label)
    <div class="relative flex justify-center">
        <span class="bg-paper px-4 text-sm font-normal text-slate">
            {{ $label }}
        </span>
    </div>
    @endif
</div>
