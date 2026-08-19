@extends('landing.layout')

@section('title', $election->name)

@section('content')
    <div class="min-h-screen sm:h-screen w-full flex flex-col items-center justify-between py-5 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8 relative sk-glow overflow-y-auto sm:overflow-hidden select-none">
        
        {{-- Header Section --}}
        <div class="w-full max-w-4xl mx-auto flex flex-col items-center text-center flex-shrink-0">
            {{-- Date Pill Ticket --}}
            <div class="sk-fade-in-up inline-flex items-center gap-2 sk-ticket px-4 py-1 rounded-full text-xs font-bold mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                <span>{{ strtoupper(\Carbon\Carbon::parse($election->date ?? $election->start_time)->translatedFormat('l, d F Y')) }}</span>
            </div>

            {{-- Election Title --}}
            <h1 class="sk-fade-in-up sk-delay-100 text-2xl sm:text-4xl lg:text-5xl font-extrabold uppercase tracking-tight text-slate-900 leading-tight">
                {{ $election->name }}
            </h1>

            {{-- Election Description --}}
            <p class="sk-fade-in-up sk-delay-200 mt-1 text-xs sm:text-sm text-slate-500 max-w-xl mx-auto line-clamp-2 leading-relaxed">
                {{ $election->description ?: 'Pelajari visi & misi setiap kandidat, lalu gunakan hak pilih Anda untuk menentukan masa depan yang lebih baik.' }}
            </p>
        </div>

        {{-- Candidates Section (Flexible Center) --}}
        <div class="w-full flex-grow flex items-center justify-center my-auto min-h-0 py-4 sm:py-0">
            @if($election->status === 'draft')
                <div class="sk-surface sk-card-outer sk-fade-in-up p-8 text-center max-w-md mx-auto">
                    <span class="inline-flex items-center justify-center size-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <h3 class="text-base font-bold text-slate-900 mb-1">Informasi Belum Tersedia</h3>
                    <p class="text-xs text-slate-500">Pemilihan ini belum dibuka untuk publik.</p>
                </div>
            @elseif($candidates->isEmpty())
                <div class="sk-surface sk-card-outer sk-fade-in-up p-8 text-center max-w-md mx-auto">
                    <span class="inline-flex items-center justify-center size-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                    <p class="text-xs text-slate-500">Belum ada kandidat yang terdaftar untuk pemilihan ini.</p>
                </div>
            @else
                <div class="flex flex-wrap sm:flex-nowrap justify-center items-center gap-4 sm:gap-6 lg:gap-8 max-w-5xl mx-auto w-full">
                    @foreach($candidates as $candidate)
                        <div class="flex-shrink-0 flex justify-center">
                            <x-voter.candidate-card :candidate="$candidate" variant="landing" :index="$loop->index" />
                        </div>
                    @endforeach
                </div>

                {{-- Visi & Misi modals --}}
                @foreach($candidates as $candidate)
                    <x-voter.visi-misi-modal :candidate="$candidate" />
                @endforeach
            @endif
        </div>

        <div class="h-2"></div>
    </div>
@endsection
