@extends('_admin._layout.app')

@section('title', 'Detail & Paslon: ' . $election->name)

@section('content')
    <div class="space-y-6">
        {{-- Header & Quick Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-xs">
            <div class="flex items-center gap-3">
                <x-admin.button href="{{ route('admin.elections.index') }}" size="icon-md" color="secondary">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </x-admin.button>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ $election->name }}</h1>
                        @php
                            $statusLabels = [
                                'draft' => 'Draft',
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                            ];
                        @endphp
                        <x-admin.badge :status="$election->status" :text="$statusLabels[$election->status] ?? ucfirst($election->status)" />
                    </div>
                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                        <span>🗓️ {{ \Carbon\Carbon::parse($election->date ?? $election->start_time)->translatedFormat('d F Y') }}</span>
                        <span>•</span>
                        <span>⏰ {{ \Carbon\Carbon::parse($election->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($election->end_time)->format('H:i') }} WIB</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($election->slug)
                    <x-admin.button href="{{ url('/' . $election->slug) }}" target="_blank" color="outline-primary" size="sm">
                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                        Buka Landing Slug
                    </x-admin.button>
                @endif
                <x-admin.button href="{{ route('admin.dashboard.print', $election->id) }}" target="_blank" color="secondary" size="sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak Laporan
                </x-admin.button>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="border-b border-gray-200">
            <nav class="flex space-x-2" aria-label="Tabs" role="tablist">
                <button type="button" 
                        class="hs-tab-active:font-bold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-3.5 px-4 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 focus:outline-hidden {{ $activeTab === 'paslon' ? 'active font-bold border-blue-600 text-blue-600' : '' }}" 
                        id="tab-paslon-item" 
                        data-hs-tab="#tab-paslon" 
                        aria-controls="tab-paslon" 
                        role="tab">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Pasangan Calon (Paslon)
                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-semibold {{ $activeTab === 'paslon' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ count($candidatesList) }}
                    </span>
                </button>
                <button type="button" 
                        class="hs-tab-active:font-bold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-3.5 px-4 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 focus:outline-hidden {{ $activeTab === 'detail' ? 'active font-bold border-blue-600 text-blue-600' : '' }}" 
                        id="tab-detail-item" 
                        data-hs-tab="#tab-detail" 
                        aria-controls="tab-detail" 
                        role="tab">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    Hasil &amp; Detail Pemilihan
                </button>
            </nav>
        </div>

        {{-- Tab 1: Paslon CRUD --}}
        <div id="tab-paslon" class="{{ $activeTab === 'paslon' ? '' : 'hidden' }} space-y-6" role="tabpanel" aria-labelledby="tab-paslon-item">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-xs">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-gray-900">Daftar Pasangan Calon</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Kelola data kandidat paslon yang bertanding pada pemilihan ini.</p>
                </div>
                <x-admin.button href="{{ route('admin.candidates.add', ['election_id' => $election->id]) }}" color="primary" size="md" class="w-full sm:w-auto justify-center">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Tambah Paslon
                </x-admin.button>
            </div>

            <x-admin.table.wrapper>
                <x-admin.table>
                    <x-admin.table.thead>
                        <tr>
                            <x-admin.table.th align="center">No. Urut</x-admin.table.th>
                            <x-admin.table.th>Foto Paslon</x-admin.table.th>
                            <x-admin.table.th>Nama Pasangan Calon</x-admin.table.th>
                            <x-admin.table.th>Visi &amp; Misi</x-admin.table.th>
                            <x-admin.table.th align="end">Aksi</x-admin.table.th>
                        </tr>
                    </x-admin.table.thead>
                    <x-admin.table.tbody>
                        @forelse($candidatesList as $c)
                            <x-admin.table.tr>
                                <x-admin.table.td innerClass="text-center">
                                    <x-admin.badge color="primary" class="font-extrabold text-xs px-2.5 py-0.5">
                                        {{ str_pad($c->order_number, 2, '0', STR_PAD_LEFT) }}
                                    </x-admin.badge>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="flex items-center gap-1.5">
                                        <div class="relative group" title="Foto Ketua: {{ $c->chairman_name }}">
                                            @if($c->photo_path)
                                                <img src="{{ Storage::url($c->photo_path) }}"
                                                     alt="Foto {{ $c->chairman_name }}"
                                                     class="w-8 h-10 object-cover rounded-lg border border-gray-200 shadow-2xs">
                                            @else
                                                <div class="w-8 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 text-[10px] font-bold">
                                                    K
                                                </div>
                                            @endif
                                        </div>
                                        <div class="relative group" title="Foto Wakil: {{ $c->vice_chairman_name }}">
                                            @if(!empty($c->vice_chairman_photo_path))
                                                <img src="{{ Storage::url($c->vice_chairman_photo_path) }}"
                                                     alt="Foto {{ $c->vice_chairman_name }}"
                                                     class="w-8 h-10 object-cover rounded-lg border border-gray-200 shadow-2xs">
                                            @else
                                                <div class="w-8 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 text-[10px] font-bold">
                                                    W
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="font-bold text-gray-900 text-sm">{{ $c->chairman_name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">&amp; {{ $c->vice_chairman_name ?: '-' }}</div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    @if(!empty($c->vision))
                                        <p class="text-xs text-gray-600 line-clamp-1 italic">"{{ $c->vision }}"</p>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </x-admin.table.td>
                                <x-admin.table.td innerClass="px-6 py-3 flex items-center justify-end gap-x-1.5">
                                    <x-admin.button
                                        size="icon-sm"
                                        color="outline-secondary"
                                        href="{{ route('admin.candidates.update', $c->id) }}"
                                        title="Ubah Data"
                                    >
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </x-admin.button>
                                    <form action="{{ route('admin.candidates.delete', $c->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus paslon ini?');" class="inline">
                                        @csrf
                                        <x-admin.button
                                            type="submit"
                                            size="icon-sm"
                                            color="outline-secondary"
                                            class="text-red-600 hover:text-red-700 hover:bg-red-50 hover:border-red-200"
                                            title="Hapus Data"
                                        >
                                            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </x-admin.button>
                                    </form>
                                </x-admin.table.td>
                            </x-admin.table.tr>
                        @empty
                            <x-admin.table.tr>
                                <x-admin.table.td colspan="5" innerClass="text-center py-10">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="size-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                                            <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-800">Belum ada Paslon terdaftar</p>
                                        <p class="text-xs text-gray-400 mt-0.5 mb-4">Tambahkan pasangan calon untuk pemilihan {{ $election->name }} ini.</p>
                                        <x-admin.button href="{{ route('admin.candidates.add', ['election_id' => $election->id]) }}" color="primary" size="sm">
                                            Tambah Paslon Sekarang
                                        </x-admin.button>
                                    </div>
                                </x-admin.table.td>
                            </x-admin.table.tr>
                        @endforelse
                    </x-admin.table.tbody>
                </x-admin.table>
            </x-admin.table.wrapper>
        </div>

        {{-- Tab 2: Detail & Hasil Pemilihan --}}
        <div id="tab-detail" class="{{ $activeTab === 'detail' ? '' : 'hidden' }} space-y-6" role="tabpanel" aria-labelledby="tab-detail-item">
            @php
                $sortedCandidates = collect($candidates)->sortByDesc('vote_count')->values();
                $leaderCandidate = $sortedCandidates->first();
                $hasVotes = $totalVotes > 0 && $leaderCandidate && $leaderCandidate->vote_count > 0;
                $leaderPct = ($totalVotes > 0 && $leaderCandidate) ? round(($leaderCandidate->vote_count / $totalVotes) * 100, 1) : 0;
                $colors = ['#2563EB', '#059669', '#D97706', '#7C3AED', '#DC2626', '#0891B2'];
            @endphp

            {{-- 1. Top KPI Summary Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- KPI 1: Total Votes -->
                <x-admin.card class="p-5 flex flex-col justify-between relative overflow-hidden bg-gradient-to-br from-blue-50/60 via-white to-white border-blue-100/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Suara</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700">
                            <span class="size-1.5 rounded-full bg-blue-600 animate-ping"></span>
                            LIVE
                        </span>
                    </div>
                    <div class="my-3">
                        <span class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight" id="total-votes-detail">{{ $totalVotes }}</span>
                        <span class="text-xs text-gray-400 font-medium ml-1">suara masuk</span>
                    </div>
                    <p class="text-[11px] text-gray-400 flex items-center gap-1">
                        <svg class="size-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Akumulasi seluruh bilik suara
                    </p>
                </x-admin.card>

                <!-- KPI 2: Leading Candidate -->
                <x-admin.card class="p-5 flex flex-col justify-between relative overflow-hidden bg-gradient-to-br from-emerald-50/60 via-white to-white border-emerald-100/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Paslon Terdepan</span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full" id="kpi-leader-pct">
                            {{ $hasVotes ? $leaderPct . '%' : '-' }}
                        </span>
                    </div>
                    <div class="my-2" id="kpi-leader-container">
                        @if($hasVotes && $leaderCandidate)
                            <div class="flex items-center gap-2.5">
                                <x-admin.badge color="primary" class="font-extrabold text-xs px-2 py-0.5">
                                    {{ str_pad($leaderCandidate->order_number, 2, '0', STR_PAD_LEFT) }}
                                </x-admin.badge>
                                <div class="truncate">
                                    <h4 class="text-sm font-bold text-gray-900 truncate leading-tight">{{ $leaderCandidate->chairman_name }}</h4>
                                    <span class="text-[11px] text-gray-500 truncate block">&amp; {{ $leaderCandidate->vice_chairman_name ?: '-' }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-sm font-semibold text-gray-400 py-1">Belum ada suara masuk</div>
                        @endif
                    </div>
                    <p class="text-[11px] text-gray-500" id="kpi-leader-votes">
                        {{ $hasVotes ? $leaderCandidate->vote_count . ' suara diperoleh' : 'Menunggu pemungutan suara' }}
                    </p>
                </x-admin.card>

                <!-- KPI 3: Voting Sessions -->
                <x-admin.card class="p-5 flex flex-col justify-between relative overflow-hidden bg-gradient-to-br from-indigo-50/40 via-white to-white border-indigo-100/60">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Bilik Suara</span>
                        <span class="size-2 rounded-full bg-indigo-500"></span>
                    </div>
                    <div class="my-3">
                        <span class="text-3xl sm:text-4xl font-black text-indigo-600 tracking-tight" id="kpi-active-sessions">{{ $activeSessions }}</span>
                        <span class="text-xs text-gray-400 font-medium ml-1">bilik aktif</span>
                    </div>
                    <p class="text-[11px] text-gray-400" id="kpi-total-sessions">
                        Dari total {{ $totalSessions }} sesi pemilihan tercatat
                    </p>
                </x-admin.card>

                <!-- KPI 4: Schedule & Status -->
                <x-admin.card class="p-5 flex flex-col justify-between relative overflow-hidden bg-gradient-to-br from-slate-50/60 via-white to-white border-gray-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status Event</span>
                        <x-admin.badge :status="$election->status" :text="$statusLabels[$election->status] ?? ucfirst($election->status)" />
                    </div>
                    <div class="my-2">
                        <p class="text-sm font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($election->date ?? $election->start_time)->translatedFormat('d M Y') }}
                        </p>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">
                            {{ \Carbon\Carbon::parse($election->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($election->end_time)->format('H:i') }} WIB
                        </p>
                    </div>
                    <p class="text-[11px] text-gray-400">
                        Slug: <span class="font-mono text-gray-600">{{ $election->slug ?: '-' }}</span>
                    </p>
                </x-admin.card>
            </div>

            {{-- 2. Dual Analytics Charts (Bar Chart & Doughnut Chart) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Bar Chart: Perolehan Suara -->
                <x-admin.card class="lg:col-span-2 p-5 sm:p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-gray-900">Distribusi Perolehan Suara</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Perbandingan jumlah suara sah antar pasangan calon.</p>
                        </div>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100/80">
                            Bar Chart
                        </span>
                    </div>
                    <div class="w-full h-[260px] sm:h-[290px] relative">
                        <canvas id="chart-bar-detail"></canvas>
                    </div>
                </x-admin.card>

                <!-- Doughnut Chart: Proporsi Pangsa Suara -->
                <x-admin.card class="lg:col-span-1 p-5 sm:p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-gray-900">Proporsi Suara</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Pangsa persentase (%) suara.</p>
                        </div>
                        <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-100/80">
                            Doughnut
                        </span>
                    </div>
                    <div class="w-full h-[260px] sm:h-[290px] relative flex items-center justify-center">
                        <canvas id="chart-doughnut-detail"></canvas>
                    </div>
                </x-admin.card>
            </div>

            {{-- 3. Candidate Performance Cards with Animated Progress Bars --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-base font-bold text-gray-900">Ringkasan Kandidat &amp; Pangsa Suara</h3>
                    <span class="text-xs text-gray-500">Live perolehan suara per paslon</span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="candidates-cards-container">
                    @foreach($candidates as $idx => $c)
                        @php
                            $pct = $totalVotes > 0 ? round(($c->vote_count / $totalVotes) * 100, 1) : 0;
                            $color = $colors[$idx % count($colors)];
                            $isLeader = $hasVotes && $leaderCandidate && $leaderCandidate->id === $c->id;
                        @endphp
                        <x-admin.card class="p-4 sm:p-5 flex flex-col justify-between border-gray-100 hover:shadow-md transition-shadow relative overflow-hidden" id="card-candidate-{{ $c->id }}">
                            @if($isLeader)
                                <div class="absolute top-0 right-0 bg-gradient-to-l from-emerald-500 to-emerald-600 text-white text-[10px] font-extrabold uppercase px-3 py-0.5 rounded-bl-xl shadow-xs">
                                    Unggul #1
                                </div>
                            @endif

                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="num-badge w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-xs shrink-0" aria-label="Nomor urut {{ $c->order_number }}">
                                        {{ str_pad($c->order_number, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    
                                    {{-- Photo Thumbnails --}}
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        @if($c->photo_path)
                                            <img src="{{ Storage::url($c->photo_path) }}" alt="Ketua" class="w-8 h-10 object-cover rounded-lg border border-gray-200 shadow-2xs">
                                        @endif
                                        @if(!empty($c->vice_chairman_photo_path))
                                            <img src="{{ Storage::url($c->vice_chairman_photo_path) }}" alt="Wakil" class="w-8 h-10 object-cover rounded-lg border border-gray-200 shadow-2xs">
                                        @endif
                                    </div>

                                    <div class="truncate min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 truncate leading-snug">{{ $c->chairman_name }}</h4>
                                        <span class="text-xs text-gray-500 truncate block">&amp; {{ $c->vice_chairman_name ?: '-' }}</span>
                                    </div>
                                </div>

                                {{-- Vote Counter & Percentage --}}
                                <div class="flex items-baseline justify-between mb-2">
                                    <span class="text-2xl font-black text-gray-900 tracking-tight" id="card-votes-{{ $c->id }}">
                                        {{ $c->vote_count }} <span class="text-xs font-normal text-gray-400">suara</span>
                                    </span>
                                    <span class="text-sm font-extrabold text-blue-600" id="card-pct-{{ $c->id }}">
                                        {{ $pct }}%
                                    </span>
                                </div>

                                {{-- Animated Progress Bar --}}
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    <div id="card-bar-{{ $c->id }}"
                                         class="h-2.5 rounded-full transition-all duration-700 ease-out"
                                         style="width: {{ $pct }}%; background-color: {{ $color }};">
                                    </div>
                                </div>
                            </div>
                        </x-admin.card>
                    @endforeach
                </div>
            </div>

            {{-- 4. Table Results Breakdown --}}
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-base font-bold text-gray-900">Tabel Rekapitulasi Perolehan Suara</h3>
                    <span class="text-xs text-gray-400">Diperbarui otomatis secara real-time</span>
                </div>

                <x-admin.table.wrapper>
                    <x-admin.table>
                        <x-admin.table.thead>
                            <tr>
                                <x-admin.table.th align="center">No. Urut</x-admin.table.th>
                                <x-admin.table.th>Foto</x-admin.table.th>
                                <x-admin.table.th>Pasangan Calon</x-admin.table.th>
                                <x-admin.table.th align="end">Perolehan Suara</x-admin.table.th>
                                <x-admin.table.th class="w-1/3">Grafik Persentase</x-admin.table.th>
                                <x-admin.table.th align="end">Persentase</x-admin.table.th>
                            </tr>
                        </x-admin.table.thead>
                        <x-admin.table.tbody id="candidates-table-body">
                            @foreach($candidates as $idx => $c)
                                @php
                                    $percentage = $totalVotes > 0 ? round(($c->vote_count / $totalVotes) * 100, 1) : 0;
                                    $color = $colors[$idx % count($colors)];
                                @endphp
                                <x-admin.table.tr>
                                    <x-admin.table.td innerClass="text-center">
                                        <x-admin.badge color="primary" class="font-extrabold text-[11px] px-2.5 py-0.5">
                                            {{ str_pad($c->order_number, 2, '0', STR_PAD_LEFT) }}
                                        </x-admin.badge>
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        <div class="flex items-center gap-1.5">
                                            @if($c->photo_path)
                                                <img src="{{ Storage::url($c->photo_path) }}" alt="Foto Ketua" class="w-7 h-9 object-cover rounded-md border border-gray-200 shadow-2xs">
                                            @endif
                                            @if(!empty($c->vice_chairman_photo_path))
                                                <img src="{{ Storage::url($c->vice_chairman_photo_path) }}" alt="Foto Wakil" class="w-7 h-9 object-cover rounded-md border border-gray-200 shadow-2xs">
                                            @endif
                                        </div>
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        <div class="font-bold text-gray-900 text-sm">{{ $c->chairman_name }}</div>
                                        <div class="text-xs text-gray-500">&amp; {{ $c->vice_chairman_name ?: '-' }}</div>
                                    </x-admin.table.td>
                                    <x-admin.table.td innerClass="text-end font-bold text-gray-900" id="vote-count-{{ $c->id }}">
                                        {{ $c->vote_count }} <span class="text-xs font-normal text-gray-400">suara</span>
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div id="table-bar-{{ $c->id }}" class="h-2 rounded-full transition-all duration-700 ease-out" style="width: {{ $percentage }}%; background-color: {{ $color }};"></div>
                                        </div>
                                    </x-admin.table.td>
                                    <x-admin.table.td innerClass="text-end font-extrabold text-blue-600" id="vote-percentage-{{ $c->id }}">
                                        {{ $percentage }}%
                                    </x-admin.table.td>
                                </x-admin.table.tr>
                            @endforeach
                        </x-admin.table.tbody>
                    </x-admin.table>
                </x-admin.table.wrapper>
            </div>

            {{-- 5. Recent Voting Sessions History --}}
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-base font-bold text-gray-900">Riwayat Sesi Bilik Suara</h3>
                    <span class="text-xs text-gray-400">Daftar sesi pemilihan yang tercatat pada event ini</span>
                </div>

                <x-admin.table.wrapper>
                    <x-admin.table>
                        <x-admin.table.thead>
                            <tr>
                                <x-admin.table.th>No.</x-admin.table.th>
                                <x-admin.table.th>Operator</x-admin.table.th>
                                <x-admin.table.th align="center">Status</x-admin.table.th>
                                <x-admin.table.th>Waktu Dibuka</x-admin.table.th>
                                <x-admin.table.th>Waktu Ditutup</x-admin.table.th>
                            </tr>
                        </x-admin.table.thead>
                        <x-admin.table.tbody>
                            @forelse($recentSessions as $i => $session)
                                <x-admin.table.tr>
                                    <x-admin.table.td class="font-medium text-gray-500">{{ ($recentSessions->currentPage() - 1) * $recentSessions->perPage() + $i + 1 }}</x-admin.table.td>
                                    <x-admin.table.td class="font-semibold text-gray-900">{{ $session->operator_name }}</x-admin.table.td>
                                    <x-admin.table.td innerClass="text-center">
                                        <x-admin.badge :status="$session->status" :text="ucfirst($session->status)" />
                                    </x-admin.table.td>
                                    <x-admin.table.td class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->open_time)->format('d M Y, H:i:s') }}</x-admin.table.td>
                                    <x-admin.table.td class="text-xs text-gray-500">
                                        {{ $session->close_time ? \Carbon\Carbon::parse($session->close_time)->format('d M Y, H:i:s') : '-' }}
                                    </x-admin.table.td>
                                </x-admin.table.tr>
                            @empty
                                <x-admin.table.tr>
                                    <x-admin.table.td colspan="5" innerClass="text-center text-gray-400 py-8">Belum ada aktivitas voting tercatat</x-admin.table.td>
                                </x-admin.table.tr>
                            @endforelse
                        </x-admin.table.tbody>
                    </x-admin.table>
                    
                    @if (count($recentSessions) > 0 && $recentSessions->hasPages())
                        <div class="p-4 border-t border-gray-100">
                            {{ $recentSessions->links() }}
                        </div>
                    @endif
                </x-admin.table.wrapper>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (() => {
                const barCtx = document.getElementById('chart-bar-detail')?.getContext('2d');
                const doughnutCtx = document.getElementById('chart-doughnut-detail')?.getContext('2d');
                if (!barCtx || !doughnutCtx) return;

                const initialLabels = @json($candidates->map(fn($c) => "Paslon {$c->order_number}")->values());
                const initialData = @json($candidates->pluck('vote_count')->values());
                const colors = ['#2563EB', '#059669', '#D97706', '#7C3AED', '#DC2626', '#0891B2'];

                // 1. Initialize Bar Chart
                const barChart = new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: initialLabels,
                        datasets: [{
                            label: 'Perolehan Suara',
                            data: initialData,
                            backgroundColor: colors.slice(0, initialLabels.length),
                            borderRadius: 8,
                            borderSkipped: false,
                            maxBarThickness: 48
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#F1F5F9' },
                                ticks: { stepSize: 1, precision: 0, font: { family: 'Geist' } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Geist', weight: 'bold' } }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1E293B',
                                titleFont: { family: 'Geist', weight: 'bold' },
                                bodyFont: { family: 'Geist' },
                                padding: 10,
                                cornerRadius: 8
                            }
                        }
                    }
                });

                // 2. Initialize Doughnut Chart
                const doughnutChart = new Chart(doughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: initialLabels,
                        datasets: [{
                            data: initialData.length && initialData.some(v => v > 0) ? initialData : [1],
                            backgroundColor: initialData.length && initialData.some(v => v > 0) ? colors.slice(0, initialLabels.length) : ['#E2E8F0'],
                            borderWidth: 2,
                            borderColor: '#FFFFFF',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: { family: 'Geist', size: 11, weight: 'bold' },
                                    usePointStyle: true,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1E293B',
                                titleFont: { family: 'Geist', weight: 'bold' },
                                bodyFont: { family: 'Geist' },
                                padding: 10,
                                cornerRadius: 8
                            }
                        }
                    }
                });

                function fetchLiveUpdates() {
                    fetch(`{{ route('admin.dashboard.data') }}?election_id={{ $election->id }}`)
                        .then(res => res.json())
                        .then(res => {
                            if (!res.success) return;
                            const d = res.data;
                            const totalVotes = d.total_votes || 0;

                            // Update Total Votes KPI
                            const totalEl = document.getElementById('total-votes-detail');
                            if (totalEl) totalEl.innerText = totalVotes;

                            // Update Active & Total Sessions KPI
                            const activeSessionsEl = document.getElementById('kpi-active-sessions');
                            if (activeSessionsEl && d.active_sessions !== undefined) {
                                activeSessionsEl.innerText = d.active_sessions;
                            }
                            const totalSessionsEl = document.getElementById('kpi-total-sessions');
                            if (totalSessionsEl && d.total_sessions !== undefined) {
                                totalSessionsEl.innerText = `Dari total ${d.total_sessions} sesi pemilihan tercatat`;
                            }

                            // Update Leading Candidate KPI
                            const sorted = [...d.candidates].sort((a, b) => b.vote_count - a.vote_count);
                            const leader = sorted[0];
                            const leaderContainer = document.getElementById('kpi-leader-container');
                            const leaderPctEl = document.getElementById('kpi-leader-pct');
                            const leaderVotesEl = document.getElementById('kpi-leader-votes');

                            if (leader && leader.vote_count > 0 && totalVotes > 0) {
                                const lPct = ((leader.vote_count / totalVotes) * 100).toFixed(1);
                                if (leaderPctEl) leaderPctEl.innerText = `${lPct}%`;
                                if (leaderVotesEl) leaderVotesEl.innerText = `${leader.vote_count} suara diperoleh`;
                                if (leaderContainer) {
                                    leaderContainer.innerHTML = `
                                        <div class="flex items-center gap-2.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                ${leader.order_number.toString().padStart(2, '0')}
                                            </span>
                                            <div class="truncate">
                                                <h4 class="text-sm font-bold text-gray-900 truncate leading-tight">${leader.chairman_name}</h4>
                                                <span class="text-[11px] text-gray-500 truncate block">&amp; ${leader.vice_chairman_name || '-'}</span>
                                            </div>
                                        </div>
                                    `;
                                }
                            }

                            // Update Charts
                            const labels = d.candidates.map(c => `Paslon ${c.order_number}`);
                            const data = d.candidates.map(c => c.vote_count);

                            barChart.data.labels = labels;
                            barChart.data.datasets[0].data = data;
                            barChart.update();

                            doughnutChart.data.labels = labels;
                            doughnutChart.data.datasets[0].data = data.some(v => v > 0) ? data : [1];
                            doughnutChart.data.datasets[0].backgroundColor = data.some(v => v > 0) ? colors.slice(0, labels.length) : ['#E2E8F0'];
                            doughnutChart.update();

                            // Update Candidate Cards & Table
                            d.candidates.forEach((c, idx) => {
                                const pct = totalVotes > 0 ? ((c.vote_count / totalVotes) * 100).toFixed(1) : 0;
                                const color = colors[idx % colors.length];

                                // Card updates
                                const cardVotes = document.getElementById(`card-votes-${c.id}`);
                                if (cardVotes) cardVotes.innerHTML = `${c.vote_count} <span class="text-xs font-normal text-gray-400">suara</span>`;
                                const cardPct = document.getElementById(`card-pct-${c.id}`);
                                if (cardPct) cardPct.innerText = `${pct}%`;
                                const cardBar = document.getElementById(`card-bar-${c.id}`);
                                if (cardBar) cardBar.style.width = `${pct}%`;

                                // Table updates
                                const tableVotes = document.getElementById(`vote-count-${c.id}`);
                                if (tableVotes) tableVotes.innerHTML = `${c.vote_count} <span class="text-xs font-normal text-gray-400">suara</span>`;
                                const tablePct = document.getElementById(`vote-percentage-${c.id}`);
                                if (tablePct) tablePct.innerText = `${pct}%`;
                                const tableBar = document.getElementById(`table-bar-${c.id}`);
                                if (tableBar) tableBar.style.width = `${pct}%`;
                            });
                        })
                        .catch(err => console.warn('Live polling error:', err));
                }

                const livePollingInterval = setInterval(fetchLiveUpdates, 8000);

                if (window.registerSpaCleanup) {
                    window.registerSpaCleanup(() => {
                        clearInterval(livePollingInterval);
                        if (barChart) barChart.destroy();
                        if (doughnutChart) doughnutChart.destroy();
                    });
                }
            })();
        </script>
    @endpush
@endsection
