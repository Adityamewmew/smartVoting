@extends('kiosk.layout')

@section('title', 'Bilik Suara - Sesi Tidak Valid')

@section('content')
<div class="flex-grow flex flex-col items-center justify-center p-6 text-center relative sk-glow">
    <div class="sk-surface sk-card-outer max-w-xl w-full p-8 md:p-10 relative z-10">
        
        <div class="mx-auto flex justify-center items-center size-20 rounded-full border border-red-200 bg-red-50 text-red-500 mb-6">
            <svg class="size-10" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 9-6 6"/><path d="m9 9 6 6"/><circle cx="12" cy="12" r="10"/></svg>
        </div>

        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-4 tracking-tight">
            Akses Ditolak
        </h1>
        <p class="text-base md:text-lg text-slate-600 mb-8 leading-relaxed">
            {{ $message ?? 'Sesi pemilihan ini tidak valid, sudah digunakan, atau telah kedaluwarsa.' }}
        </p>

        <div class="text-sm font-medium text-slate-700 bg-slate-100 p-4 rounded-xl border border-slate-200 mb-6">
            Silakan hubungi Operator TPS untuk mendapatkan akses kembali.
        </div>

        <p class="text-sm text-slate-500">
            Halaman ini akan otomatis kembali dalam <span id="countdown" class="font-bold text-primary-600">10</span> detik...
        </p>
    </div>
</div>

<script>
    const redirectUrl = @if(!empty($electionId))
        '/bilik/start/{{ $electionId }}'
    @else
        '/login'
    @endif;

    let secs = 10;
    const el = document.getElementById('countdown');

    setInterval(() => {
        secs--;
        el.textContent = secs;
        if (secs <= 0) window.location.href = redirectUrl;
    }, 1000);
</script>
@endsection
