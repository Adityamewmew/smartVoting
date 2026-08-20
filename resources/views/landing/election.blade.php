@extends('landing.layout')

@section('title', $election->name)

@section('content')
    <div class="min-h-screen w-full flex flex-col items-center justify-start overflow-x-hidden">
        
        {{-- Header Section --}}
        <header class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 pt-16 pb-8 text-center w-full flex flex-col items-center">
            {{-- Election Title --}}
            <h1 class="text-4xl sm:text-5xl lg:text-[52px] font-extrabold text-gray-900 tracking-tight leading-tight mb-4 max-w-4xl fade-up">
                {{ $election->name }}
            </h1>

            {{-- Date & Time Pill Ticket --}}
            <div class="inline-flex items-center gap-2.5 bg-white border border-gray-200/90 text-gray-600 text-sm font-medium px-5 py-2.5 rounded-2xl shadow-xs mb-3 fade-up d-1">
                <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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

            {{-- Reusable Countdown Timer Component --}}
            <x-voter.countdown-timer :election="$election" />

            {{-- Election Description --}}
            @if($election->description)
                <div class="mt-4 max-w-2xl mx-auto text-sm text-gray-600 leading-relaxed markdown-content fade-up d-2">
                    {!! \Illuminate\Support\Str::markdown($election->description) !!}
                </div>
            @endif
        </header>

        {{-- Candidates Section --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 pb-24 w-full">
            <section aria-label="Daftar Pasangan Calon">
                @if($election->status === 'draft')
                    <div class="bg-white rounded-3xl border border-gray-100 p-8 sm:p-12 text-center max-w-md mx-auto shadow-md">
                        <span class="inline-flex items-center justify-center size-14 rounded-2xl bg-gray-50 text-gray-400 mb-4 border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Informasi Belum Tersedia</h3>
                        <p class="text-xs text-gray-500">Pemilihan ini masih dalam status draf dan belum dibuka untuk publik.</p>
                    </div>
                @elseif($candidates->isEmpty())
                    <div class="bg-white rounded-3xl border border-gray-100 p-8 sm:p-12 text-center max-w-md mx-auto shadow-md">
                        <span class="inline-flex items-center justify-center size-14 rounded-2xl bg-gray-50 text-gray-400 mb-4 border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Paslon</h3>
                        <p class="text-xs text-gray-500">Belum ada pasangan calon yang terdaftar untuk pemilihan ini.</p>
                    </div>
                @else
                    <ol class="flex flex-wrap justify-center gap-8 lg:gap-10 list-none p-0 m-0 w-full" role="list">
                        @foreach($candidates as $candidate)
                            <li class="fade-up w-full max-w-[360px] flex justify-center">
                                <x-voter.candidate-card :candidate="$candidate" variant="landing" :index="$loop->index" />
                            </li>
                        @endforeach
                    </ol>

                    {{-- Visi & Misi Modals --}}
                    @foreach($candidates as $candidate)
                        <x-voter.visi-misi-modal :candidate="$candidate" />
                    @endforeach
                @endif
            </section>
        </main>
    </div>
@endsection
