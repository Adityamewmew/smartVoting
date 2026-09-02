@extends('_admin._layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    @if($showOnboarding)
                        Panduan persiapan dan simulasi pemungutan suara.
                    @elseif($isLive)
                        Hasil perhitungan suara dan aktivitas bilik suara.
                    @else
                        Ringkasan dan status pemilihan institusi.
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <x-admin.button :href="route('admin.elections.index')" color="secondary" size="md" class="w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
                    <span>Daftar Pemilihan</span>
                </x-admin.button>
            </div>
        </div>

        @if($showOnboarding)
            {{-- ONBOARDING CHECKLIST WIZARD CARD --}}
            <x-admin.onboarding-wizard :onboarding="$onboarding" />
        @elseif($isLive && $selectedElection)
            @php
                $colors = ['#2563EB', '#059669', '#D97706', '#7C3AED', '#DC2626', '#0891B2'];
            @endphp

            {{-- LIVE COUNTING CARD --}}
            <x-admin.card class="p-4 sm:p-6 overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-4 mb-5 sm:mb-6 border-b border-gray-100">
                    <div>
                        <div class="mb-2">
                            <x-admin.badge status="active" pulse="true" size="sm">
                                LIVE COUNTING
                            </x-admin.badge>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">{{ $selectedElection->name }}</h2>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <x-admin.button :href="route('operator.kiosk.index')" color="primary" size="sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
                            <span>Buka Bilik Suara</span>
                        </x-admin.button>
                        <x-admin.button :href="route('admin.dashboard.print', $selectedElection->id)" target="_blank" color="secondary" size="sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                            <span>Cetak Rekap</span>
                        </x-admin.button>
                    </div>
                </div>

                {{-- Total Votes Counter --}}
                <div class="mb-6 bg-gradient-to-r from-blue-50/70 via-indigo-50/30 to-white p-4 sm:p-6 rounded-2xl border border-blue-100/90 shadow-2xs flex items-center justify-between">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">Total Suara Masuk</span>
                    <span class="text-2xl sm:text-3xl font-black text-blue-600 tracking-tight">{{ $totalVotes }} <span class="text-xs sm:text-sm font-normal text-gray-500">suara</span></span>
                </div>

                {{-- Candidate Poll Cards (Server-rendered via Blade) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 sm:mb-8">
                    @forelse($candidates as $index => $c)
                        @php
                            $color = $colors[$index % count($colors)];
                            $pct = $totalVotes > 0 ? round(($c->vote_count / $totalVotes) * 100) : 0;
                            $numericPct = $totalVotes > 0 ? ($c->vote_count / $totalVotes) * 100 : 0;
                        @endphp
                        <div class="p-4 border border-gray-200/80 rounded-2xl bg-white shadow-2xs hover:shadow-xs hover:-translate-y-0.5 transition-all duration-200 flex flex-col relative overflow-hidden">
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold tracking-wider text-white shadow-2xs" style="background-color: {{ $color }}">
                                    PASLON {{ str_pad($c->order_number, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-xs font-black text-gray-800">{{ $pct }}%</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 truncate leading-tight">{{ $c->chairman_name }}</h4>
                            <p class="text-xs text-gray-500 truncate mb-3">&amp; {{ $c->vice_chairman_name ?: '-' }}</p>
                            <div class="mt-auto">
                                <div class="flex items-baseline justify-between mb-1.5">
                                    <span class="text-2xl font-black text-gray-900 tracking-tight">{{ $c->vote_count }}</span>
                                    <span class="text-[11px] font-medium text-gray-400">suara</span>
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden p-0.5 border border-gray-100">
                                    <div class="h-full rounded-full transition-all duration-700 ease-out" style="width: {{ $numericPct }}%; background-color: {{ $color }}"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-sm text-gray-400">
                            Belum ada data paslon terdaftar pada pemilihan ini.
                        </div>
                    @endforelse
                </div>

                {{-- Chart Visualization --}}
                @if(count($candidates) > 0)
                    <div class="pt-4 border-t border-gray-100">
                        <x-admin.chart 
                            type="bar" 
                            :labels="$chartLabels" 
                            :data="$chartData" 
                            :id="'chart-'.$selectedElection->id" 
                        />
                    </div>
                @endif
            </x-admin.card>

            {{-- Recent Voting Sessions --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-base font-bold text-gray-900">Sesi Bilik Suara Terkini</h3>
                    <span class="text-xs text-gray-400">Menampilkan 10 sesi terakhir</span>
                </div>

                <x-admin.table.wrapper>
                    <x-admin.table>
                        <x-admin.table.thead>
                            <tr>
                                <x-admin.table.th>No.</x-admin.table.th>
                                <x-admin.table.th>Nama Event</x-admin.table.th>
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
                                    <x-admin.table.td class="font-semibold text-gray-900">{{ $session->election_name }}</x-admin.table.td>
                                    <x-admin.table.td>{{ $session->operator_name }}</x-admin.table.td>
                                    <x-admin.table.td innerClass="text-center">
                                        <x-admin.badge :status="$session->status" :text="ucfirst($session->status)" />
                                    </x-admin.table.td>
                                    <x-admin.table.td class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($session->open_time)->format('d M Y, H:i:s') }}</x-admin.table.td>
                                    <x-admin.table.td class="text-gray-500 text-xs">
                                        {{ $session->close_time ? \Carbon\Carbon::parse($session->close_time)->format('d M Y, H:i:s') : '-' }}
                                    </x-admin.table.td>
                                </x-admin.table.tr>
                            @empty
                                <x-admin.table.tr>
                                    <x-admin.table.td colspan="6" innerClass="text-center text-gray-400 py-8">Belum ada aktivitas voting tercatat</x-admin.table.td>
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
        @else
            <x-admin.empty-state message="Seluruh persiapan pemilihan telah selesai atau belum ada pemilihan yang sedang berlangsung hari ini.">
                <div class="mt-4 flex flex-wrap items-center justify-center gap-3">
                    <x-admin.button :href="route('admin.elections.index')" color="primary" size="md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
                        <span>Kelola Pemilihan</span>
                    </x-admin.button>
                    <x-admin.button :href="route('operator.kiosk.index')" color="secondary" size="md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
                        <span>Buka Bilik Suara</span>
                    </x-admin.button>
                </div>
            </x-admin.empty-state>
        @endif
    </div>
@endsection
