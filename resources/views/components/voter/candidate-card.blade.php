@props(['candidate', 'variant' => 'landing', 'index' => 0])

@php
    $pad   = str_pad($candidate->order_number, 2, '0', STR_PAD_LEFT);
    $booth = ($variant === 'booth');
    $delay = ($index % 4) * 0.07;
@endphp

@if(!$booth)
    {{-- Landing Variant (matching landing_slug.html) --}}
    <article class="candidate-card bg-white rounded-[32px] border border-gray-100/90 overflow-hidden flex flex-col h-full w-full max-w-[360px]" style="animation-delay: {{ $delay }}s;" aria-labelledby="paslon-{{ $pad }}-title">

        {{-- Card Header: Nomor & Label Paslon --}}
        <div class="px-6 pt-5 pb-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="num-badge w-10 h-10 rounded-2xl flex items-center justify-center text-white font-black text-sm" aria-label="Nomor urut {{ $pad }}">{{ $pad }}</span>
                <div>
                    <h2 id="paslon-{{ $pad }}-title" class="text-base font-bold text-gray-900 leading-tight">Pasangan Calon {{ $pad }}</h2>
                </div>
            </div>
        </div>

        {{-- Candidate photos: 2 foto (Ketua & Wakil) --}}
        <figure class="relative m-0 px-4">
            <div class="grid grid-cols-2 gap-3">
                {{-- Foto Calon Ketua --}}
                <div class="relative overflow-hidden rounded-2xl bg-gray-100 group aspect-[4/5]">
                    @if($candidate->photo_path)
                        <img src="{{ Storage::url($candidate->photo_path) }}"
                             alt="Foto Calon Ketua Paslon {{ $pad }}"
                             class="w-full h-full object-cover rounded-2xl transition-transform duration-300 group-hover:scale-105"
                             loading="eager" />
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                            <svg class="size-8 mb-1 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <span class="text-[10px] font-bold">Ketua</span>
                        </div>
                    @endif
                    <span class="absolute bottom-2.5 inset-x-2.5 text-center text-xs font-bold tracking-wider uppercase text-white bg-slate-900/65 backdrop-blur-md py-1 rounded-lg">
                        Ketua
                    </span>
                </div>

                {{-- Foto Calon Wakil --}}
                <div class="relative overflow-hidden rounded-2xl bg-gray-100 group aspect-[4/5]">
                    @if(!empty($candidate->vice_chairman_photo_path))
                        <img src="{{ Storage::url($candidate->vice_chairman_photo_path) }}"
                             alt="Foto Calon Wakil Paslon {{ $pad }}"
                             class="w-full h-full object-cover rounded-2xl transition-transform duration-300 group-hover:scale-105"
                             loading="eager" />
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                            <svg class="size-8 mb-1 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <span class="text-[10px] font-bold">Wakil</span>
                        </div>
                    @endif
                    <span class="absolute bottom-2.5 inset-x-2.5 text-center text-xs font-bold tracking-wider uppercase text-white bg-slate-900/65 backdrop-blur-md py-1 rounded-lg">
                        Wakil
                    </span>
                </div>
            </div>
        </figure>

        {{-- Candidate Info & CTA Button --}}
        <div class="px-6 pb-6 flex flex-col flex-grow pt-4">
            @if(!empty($candidate->chairman_name) || !empty($candidate->vice_chairman_name))
                <div class="text-center mb-3">
                    <p class="text-sm font-bold text-gray-900 leading-snug m-0">
                        {{ $candidate->chairman_name }}
                        @if(!empty($candidate->vice_chairman_name))
                            <span class="text-xs text-gray-400 font-semibold mx-1">&amp;</span>
                            {{ $candidate->vice_chairman_name }}
                        @endif
                    </p>
                </div>
            @endif

            <button type="button"
                    data-open="visi-misi-{{ $candidate->id }}"
                    class="btn-primary mt-auto"
                    aria-haspopup="dialog"
                    aria-controls="visi-misi-{{ $candidate->id }}">
                <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/>
                </svg>
                Lihat Visi &amp; Misi
            </button>
        </div>
    </article>
@else
    {{-- Kiosk Booth Variant (matching bilik-suara.html with full card touch trigger) --}}
    <article class="kiosk-card bg-white rounded-[28px] border border-gray-100 overflow-hidden flex flex-col h-full w-full max-w-[380px] cursor-pointer group hover:ring-2 hover:ring-blue-500/40 active:scale-[0.99] transition-all"
             onclick="confirmVote({{ $candidate->id }}, '{{ $candidate->order_number }}', '{{ addslashes($candidate->chairman_name) }}', '{{ addslashes($candidate->vice_chairman_name) }}')"
             role="button"
             tabindex="0"
             aria-labelledby="paslon-{{ $pad }}-heading">

        {{-- Header Card --}}
        <header class="px-6 pt-5 pb-3 flex items-center justify-between border-b border-gray-50 group-hover:bg-blue-50/20 transition-colors">
            <div class="flex items-center gap-3">
                <span class="num-badge w-10 h-10 rounded-2xl flex items-center justify-center text-white font-black text-sm" aria-label="Nomor urut {{ $pad }}">{{ $pad }}</span>
                <div>
                    <h2 id="paslon-{{ $pad }}-heading" class="text-base font-bold text-gray-900 leading-tight">Pasangan Calon {{ $pad }}</h2>
                </div>
            </div>
        </header>

        {{-- Candidate photos: 2 foto (Ketua & Wakil) with figcaption --}}
        <figure class="m-0 p-4 pb-2">
            <div class="grid grid-cols-2 gap-3">
                {{-- Calon Ketua --}}
                <div class="flex flex-col items-center text-center">
                    <div class="w-full relative overflow-hidden rounded-2xl bg-gray-100 aspect-[4/5] mb-2 border border-gray-100">
                        @if($candidate->photo_path)
                            <img src="{{ Storage::url($candidate->photo_path) }}"
                                 alt="Foto Calon Ketua {{ $candidate->chairman_name }}"
                                 class="w-full h-full object-cover rounded-2xl transition-transform duration-300 group-hover:scale-105"
                                 loading="eager" />
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                <svg class="size-8 mb-1 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span class="text-[10px] font-bold">Ketua</span>
                            </div>
                        @endif
                    </div>
                    <figcaption class="w-full px-1">
                        <p class="text-xs font-bold text-gray-900 leading-snug truncate m-0">{{ $candidate->chairman_name }}</p>
                        <p class="text-[11px] text-gray-500 font-medium m-0">Calon Ketua</p>
                    </figcaption>
                </div>

                {{-- Calon Wakil --}}
                <div class="flex flex-col items-center text-center">
                    <div class="w-full relative overflow-hidden rounded-2xl bg-gray-100 aspect-[4/5] mb-2 border border-gray-100">
                        @if(!empty($candidate->vice_chairman_photo_path))
                            <img src="{{ Storage::url($candidate->vice_chairman_photo_path) }}"
                                 alt="Foto Calon Wakil {{ $candidate->vice_chairman_name }}"
                                 class="w-full h-full object-cover rounded-2xl transition-transform duration-300 group-hover:scale-105"
                                 loading="eager" />
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                <svg class="size-8 mb-1 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span class="text-[10px] font-bold">Wakil</span>
                            </div>
                        @endif
                    </div>
                    <figcaption class="w-full px-1">
                        <p class="text-xs font-bold text-gray-900 leading-snug truncate m-0">{{ $candidate->vice_chairman_name ?: '-' }}</p>
                        <p class="text-[11px] text-gray-500 font-medium m-0">Calon Wakil</p>
                    </figcaption>
                </div>
            </div>
        </figure>

        <div class="p-5 pt-2 flex flex-col flex-grow">
            <div class="mt-auto space-y-2.5">
                <button type="button"
                        class="btn-primary w-full py-3 text-sm font-bold uppercase tracking-wider group-hover:brightness-105 pointer-events-none">
                    PILIH PASLON {{ $pad }}
                </button>
            </div>
        </div>
    </article>
@endif
