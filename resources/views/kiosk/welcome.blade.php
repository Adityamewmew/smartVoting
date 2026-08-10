@extends('kiosk.layout')

@section('title', 'Bilik Suara - Mulai Memilih')

@section('content')
<div class="flex-grow flex flex-col items-center justify-center p-6 text-center">
    <div class="max-w-2xl w-full">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-6 tracking-tight">
            Selamat Datang di Bilik Suara
        </h1>
        <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 mb-12">
            Silakan tekan tombol di bawah ini untuk memulai proses pemungutan suara. Rahasia pilihan Anda dijamin aman.
        </p>

        @if(session('error'))
            <div class="mb-4 text-red-600 bg-red-50 p-4 rounded-xl border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('kiosk.generate', $election->id) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center py-5 px-10 text-2xl font-bold rounded-full border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all shadow-xl shadow-blue-600/30 active:scale-95 cursor-pointer">
                Mulai Memilih
                <svg class="ml-3 flex-shrink-0 size-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </form>
    </div>
</div>
@endsection
