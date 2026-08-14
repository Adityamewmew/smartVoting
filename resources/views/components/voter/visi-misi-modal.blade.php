@props(['candidate'])

@php
    $missionRaw  = $candidate->mission ?? null;
    $missionItems = json_decode($missionRaw, true);
    if (!is_array($missionItems)) {
        $missionItems = array_values(array_filter(array_map('trim', explode("\n", (string) $missionRaw))));
    }
    $useParagraph = count($missionItems) === 1 && strlen($missionItems[0]) > 150;
    $modalId = 'visi-misi-' . $candidate->id;
@endphp

<div id="{{ $modalId }}"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"
     role="dialog" aria-modal="true" aria-labelledby="{{ $modalId }}-title">

    <div class="visi-misi-panel sk-surface sk-card-outer relative w-full max-w-2xl max-h-[90vh] overflow-y-auto p-7 sm:p-9 transform scale-95 transition-transform duration-300">

        {{-- Close --}}
        <button type="button" data-close="{{ $modalId }}"
                class="absolute top-4 right-4 size-9 rounded-full inline-flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors cursor-pointer"
                aria-label="Tutup">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>

        <h3 id="{{ $modalId }}-title" class="text-2xl font-bold text-primary-700 mb-6 pr-10">Visi &amp; Misi</h3>

        @if($candidate->vision || $candidate->mission)
            @if($candidate->vision)
                <div class="mb-6">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
                        Visi
                    </h4>
                    <p class="text-base italic leading-relaxed text-slate-700">"{{ $candidate->vision }}"</p>
                </div>
            @endif

            @if($candidate->mission)
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                        Misi
                    </h4>
                    @if($useParagraph)
                        <p class="text-base leading-relaxed text-slate-700">{{ $missionItems[0] }}</p>
                    @else
                        <ul class="space-y-2.5">
                            @foreach($missionItems as $item)
                                <li class="flex items-start gap-2.5">
                                    <span class="mt-0.5 w-5 h-5 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </span>
                                    <span class="text-base leading-relaxed text-slate-700">{{ trim($item) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-12 mx-auto text-slate-300 mb-3" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                <h4 class="text-base font-semibold text-slate-700 mb-1">Data Belum Lengkap</h4>
                <p class="text-sm text-slate-500">Visi &amp; Misi belum ditambahkan oleh kandidat ini.</p>
            </div>
        @endif

        <button type="button" data-close="{{ $modalId }}"
                class="mt-8 w-full sk-btn-ghost rounded-xl py-3 text-sm font-semibold cursor-pointer">
            Tutup
        </button>
    </div>
</div>

@once
@push('scripts')
<script>
(function () {
    function openModal(id) {
        var m = document.getElementById(id);
        if (!m) return;
        m.classList.remove('hidden');
        m.classList.add('flex');
        requestAnimationFrame(function () {
            m.classList.remove('opacity-0');
            var p = m.querySelector('.visi-misi-panel');
            if (p) p.classList.remove('scale-95');
        });
    }
    function closeModal(id) {
        var m = document.getElementById(id);
        if (!m) return;
        m.classList.add('opacity-0');
        var p = m.querySelector('.visi-misi-panel');
        if (p) p.classList.add('scale-95');
        setTimeout(function () {
            m.classList.add('hidden');
            m.classList.remove('flex');
        }, 300);
    }
    document.addEventListener('click', function (e) {
        var opener = e.target.closest('[data-open]');
        if (opener) { openModal(opener.getAttribute('data-open')); return; }
        var closer = e.target.closest('[data-close]');
        if (closer) { closeModal(closer.getAttribute('data-close')); return; }
        var backdrop = e.target.closest('.visi-misi-overlay, [id^="visi-misi-"]');
        if (backdrop && e.target === backdrop) { closeModal(backdrop.id); }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('[id^="visi-misi-"]:not(.hidden)').forEach(function (m) {
            closeModal(m.id);
        });
    });
})();
</script>
@endpush
@endonce
