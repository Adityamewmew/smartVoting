@extends('landing.layout')

@section('title', $election->name)

@section('content')
    <div class="min-h-screen w-full flex flex-col items-center justify-start overflow-x-hidden relative">
        {{-- Subtle Ambient Radial Glow --}}
        <div class="absolute top-0 inset-x-0 h-96 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-200/40 via-sky-100/20 to-transparent pointer-events-none -z-10"></div>
        
        {{-- Header Section --}}
        <header class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6 text-center w-full flex flex-col items-center relative z-10">
            
            {{-- Logo Brand / Election Logo in Skeuomorphic Inset Bevel --}}
            <div class="mb-4 fade-up flex justify-center items-center">
                <div class="p-2.5 bg-gradient-to-b from-white to-slate-50 border border-slate-200/90 shadow-[0_8px_20px_-4px_rgba(15,23,42,0.08),inset_0_1px_1px_rgba(255,255,255,1)] rounded-2xl flex items-center justify-center">
                    @if(!empty($election->logo_path))
                        <img src="{{ Storage::url($election->logo_path) }}" alt="Logo {{ $election->name }}" class="h-14 sm:h-16 w-auto max-w-[180px] object-contain rounded-xl">
                    @else
                        <img src="{{ asset('images/logo-light.png') }}" alt="Logo SmartVoting" class="h-8 sm:h-9 w-auto object-contain">
                    @endif
                </div>
            </div>

            {{-- Institution Context Tag (if available) --}}
            @if(!empty($institution->name))
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/90 text-slate-700 border border-slate-200/90 shadow-2xs mb-2.5 fade-up">
                    <span class="size-1.5 rounded-full bg-blue-600"></span>
                    <span>{{ $institution->name }}</span>
                </div>
            @endif

            {{-- Election Title --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-3 max-w-3xl fade-up">
                {{ $election->name }}
            </h1>

            {{-- Date & Time Pill Ticket --}}
            <div class="inline-flex items-center gap-2 bg-gradient-to-b from-white to-slate-50 border border-slate-200/90 text-slate-700 text-xs sm:text-sm font-semibold px-4 sm:px-5 py-2 sm:py-2.5 rounded-2xl shadow-[0_2px_8px_rgba(0,0,0,0.03),inset_0_1px_0_rgba(255,255,255,1)] mb-4 fade-up d-1">
                <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <time>
                    {{ \Carbon\Carbon::parse($election->date ?? $election->start_time)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    @if($election->start_time && $election->end_time)
                        &bull; {{ \Carbon\Carbon::parse($election->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($election->end_time)->format('H:i') }} WIB
                    @endif
                </time>
            </div>

            {{-- Election Description --}}
            @if($election->description)
                <div class="mt-4 max-w-2xl mx-auto text-xs sm:text-sm text-slate-600 leading-relaxed markdown-content bg-white/70 backdrop-blur-xs p-4 rounded-2xl border border-slate-200/70 shadow-2xs fade-up d-2">
                    {!! \Illuminate\Support\Str::markdown($election->description) !!}
                </div>
            @endif
        </header>

        {{-- Candidates Section --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 w-full relative z-10">
            <section aria-label="Daftar Pasangan Calon">
                @if($election->status === 'draft')
                    <div class="bg-gradient-to-b from-white to-slate-50 rounded-3xl border border-slate-200/90 p-8 sm:p-12 text-center max-w-md mx-auto shadow-md">
                        <span class="inline-flex items-center justify-center size-14 rounded-2xl bg-slate-100 text-slate-400 mb-4 border border-slate-200/80 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">Informasi Belum Tersedia</h3>
                        <p class="text-xs text-slate-500 m-0">Pemilihan ini masih dalam status draf dan belum dibuka untuk publik.</p>
                    </div>
                @elseif($candidates->isEmpty())
                    <div class="bg-gradient-to-b from-white to-slate-50 rounded-3xl border border-slate-200/90 p-8 sm:p-12 text-center max-w-md mx-auto shadow-md">
                        <span class="inline-flex items-center justify-center size-14 rounded-2xl bg-slate-100 text-slate-400 mb-4 border border-slate-200/80 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">Belum Ada Paslon</h3>
                        <p class="text-xs text-slate-500 m-0">Belum ada pasangan calon yang terdaftar untuk pemilihan ini.</p>
                    </div>
                @else
                    <ol class="flex flex-wrap justify-center gap-6 lg:gap-8 list-none p-0 m-0 w-full" role="list">
                        @foreach($candidates as $candidate)
                            <li class="fade-up w-full max-w-[360px] flex justify-center">
                                <x-voter.candidate-card :candidate="$candidate" variant="landing" :index="$loop->index" />
                            </li>
                        @endforeach
                    </ol>

                    {{-- Voting Notice & Information Callout Banner --}}
                    <div class="mt-12 max-w-2xl mx-auto w-full p-4 sm:p-5 rounded-2xl bg-gradient-to-b from-white to-slate-50 border border-slate-200/90 shadow-[0_6px_20px_-4px_rgba(15,23,42,0.05),inset_0_1px_0_rgba(255,255,255,1)] flex items-start sm:items-center gap-3.5 text-left fade-up d-3">
                        <div class="size-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 shadow-2xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-xs text-slate-600 leading-relaxed">
                            <h4 class="font-bold text-slate-900 text-xs sm:text-sm mb-0.5">Panduan Pemungutan Suara</h4>
                            <p class="m-0">Pemilihan dilakukan langsung di Bilik Suara Kiosk fisik menggunakan token sesi pemilihan yang diterbitkan panitia saat jadwal berlangsung.</p>
                        </div>
                    </div>

                    {{-- Visi & Misi Modals --}}
                    @foreach($candidates as $candidate)
                        <x-voter.visi-misi-modal :candidate="$candidate" />
                    @endforeach
                @endif
            </section>
        </main>
    </div>
@endsection
