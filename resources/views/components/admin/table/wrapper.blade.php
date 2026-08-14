<div class="flex flex-col">
    <div class="overflow-x-auto overscroll-x-contain [-webkit-overflow-scrolling:touch]">
        <div class="min-w-full inline-block align-middle">
            <div {{ $attributes->merge(['class' => 'bg-white p-0 rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden']) }}>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
