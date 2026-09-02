@props(['candidate', 'variant' => 'landing', 'index' => 0])

@php
    $pad   = str_pad($candidate->order_number, 2, '0', STR_PAD_LEFT);
    $booth = ($variant === 'booth');
    $delay = ($index % 4) * 0.07;
@endphp

@if(!$booth)
    {{-- Landing Variant with Skeuomorphic Depth & Structured Names --}}
    <article class="candidate-card group bg-gradient-to-b from-white via-white to-slate-50/70 rounded-[28px] border border-slate-200/90 overflow-hidden flex flex-col h-full w-full max-w-[360px] shadow-[0_10px_30px_-8px_rgba(15,23,42,0.06),inset_0_1px_0_rgba(255,255,255,1)] hover:shadow-[0_20px_40px_-12px_rgba(15,23,42,0.12),inset_0_1px_0_rgba(255,255,255,1)] hover:-translate-y-1.5 transition-all duration-300" style="animation-delay: {{ $delay }}s;" aria-labelledby="paslon-{{ $pad }}-title">

        {{-- Card Header: Nomor & Label Paslon --}}
        <div class="px-5 pt-4 pb-3 flex items-center justify-between border-b border-slate-100/80">
            <div class="flex items-center gap-3">
                <span class="size-9 sm:size-10 rounded-2xl bg-gradient-to-b from-blue-500 to-blue-600 text-white font-black text-sm flex items-center justify-center shadow-md shadow-blue-500/25 border-t border-white/30" aria-label="Nomor urut {{ $pad }}">{{ $pad }}</span>
                <div>
                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest block">Nomor Urut</span>
                    <h2 id="paslon-{{ $pad }}-title" class="text-sm sm:text-base font-bold text-slate-900 leading-tight m-0">Pasangan Calon {{ $pad }}</h2>
                </div>
            </div>
            <span class="size-2 rounded-full bg-slate-200 group-hover:bg-blue-500 transition-colors"></span>
        </div>

        {{-- Candidate photos: 2 foto (Ketua & Wakil) --}}
        <figure class="relative m-0 px-4 pt-4">
            <div class="grid grid-cols-2 gap-3">
                {{-- Foto Calon Ketua --}}
                <div class="relative overflow-hidden rounded-2xl bg-slate-100 border border-slate-200/80 shadow-inner group-hover:border-blue-200 aspect-[4/5] transition-colors">
                    @if($candidate->photo_path)
                        <img src="{{ Storage::url($candidate->photo_path) }}"
                             alt="Foto Calon Ketua Paslon {{ $pad }}"
                             class="w-full h-full object-cover rounded-2xl transition-transform duration-500 group-hover:scale-105"
                             loading="eager" />
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                            <svg class="size-8 mb-1 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <span class="text-[10px] font-bold text-slate-400">Ketua</span>
                        </div>
                    @endif
                    <span class="absolute bottom-2 inset-x-2 text-center text-[10px] font-bold tracking-wider uppercase text-slate-800 bg-white/90 backdrop-blur-md py-0.5 rounded-lg border border-white/80 shadow-xs">
                        Ketua
                    </span>
                </div>

                {{-- Foto Calon Wakil --}}
                <div class="relative overflow-hidden rounded-2xl bg-slate-100 border border-slate-200/80 shadow-inner group-hover:border-blue-200 aspect-[4/5] transition-colors">
                    @if(!empty($candidate->vice_chairman_photo_path))
                        <img src="{{ Storage::url($candidate->vice_chairman_photo_path) }}"
                             alt="Foto Calon Wakil Paslon {{ $pad }}"
                             class="w-full h-full object-cover rounded-2xl transition-transform duration-500 group-hover:scale-105"
                             loading="eager" />
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                            <svg class="size-8 mb-1 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <span class="text-[10px] font-bold text-slate-400">Wakil</span>
                        </div>
                    @endif
                    <span class="absolute bottom-2 inset-x-2 text-center text-[10px] font-bold tracking-wider uppercase text-slate-800 bg-white/90 backdrop-blur-md py-0.5 rounded-lg border border-white/80 shadow-xs">
                        Wakil
                    </span>
                </div>
            </div>
        </figure>

        {{-- Candidate Info & Structured Names --}}
        <div class="px-5 pb-5 pt-3 flex flex-col flex-grow">
            <div class="grid grid-cols-2 gap-2 text-center bg-slate-50/80 p-2.5 rounded-xl border border-slate-100 mb-4">
                <div class="truncate px-1">
                    <p class="text-xs font-bold text-slate-900 truncate leading-snug m-0" title="{{ $candidate->chairman_name }}">{{ $candidate->chairman_name ?: '-' }}</p>
                    <p class="text-[10px] text-slate-500 font-medium m-0 mt-0.5">Calon Ketua</p>
                </div>
                <div class="truncate border-l border-slate-200/80 pl-2 pr-1">
                    <p class="text-xs font-bold text-slate-900 truncate leading-snug m-0" title="{{ $candidate->vice_chairman_name }}">{{ $candidate->vice_chairman_name ?: '-' }}</p>
                    <p class="text-[10px] text-slate-500 font-medium m-0 mt-0.5">Calon Wakil</p>
                </div>
            </div>

            {{-- Tactile CTA Button --}}
            <button type="button"
                    data-open="visi-misi-{{ $candidate->id }}"
                    class="w-full mt-auto py-2.5 px-4 rounded-xl bg-gradient-to-b from-white to-slate-50 hover:from-blue-50 hover:to-blue-100/60 text-blue-700 hover:text-blue-800 border border-blue-200/90 shadow-[0_2px_4px_rgba(0,0,0,0.02),inset_0_1px_0_rgba(255,255,255,1)] hover:shadow-xs font-bold text-xs sm:text-sm inline-flex items-center justify-center gap-2 transition-all active:scale-[0.98] cursor-pointer"
                    aria-haspopup="dialog"
                    aria-controls="visi-misi-{{ $candidate->id }}">
                <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/>
                </svg>
                Lihat Visi &amp; Misi
            </button>
        </div>
    </article>
@else
    {{-- Kiosk Booth Variant (matching bilik-suara.html with full card touch trigger) --}}
    <article class="kiosk-card bg-white rounded-[28px] border border-slate-200/90 overflow-hidden flex flex-col h-full w-full max-w-[380px] cursor-pointer group hover:ring-2 hover:ring-blue-500/40 active:scale-[0.99] transition-all shadow-md"
             onclick="confirmVote({{ $candidate->id }}, '{{ $candidate->order_number }}', '{{ addslashes($candidate->chairman_name) }}', '{{ addslashes($candidate->vice_chairman_name) }}')"
             role="button"
             tabindex="0"
             aria-labelledby="paslon-{{ $pad }}-heading">

        {{-- Header Card --}}
        <header class="px-6 pt-5 pb-3 flex items-center justify-between border-b border-slate-100 group-hover:bg-blue-50/20 transition-colors">
            <div class="flex items-center gap-3">
                <span class="size-10 rounded-2xl bg-gradient-to-b from-blue-500 to-blue-600 text-white font-black text-sm flex items-center justify-center shadow-md shadow-blue-500/25 border-t border-white/30" aria-label="Nomor urut {{ $pad }}">{{ $pad }}</span>
                <div>
                    <h2 id="paslon-{{ $pad }}-heading" class="text-base font-bold text-slate-900 leading-tight">Pasangan Calon {{ $pad }}</h2>
                </div>
            </div>
        </header>

        {{-- Candidate photos: 2 foto (Ketua & Wakil) with figcaption --}}
        <figure class="m-0 p-4 pb-2">
            <div class="grid grid-cols-2 gap-3">
                {{-- Calon Ketua --}}
                <div class="flex flex-col items-center text-center">
                    <div class="w-full relative overflow-hidden rounded-2xl bg-slate-100 aspect-[4/5] mb-2 border border-slate-100">
                        @if($candidate->photo_path)
                            <img src="{{ Storage::url($candidate->photo_path) }}"
                                 alt="Foto Calon Ketua {{ $candidate->chairman_name }}"
                                 class="w-full h-full object-cover rounded-2xl transition-transform duration-300 group-hover:scale-105"
                                 loading="eager" />
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                                <svg class="size-8 mb-1 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span class="text-[10px] font-bold">Ketua</span>
                            </div>
                        @endif
                    </div>
                    <figcaption class="w-full px-1">
                        <p class="text-xs font-bold text-slate-900 leading-snug truncate m-0">{{ $candidate->chairman_name }}</p>
                        <p class="text-[11px] text-slate-500 font-medium m-0">Calon Ketua</p>
                    </figcaption>
                </div>

                {{-- Calon Wakil --}}
                <div class="flex flex-col items-center text-center">
                    <div class="w-full relative overflow-hidden rounded-2xl bg-slate-100 aspect-[4/5] mb-2 border border-slate-100">
                        @if(!empty($candidate->vice_chairman_photo_path))
                            <img src="{{ Storage::url($candidate->vice_chairman_photo_path) }}"
                                 alt="Foto Calon Wakil {{ $candidate->vice_chairman_name }}"
                                 class="w-full h-full object-cover rounded-2xl transition-transform duration-300 group-hover:scale-105"
                                 loading="eager" />
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                                <svg class="size-8 mb-1 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span class="text-[10px] font-bold">Wakil</span>
                            </div>
                        @endif
                    </div>
                    <figcaption class="w-full px-1">
                        <p class="text-xs font-bold text-slate-900 leading-snug truncate m-0">{{ $candidate->vice_chairman_name ?: '-' }}</p>
                        <p class="text-[11px] text-slate-500 font-medium m-0">Calon Wakil</p>
                    </figcaption>
                </div>
            </div>
        </figure>

        <div class="p-4 sm:p-6 pt-2 flex flex-col flex-grow">
            <div class="mt-auto space-y-2.5">
                <button type="button"
                        class="btn-primary w-full py-3 text-sm font-bold uppercase tracking-wider group-hover:brightness-105 pointer-events-none">
                    PILIH PASLON {{ $pad }}
                </button>
            </div>
        </div>
    </article>
@endif
