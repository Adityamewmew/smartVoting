@extends('kiosk.layout')

@section('title', 'Bilik Suara - Pilih Kandidat')

@section('content')
<div class="flex-grow flex flex-col p-4 md:p-8 relative">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8 bg-white dark:bg-neutral-800 p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-neutral-700">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $election->name }}</h2>
            <p class="text-sm text-gray-500">Silakan sentuh pada kartu Paslon pilihan Anda</p>
        </div>
        <div class="flex flex-col items-end">
            <span class="text-sm font-semibold text-gray-500 uppercase tracking-widest mb-1">Waktu Tersisa</span>
            <div id="timer" class="text-4xl font-black text-red-600 tabular-nums">01:00</div>
        </div>
    </div>

    <!-- Candidate Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 flex-grow content-start">
        @foreach($candidates as $candidate)
        <button type="button" onclick="confirmVote({{ $candidate->id }}, '{{ $candidate->order_number }}')" class="group relative flex flex-col items-center bg-white dark:bg-neutral-800 rounded-3xl p-6 border-2 border-transparent hover:border-blue-500 shadow-lg hover:shadow-2xl hover:shadow-blue-500/20 transition-all text-left text-gray-800 dark:text-neutral-200 cursor-pointer active:scale-95 focus:outline-none focus:ring-4 focus:ring-blue-500">
            
            <div class="absolute -top-6 bg-blue-600 text-white size-14 rounded-full flex items-center justify-center text-2xl font-black border-4 border-gray-50 dark:border-neutral-900 shadow-md">
                {{ $candidate->order_number }}
            </div>

            <div class="mt-4 mb-4 w-full aspect-square bg-gray-100 dark:bg-neutral-700 rounded-2xl overflow-hidden flex items-center justify-center border border-gray-200 dark:border-neutral-600 relative">
                @if($candidate->photo_path)
                    <img src="{{ Storage::url($candidate->photo_path) }}" alt="Foto Paslon {{ $candidate->order_number }}" class="w-full h-full object-cover">
                @else
                    <span class="text-gray-400 font-bold text-xl uppercase">No Image</span>
                @endif
                <div class="absolute inset-0 bg-blue-600/0 group-hover:bg-blue-600/10 transition-colors"></div>
            </div>

            <h3 class="text-xl font-bold text-center w-full">{{ $candidate->chairman_name }}</h3>
            <span class="text-sm text-gray-500 dark:text-gray-400 font-semibold mb-3">&</span>
            <h3 class="text-xl font-bold text-center w-full">{{ $candidate->vice_chairman_name }}</h3>

        </button>
        @endforeach
    </div>

    <!-- Overlay Loading & Success -->
    <div id="loading-overlay" class="fixed inset-0 bg-white/90 dark:bg-neutral-900/90 backdrop-blur-sm z-50 hidden flex-col items-center justify-center">
        <div id="loading-spinner" class="animate-spin inline-block size-16 border-[4px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <div id="success-message" class="hidden flex-col items-center">
            <div class="size-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6">
                <svg class="size-14" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white">Terima Kasih!</h2>
            <p class="text-xl text-gray-600 dark:text-neutral-400 mt-2">Suara Anda telah berhasil disimpan.</p>
        </div>
    </div>

    <!-- Modal Confirm -->
    <div id="confirm-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden flex-col items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white dark:bg-neutral-800 max-w-lg w-full rounded-3xl p-8 shadow-2xl transform scale-95 transition-transform duration-300" id="confirm-modal-content">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-4">Konfirmasi Pilihan</h3>
            <p class="text-lg text-gray-600 dark:text-neutral-400 text-center mb-8">
                Apakah Anda yakin ingin memilih Paslon Nomor <span id="confirm-nomor" class="font-black text-blue-600 text-2xl ml-1"></span> ?
            </p>
            <div class="flex gap-4">
                <button type="button" onclick="closeConfirm()" class="flex-1 py-4 px-4 inline-flex justify-center items-center gap-x-2 text-lg font-bold rounded-xl border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800">
                    Batal
                </button>
                <button type="button" id="btn-submit-vote" class="flex-1 py-4 px-4 inline-flex justify-center items-center gap-x-2 text-lg font-bold rounded-xl border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 shadow-lg shadow-blue-500/30">
                    Ya, Yakin
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let timeLeft = 60;
    const timerEl = document.getElementById('timer');
    const token = '{{ $token }}';
    let selectedCandidateId = null;
    let timerInterval;

    function startTimer() {
        timerInterval = setInterval(() => {
            timeLeft--;
            
            if (timeLeft <= 10) {
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

    function confirmVote(candidateId, orderNumber) {
        selectedCandidateId = candidateId;
        document.getElementById('confirm-nomor').textContent = orderNumber;
        
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

                let redirectSecs = 15;
                
                const rInterval = setInterval(() => {
                    redirectSecs--;
                    if (redirectSecs <= 0) {
                        clearInterval(rInterval);
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

    // Start timer on load
    startTimer();
</script>
@endsection
