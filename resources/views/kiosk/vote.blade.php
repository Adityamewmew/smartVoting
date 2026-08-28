@extends('kiosk.layout')

@section('title', 'Bilik Suara — ' . $election->name)

@section('content')
<div class="flex-grow flex flex-col p-4 sm:p-8 relative min-h-screen">

    {{-- Floating Countdown Timer Card (Fixed Top Right) --}}
    <aside class="fixed top-5 right-5 sm:top-6 sm:right-6 z-40 flex items-center gap-3 bg-white/95 backdrop-blur-md border border-gray-200/90 py-2.5 px-4 sm:px-5 rounded-2xl shadow-lg shadow-slate-900/5" style="top: 1.5rem; right: 1.5rem;" aria-label="Waktu Tersisa">
        <div class="flex flex-col items-end text-right">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Sisa Waktu</span>
            <div id="timer" class="text-xl sm:text-2xl font-black {{ $remainingSeconds <= 10 ? 'text-red-600 animate-pulse' : ($remainingSeconds <= 20 ? 'text-amber-500' : 'text-blue-600') }} tabular-nums leading-none mt-0.5">
                {{ sprintf('00:%02d', $remainingSeconds) }}
            </div>
        </div>
        <div class="size-8 sm:size-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 sm:size-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
    </aside>

    {{-- Candidate Grid (Strict 3 columns on desktop) --}}
    <main class="w-full max-w-6xl mx-auto flex-grow flex flex-col justify-center pt-16 sm:pt-20 pb-16">
        <ol class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 w-full list-none m-0 p-0 justify-items-center" role="list">
            @foreach($candidates as $candidate)
                <li class="flex justify-center h-full w-full max-w-[360px]">
                    <x-voter.candidate-card :candidate="$candidate" variant="booth" :index="$loop->index" />
                </li>
            @endforeach
        </ol>
    </main>

    {{-- Overlay Loading & Success Modal --}}
    <div id="loading-overlay" class="modal-overlay fixed inset-0 z-50 hidden items-center justify-center p-4 transition-opacity opacity-0 duration-300">
        <div id="loading-spinner" class="bg-white rounded-3xl p-8 flex flex-col items-center shadow-2xl border border-gray-100">
            <div class="animate-spin inline-block size-14 border-4 border-slate-200 border-t-blue-600 rounded-full mb-4" role="status" aria-label="loading">
                <span class="sr-only">Menyimpan suara...</span>
            </div>
            <p class="text-sm font-bold text-gray-700">Merekam suara Anda...</p>
        </div>

        <article id="modal-success" class="hidden bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 text-center transform scale-95 transition-transform duration-300 flex-col items-center border border-gray-100" role="dialog" aria-modal="true" aria-labelledby="success-modal-title">
            <div class="size-20 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-3xl flex items-center justify-center text-3xl mb-5 shadow-xs">
                <svg class="size-10 text-emerald-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>

            <h2 id="success-modal-title" class="text-2xl font-black text-gray-900 mb-2">Terima Kasih!</h2>

            <p class="text-sm text-gray-600 mb-6 max-w-xs mx-auto leading-relaxed">
                Hak suara Anda telah berhasil disimpan dan direkam secara aman ke dalam sistem SmartVoting.
            </p>

            {{-- Auto Countdown Timer --}}
            <div class="w-full flex items-center justify-center bg-gray-50 border border-gray-200/70 py-3.5 px-4 rounded-2xl mb-5" aria-live="polite">
                <div class="flex items-center gap-3 text-xs sm:text-sm text-gray-600 font-medium">
                    <svg class="animate-spin size-4 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Sesi akan kembali otomatis dalam <output id="reset-countdown" class="text-gray-900 font-bold mx-0.5">5</output> detik</span>
                </div>
            </div>

            <p class="text-xs text-gray-400 max-w-[260px] mx-auto leading-relaxed">
                Silakan tinggalkan bilik suara agar dapat digunakan oleh pemilih berikutnya.
            </p>
        </article>
    </div>

    {{-- Confirmation Modal --}}
    <aside id="confirm-modal" class="modal-overlay fixed inset-0 z-40 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300" role="dialog" aria-modal="true" aria-labelledby="confirm-modal-title">
        <article class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-7 sm:p-8 transform scale-95 transition-transform duration-300 flex flex-col items-center border border-gray-100 text-center" id="confirm-modal-content">

            <div class="size-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-xs border border-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-blue-600" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
            </div>

            <h2 id="confirm-modal-title" class="text-xl font-bold text-gray-900 text-center mb-2">Konfirmasi Pilihan Suara</h2>

            <p class="text-gray-600 text-center mb-4 text-sm leading-relaxed">
                Apakah Anda yakin ingin memberikan suara untuk:
            </p>

            <div class="bg-blue-50/80 border border-blue-100 rounded-2xl p-4 mb-5 w-full text-center">
                <div id="confirm-candidate-pad" class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-1"></div>
                <div id="confirm-candidate-name" class="text-gray-900 font-extrabold text-base sm:text-lg leading-snug"></div>
            </div>

            <div class="bg-amber-50 border border-amber-200/80 w-full p-3.5 rounded-xl mb-6 text-center" role="alert">
                <p class="text-xs text-amber-800 font-semibold flex items-center justify-center gap-2 m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Pilihan yang sudah dikonfirmasi bersifat final dan tidak dapat diubah.
                </p>
            </div>

            <footer class="flex gap-3 w-full">
                <button type="button" onclick="closeConfirm()" class="btn-secondary flex-1 py-3 text-sm font-bold">
                    Batal
                </button>
                <button type="button" id="btn-submit-vote" class="btn-primary flex-1 py-3 text-sm font-bold">
                    Ya, Konfirmasi
                </button>
            </footer>
        </article>
    </aside>
</div>
@endsection

@push('scripts')
<script>
    let timeLeft = {{ $remainingSeconds }}, selectedCandidateId = null;
    const $ = (id) => document.getElementById(id);
    const token = '{!! $token !!}';
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const timer = setInterval(() => {
        if (--timeLeft <= 0) {
            clearInterval(timer);
            $('timer').textContent = '00:00';
            fetch(`/bilik/${token}/expire`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } })
                .then(() => { alert('Waktu Anda telah habis! Sesi dibatalkan.'); location.href = '/bilik/start/{{ $session['election_id'] }}'; });
            return;
        }
        $('timer').textContent = `00:${String(timeLeft).padStart(2, '0')}`;
        $('timer').className = `text-xl sm:text-2xl font-black tabular-nums leading-none mt-0.5 ${timeLeft <= 10 ? 'text-red-600 animate-pulse' : (timeLeft <= 20 ? 'text-amber-500' : 'text-blue-600')}`;
    }, 1000);

    function playTingSound() {
        const audio = new Audio('/audio/vote-success.mp3');
        audio.play().catch(() => {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                osc.connect(ctx.destination);
                osc.frequency.setValueAtTime(1200, ctx.currentTime);
                osc.start();
                osc.stop(ctx.currentTime + 0.3);
            } catch(e) {}
        });
    }

    function confirmVote(id, num, c1 = '', c2 = '') {
        selectedCandidateId = id;
        const pad = String(num).padStart(2, '0');
        if ($('confirm-candidate-pad')) $('confirm-candidate-pad').textContent = `Pasangan Calon ${pad}`;
        if ($('confirm-candidate-name')) $('confirm-candidate-name').textContent = c2 ? `${c1} & ${c2}` : (c1 || `Paslon ${pad}`);

        const m = $('confirm-modal'), c = $('confirm-modal-content');
        m.classList.remove('hidden');
        m.classList.add('flex');
        requestAnimationFrame(() => { m.classList.remove('opacity-0'); c?.classList.remove('scale-95'); });
    }

    function closeConfirm() {
        selectedCandidateId = null;
        const m = $('confirm-modal'), c = $('confirm-modal-content');
        m.classList.add('opacity-0');
        c?.classList.add('scale-95');
        setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300);
    }

    $('btn-submit-vote').addEventListener('click', async () => {
        if (!selectedCandidateId) return;
        clearInterval(timer);
        const candidateId = selectedCandidateId;
        closeConfirm();

        const overlay = $('loading-overlay');
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        setTimeout(() => overlay.classList.remove('opacity-0'), 10);

        try {
            const res = await fetch(`/bilik/${token}/submit`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ candidate_id: candidateId })
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.message);

            $('loading-spinner').classList.add('hidden');
            const modal = $('modal-success');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => modal.classList.remove('scale-95'), 10);
            playTingSound();

            let cd = 5;
            $('reset-countdown').textContent = cd;
            const cdInterval = setInterval(() => {
                if (--cd <= 0) {
                    clearInterval(cdInterval);
                    location.href = '/bilik/start/{{ $session['election_id'] }}';
                }
                $('reset-countdown').textContent = cd;
            }, 1000);
        } catch (err) {
            alert('Terjadi kesalahan: ' + (err.message || 'Jaringan bermasalah'));
            location.reload();
        }
    });
</script>
@endpush
