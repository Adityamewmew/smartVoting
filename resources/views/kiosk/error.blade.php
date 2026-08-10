@extends('kiosk.layout')

@section('title', 'Bilik Suara - Sesi Tidak Valid')

@section('content')
<div class="flex-grow flex flex-col items-center justify-center p-6 text-center">
    <div class="max-w-xl w-full bg-white dark:bg-neutral-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-neutral-700">
        
        <div class="mx-auto flex justify-center items-center size-20 rounded-full border-4 border-red-50 bg-red-100 text-red-500 mb-6">
            <svg class="size-10" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 9-6 6"/><path d="m9 9 6 6"/><circle cx="12" cy="12" r="10"/></svg>
        </div>

        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
            Akses Ditolak
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
            {{ $message ?? 'Sesi pemilihan ini tidak valid, sudah digunakan, atau telah kedaluwarsa.' }}
        </p>

        <p class="text-sm font-medium text-gray-500 bg-gray-50 dark:bg-neutral-900 p-4 rounded-xl">
            Silakan hubungi Operator TPS untuk mendapatkan akses kembali.
        </p>

    </div>
</div>
@endsection
