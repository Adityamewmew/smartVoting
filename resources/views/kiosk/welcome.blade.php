@extends('kiosk.layout')

@section('title', 'Bilik Suara - Mulai Memilih')

@section('content')
<div class="flex-grow flex flex-col items-center justify-center p-6 text-center relative sk-glow">

    <div class="max-w-2xl w-full relative z-10">
        <span class="sk-badge inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold tracking-wider mb-7">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
            BILIK SUARA DIGITAL
        </span>

        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 mb-6">
            Selamat Datang di Bilik Suara
        </h1>

        <p class="text-lg md:text-xl text-slate-600 mb-12 max-w-xl mx-auto leading-relaxed">
            Silakan tekan tombol di bawah ini untuk memulai proses pemungutan suara. Rahasia pilihan Anda dijamin aman.
        </p>

        @if(session('error'))
            <div class="mb-6 text-sm font-medium text-red-700 bg-red-50 border border-red-200 p-4 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('kiosk.generate', $election->id) }}" method="POST">
            @csrf
            <button type="submit" class="sk-btn-primary rounded-xl py-5 px-10 text-xl font-bold inline-flex items-center justify-center gap-3 cursor-pointer">
                Mulai Memilih
                <svg class="flex-shrink-0 size-7" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </form>
    </div>
</div>
@endsection
