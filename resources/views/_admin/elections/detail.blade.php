@extends('_admin._layout.app')

@section('title', 'Detail Laporan: ' . $election->name)

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" navigate class="p-2 border border-graphite-hairline bg-paper rounded-full hover:bg-vellum transition-colors">
                <svg class="size-5 text-ink" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <h1 class="text-3xl font-normal text-ink">
                Detail Laporan
            </h1>
        </div>
        <x-admin.button href="{{ route('admin.dashboard.print', $election->id) }}" target="_blank" color="outline-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak Laporan
        </x-admin.button>
    </div>

    {{-- Info Card --}}
    <x-admin.card class="mb-6 border-graphite-hairline flex flex-col md:flex-row gap-8 items-center p-8">
        <!-- Total Votes -->
        <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-8 bg-vellum rounded-[20px]">
            <span class="text-sm font-normal text-slate uppercase tracking-wider mb-2">Total Suara Masuk</span>
            <span class="text-6xl font-normal text-ink tabular-nums" id="total-votes-detail">{{ $totalVotes }}</span>
        </div>
        <!-- Chart -->
        <div class="w-full md:w-2/3 h-[300px]">
            <canvas id="chart-detail"></canvas>
        </div>
    </x-admin.card>

    {{-- Table Results --}}
    <x-admin.card class="mb-6 border-graphite-hairline p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-graphite-hairline">
                <thead class="bg-paper">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-start text-xs font-normal text-slate uppercase">No. Urut</th>
                        <th scope="col" class="px-6 py-4 text-start text-xs font-normal text-slate uppercase">Nama Pasangan Calon</th>
                        <th scope="col" class="px-6 py-4 text-end text-xs font-normal text-slate uppercase">Perolehan Suara</th>
                        <th scope="col" class="px-6 py-4 text-end text-xs font-normal text-slate uppercase">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-graphite-hairline" id="candidates-table-body">
                    @foreach($candidates as $c)
                        @php
                            $percentage = $totalVotes > 0 ? round(($c->vote_count / $totalVotes) * 100, 1) : 0;
                        @endphp
                        <tr class="hover:bg-vellum/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-normal text-ink text-center">{{ $c->order_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">
                                {{ $c->chairman_name }} & {{ $c->vice_chairman_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-normal text-ink text-end" id="vote-count-{{ $c->id }}">
                                {{ $c->vote_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink text-end" id="vote-percentage-{{ $c->id }}">
                                {{ $percentage }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-admin.card>

    {{-- Recent Voting Sessions --}}
    <x-admin.card class="border-graphite-hairline p-0 overflow-hidden mb-6">
        <div class="p-6 border-b border-graphite-hairline">
            <h2 class="text-2xl font-normal text-ink">Riwayat Aktivitas Voting</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-graphite-hairline">
                <thead class="bg-paper">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-start text-xs font-normal text-slate uppercase">No.</th>
                        <th scope="col" class="px-6 py-4 text-start text-xs font-normal text-slate uppercase">Operator</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-normal text-slate uppercase">Status</th>
                        <th scope="col" class="px-6 py-4 text-start text-xs font-normal text-slate uppercase">Waktu Dibuka</th>
                        <th scope="col" class="px-6 py-4 text-start text-xs font-normal text-slate uppercase">Waktu Ditutup</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-graphite-hairline">
                    @forelse($recentSessions as $i => $session)
                        <tr class="hover:bg-vellum/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-normal text-ink">
                                {{ ($recentSessions->currentPage() - 1) * $recentSessions->perPage() + $i + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">{{ $session->operator_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <x-admin.badge :text="ucfirst($session->status)" :status="$session->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate">{{ \Carbon\Carbon::parse($session->open_time)->format('d M Y, H:i:s') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate">
                                {{ $session->close_time ? \Carbon\Carbon::parse($session->close_time)->format('d M Y, H:i:s') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-center text-slate">Belum ada aktivitas voting</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if (count($recentSessions) > 0 && $recentSessions->hasPages())
            <div class="p-4 border-t border-graphite-hairline">
                {{ $recentSessions->links() }}
            </div>
        @endif
    </x-admin.card>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (() => {
                const ctx = document.getElementById('chart-detail').getContext('2d');
                const initialLabels = @json($candidates->map(fn($c) => "Paslon {$c->order_number}")->values());
                const initialData = @json($candidates->pluck('vote_count')->values());

                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: initialLabels,
                        datasets: [{
                            label: 'Perolehan Suara',
                            data: initialData,
                            backgroundColor: '#171717',
                            borderColor: '#171717',
                            borderWidth: 1,
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

                function fetchLiveUpdates() {
                    fetch(`{{ route('admin.dashboard.data') }}?election_id={{ $election->id }}`)
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                document.getElementById('total-votes-detail').innerText = res.data.total_votes;
                                
                                const labels = res.data.candidates.map(c => `Paslon ${c.order_number}`);
                                const data = res.data.candidates.map(c => c.vote_count);
                                
                                chart.data.labels = labels;
                                chart.data.datasets[0].data = data;
                                chart.update();

                                // Update table
                                const tbody = document.getElementById('candidates-table-body');
                                tbody.innerHTML = '';
                                res.data.candidates.forEach(c => {
                                    const tr = document.createElement('tr');
                                    tr.className = 'hover:bg-vellum/50 transition';
                                    
                                    const pct = res.data.total_votes > 0 
                                        ? ((c.vote_count / res.data.total_votes) * 100).toFixed(1) 
                                        : 0;

                                    tr.innerHTML = `
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-normal text-ink text-center">${c.order_number}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">${c.chairman_name} & ${c.vice_chairman_name || ''}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-normal text-ink text-end">${c.vote_count}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-ink text-end">${pct}%</td>
                                    `;
                                    tbody.appendChild(tr);
                                });
                            }
                        });
                }

                setInterval(fetchLiveUpdates, 10000);
            })();
        </script>
    @endpush
@endsection
