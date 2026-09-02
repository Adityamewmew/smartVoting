@extends('_admin._layout.app')

@section('title', 'Detail & Paslon: ' . $election->name)

@section('content')
    <div class="space-y-6">
        {{-- Header & Quick Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div class="flex items-center gap-4 min-w-0">
                <x-admin.button :href="route('admin.elections.index')" size="icon-md" color="secondary" class="shrink-0">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </x-admin.button>
                <div class="min-w-0 space-y-1">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight truncate">{{ $election->name }}</h1>
                        @php
                            $statusLabels = [
                                'draft' => 'Draft',
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                            ];
                        @endphp
                        <x-admin.badge :status="$election->status" :text="$statusLabels[$election->status] ?? ucfirst($election->status)" />
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500 font-medium flex-wrap">
                        <span class="inline-flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            <span>{{ \Carbon\Carbon::parse($election->date ?? $election->start_time)->translatedFormat('d F Y') }}</span>
                        </span>
                        <span class="text-gray-300">•</span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>{{ \Carbon\Carbon::parse($election->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($election->end_time)->format('H:i') }} WIB</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-start sm:justify-end gap-2.5 flex-wrap sm:flex-nowrap shrink-0">
                @if($election->slug)
                    <x-admin.button :href="url('/' . $election->slug)" target="_blank" color="secondary" size="sm" class="gap-1.5">
                        <svg class="size-3.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                        <span>Buka Landing Page</span>
                    </x-admin.button>
                @endif

                <x-admin.button :href="route('admin.dashboard.print', $election->id)" target="_blank" color="secondary" size="sm" class="gap-1.5">
                    <svg class="size-3.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    <span>Cetak Laporan</span>
                </x-admin.button>

                <x-admin.button :href="route('operator.kiosk.index')" color="primary" size="sm" class="gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
                    <span>Buka Bilik Suara</span>
                </x-admin.button>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="border-b border-gray-200 overflow-x-auto">
            <nav class="flex space-x-2 min-w-max pb-1" aria-label="Tabs" role="tablist">
                <button type="button" 
                        class="hs-tab-active:font-bold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-4 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 focus:outline-hidden {{ $activeTab === 'paslon' ? 'active font-bold border-blue-600 text-blue-600' : '' }}" 
                        id="tab-paslon-item" 
                        data-hs-tab="#tab-paslon" 
                        aria-controls="tab-paslon" 
                        role="tab">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Pasangan Calon (Paslon)
                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-semibold {{ $activeTab === 'paslon' ? 'bg-blue-100 text-blue-900' : 'bg-slate-100 text-slate-900' }}">
                        {{ count($candidatesList) }}
                    </span>
                </button>
                <button type="button" 
                        class="hs-tab-active:font-bold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-4 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 focus:outline-hidden {{ $activeTab === 'detail' ? 'active font-bold border-blue-600 text-blue-600' : '' }}" 
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
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-100 shadow-xs">
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
                                <x-admin.table.td innerClass="px-6 py-3 flex items-center justify-end gap-x-1.5">
                                    <x-admin.button
                                        type="button"
                                        size="icon-sm"
                                        color="outline-secondary"
                                        title="Lihat Visi & Misi"
                                        data-hs-overlay="#vision-mission-modal"
                                        onclick="setVisionMissionData('{{ str_pad($c->order_number, 2, '0', STR_PAD_LEFT) }}', '{{ addslashes($c->chairman_name . ($c->vice_chairman_name ? ' & ' . $c->vice_chairman_name : '')) }}', {{ json_encode($c->vision ?? '') }}, {{ json_encode($c->mission ?? '') }})"
                                        class="hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 cursor-pointer"
                                    >
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </x-admin.button>
                                    <x-admin.button
                                        size="icon-sm"
                                        color="outline-secondary"
                                        href="{{ route('admin.candidates.update', $c->id) }}"
                                        title="Ubah Data"
                                    >
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </x-admin.button>
                                    <x-admin.button
                                        type="button"
                                        size="icon-sm"
                                        color="outline-secondary"
                                        class="text-red-600 hover:text-red-700 hover:bg-red-50 hover:border-red-200 cursor-pointer"
                                        title="Hapus Paslon"
                                        data-hs-overlay="#delete-candidate-modal"
                                        onclick="setDeleteCandidate('{{ $c->id }}', '{{ addslashes($c->chairman_name . ($c->vice_chairman_name ? ' & ' . $c->vice_chairman_name : '')) }}', '{{ str_pad($c->order_number, 2, '0', STR_PAD_LEFT) }}')"
                                    >
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </x-admin.button>
                                </x-admin.table.td>
                            </x-admin.table.tr>
                        @empty
                            <x-admin.table.tr>
                                <x-admin.table.td colspan="4" innerClass="text-center py-10">
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

            {{-- 1. Top KPI Summary Cards Grid (3 clean, essential metrics) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                <!-- KPI 1: Total Suara Masuk -->
                <x-admin.card class="p-4 sm:p-6 flex flex-col justify-between relative overflow-hidden bg-white border-gray-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Suara Masuk</span>
                        <span class="size-2 rounded-full bg-blue-500"></span>
                    </div>
                    <div class="my-3">
                        <span class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight" id="total-votes-detail">{{ $totalVotes }}</span>
                        <span class="text-xs text-gray-400 font-medium ml-1">suara sah</span>
                    </div>
                    <p class="text-[11px] text-gray-500 flex items-center gap-1">
                        <svg class="size-3.5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Akumulasi seluruh bilik suara
                    </p>
                </x-admin.card>

                <!-- KPI 2: Paslon Terdepan -->
                <x-admin.card class="p-4 sm:p-6 flex flex-col justify-between relative overflow-hidden bg-white border-gray-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Paslon Terdepan</span>
                        <span class="text-xs font-bold text-emerald-600" id="kpi-leader-pct">
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

                <!-- KPI 3: Aktivitas Bilik Suara -->
                <x-admin.card class="p-4 sm:p-6 flex flex-col justify-between relative overflow-hidden bg-white border-gray-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Aktivitas Bilik Suara</span>
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                    </div>
                    <div class="my-3">
                        <span class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight" id="kpi-active-sessions">{{ $activeSessions }}</span>
                        <span class="text-xs text-gray-400 font-medium ml-1">bilik aktif saat ini</span>
                    </div>
                    <p class="text-[11px] text-gray-500" id="kpi-total-sessions">
                        Dari total {{ $totalSessions }} sesi pemilihan tercatat
                    </p>
                </x-admin.card>
            </div>

            {{-- 2. Dual Analytics Charts (Clean, No-badge visual summary) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Bar Chart: Perolehan Suara -->
                <x-admin.card class="lg:col-span-2 p-4 sm:p-6 flex flex-col justify-between">
                    <div class="mb-4 pb-3 border-b border-gray-100">
                        <h3 class="text-sm sm:text-base font-bold text-gray-900">Distribusi Perolehan Suara</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Perbandingan jumlah suara sah antar pasangan calon.</p>
                    </div>
                    <x-admin.chart 
                        type="bar" 
                        :labels="$candidates->map(fn($c) => 'Paslon '.$c->order_number)->values()->toArray()" 
                        :data="$candidates->pluck('vote_count')->values()->toArray()" 
                        id="chart-bar-detail" 
                        height="h-[260px] sm:h-[290px]"
                    />
                </x-admin.card>

                <!-- Doughnut Chart: Proporsi Pangsa Suara -->
                <x-admin.card class="lg:col-span-1 p-4 sm:p-6 flex flex-col justify-between">
                    <div class="mb-4 pb-3 border-b border-gray-100">
                        <h3 class="text-sm sm:text-base font-bold text-gray-900">Proporsi Suara</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pangsa persentase (%) suara sah.</p>
                    </div>
                    <x-admin.chart 
                        type="doughnut" 
                        :labels="$candidates->map(fn($c) => 'Paslon '.$c->order_number)->values()->toArray()" 
                        :data="$candidates->pluck('vote_count')->values()->toArray()" 
                        id="chart-doughnut-detail" 
                        height="h-[260px] sm:h-[290px]"
                    />
                </x-admin.card>
            </div>

            {{-- 3. Table Results Breakdown --}}
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between px-1">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tabel Rekapitulasi Perolehan Suara</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Perolehan suara resmi per pasangan calon</p>
                    </div>
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
                                    $isLeader = $hasVotes && $leaderCandidate && $leaderCandidate->id === $c->id;
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
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-900 text-sm">{{ $c->chairman_name }}</span>
                                            @if($isLeader)
                                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 rounded-full">Unggul #1</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">&amp; {{ $c->vice_chairman_name ?: '-' }}</div>
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

        {{-- Vision & Mission Modal Component --}}
        <x-admin.vision-mission-modal />

        {{-- Delete Candidate Modal --}}
        <x-admin.modal id="delete-candidate-modal" title="Hapus Pasangan Calon" size="sm:max-w-md">
            <div class="text-center py-3">
                <div class="mx-auto flex items-center justify-center size-12 rounded-full bg-rose-50 border border-rose-100 text-rose-600 mb-3 shadow-2xs">
                    <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Pasangan Calon?</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto leading-relaxed mb-4">
                    Apakah Anda yakin ingin menghapus <strong id="delete-candidate-pad" class="text-blue-600 font-bold"></strong>: <strong id="delete-candidate-name" class="font-bold text-gray-900"></strong>?
                </p>
                <div class="bg-rose-50/70 border border-rose-200/80 rounded-xl p-3 text-left flex items-start gap-2.5">
                    <svg class="size-4 text-rose-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <p class="text-[11px] font-medium text-rose-800 leading-tight">
                        Data paslon beserta foto profil yang tersimpan akan dihapus dari pemilihan ini.
                    </p>
                </div>
            </div>
            <x-slot:footer>
                <div class="grid grid-cols-2 gap-2.5 w-full">
                    <x-admin.button color="secondary" size="md" class="w-full justify-center font-medium" data-hs-overlay="#delete-candidate-modal">Batal</x-admin.button>
                    <form id="delete-candidate-form" method="POST" action="" class="w-full m-0 p-0" navigate-form>
                        @csrf
                        @method('DELETE')
                        <x-admin.button type="submit" color="danger" size="md" class="w-full justify-center font-bold">Ya, Hapus</x-admin.button>
                    </form>
                </div>
            </x-slot:footer>
        </x-admin.modal>
    </div>

    @push('scripts')
        <script>
            window.setVisionMissionData = function(pad, name, vision, mission) {
                const padEl = document.getElementById('vm-modal-pad');
                if (padEl) padEl.textContent = pad;

                const nameEl = document.getElementById('vm-modal-name');
                if (nameEl) nameEl.textContent = name;

                const visionEl = document.getElementById('vm-modal-vision');
                if (visionEl) {
                    if (vision && vision.trim() !== '') {
                        visionEl.textContent = vision.trim().replace(/^["']|["']$/g, '');
                        visionEl.classList.remove('text-gray-400', 'italic');
                        visionEl.classList.add('text-gray-800');
                    } else {
                        visionEl.textContent = 'Belum ada visi yang dicantumkan.';
                        visionEl.classList.add('text-gray-400', 'italic');
                        visionEl.classList.remove('text-gray-800');
                    }
                }

                const missionListEl = document.getElementById('vm-modal-mission-list');
                if (missionListEl) {
                    missionListEl.innerHTML = '';
                    if (!mission || mission.trim() === '') {
                        missionListEl.innerHTML = '<li class="text-xs sm:text-sm text-gray-400 italic">Belum ada misi yang dicantumkan.</li>';
                    } else {
                        const rawLines = mission.split(/\r?\n/).map(l => l.trim()).filter(l => l.length > 0);
                        let itemIndex = 1;
                        rawLines.forEach(line => {
                            const cleanLine = line.replace(/^(\*|\-|\d+[\.\)])\s*/, '').trim();
                            if (cleanLine.length > 0) {
                                const li = document.createElement('li');
                                li.className = 'flex items-start gap-3 text-xs sm:text-sm text-gray-800 leading-relaxed';
                                li.innerHTML = `
                                    <span class="size-5 rounded-full bg-blue-100/80 text-blue-700 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">
                                        ${itemIndex}
                                    </span>
                                    <span class="grow">${cleanLine}</span>
                                `;
                                missionListEl.appendChild(li);
                                itemIndex++;
                            }
                        });
                        if (missionListEl.children.length === 0) {
                            missionListEl.innerHTML = '<li class="text-xs sm:text-sm text-gray-400 italic">Belum ada misi yang dicantumkan.</li>';
                        }
                    }
                }
            };

            window.setDeleteCandidate = function(id, name, pad) {
                document.getElementById('delete-candidate-pad').textContent = 'Paslon ' + pad;
                document.getElementById('delete-candidate-name').textContent = name;
                document.getElementById('delete-candidate-form').action = '{{ url('admin/candidates/delete') }}/' + id;
            };
        </script>
    @endpush
@endsection
