@extends('kiosk.layout')

@section('title', 'Selamat Datang di Bilik Suara')

@section('content')
<div class="flex-grow flex flex-col items-center justify-center p-6 text-center relative sk-glow">
    <div class="sk-surface sk-card-outer max-w-xl w-full p-8 md:p-12 relative z-10">
        <div class="mx-auto flex justify-center items-center size-20 rounded-full border border-primary-200 bg-primary-50 text-primary-600 mb-6 animate-pulse-glow shadow-md shadow-blue-500/15">
            <svg class="size-10" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">
            Bilik Suara Siap
        </h1>
        <p class="text-base md:text-lg text-slate-600 leading-relaxed">
            Silakan tunggu instruksi dari panitia / operator TPS untuk memulai sesi pemilihan Anda.
        </p>
    </div>
</div>
@endsection
