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
            {{-- Summary Cards & Chart --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Total Votes Card -->
                <x-admin.card class="flex flex-col justify-center items-center text-center p-8 bg-gradient-to-b from-blue-50/40 via-white to-white">
                    <x-admin.badge color="primary" class="mb-2 uppercase tracking-wider font-bold">
                        Total Suara Masuk
                    </x-admin.badge>
                    <span class="text-5xl font-extrabold text-gray-900 tracking-tight my-2" id="total-votes-detail">{{ $totalVotes }}</span>
                    <p class="text-xs text-gray-400">Akumulasi seluruh sesi bilik suara</p>
                </x-admin.card>

                <!-- Chart Card -->
                <x-admin.card class="lg:col-span-2 p-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Distribusi Perolehan Suara</h3>
                    <div class="w-full h-[240px]">
                        <canvas id="chart-detail"></canvas>
                    </div>
                </x-admin.card>
            </div>

            {{-- Table Results --}}
            <div class="space-y-3">
                <h3 class="text-base font-bold text-gray-900 px-1">Perolehan Suara Pasangan Calon</h3>
                <x-admin.table.wrapper>
                    <x-admin.table>
                        <x-admin.table.thead>
                            <tr>
                                <x-admin.table.th align="center">No. Urut</x-admin.table.th>
                                <x-admin.table.th>Nama Pasangan Calon</x-admin.table.th>
                                <x-admin.table.th align="end">Perolehan Suara</x-admin.table.th>
                                <x-admin.table.th align="end">Persentase</x-admin.table.th>
                            </tr>
                        </x-admin.table.thead>
                        <x-admin.table.tbody id="candidates-table-body">
                            @foreach($candidates as $c)
                                @php
                                    $percentage = $totalVotes > 0 ? round(($c->vote_count / $totalVotes) * 100, 1) : 0;
                                @endphp
                                <x-admin.table.tr>
                                    <x-admin.table.td innerClass="text-center">
                                        <x-admin.badge color="primary" class="font-extrabold text-[11px] px-2.5 py-0.5">
                                            {{ str_pad($c->order_number, 2, '0', STR_PAD_LEFT) }}
                                        </x-admin.badge>
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        <span class="font-bold text-gray-900 text-sm">{{ $c->chairman_name }}</span>
                                        <span class="text-xs text-gray-500 block">&amp; {{ $c->vice_chairman_name ?: '-' }}</span>
                                    </x-admin.table.td>
                                    <x-admin.table.td innerClass="text-end font-bold text-gray-900" id="vote-count-{{ $c->id }}">
                                        {{ $c->vote_count }} <span class="text-xs font-normal text-gray-400">suara</span>
                                    </x-admin.table.td>
                                    <x-admin.table.td innerClass="text-end font-semibold text-blue-600" id="vote-percentage-{{ $c->id }}">
                                        {{ $percentage }}%
                                    </x-admin.table.td>
                                </x-admin.table.tr>
                            @endforeach
                        </x-admin.table.tbody>
                    </x-admin.table>
                </x-admin.table.wrapper>
            </div>

            {{-- Recent Voting Sessions --}}
            <div class="space-y-3 pt-2">
                <h3 class="text-base font-bold text-gray-900 px-1">Riwayat Sesi Voting</h3>
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
                                    <x-admin.table.td colspan="5" innerClass="text-center text-gray-400 py-8">Belum ada aktivitas voting</x-admin.table.td>
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
                const ctx = document.getElementById('chart-detail')?.getContext('2d');
                if (!ctx) return;

                const initialLabels = @json($candidates->map(fn($c) => "Paslon {$c->order_number}")->values());
                const initialData = @json($candidates->pluck('vote_count')->values());
                const colors = ['#2563EB', '#059669', '#D97706', '#7C3AED', '#DC2626'];

                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: initialLabels,
                        datasets: [{
                            label: 'Perolehan Suara',
                            data: initialData,
                            backgroundColor: colors.slice(0, initialLabels.length),
                            borderRadius: 8,
                            borderSkipped: false,
                            maxBarThickness: 45
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
                                titleFont: { family: 'Geist' },
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
                            if (res.success) {
                                const totalEl = document.getElementById('total-votes-detail');
                                if (totalEl) totalEl.innerText = res.data.total_votes;
                                
                                const labels = res.data.candidates.map(c => `Paslon ${c.order_number}`);
                                const data = res.data.candidates.map(c => c.vote_count);
                                
                                chart.data.labels = labels;
                                chart.data.datasets[0].data = data;
                                chart.update();

                                // Update table
                                const tbody = document.getElementById('candidates-table-body');
                                if (tbody) {
                                    tbody.innerHTML = '';
                                    res.data.candidates.forEach(c => {
                                        const tr = document.createElement('tr');
                                        tr.className = 'hover:bg-blue-50/30 transition-colors';
                                        
                                        const pct = res.data.total_votes > 0 
                                            ? ((c.vote_count / res.data.total_votes) * 100).toFixed(1) 
                                            : 0;

                                        tr.innerHTML = `
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                    ${c.order_number.toString().padStart(2, '0')}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="font-bold text-gray-900 text-sm">${c.chairman_name}</span>
                                                <span class="text-xs text-gray-500 block">&amp; ${c.vice_chairman_name || '-'}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end font-bold text-gray-900">${c.vote_count} <span class="text-xs font-normal text-gray-400">suara</span></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end font-semibold text-blue-600">${pct}%</td>
                                        `;
                                        tbody.appendChild(tr);
                                    });
                                }
                            }
                        });
                }

                const livePollingInterval = setInterval(fetchLiveUpdates, 10000);

                if (window.registerSpaCleanup) {
                    window.registerSpaCleanup(() => {
                        clearInterval(livePollingInterval);
                        if (chart) {
                            chart.destroy();
                        }
                    });
                }
            })();
        </script>
    @endpush
@endsection
