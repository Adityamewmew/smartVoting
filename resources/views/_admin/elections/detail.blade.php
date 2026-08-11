@extends('_admin._layout.app')

@section('title', 'Detail Laporan: ' . $election->name)

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" navigate class="p-2 border border-gray-200 bg-white rounded-lg hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:hover:bg-neutral-700">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">
                Detail Laporan
            </h1>
        </div>
        <a href="{{ route('admin.dashboard.print', $election->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak Laporan
        </a>
    </div>

    {{-- Info Card --}}
    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-3xl p-6 shadow-sm mb-6 flex flex-col md:flex-row gap-8 items-center">
        <!-- Total Votes -->
        <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-8 bg-blue-50 dark:bg-blue-500/10 rounded-2xl border border-blue-100 dark:border-blue-500/20">
            <span class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">Total Suara Masuk</span>
            <span class="text-6xl font-black text-blue-700 dark:text-blue-500 tabular-nums" id="total-votes-detail">{{ $totalVotes }}</span>
        </div>
        <!-- Chart -->
        <div class="w-full md:w-2/3 h-[300px]">
            <canvas id="chart-detail"></canvas>
        </div>
    </div>

    {{-- Table Results --}}
    <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-2xl overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
            <thead class="bg-gray-50 dark:bg-neutral-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">No. Urut</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Nama Pasangan Calon</th>
                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Perolehan Suara</th>
                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-400">Persentase</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700" id="candidates-table-body">
                @foreach($candidates as $c)
                    @php
                        $percentage = $totalVotes > 0 ? round(($c->vote_count / $totalVotes) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800 dark:text-neutral-200 text-center">{{ $c->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            {{ $c->chairman_name }} & {{ $c->vice_chairman_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800 dark:text-neutral-200 text-end" id="vote-count-{{ $c->id }}">
                            {{ $c->vote_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 text-end" id="vote-percentage-{{ $c->id }}">
                            {{ $percentage }}%
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

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
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgb(37, 99, 235)',
                            borderWidth: 1,
                            borderRadius: 8,
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

                function fetchElectionData() {
                    fetch(`{{ route('admin.dashboard.data') }}?election_id={{ $election->id }}`)
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                const totalVotes = res.data.total_votes;
                                document.getElementById('total-votes-detail').innerText = totalVotes;
                                
                                const labels = res.data.candidates.map(c => `Paslon ${c.order_number}`);
                                const data = res.data.candidates.map(c => c.vote_count);
                                
                                chart.data.labels = labels;
                                chart.data.datasets[0].data = data;
                                chart.update();

                                res.data.candidates.forEach(c => {
                                    const voteCell = document.getElementById(`vote-count-${c.id}`);
                                    const percCell = document.getElementById(`vote-percentage-${c.id}`);
                                    if (voteCell && percCell) {
                                        voteCell.innerText = c.vote_count;
                                        percCell.innerText = totalVotes > 0 ? ((c.vote_count / totalVotes) * 100).toFixed(1) + '%' : '0%';
                                    }
                                });
                            }
                        });
                }

                setInterval(fetchElectionData, 10000); // 10 seconds
            })();
        </script>
    @endpush
@endsection
