@extends('kiosk.layout')

@section('title', 'Bilik Suara - Pilih Kandidat')

@section('content')
<div class="flex-grow flex flex-col p-4 md:p-8 relative">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8 sk-surface sk-card-outer p-4 sm:p-6 w-full max-w-7xl mx-auto">
        <div>
            <h2 class="text-lg sm:text-2xl font-bold text-slate-900 leading-tight">{{ $election->name }}</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Silakan pilih Paslon pilihan Anda</p>
        </div>
        <div class="flex items-center sm:items-end justify-between w-full sm:w-auto sm:flex-col pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest sm:mb-1">Waktu Tersisa</span>
            <div id="timer" class="text-2xl sm:text-4xl font-black text-primary-600 tabular-nums">01:00</div>
        </div>
    </div>

    <!-- Candidate Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 md:gap-8 flex-grow content-start justify-items-center w-full max-w-7xl mx-auto">
        @foreach($candidates as $candidate)
            <div class="w-full max-w-sm sm:max-w-xs md:max-w-sm">
                <x-voter.candidate-card :candidate="$candidate" variant="booth" :index="$loop->index" />
            </div>
        @endforeach
    </div>

    <!-- Visi & Misi modals -->
    @foreach($candidates as $candidate)
        <x-voter.visi-misi-modal :candidate="$candidate" />
    @endforeach

    <!-- Overlay Loading & Success -->
    <div id="loading-overlay" class="fixed inset-0 bg-slate-50/90 backdrop-blur-sm z-50 hidden flex-col items-center justify-center">
        <div id="loading-spinner" class="animate-spin inline-block size-16 border-4 border-slate-200 border-t-primary-600 rounded-full" role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <div id="success-message" class="hidden flex-col items-center">
            <div class="size-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-6">
                <svg class="size-14" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Terima Kasih!</h2>
            <p class="text-lg md:text-xl text-slate-500 mt-2">Suara Anda telah berhasil disimpan.</p>
        </div>
    </div>

    <!-- Modal Confirm -->
    <div id="confirm-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden flex-col items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="sk-surface sk-card-outer max-w-lg w-full p-7 md:p-9 transform scale-95 transition-transform duration-300" id="confirm-modal-content">

            <div class="flex flex-col items-center text-center mb-6">
                <span class="sk-badge inline-flex items-center justify-center size-14 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                </span>
                <h3 class="text-xl md:text-2xl font-bold text-slate-900">Konfirmasi Pilihan</h3>
            </div>

            <div class="text-center mb-6">
                <p class="text-base text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin memberikan suara untuk
                </p>
                <div class="mt-2 text-lg font-bold text-primary-600">
                    Paslon <span id="confirm-nomor"></span>
                    <span id="confirm-candidate-names" class="block text-base font-semibold text-slate-800 mt-0.5"></span>
                </div>
            </div>

            <div class="mb-7 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-xl p-3.5 flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 flex-shrink-0 mt-0.5 text-red-600" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                <span class="leading-snug">Perhatian: Pilihan yang sudah dikonfirmasi tidak dapat diubah kembali.</span>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeConfirm()" class="sk-btn-ghost flex-1 py-3.5 px-4 inline-flex justify-center items-center gap-x-2 text-base font-semibold rounded-xl cursor-pointer">
                    Batal
                </button>
                <button type="button" id="btn-submit-vote" class="sk-btn-primary flex-1 py-3.5 px-4 inline-flex justify-center items-center gap-x-2 text-base font-bold rounded-xl cursor-pointer">
                    Ya, Yakin
                </button>
            </div>
        </div>
    </div>
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
                timerEl.classList.remove('text-primary-600');
                timerEl.classList.remove('text-red-600');
                timerEl.classList.add('text-red-700', 'animate-pulse');
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
            osc.frequency.setValueAtTime(1200, audioCtx.currentTime); // High pitch
            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(1, audioCtx.currentTime + 0.05); // Attack
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 1.5); // Decay
            osc.start(audioCtx.currentTime);
            osc.stop(audioCtx.currentTime + 1.5);
        } catch(e) {}
    }

    function confirmVote(candidateId, orderNumber, chairmanName = '', viceChairmanName = '') {
        selectedCandidateId = candidateId;
        document.getElementById('confirm-nomor').textContent = orderNumber;
        
        const namesEl = document.getElementById('confirm-candidate-names');
        if (namesEl) {
            if (chairmanName || viceChairmanName) {
                namesEl.textContent = chairmanName + (viceChairmanName ? ' & ' + viceChairmanName : '');
            } else {
                namesEl.textContent = '';
            }
        }

        const modal = document.getElementById('confirm-modal');
        const modalContent = document.getElementById('confirm-modal-content');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Trigger animation
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
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

        clearInterval(timerInterval); // Stop timer

        const candidateIdToSubmit = selectedCandidateId;
        closeConfirm();

        const overlay = document.getElementById('loading-overlay');
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');

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
                // Show success message
                document.getElementById('loading-spinner').classList.add('hidden');
                document.getElementById('success-message').classList.remove('hidden');
                document.getElementById('success-message').classList.add('flex');

                playTingSound();

                setTimeout(() => {
                    window.location.href = '/bilik/start/{{ $session['election_id'] }}';
                }, 3000);
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

    // Start timer on load
    startTimer();
</script>
@endpush
