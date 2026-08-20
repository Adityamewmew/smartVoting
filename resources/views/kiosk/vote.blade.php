@extends('kiosk.layout')

@section('title', 'Bilik Suara — ' . $election->name)

@section('content')
<div class="flex-grow flex flex-col p-4 sm:p-8 relative min-h-screen">

    {{-- Header Bilik Suara --}}
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 bg-white p-5 sm:p-6 rounded-3xl border border-gray-100 shadow-md w-full max-w-6xl mx-auto">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-gray-900 leading-tight">{{ $election->name }}</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Gunakan hak suara Anda dengan bijak. Tentukan pasangan calon pilihan Anda.</p>
        </div>
        <div class="flex items-center sm:items-end justify-between w-full sm:w-auto sm:flex-col pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest sm:mb-1">Waktu Tersisa</span>
            <div id="timer" class="text-2xl sm:text-4xl font-black text-blue-600 tabular-nums">01:00</div>
        </div>
    </header>

    {{-- Candidate Grid --}}
    <main class="w-full max-w-6xl mx-auto flex-grow flex flex-col justify-start">
        <ol class="flex flex-wrap justify-center gap-7 w-full pb-16 list-none m-0 p-0" role="list">
            @foreach($candidates as $candidate)
                <li class="flex justify-center h-full w-full max-w-[380px]">
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
    let timeLeft = 60;
    const timerEl = document.getElementById('timer');
    const token = '{!! $token !!}';
    let selectedCandidateId = null;
    let timerInterval;

    function startTimer() {
        timerInterval = setInterval(() => {
            timeLeft--;

            if (timeLeft <= 10) {
                timerEl.className = 'text-2xl sm:text-4xl font-black text-red-600 animate-pulse tabular-nums';
            } else if (timeLeft <= 20) {
                timerEl.className = 'text-2xl sm:text-4xl font-black text-amber-500 tabular-nums';
            } else {
                timerEl.className = 'text-2xl sm:text-4xl font-black text-blue-600 tabular-nums';
            }

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerEl.textContent = '00:00';
                handleTimeout();
            } else {
                let seconds = timeLeft;
                timerEl.textContent = '00:' + (seconds < 10 ? '0' : '') + seconds;
            }
        }, 1000);
    }

    function handleTimeout() {
        fetch(`/bilik/${token}/expire`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            alert('Waktu Anda telah habis! Sesi ini dibatalkan.');
            window.location.href = '/bilik/start/{{ $session['election_id'] }}';
        });
    }

    function playTingSound() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(1200, audioCtx.currentTime);
            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(1, audioCtx.currentTime + 0.05);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 1.5);
            osc.start(audioCtx.currentTime);
            osc.stop(audioCtx.currentTime + 1.5);
        } catch(e) {}
    }

    function confirmVote(candidateId, orderNumber, chairmanName = '', viceChairmanName = '') {
        selectedCandidateId = candidateId;
        const pad = String(orderNumber).padStart(2, '0');
        const padEl = document.getElementById('confirm-candidate-pad');
        const nameEl = document.getElementById('confirm-candidate-name');

        if (padEl) padEl.textContent = 'Pasangan Calon ' + pad;
        let displayName = chairmanName || `Paslon ${pad}`;
        if (viceChairmanName) {
            displayName += ' & ' + viceChairmanName;
        }
        if (nameEl) nameEl.textContent = displayName;

        const modal = document.getElementById('confirm-modal');
        const modalContent = document.getElementById('confirm-modal-content');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        });
    }

    function closeConfirm() {
        selectedCandidateId = null;

        const modal = document.getElementById('confirm-modal');
        const modalContent = document.getElementById('confirm-modal-content');

        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    document.getElementById('btn-submit-vote').addEventListener('click', function() {
        if (!selectedCandidateId) return;

        clearInterval(timerInterval);

        const candidateIdToSubmit = selectedCandidateId;
        closeConfirm();

        const overlay = document.getElementById('loading-overlay');
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
        }, 10);

        fetch(`/bilik/${token}/submit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                candidate_id: candidateIdToSubmit
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('loading-spinner').classList.add('hidden');
                const successModal = document.getElementById('modal-success');
                successModal.classList.remove('hidden');
                successModal.classList.add('flex');
                setTimeout(() => {
                    successModal.classList.remove('scale-95');
                }, 10);

                playTingSound();

                let secondsLeft = 5;
                const countdownEl = document.getElementById('reset-countdown');
                countdownEl.textContent = secondsLeft;
                const cdInterval = setInterval(() => {
                    secondsLeft--;
                    countdownEl.textContent = secondsLeft;
                    if (secondsLeft <= 0) {
                        clearInterval(cdInterval);
                        window.location.href = '/bilik/start/{{ $session['election_id'] }}';
                    }
                }, 1000);
            } else {
                alert('Terjadi kesalahan: ' + data.message);
                window.location.reload();
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan jaringan.');
            window.location.reload();
        });
    });

    startTimer();
</script>
@endpush
