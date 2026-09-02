@props([
    'title' => null,
    'message' => 'Data tidak ditemukan',
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center p-8 sm:p-12 rounded-2xl bg-white border border-gray-100 shadow-2xs']) }}>
    <div class="size-14 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-400 mb-4 shadow-2xs">
        @if ($icon)
            {!! $icon !!}
        @else
            <svg class="size-7" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="21" y1="21" y2="21"/><path d="m21 21-4.3-4.3"/></svg>
        @endif
    </div>
    @if ($title)
        <h3 class="text-base font-bold text-gray-900 mb-1 tracking-tight">{{ $title }}</h3>
    @endif
    <p class="text-xs sm:text-sm text-gray-500 max-w-md mb-4 leading-relaxed">{{ $message }}</p>
    @if ($slot->isNotEmpty())
        <div class="mt-1">
            {{ $slot }}
        </div>
    @endif
</div>
