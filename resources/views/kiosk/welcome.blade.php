@extends('kiosk.layout')

@section('title', 'Bilik Suara - Mulai Memilih')

@section('content')
<div class="flex-grow flex flex-col items-center justify-center p-6 text-center">
    <div class="max-w-2xl w-full">
        <h1 class="text-4xl md:text-5xl font-normal text-ink mb-6 tracking-tight">
            Selamat Datang di Bilik Suara
        </h1>
        <p class="text-lg md:text-xl text-slate font-normal mb-12">
            Silakan tekan tombol di bawah ini untuk memulai proses pemungutan suara. Rahasia pilihan Anda dijamin aman.
        </p>

        @if(session('error'))
            <div class="mb-4 text-red-600 bg-red-50 p-4 rounded-[20px] border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('kiosk.generate', $election->id) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center py-5 px-10 text-2xl font-normal rounded-full border border-graphite-hairline bg-paper text-ink hover:bg-vellum hover:border-ink focus:outline-none transition-colors cursor-pointer">
                Mulai Memilih
                <svg class="ml-3 flex-shrink-0 size-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </form>
    </div>
</div>
@endsection
