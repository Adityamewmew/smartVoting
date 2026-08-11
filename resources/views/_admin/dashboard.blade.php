@extends('_admin._layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        
        {{-- LIVE POLLING SECTION --}}
        <section>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h2 class="text-2xl font-semibold text-ink">Dasbor</h2>
                <form action="{{ route('admin.dashboard') }}" method="GET" navigate-form class="flex items-center gap-3">
                    <div class="w-full sm:w-64">
                        <x-admin.select name="election_id" :options="$electionsList->pluck('name', 'id')->toArray()" :value="$selectedElection?->id" size="sm" onchange="this.form.submit()" />
                    </div>
                </form>
            </div>

            @if($selectedElection)
            <x-admin.card class="p-6 border-graphite-hairline overflow-hidden">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-2">
                    <h3 class="text-xl font-normal text-ink">{{ $selectedElection->name }}</h3>
                    <span class="text-sm text-slate mt-2 md:mt-0" id="last-update">Update baru saja</span>
                </div>
                <p class="text-sm text-slate mb-8" id="total-votes-text">Total suara masuk <span class="font-normal text-ink">0</span></p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8" id="candidates-container">
                    {{-- Candidate cards will be injected here via JS --}}
                </div>

                <div class="w-full h-[300px]">
                    <canvas id="chart-{{ $selectedElection->id }}"></canvas>
                </div>
            </x-admin.card>
            @else
            <x-admin.card class="text-center p-10 border-dashed border-graphite-hairline">
                <p class="text-sm text-slate">Tidak ada data pemilihan.</p>
            </x-admin.card>
            @endif
        </section>

        {{-- Recent Voting Sessions --}}
        <section>
            <x-admin.card class="border-graphite-hairline p-0 overflow-hidden shadow-none">
            <x-admin.table.wrapper class="shadow-none rounded-none border-0">
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
                            <x-admin.table.tr class="hover:bg-vellum/50 transition">
                                <x-admin.table.td>{{ ($recentSessions->currentPage() - 1) * $recentSessions->perPage() + $i + 1 }}</x-admin.table.td>
                                <x-admin.table.td>{{ $session->election_name }}</x-admin.table.td>
                                <x-admin.table.td>{{ $session->operator_name }}</x-admin.table.td>
                                <x-admin.table.td innerClass="text-center">
                                    <x-admin.badge :text="ucfirst($session->status)" :status="$session->status" />
                                </x-admin.table.td>
                                <x-admin.table.td class="text-slate">{{ \Carbon\Carbon::parse($session->open_time)->format('d M Y, H:i:s') }}</x-admin.table.td>
                                <x-admin.table.td class="text-slate">
                                    {{ $session->close_time ? \Carbon\Carbon::parse($session->close_time)->format('d M Y, H:i:s') : '-' }}
                                </x-admin.table.td>
                            </x-admin.table.tr>
                        @empty
                            <x-admin.table.tr>
                                <x-admin.table.td colspan="6" innerClass="text-center text-slate py-8">Belum ada aktivitas voting</x-admin.table.td>
                            </x-admin.table.tr>
                        @endforelse
                    </x-admin.table.tbody>
                </x-admin.table>
            </x-admin.table.wrapper>
                
                @if (count($recentSessions) > 0 && $recentSessions->hasPages())
                    <div class="p-4 border-t border-graphite-hairline">
                        {{ $recentSessions->links() }}
                    </div>
                @endif
            </x-admin.card>
        </section>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (() => {
                @if($selectedElection)
                    const colors = [
                        '#FFB22C', // Yellow brand
                        '#854836', // Brown brand
                        '#eab308', // yellow-500
                        '#f59e0b', // amber-500
                        '#d97706', // amber-600
                        '#b45309', // amber-700
                        '#78350f', // amber-900
                        '#000000'  // black
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
                                    totalVotesText.innerHTML = `Total suara masuk <span class="font-normal text-ink">${totalVotes}</span>`;
                                    
                                    const now = new Date();
                                    lastUpdate.innerText = 'Update ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
                                    
                                    // Update candidate cards
                                    container.innerHTML = '';
                                    
                                    const labels = [];
                                    const dataPoints = [];
                                    const bgColors = [];

                                    res.data.candidates.forEach((c, index) => {
                                        const color = colors[index % colors.length];
                                        const percentage = formatPercentage(c.vote_count, totalVotes);
                                        
                                        labels.push(`Paslon ${c.order_number}`);
                                        dataPoints.push(c.vote_count);
                                        bgColors.push(color);

                                        const card = document.createElement('div');
                                        card.className = `p-4 border border-graphite-hairline rounded-[20px] bg-paper shadow-none flex flex-col relative overflow-hidden`;
                                        card.innerHTML = `
                                            <div class="absolute left-0 top-0 bottom-0 w-1" style="background-color: ${color}"></div>
                                            <div class="pl-2">
                                                <p class="text-sm text-slate mb-1">
                                                    ${c.order_number.toString().padStart(2, '0')} &middot; ${c.chairman_name}
                                                </p>
                                                <h4 class="text-3xl font-normal text-ink mb-1">${c.vote_count} <span class="text-xl">suara</span></h4>
                                                <p class="text-sm font-normal" style="color: ${color}">${percentage}</p>
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
                                                    borderWidth: 0,
                                                    borderRadius: 0, 
                                                    maxBarThickness: 60
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                scales: {
                                                    y: {
                                                        beginAtZero: true,
                                                        ticks: { stepSize: 1, precision: 0 }
                                                    }
                                                },
                                                plugins: { legend: { display: false } }
                                            }
                                        });
                                    }
                                }
                            });
                    }

                    fetchElectionData();
                    pollingInterval = setInterval(fetchElectionData, 10000); // 10 seconds
                @endif
            })();
        </script>
    @endpush
@endsection
