@props(['candidate'])

@php
    $modalId = 'visi-misi-' . $candidate->id;
    $pad = str_pad($candidate->order_number ?? 1, 2, '0', STR_PAD_LEFT);
@endphp

<aside id="{{ $modalId }}"
       class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300"
       role="dialog"
       aria-modal="true"
       aria-labelledby="{{ $modalId }}-title">

    <article class="visi-misi-panel bg-white rounded-3xl w-full max-w-2xl max-h-[85vh] flex flex-col relative scale-95 transition-transform duration-300 border border-slate-200/90 shadow-[0_25px_50px_-12px_rgba(15,23,42,0.25),inset_0_1px_0_rgba(255,255,255,1)] overflow-hidden">

        {{-- Sticky Header Modal with Skeuomorphic Depth --}}
        <header class="sticky top-0 bg-gradient-to-b from-white via-white to-slate-50/90 backdrop-blur-md z-10 px-6 sm:px-8 py-4 border-b border-slate-100 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-gradient-to-b from-blue-500 to-blue-600 text-white font-black text-sm flex items-center justify-center shadow-md shadow-blue-500/25 border-t border-white/30 flex-shrink-0" aria-label="Nomor urut {{ $pad }}">{{ $pad }}</span>
                <div>
                    <span class="text-[10px] font-bold tracking-wider text-blue-600 uppercase">Pasangan Calon {{ $pad }}</span>
                    <h2 id="{{ $modalId }}-title" class="text-base sm:text-lg font-bold text-slate-900 leading-snug m-0">
                        Visi &amp; Misi Paslon {{ $pad }}
                    </h2>
                </div>
            </div>

            {{-- Close button --}}
            <button type="button"
                    data-close="{{ $modalId }}"
                    class="size-8 rounded-xl bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-500 border border-slate-200/60 flex items-center justify-center transition-colors flex-shrink-0 ml-4 cursor-pointer shadow-2xs"
                    aria-label="Tutup dialog">
                <svg aria-hidden="true" class="size-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </header>

        {{-- Scrollable Visi & Misi Content --}}
        <div class="overflow-y-auto px-6 sm:px-8 py-6 space-y-6 flex-grow">
            @if($candidate->vision || $candidate->mission)
                {{-- Visi --}}
                @if($candidate->vision)
                    <section aria-labelledby="{{ $modalId }}-visi-label" class="rounded-2xl bg-gradient-to-b from-blue-50/80 to-blue-50/40 border border-blue-100 p-4 sm:p-6 shadow-2xs">
                        <header class="flex items-center gap-2 mb-2.5">
                            <svg aria-hidden="true" class="size-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <h3 id="{{ $modalId }}-visi-label" class="text-[11px] font-bold uppercase tracking-wider text-blue-700 m-0">Visi</h3>
                        </header>
                        <blockquote class="text-sm sm:text-base font-semibold text-slate-800 leading-relaxed italic m-0">
                            &ldquo;{!! trim(preg_replace('/^<p>|<\/p>$/i', '', \Illuminate\Support\Str::markdown($candidate->vision))) !!}&rdquo;
                        </blockquote>
                    </section>
                @endif

                {{-- Misi --}}
                @if($candidate->mission)
                    <section aria-labelledby="{{ $modalId }}-misi-label" class="bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-2xs">
                        <header class="flex items-center gap-2 mb-4">
                            <svg aria-hidden="true" class="size-4 text-slate-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="2.5" stroke-linecap="round">
                                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                                <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                            </svg>
                            <h3 id="{{ $modalId }}-misi-label" class="text-[11px] font-bold uppercase tracking-wider text-slate-600 m-0">Misi Utama</h3>
                        </header>
                        <div class="markdown-mission text-sm leading-relaxed text-slate-700">
                            @php
                                $missionContent = $candidate->mission;
                                if (!preg_match('/^\s*(\*|\-|\+|\d+[\.\)])\s+/m', $missionContent)) {
                                    $lines = preg_split('/\r\n|\r|\n/', trim($missionContent));
                                    $missionContent = implode("\n", array_map(fn($l) => trim($l) !== '' ? '- ' . trim($l) : '', $lines));
                                }
                            @endphp
                            {!! \Illuminate\Support\Str::markdown($missionContent) !!}
                        </div>
                    </section>
                @endif
            @else
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-12 mx-auto text-slate-300 mb-3" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                    <h4 class="text-base font-semibold text-slate-700 mb-1">Data Belum Lengkap</h4>
                    <p class="text-sm text-slate-500 m-0">Visi &amp; Misi belum ditambahkan oleh kandidat ini.</p>
                </div>
            @endif
        </div>

        {{-- Sticky Footer --}}
        <footer class="sticky bottom-0 bg-gradient-to-b from-white to-slate-50/90 backdrop-blur-md z-10 px-6 sm:px-8 py-4 border-t border-slate-100">
            <button type="button" data-close="{{ $modalId }}"
                    class="btn-secondary w-full py-2.5 px-4 rounded-xl text-sm font-bold cursor-pointer border border-slate-200/90 shadow-2xs">
                Tutup
            </button>
        </footer>
    </article>
</aside>

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
