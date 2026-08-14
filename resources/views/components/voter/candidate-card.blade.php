@props(['candidate', 'variant' => 'landing', 'index' => 0])

@php
    $pad   = str_pad($candidate->order_number, 2, '0', STR_PAD_LEFT);
    $booth = ($variant === 'booth');
    $delay = ($index % 4) * 0.1;
@endphp

<div class="sk-surface {{ $booth ? 'sk-card-outer p-5' : 'rounded-2xl p-3.5 sm:p-4 max-w-[270px] sm:max-w-[290px] lg:max-w-[310px]' }} sk-fade-in-up flex flex-col w-full group transition-transform duration-300 hover:-translate-y-1" style="animation-delay: {{ $delay }}s;">

    {{-- Photo --}}
    <div class="relative {{ $booth ? 'sk-card-inner aspect-[3/4] mb-5' : 'rounded-xl aspect-[3/3.6] max-h-[38vh] mb-3' }} overflow-hidden bg-slate-100">
        <span class="sk-badge absolute top-2.5 left-2.5 z-10 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider">
            PASLON {{ $pad }}
        </span>
        @if($candidate->photo_path)
            <img src="{{ Storage::url($candidate->photo_path) }}"
                 alt="Foto {{ $candidate->chairman_name }}"
                 class="w-full h-full object-cover object-center">
        @else
            <div class="flex items-center justify-center h-full text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="{{ $booth ? 'size-16' : 'size-12' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
        @endif
    </div>

    {{-- Names --}}
    <div class="text-center px-1 {{ $booth ? 'mb-5' : 'mb-3' }}">
        <h3 class="{{ $booth ? 'text-lg' : 'text-sm sm:text-base' }} font-bold leading-tight text-slate-900">{{ $candidate->chairman_name }}</h3>
        <span class="block {{ $booth ? 'text-sm my-0.5' : 'text-xs my-0' }} font-medium text-slate-400">&amp;</span>
        <h3 class="{{ $booth ? 'text-lg' : 'text-sm sm:text-base' }} font-bold leading-tight text-slate-900">{{ $candidate->vice_chairman_name }}</h3>
        <p class="mt-1 {{ $booth ? 'text-[10px]' : 'text-[9px]' }} uppercase tracking-widest text-slate-500 font-semibold">Calon Ketua &amp; Wakil Ketua</p>
    </div>

    {{-- Actions --}}
    <div class="mt-auto flex flex-col gap-2">
        <button type="button"
                data-open="visi-misi-{{ $candidate->id }}"
                class="sk-btn-ghost {{ $booth ? 'rounded-xl px-4 py-2.5 text-sm' : 'rounded-lg px-3 py-1.5 text-xs' }} font-semibold inline-flex items-center justify-center gap-1.5 cursor-pointer w-full active:scale-95 transition-all duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            Lihat Visi &amp; Misi
        </button>

        @if($booth)
            <button type="button"
                    onclick="confirmVote({{ $candidate->id }}, '{{ $candidate->order_number }}', '{{ addslashes($candidate->chairman_name) }}', '{{ addslashes($candidate->vice_chairman_name) }}')"
                    class="sk-btn-primary rounded-xl px-4 py-3 text-sm font-bold uppercase tracking-wide inline-flex items-center justify-center gap-2 cursor-pointer w-full active:scale-95 transition-all duration-150 shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/35">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Pilih Kandidat {{ $candidate->order_number }}
            </button>
        @endif
    </div>
</div>
