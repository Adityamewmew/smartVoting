@extends('kiosk.layout')

@section('title', 'Selamat Datang di Bilik Suara')

@section('content')
<div class="flex-grow flex flex-col items-center justify-center p-6 text-center">
    <div class="max-w-2xl w-full">
        <h1 class="text-4xl md:text-5xl font-normal text-ink mb-6 tracking-tight">
            Selamat Datang di Bilik Suara
        </h1>
        <p class="text-lg md:text-xl text-slate font-normal">
            Silakan tunggu aba-aba dari panitia/operator untuk memulai sesi pemilihan Anda.
        </p>
    </div>
</div>
@endsection
