@extends('_admin._layout.operator')

@section('title', 'Manajemen Bilik Suara')

@section('content')
    @php
        $totalElections = count($data);
        $totalVotesAll = collect($data)->sum('total_votes');
        $activeSessionsAll = collect($data)->sum('active_sessions');
    @endphp

    {{-- Top Header Section --}}
    <div class="px-4 sm:px-8 pt-6 sm:pt-8 max-w-7xl mx-auto">
        <x-admin.card class="p-6 sm:p-8 relative overflow-hidden">
            {{-- Ambient radial background glow --}}
            <div class="absolute -top-24 -right-24 size-96 rounded-full bg-blue-500/5 blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative z-10">
                <div class="flex items-center gap-3.5">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl shadow-md shadow-blue-500/20 border-t border-white/30 shrink-0">
                        <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Bilik Suara</h1>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 font-medium">Daftar event pemilihan aktif yang siap dibuka di bilik suara.</p>
                    </div>
                </div>

                @if(Auth::user()->access_type == \App\Constants\UserConst::SUPERADMIN)
                    <x-admin.button href="{{ route('admin.dashboard') }}" color="secondary" size="sm">
                        <svg class="size-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        <span>Kembali ke Dashboard</span>
                    </x-admin.button>
                @else
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <x-admin.button type="submit" color="outline-danger" size="sm">
                            <svg class="size-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            <span>Keluar Panel</span>
                        </x-admin.button>
                    </form>
                @endif
            </div>

            {{-- 3 Summary Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mt-6 sm:mt-8 relative z-10">
                <div class="rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-blue-50/70 to-blue-100/40 border border-blue-200/80 shadow-2xs">
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-700 mb-1">Pemilihan Aktif</p>
                    <p class="text-2xl font-black text-blue-950 tracking-tight">
                        {{ $totalElections }} <span class="text-xs font-semibold text-blue-600 ml-0.5">Event</span>
                    </p>
                </div>
                <div class="rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-emerald-50/70 to-emerald-100/40 border border-emerald-200/80 shadow-2xs">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700 mb-1">Total Suara Masuk</p>
                    <p class="text-2xl font-black text-emerald-950 tracking-tight">
                        {{ $totalVotesAll }} <span class="text-xs font-semibold text-emerald-600 ml-0.5">Suara</span>
                    </p>
                </div>
                <div class="rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-indigo-50/70 to-indigo-100/40 border border-indigo-200/80 shadow-2xs">
                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-700 mb-1">Sesi Bilik Berlangsung</p>
                    <p class="text-2xl font-black text-indigo-950 tracking-tight">
                        {{ $activeSessionsAll }} <span class="text-xs font-semibold text-indigo-600 ml-0.5">Sesi</span>
                    </p>
                </div>
            </div>
        </x-admin.card>
    </div>

    {{-- Main Content (Event Cards Grid) --}}
    <main class="px-4 sm:px-8 py-8 pb-16 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($data as $election)
                <x-admin.card class="flex flex-col h-full hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative">
                    
                    {{-- Card Header --}}
                    <div class="mb-6 flex-grow">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="text-xl font-bold text-gray-900 tracking-tight leading-snug">{{ $election->name }}</h3>
                            <x-admin.badge color="success" size="sm">
                                AKTIF
                            </x-admin.badge>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">{{ $election->description ?? 'Tidak ada deskripsi event.' }}</p>
                    </div>

                    {{-- Statistics Container (2 Columns) --}}
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-6">
                        <!-- Total Suara -->
                        <div class="bg-slate-50/80 rounded-2xl p-4 flex flex-col items-center justify-center border border-slate-100/90 shadow-2xs text-center">
                            <span class="text-xs text-gray-500 font-semibold mb-1">Total Suara</span>
                            <span class="text-2xl font-black text-gray-900 tracking-tight">{{ $election->total_votes }}</span>
                        </div>
                        <!-- Sesi Aktif -->
                        <div class="bg-slate-50/80 rounded-2xl p-4 flex flex-col items-center justify-center border border-slate-100/90 shadow-2xs text-center">
                            <span class="text-xs text-gray-500 font-semibold mb-1">Sesi Aktif</span>
                            <span class="text-2xl font-black {{ $election->active_sessions > 0 ? 'text-blue-600' : 'text-gray-900' }} tracking-tight">{{ $election->active_sessions }}</span>
                        </div>
                    </div>

                    {{-- Actions Container --}}
                    <div class="flex flex-col space-y-3 mt-auto">
                        {{-- Tombol Lihat Paslon (Preline Modal Trigger) --}}
                        <x-admin.button
                            type="button"
                            color="secondary"
                            size="md"
                            class="w-full justify-center"
                            data-hs-overlay="#modal-candidates-{{ $election->id }}"
                        >
                            <svg class="size-4 text-gray-500 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span>Lihat Daftar Paslon ({{ count($election->candidates) }})</span>
                        </x-admin.button>

                        {{-- Tombol Buka Bilik Suara (Layar Selamat Datang / Standby Bilik) --}}
                        <x-admin.button
                            href="{{ route('kiosk.start', $election->id) }}"
                            target="_blank"
                            color="primary"
                            size="lg"
                            class="w-full justify-center font-bold tracking-wide uppercase"
                        >
                            <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            <span>Buka Bilik Suara</span>
                        </x-admin.button>
                    </div>
                </x-admin.card>
            @empty
                <div class="col-span-full">
                    <x-admin.empty-state title="Tidak ada event aktif" message="Saat ini tidak ada event pemilihan yang berstatus aktif." />
                </div>
            @endforelse
        </div>
    </main>

    {{-- Modals Daftar Paslon (Placed outside cards to cover full viewport) --}}
    @foreach($data as $election)
        <x-admin.modal
            id="modal-candidates-{{ $election->id }}"
            title="Daftar Pasangan Calon - {{ $election->name }}"
            size="sm:max-w-xl"
        >
            <div class="space-y-4">
                @forelse($election->candidates as $c)
                    @php $padOrder = str_pad($c->order_number, 2, '0', STR_PAD_LEFT); @endphp
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 hover:shadow-md transition-all relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-gradient-to-br from-blue-500 to-blue-600 text-white w-10 sm:w-12 h-10 sm:h-12 flex items-center justify-center font-black text-base sm:text-lg rounded-bl-2xl shadow-xs border-b border-l border-white/30">
                            {{ $padOrder }}
                        </div>

                        <div class="flex items-center gap-3.5 pr-10 sm:pr-12">
                            <div class="flex items-center gap-1.5 shrink-0">
                                @if($c->photo_path)
                                    <img src="{{ Storage::url($c->photo_path) }}" alt="Foto {{ $c->chairman_name }}" class="w-10 h-13 sm:w-12 sm:h-16 object-cover rounded-xl border border-slate-200 shadow-2xs">
                                @else
                                    <div class="w-10 h-13 sm:w-12 sm:h-16 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-bold text-xs">K</div>
                                @endif

                                @if($c->vice_chairman_photo_path)
                                    <img src="{{ Storage::url($c->vice_chairman_photo_path) }}" alt="Foto {{ $c->vice_chairman_name }}" class="w-10 h-13 sm:w-12 sm:h-16 object-cover rounded-xl border border-slate-200 shadow-2xs">
                                @elseif($c->vice_chairman_name)
                                    <div class="w-10 h-13 sm:w-12 sm:h-16 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-bold text-xs">W</div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="mb-1.5">
                                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block">Calon Ketua</span>
                                    <p class="text-sm sm:text-base font-bold text-gray-900 leading-snug">{{ $c->chairman_name }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Calon Wakil Ketua</span>
                                    <p class="text-sm sm:text-base font-bold text-gray-900 leading-snug">{{ $c->vice_chairman_name ?: '-' }}</p>
                                </div>
                                @if(!empty($c->vision))
                                    <p class="text-xs text-gray-500 italic mt-2 line-clamp-1">"{{ $c->vision }}"</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="mx-auto flex items-center justify-center size-14 rounded-full bg-slate-50 border border-slate-200/80 mb-3">
                            <svg class="size-7 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-700">Belum ada paslon terdaftar pada event ini.</p>
                    </div>
                @endforelse
            </div>
        </x-admin.modal>
    @endforeach
@endsection



