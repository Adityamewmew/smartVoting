@extends('landing.layout')

@section('title', $election->name)

@section('content')
    {{-- ======================== HERO (bg-white) ======================== --}}
    <section class="bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                <div class="md:w-1/2 space-y-5">
                    <h1 class="text-3xl md:text-4xl font-bold leading-tight text-ink">
                        Ayo Pilih Kandidatmu
                    </h1>
                    <p class="text-pewter text-sm leading-relaxed max-w-md">
                        @if($election->description)
                            {{ $election->description }}
                        @else
                            Gunakan hak suara Anda untuk menentukan masa depan yang lebih baik. Pelajari visi misi setiap kandidat dan tentukan pilihan terbaik Anda.
                        @endif
                    </p>
                    <div class="inline-flex items-center gap-2 bg-cream-notice/60 text-brand-brown px-4 py-2 rounded-full text-xs font-semibold border border-brand-yellow/30">
                        <i class="fa-regular fa-calendar-check"></i>
                        <span>PELAKSANAAN: {{ strtoupper(\Carbon\Carbon::parse($election->start_time)->translatedFormat('l, d F Y')) }}</span>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div class="w-full max-w-sm h-[280px] md:h-[320px] rounded-[20px] overflow-hidden bg-vellum">
                        @if($candidates->count() > 0 && $candidates->first()->photo_path)
                            <img src="{{ Storage::url($candidates->first()->photo_path) }}"
                                 alt="Kandidat" class="w-full h-full object-cover object-center">
                        @else
                            <div class="flex items-center justify-center h-full text-ash">
                                <i class="fa-solid fa-users text-5xl"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($election->status === 'draft')
        <section class="bg-vellum">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="landing-card p-10 text-center">
                    <i class="fa-solid fa-lock text-3xl text-ash mb-3"></i>
                    <h3 class="text-base font-semibold text-ink mb-1">Informasi Belum Tersedia</h3>
                    <p class="text-pewter text-sm">Pemilihan ini belum dibuka untuk publik.</p>
                </div>
            </div>
        </section>
    @else
        {{-- ======================== KANDIDAT (bg-carbon/dark) ======================== --}}
        <section id="kandidat" class="bg-carbon">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center mb-10">
                    <h2 class="text-xl font-bold text-white">Pasangan Calon</h2>
                    <p class="text-white/50 text-sm mt-1">Klik untuk melihat visi & misi</p>
                </div>
                <div class="flex flex-col md:flex-row justify-center gap-6 md:gap-8">
                    @foreach($candidates as $candidate)
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-[20px] p-4 w-full md:w-64 flex flex-col items-center group cursor-pointer transition-transform duration-300 hover:-translate-y-1 hover:bg-white/15"
                             onclick="document.getElementById('paslon-{{ $candidate->id }}').scrollIntoView({behavior:'smooth'})">

                            <div class="relative w-full h-[260px] rounded-2xl overflow-hidden mb-4 bg-white/5">
                                <div class="absolute top-2 left-2 z-10 bg-brand-yellow text-white px-3 py-0.5 rounded-full text-[10px] font-bold tracking-wider">
                                    PASLON {{ str_pad($candidate->order_number, 2, '0', STR_PAD_LEFT) }}
                                </div>
                                @if($candidate->photo_path)
                                    <img src="{{ Storage::url($candidate->photo_path) }}"
                                         alt="Foto {{ $candidate->chairman_name }}"
                                         class="w-full h-full object-cover object-center">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <span class="text-white/30 font-semibold text-lg">Foto Belum Tersedia</span>
                                    </div>
                                @endif
                            </div>

                            <h3 class="text-sm font-semibold text-white text-center leading-tight">
                                {{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}
                            </h3>
                            <p class="text-[10px] uppercase tracking-widest text-white/40 font-medium mt-1">Calon Ketua & Wakil Ketua</p>

                            <button class="mt-4 inline-flex items-center gap-1.5 text-xs font-medium text-white/60 bg-white/10 hover:bg-white/20 px-4 py-1.5 rounded-full transition-colors">
                                <i class="fa-regular fa-eye text-[10px]"></i> Lihat Profil
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ======================== VISI & MISI (bg-vellum) ======================== --}}
        <section id="visi-misi" class="bg-vellum">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center mb-10">
                    <h2 class="text-xl font-bold text-ink">Visi & Misi Kandidat</h2>
                    <p class="text-pewter text-sm mt-1">Pelajari lebih lanjut tentang program kerja masing-masing paslon.</p>
                </div>

                @foreach($candidates as $candidate)
                    @php $isEven = $loop->even; @endphp
                    <div class="mb-10" id="paslon-{{ $candidate->id }}">
                        <div class="flex flex-col md:flex-row items-stretch gap-6 {{ $isEven ? 'md:flex-row-reverse' : '' }}">
                            {{-- Profile Card --}}
                            <div class="md:w-1/3 landing-card p-6 flex flex-col items-center justify-center text-center">
                                <div class="relative mb-3">
                                    <div class="absolute -top-2 -left-2 z-10 bg-brand-yellow text-white px-3 py-0.5 rounded-full text-[10px] font-bold tracking-wider">
                                        PASLON {{ str_pad($candidate->order_number, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-white shadow-sm bg-white">
                                        @if($candidate->photo_path)
                                            <img src="{{ Storage::url($candidate->photo_path) }}"
                                                 alt="{{ $candidate->chairman_name }}"
                                                 class="w-full h-full object-cover object-center">
                                        @else
                                            <div class="flex items-center justify-center h-full text-ash">
                                                <i class="fa-solid fa-user text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <h3 class="text-sm font-bold text-ink">{{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}</h3>
                                <p class="text-[10px] text-ash uppercase tracking-widest mt-0.5">Calon Ketua & Wakil Ketua</p>
                            </div>

                            {{-- Visi Misi Card --}}
                            <div class="md:w-2/3">
                                @if($candidate->vision || $candidate->mission)
                                    <div class="landing-card p-6 h-full">
                                        @if($candidate->vision)
                                            <div class="mb-5">
                                                <h4 class="text-xs font-bold text-brand-brown uppercase tracking-widest mb-2 flex items-center gap-2">
                                                    <i class="fa-solid fa-compass text-brand-yellow"></i> Visi
                                                </h4>
                                                <p class="text-sm text-ink italic leading-relaxed">"{{ $candidate->vision }}"</p>
                                            </div>
                                        @endif

                                        @if($candidate->mission)
                                            <div>
                                                <h4 class="text-xs font-bold text-brand-brown uppercase tracking-widest mb-3 flex items-center gap-2">
                                                    <i class="fa-solid fa-clipboard-list text-brand-yellow"></i> Misi Utama
                                                </h4>
                                                @php
                                                    $missionItems = json_decode($candidate->mission, true)
                                                        ?? array_values(array_filter(array_map('trim', explode("\n", $candidate->mission))));
                                                    $useParagraph = count($missionItems) === 1 && strlen($missionItems[0]) > 150;
                                                @endphp
                                                @if($useParagraph)
                                                    <p class="text-sm text-pewter leading-relaxed">{{ $missionItems[0] }}</p>
                                                @else
                                                    <ul class="space-y-2.5">
                                                        @foreach($missionItems as $item)
                                                            <li class="flex items-start gap-2.5">
                                                                <span class="mt-0.5 w-5 h-5 rounded-full bg-brand-yellow/20 text-brand-yellow flex items-center justify-center flex-shrink-0">
                                                                    <i class="fa-solid fa-check text-[8px]"></i>
                                                                </span>
                                                                <span class="text-sm text-pewter leading-relaxed">{{ trim($item) }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="landing-card p-8 h-full flex flex-col items-center justify-center text-center">
                                        <i class="fa-regular fa-file-circle-xmark text-3xl text-ash mb-3"></i>
                                        <h4 class="text-sm font-semibold text-ink mb-1">Data Belum Lengkap</h4>
                                        <p class="text-xs text-pewter">Visi & Misi belum ditambahkan oleh kandidat. Silakan cek kembali nanti.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($candidates->isEmpty())
                    <div class="landing-card p-10 text-center">
                        <i class="fa-solid fa-users text-3xl text-ash mb-3"></i>
                        <p class="text-pewter text-sm">Belum ada kandidat yang terdaftar untuk pemilihan ini.</p>
                    </div>
                @endif
            </div>
        </section>
    @endif
@endsection
