<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="min-w-full inline-block align-middle">
            <div {{ $attributes->merge(['class' => 'glass-card p-0 rounded-2xl shadow-xs overflow-hidden']) }}>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
