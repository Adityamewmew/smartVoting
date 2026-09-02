@props([
    'title' => '',
    'subtitle' => null,
    'backUrl' => null,
])

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-100 shadow-xs mb-6">
    <div class="flex items-center gap-3">
        @if ($backUrl)
            <a href="{{ $backUrl }}" navigate
                class="size-9 inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors shadow-2xs">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
        @endif
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">
                {{ $title }}
            </h1>
            @if ($subtitle)
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
    </div>

    @if ($slot->isNotEmpty())
        <div class="flex items-center gap-2 w-full sm:w-auto">
            {{ $slot }}
        </div>
    @endif
</div>
