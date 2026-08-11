@extends('kiosk.layout')

@section('title', 'Bilik Suara - Pilih Kandidat')

@section('content')
<div class="flex-grow flex flex-col p-4 md:p-8 relative">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8 bg-paper p-4 md:p-6 rounded-[20px] shadow-none border border-graphite-hairline">
        <div>
            <h2 class="text-2xl font-normal text-ink">{{ $election->name }}</h2>
            <p class="text-sm text-slate font-normal">Silakan sentuh pada kartu Paslon pilihan Anda</p>
        </div>
        <div class="flex flex-col items-end">
            <span class="text-sm font-normal text-slate uppercase tracking-widest mb-1">Waktu Tersisa</span>
            <div id="timer" class="text-4xl font-normal text-red-600 tabular-nums">01:00</div>
        </div>
    </div>

    <!-- Candidate Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 flex-grow content-start">
        @foreach($candidates as $candidate)
        <button type="button" onclick="confirmVote({{ $candidate->id }}, '{{ $candidate->order_number }}')" class="group relative flex flex-col items-center bg-paper rounded-[20px] p-6 border border-graphite-hairline hover:border-ink transition-colors text-left text-ink cursor-pointer focus:outline-none">
            
            <div class="absolute -top-6 bg-ink text-paper size-14 rounded-full flex items-center justify-center text-2xl font-normal border-4 border-paper shadow-none">
                {{ $candidate->order_number }}
            </div>

            <div class="mt-4 mb-4 w-full aspect-square bg-vellum rounded-xl overflow-hidden flex items-center justify-center border border-graphite-hairline relative">
                @if($candidate->photo_path)
                    <img src="{{ Storage::url($candidate->photo_path) }}" alt="Foto Paslon {{ $candidate->order_number }}" class="w-full h-full object-cover">
                @else
                    <span class="text-slate font-normal text-xl uppercase">No Image</span>
                @endif
                <div class="absolute inset-0 bg-ink/0 group-hover:bg-ink/5 transition-colors"></div>
            </div>

            <h3 class="text-xl font-normal text-center w-full">{{ $candidate->chairman_name }}</h3>
            <span class="text-sm text-slate font-normal mb-3">&</span>
            <h3 class="text-xl font-normal text-center w-full">{{ $candidate->vice_chairman_name }}</h3>

        </button>
        @endforeach
    </div>

    <!-- Overlay Loading & Success -->
    <div id="loading-overlay" class="fixed inset-0 bg-paper/90 backdrop-blur-sm z-50 hidden flex-col items-center justify-center">
        <div id="loading-spinner" class="animate-spin inline-block size-16 border-2 border-vellum border-t-ink rounded-full" role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <div id="success-message" class="hidden flex-col items-center">
            <div class="size-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6">
                <svg class="size-14" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h2 class="text-3xl font-normal text-ink">Terima Kasih!</h2>
            <p class="text-xl text-slate font-normal mt-2">Suara Anda telah berhasil disimpan.</p>
        </div>
    </div>

    <!-- Modal Confirm -->
    <div id="confirm-modal" class="fixed inset-0 bg-ink/60 backdrop-blur-sm z-40 hidden flex-col items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-paper max-w-lg w-full rounded-[20px] p-8 shadow-none border border-graphite-hairline transform scale-95 transition-transform duration-300" id="confirm-modal-content">
            <h3 class="text-2xl font-normal text-ink text-center mb-4">Konfirmasi Pilihan</h3>
            <p class="text-lg text-slate font-normal text-center mb-8">
                Apakah Anda yakin ingin memilih Paslon Nomor <span id="confirm-nomor" class="font-normal text-ink text-2xl ml-1"></span> ?
            </p>
            <div class="flex gap-4">
                <button type="button" onclick="closeConfirm()" class="flex-1 py-4 px-4 inline-flex justify-center items-center gap-x-2 text-lg font-normal rounded-full border border-graphite-hairline bg-paper text-ink hover:bg-vellum focus:outline-none transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="button" id="btn-submit-vote" class="flex-1 py-4 px-4 inline-flex justify-center items-center gap-x-2 text-lg font-normal rounded-full border border-transparent bg-ink text-paper hover:bg-ink/90 focus:outline-none transition-colors cursor-pointer">
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
    const token = '{!! $token !!}';
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
@endsection
