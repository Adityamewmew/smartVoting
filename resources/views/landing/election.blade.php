@extends('landing.layout')

@section('title', $election->name)

@section('content')
    {{-- Hero Header --}}
    <header class="text-center pb-8">
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 mb-2 uppercase">
            {{ $election->name }}
        </h1>
        <p class="text-slate-400 text-sm mt-1">Kenali pasangan calon sebelum memberikan suaramu.</p>
        @if($election->description)
            <p class="text-slate-500 text-sm mt-2 max-w-xl mx-auto">{{ $election->description }}</p>
        @endif
    </header>

    @if($election->status === 'draft')
        <div class="bg-white/70 backdrop-blur-xl rounded-xl p-10 text-center border border-slate-200 shadow-md">
            <i class="fa-solid fa-lock text-3xl text-slate-300 mb-3"></i>
            <h3 class="text-base font-semibold text-slate-700 mb-1">Informasi Belum Tersedia</h3>
            <p class="text-slate-500 text-sm">Pemilihan ini belum dibuka untuk publik.</p>
        </div>
    @else
        {{-- === SECTION 1: Grid Overview Kandidat === --}}
        <div class="bg-white/70 backdrop-blur-xl rounded-xl p-6 md:p-8 mb-10 shadow-md border border-slate-200 flex flex-col items-center">
            {{-- Tanggal Pelaksanaan --}}
            <div class="mb-8 bg-yellow-400 text-slate-900 px-6 py-2 rounded-md inline-flex items-center gap-2 text-sm font-semibold tracking-wide shadow-md hover:-translate-y-0.5 transition-transform duration-300">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Pelaksanaan: <strong class="font-bold uppercase">{{ \Carbon\Carbon::parse($election->start_time)->translatedFormat('l, d F Y') }}</strong></span>
            </div>

            {{-- Jajaran Kandidat --}}
            <div class="w-full flex flex-col md:flex-row justify-around items-start gap-8 md:gap-4">
                @foreach($candidates as $candidate)
                    <div class="text-center flex flex-col items-center cursor-pointer w-full md:w-1/{{ min(count($candidates), 3) }} group"
                         onclick="document.getElementById('paslon-{{ $candidate->id }}').scrollIntoView({behavior:'smooth'})">

                        {{-- Foto / Placeholder dengan badge overlay --}}
                        <div class="relative w-full h-[260px] md:h-[300px] rounded-xl overflow-hidden mb-4 shadow-sm bg-slate-100 transition-transform duration-300 group-hover:scale-[1.02] group-hover:shadow-md">
                            {{-- Badge nomor: overlay pojok kiri atas --}}
                            <div class="absolute top-3 left-3 z-10 bg-yellow-400 text-slate-900 px-3 py-1 rounded-md text-xs font-bold tracking-widest shadow-sm">
                                PASLON {{ str_pad($candidate->order_number, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            @if($candidate->photo_path)
                                <img src="{{ Storage::url($candidate->photo_path) }}"
                                     alt="Foto Paslon {{ $candidate->order_number }}"
                                     class="w-full h-full object-cover object-center">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <span class="text-slate-300 font-bold text-2xl tracking-tighter">
                                        PASLON {{ str_pad($candidate->order_number, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="w-full px-2">
                            <p class="text-slate-400 text-[10px] uppercase tracking-widest font-semibold mb-0.5">Calon Ketua</p>
                            <h3 class="text-slate-900 font-semibold text-base leading-tight mb-2">{{ $candidate->chairman_name }}</h3>
                            <p class="text-slate-400 text-[10px] uppercase tracking-widest font-semibold mb-0.5">Calon Wakil</p>
                            <h3 class="text-slate-600 font-medium text-sm leading-tight">{{ $candidate->vice_chairman_name }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- === SECTION 2: Detail Per Paslon === --}}
        @foreach($candidates as $candidate)
            <div class="flex flex-col md:flex-row bg-white/80 backdrop-blur-md rounded-xl shadow-md overflow-hidden mb-10 border border-slate-200"
                 id="paslon-{{ $candidate->id }}">

                {{-- Kiri: Foto + Nama --}}
                <div class="md:w-5/12 relative bg-slate-50/80 flex flex-col p-6 items-center justify-center border-r border-slate-200">
                    <div class="absolute top-6 left-6 bg-yellow-400 text-slate-900 px-4 py-1 rounded-md font-bold text-sm shadow-sm z-20 tracking-wider">
                        {{ str_pad($candidate->order_number, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="w-full h-[300px] md:h-[340px] rounded-lg overflow-hidden shadow-sm relative mt-10 mb-6 bg-slate-100">
                        @if($candidate->photo_path)
                            <img src="{{ Storage::url($candidate->photo_path) }}"
                                 alt="Foto Paslon {{ $candidate->order_number }}"
                                 class="w-full h-full object-cover object-center">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <span class="text-slate-300 font-bold text-3xl tracking-tighter">
                                    PASLON {{ str_pad($candidate->order_number, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="text-center w-full space-y-2">
                        <div class="bg-white p-4 rounded-lg border border-slate-200">
                            <p class="text-slate-400 font-semibold text-[10px] uppercase tracking-widest mb-0.5">Calon Ketua</p>
                            <h2 class="text-base font-semibold text-slate-900">{{ $candidate->chairman_name }}</h2>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-200">
                            <p class="text-slate-400 font-semibold text-[10px] uppercase tracking-widest mb-0.5">Calon Wakil</p>
                            <h2 class="text-sm font-medium text-slate-700">{{ $candidate->vice_chairman_name }}</h2>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Visi & Misi --}}
                <div class="md:w-7/12 p-6 lg:p-10 flex flex-col justify-center bg-white/90">
                    @if($candidate->vision)
                        <div class="mb-8 border-l-4 border-yellow-400 pl-5 py-1">
                            <h3 class="text-yellow-600 font-semibold uppercase tracking-widest text-xs mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-compass"></i> Visi
                            </h3>
                            <p class="text-slate-700 text-lg md:text-xl font-medium italic leading-relaxed">
                                "{{ $candidate->vision }}"
                            </p>
                        </div>
                    @endif

                    @if($candidate->mission)
                        @php
                            // ponytail: try JSON first, fallback to newline split
                            $missionItems = json_decode($candidate->mission, true)
                                ?? array_values(array_filter(array_map('trim', explode("\n", $candidate->mission))));
                            // if single long paragraph, skip list treatment
                            $useParagraph = count($missionItems) === 1 && strlen($missionItems[0]) > 150;
                        @endphp
                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                            <h3 class="text-yellow-600 font-semibold uppercase tracking-widest text-xs mb-5 flex items-center gap-2">
                                <i class="fa-solid fa-list-check"></i> Misi Utama
                            </h3>
                            @if($useParagraph)
                                <p class="text-sm leading-relaxed text-slate-700">{{ $missionItems[0] }}</p>
                            @else
                                <ul class="space-y-4">
                                    @foreach($missionItems as $item)
                                        <li class="flex items-start gap-3 text-slate-700">
                                            <div class="mt-0.5 w-7 h-7 rounded-md bg-yellow-400 text-slate-900 flex items-center justify-center flex-shrink-0">
                                                <i class="fa-solid fa-check text-sm"></i>
                                            </div>
                                            <span class="text-sm leading-relaxed">{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if($candidates->isEmpty())
            <div class="bg-white/70 backdrop-blur-xl rounded-xl p-10 text-center border border-dashed border-slate-300">
                <i class="fa-solid fa-users text-3xl text-slate-300 mb-3"></i>
                <p class="text-slate-500 text-sm">Belum ada kandidat yang terdaftar untuk pemilihan ini.</p>
            </div>
        @endif
    @endif
@endsection
