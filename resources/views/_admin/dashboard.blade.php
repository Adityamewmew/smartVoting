@extends('_admin._layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        
        {{-- Header & Election Selector --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900 tracking-tight">Dasbor Pemantauan</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Hasil perhitungan suara dan aktivitas bilik suara secara real-time.</p>
            </div>
            <form action="{{ route('admin.dashboard') }}" method="GET" navigate-form class="w-full sm:w-72">
                <x-admin.select name="election_id" :options="$electionsList->pluck('name', 'id')->toArray()" :value="$selectedElection?->id" size="sm" onchange="this.form.submit()" />
            </form>
        </div>

        @if($selectedElection)
            {{-- LIVE POLLING CARD --}}
            <x-admin.card class="p-4 sm:p-6 overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 pb-4 mb-5 sm:mb-6 border-b border-gray-100">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-blue-50 to-blue-100/80 text-blue-700 border border-blue-200/90 shadow-2xs mb-2">
                            <span class="size-2 rounded-full bg-blue-600 animate-pulse"></span>
                            LIVE COUNTING
                        </span>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">{{ $selectedElection->name }}</h2>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-200/80 shadow-2xs" id="last-update">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-gray-400" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Update baru saja</span>
                    </div>
                </div>

                {{-- Total Votes Counter --}}
                <div class="mb-5 sm:mb-6 bg-gradient-to-r from-blue-50/70 via-indigo-50/30 to-white p-4 sm:p-5 rounded-2xl border border-blue-100/90 shadow-2xs flex items-center justify-between">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">Total Suara Masuk</span>
                    <span class="text-2xl sm:text-3xl font-black text-blue-600 tracking-tight" id="total-votes-text">0 <span class="text-xs sm:text-sm font-normal text-gray-500">suara</span></span>
                </div>

                {{-- Candidate Poll Cards Container --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4 mb-6 sm:mb-8" id="candidates-container">
                    {{-- Dynamically populated via JS --}}
                </div>

                {{-- Chart Visualization --}}
                <div class="w-full h-64 sm:h-72 md:h-80 pt-4 border-t border-gray-100">
                    <canvas id="chart-{{ $selectedElection->id }}"></canvas>
                </div>
            </x-admin.card>

            {{-- Recent Voting Sessions (Only when election is active) --}}
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
            <x-admin.empty-state message="Belum ada event pemilihan yang berstatus aktif saat ini." />
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (() => {
                @if($selectedElection)
                    const colors = [
                        '#2563EB', // Blue-600
                        '#059669', // Emerald-600
                        '#D97706', // Amber-600
                        '#7C3AED', // Violet-600
                        '#DC2626', // Red-600
                        '#0891B2', // Cyan-600
                    ];

                    let myChart = null;
                    let pollingInterval = null;
                    let isDashboardActive = true;
                    const chartCanvas = document.getElementById('chart-{{ $selectedElection->id }}');
                    const ctx = chartCanvas ? chartCanvas.getContext('2d') : null;

                    if (window.registerSpaCleanup) {
                        window.registerSpaCleanup(() => {
                            isDashboardActive = false;
                            if (pollingInterval) {
                                clearInterval(pollingInterval);
                            }
                            if (myChart) {
                                myChart.destroy();
                                myChart = null;
                            }
                        });
                    }

                    function formatPercentage(voteCount, totalVotes) {
                        if (totalVotes === 0) return '0%';
                        return Math.round((voteCount / totalVotes) * 100) + '%';
                    }

                    function fetchElectionData() {
                        const totalVotesText = document.getElementById('total-votes-text');
                        const lastUpdate = document.getElementById('last-update');
                        const container = document.getElementById('candidates-container');

                        if (!isDashboardActive || !ctx || !totalVotesText || !lastUpdate || !container) {
                            return;
                        }

                        fetch(`{{ route('admin.dashboard.data') }}?election_id={{ $selectedElection->id }}`)
                            .then(res => res.json())
                            .then(res => {
                                if (isDashboardActive && res.success) {
                                    const totalVotes = res.data.total_votes;
                                    totalVotesText.innerHTML = `${totalVotes} <span class="text-sm font-normal text-gray-500">suara</span>`;
                                    
                                    const now = new Date();
                                    lastUpdate.innerHTML = `
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-gray-400" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span>Update ${now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })} WIB</span>
                                    `;
                                    
                                    container.innerHTML = '';
                                    
                                    const labels = [];
                                    const dataPoints = [];
                                    const bgColors = [];

                                    res.data.candidates.forEach((c, index) => {
                                        const color = colors[index % colors.length];
                                        const percentage = formatPercentage(c.vote_count, totalVotes);
                                        const numericPct = totalVotes > 0 ? (c.vote_count / totalVotes) * 100 : 0;
                                        
                                        labels.push(`Paslon ${c.order_number}`);
                                        dataPoints.push(c.vote_count);
                                        bgColors.push(color);

                                        const card = document.createElement('div');
                                        card.className = `p-4 border border-gray-200/80 rounded-2xl bg-white shadow-2xs hover:shadow-xs hover:-translate-y-0.5 transition-all duration-200 flex flex-col relative overflow-hidden`;
                                        card.innerHTML = `
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold tracking-wider text-white shadow-2xs" style="background-color: ${color}">
                                                    PASLON ${c.order_number.toString().padStart(2, '0')}
                                                </span>
                                                <span class="text-xs font-black text-gray-800">${percentage}</span>
                                            </div>
                                            <h4 class="text-sm font-bold text-gray-900 truncate leading-tight">${c.chairman_name}</h4>
                                            <p class="text-xs text-gray-500 truncate mb-3">&amp; ${c.vice_chairman_name || '-'}</p>
                                            <div class="mt-auto">
                                                <div class="flex items-baseline justify-between mb-1.5">
                                                    <span class="text-2xl font-black text-gray-900 tracking-tight">${c.vote_count}</span>
                                                    <span class="text-[11px] font-medium text-gray-400">suara</span>
                                                </div>
                                                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden p-0.5 border border-gray-100">
                                                    <div class="h-full rounded-full transition-all duration-700 ease-out" style="width: ${numericPct}%; background-color: ${color}"></div>
                                                </div>
                                            </div>
                                        `;
                                        container.appendChild(card);
                                    });
                                    
                                    if (myChart) {
                                        myChart.data.labels = labels;
                                        myChart.data.datasets[0].data = dataPoints;
                                        myChart.data.datasets[0].backgroundColor = bgColors;
                                        myChart.update();
                                    } else {
                                        myChart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    label: 'Perolehan Suara',
                                                    data: dataPoints,
                                                    backgroundColor: bgColors,
                                                    borderRadius: 8,
                                                    borderSkipped: false,
                                                    maxBarThickness: 50
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
                                    }
                                }
                            });
                    }

                    fetchElectionData();
                    pollingInterval = setInterval(fetchElectionData, 10000);
                @endif
            })();
        </script>
    @endpush
@endsection
