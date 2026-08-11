@extends('landing.layout')

@section('title', $election->name)

@section('content')
    <div class="bg-paper rounded-[20px] shadow-none border border-graphite-hairline overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex items-start justify-between flex-wrap gap-4 mb-4">
                <h1 class="text-3xl font-normal text-ink tracking-tight">{{ $election->name }}</h1>
                
                @php
                    $statusLabels = [
                        'draft' => 'Draft',
                        'scheduled' => 'Terjadwal',
                        'active' => 'Sedang Berlangsung',
                        'closed' => 'Ditutup',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-normal border border-graphite-hairline bg-vellum text-ink">
                    {{ $statusLabels[$election->status] ?? ucfirst($election->status) }}
                </span>
            </div>

            @if($election->description)
                <p class="text-slate text-lg font-normal leading-relaxed mb-6">{{ $election->description }}</p>
            @endif

            <div class="flex items-center gap-6 text-sm text-slate bg-vellum p-4 rounded-xl border border-graphite-hairline">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Mulai: <span class="font-normal text-ink">{{ \Carbon\Carbon::parse($election->start_time)->translatedFormat('d F Y, H:i') }}</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Selesai: <span class="font-normal text-ink">{{ \Carbon\Carbon::parse($election->end_time)->translatedFormat('d F Y, H:i') }}</span></span>
                </div>
            </div>
        </div>
    </div>

    @if($election->status === 'draft')
        <div class="bg-paper border border-graphite-hairline rounded-[20px] p-8 text-center text-ink">
            <svg class="w-12 h-12 mx-auto mb-4 text-slate" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <h3 class="text-lg font-normal mb-1">Informasi Belum Tersedia</h3>
            <p class="text-slate font-normal">Pemilihan ini belum dibuka untuk publik.</p>
        </div>
    @else
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-normal text-ink">Kandidat Pasangan Calon</h2>
            <span class="border border-graphite-hairline bg-vellum text-ink text-sm font-normal px-3 py-1 rounded-full">{{ count($candidates) }} Paslon</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($candidates as $candidate)
                <div class="bg-paper rounded-[20px] shadow-none border border-graphite-hairline overflow-hidden hover:border-ink transition-colors">
                    <div class="bg-vellum border-b border-graphite-hairline p-6 flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-paper rounded-full flex items-center justify-center text-2xl font-normal text-ink border border-graphite-hairline mb-4">
                            {{ $candidate->order_number }}
                        </div>
                        <h3 class="text-xl font-normal text-ink mb-1">{{ $candidate->chairman_name }}</h3>
                        <p class="text-sm font-normal text-slate">Calon Ketua</p>
                        
                        <div class="w-8 h-px bg-graphite-hairline my-4"></div>
                        
                        <h3 class="text-xl font-normal text-ink mb-1">{{ $candidate->vice_chairman_name }}</h3>
                        <p class="text-sm font-normal text-slate">Calon Wakil</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-vellum border border-dashed border-graphite-hairline rounded-[20px] p-12 text-center text-slate font-normal">
                    Belum ada kandidat yang terdaftar untuk pemilihan ini.
                </div>
            @endforelse
        </div>
    @endif
@endsection
