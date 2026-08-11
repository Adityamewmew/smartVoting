@extends('_admin._layout.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $today = now();

        $greeting = match (true) {
            $today->hour < 11 => 'Selamat Pagi',
            $today->hour < 15 => 'Selamat Siang',
            $today->hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };

        $accentPalette = ['blue', 'emerald', 'purple', 'orange', 'indigo', 'violet'];

        $accent = [
            'blue' => [
                'bg' => 'from-blue-500/10 via-blue-500/5 to-transparent',
                'icon' => 'bg-gradient-to-br from-blue-500 to-blue-600 text-white',
                'icon_shadow' => 'shadow-blue-500/25',
                'arrow_hover' => 'group-hover:text-blue-400',
                'glow' => 'group-hover:shadow-blue-500/10',
            ],
            'emerald' => [
                'bg' => 'from-emerald-500/10 via-emerald-500/5 to-transparent',
                'icon' => 'bg-gradient-to-br from-emerald-500 to-emerald-600 text-white',
                'icon_shadow' => 'shadow-emerald-500/25',
                'arrow_hover' => 'group-hover:text-emerald-400',
                'glow' => 'group-hover:shadow-emerald-500/10',
            ],
            'purple' => [
                'bg' => 'from-purple-500/10 via-purple-500/5 to-transparent',
                'icon' => 'bg-gradient-to-br from-purple-500 to-purple-600 text-white',
                'icon_shadow' => 'shadow-purple-500/25',
                'arrow_hover' => 'group-hover:text-purple-400',
                'glow' => 'group-hover:shadow-purple-500/10',
            ],
            'orange' => [
                'bg' => 'from-orange-500/10 via-orange-500/5 to-transparent',
                'icon' => 'bg-gradient-to-br from-orange-500 to-orange-600 text-white',
                'icon_shadow' => 'shadow-orange-500/25',
                'arrow_hover' => 'group-hover:text-orange-400',
                'glow' => 'group-hover:shadow-orange-500/10',
            ],
            'indigo' => [
                'bg' => 'from-indigo-500/10 via-indigo-500/5 to-transparent',
                'icon' => 'bg-gradient-to-br from-indigo-500 to-indigo-600 text-white',
                'icon_shadow' => 'shadow-indigo-500/25',
                'arrow_hover' => 'group-hover:text-indigo-400',
                'glow' => 'group-hover:shadow-indigo-500/10',
            ],
            'violet' => [
                'bg' => 'from-violet-500/10 via-violet-500/5 to-transparent',
                'icon' => 'bg-gradient-to-br from-violet-500 to-violet-600 text-white',
                'icon_shadow' => 'shadow-violet-500/25',
                'arrow_hover' => 'group-hover:text-violet-400',
                'glow' => 'group-hover:shadow-violet-500/10',
            ],
        ];
    @endphp

    <div class="space-y-8">

        {{-- HERO --}}
        <section class="relative overflow-hidden rounded-[32px] bg-white dark:bg-neutral-900 border border-white/20 dark:border-white/5 shadow-[0_10px_60px_rgba(0,0,0,0.06)] mb-10">

            {{-- Background Decoration --}}
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-0 right-0 w-[420px] h-[420px] bg-blue-500/10 blur-3xl rounded-full"
                     style="animation: blob1 8s ease-in-out infinite;"></div>
                <div class="absolute bottom-0 left-0 w-[320px] h-[320px] bg-indigo-500/10 blur-3xl rounded-full"
                     style="animation: blob2 10s ease-in-out infinite;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-violet-500/5 blur-3xl rounded-full"
                     style="animation: blob3 14s ease-in-out infinite;"></div>
            </div>



            <div class="relative px-8 lg:px-10 py-10 lg:py-12">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-10">

                    {{-- LEFT --}}
                    <div class="max-w-2xl">

                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-neutral-100 dark:bg-white/5 mb-5">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>

                            <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                {{ $today->translatedFormat('l, d F Y') }}
                            </span>
                        </div>

                        <h1 class="text-4xl lg:text-5xl font-semibold tracking-tight text-neutral-900 dark:text-white leading-tight">
                            {{ $greeting }},
                            <span class="bg-gradient-to-r from-blue-600 to-indigo-500 bg-clip-text text-transparent">
                                {{ Auth::user()->name }}
                            </span>
                        </h1>

                        <p class="mt-4 text-[15px] leading-7 text-neutral-500 dark:text-neutral-400 max-w-xl">
                            Selamat datang kembali di Aplikasi Smart Project Starter Kit
                        </p>

                    </div>

                    {{-- RIGHT --}}
                    <div class="flex flex-col items-start lg:items-end">

                        <div class="text-[72px] leading-none font-light tracking-[-4px] text-neutral-900 dark:text-white tabular-nums"
                             id="dashboard-clock">
                            {{ $today->format('H:i') }}
                        </div>


                    </div>

                </div>
            </div>
        </section>

        {{-- LIVE POLLING --}}
        @if(isset($activeElections) && $activeElections->isNotEmpty())
        <section class="mb-10">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Laporan Hasil Suara (Live)
                    </h2>
                </div>
            </div>

            @foreach($activeElections as $election)
            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-3xl p-6 shadow-sm mb-6 flex flex-col md:flex-row gap-8 items-center hover:shadow-lg transition-shadow relative">
                <!-- Detail Button -->
                <a href="{{ route('admin.elections.detail', $election->id) }}" navigate class="absolute top-4 right-4 inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 transition z-10">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    Detail Laporan
                </a>

                <!-- Total Votes -->
                <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-8 bg-blue-50 dark:bg-blue-500/10 rounded-2xl border border-blue-100 dark:border-blue-500/20">
                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">Total Suara Masuk</span>
                    <span class="text-6xl font-black text-blue-700 dark:text-blue-500 tabular-nums" id="total-votes-{{ $election->id }}">0</span>
                    <span class="text-md font-semibold text-gray-500 dark:text-gray-400 mt-2 text-center">{{ $election->name }}</span>
                </div>
                <!-- Chart -->
                <div class="w-full md:w-2/3 h-[300px]">
                    <canvas id="chart-{{ $election->id }}"></canvas>
                </div>
            </div>
            @endforeach
        </section>
        @endif

        {{-- MENU --}}
        <section>

            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                        Modul Aplikasi
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                @forelse ($modules as $index => $menu)
                    @php
                        $accentKey = $accentPalette[$index % count($accentPalette)];
                        $c = $accent[$accentKey];

                        try {
                            $moduleUrl = route($menu->route_name);
                        } catch (\Exception $e) {
                            $moduleUrl = '#';
                        }

                        $moduleDesc = ! empty($menu->children)
                            ? collect($menu->children)->pluck('label')->join(', ')
                            : 'Buka modul ' . $menu->label . '.';
                    @endphp

                    <a navigate
                       href="{{ $moduleUrl }}"
                       class="group relative overflow-hidden rounded-[28px] bg-white dark:bg-neutral-900 border border-neutral-200/70 dark:border-white/5 p-7 transition-all duration-500 hover:-translate-y-1 hover:shadow-lg shadow-black/5 {{ $c['glow'] }}">

                        {{-- Gradient Layer --}}
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 bg-gradient-to-br {{ $c['bg'] }}"></div>

                        <div class="relative flex flex-col h-full">

                            {{-- TOP --}}
                            <div class="flex items-start justify-between">

                                <div class="size-14 rounded-2xl {{ $c['icon'] }} flex items-center justify-center shadow-lg {{ $c['icon_shadow'] }} ring-1 ring-white/20 ring-inset transition-all duration-300 group-hover:shadow-xl group-hover:scale-[1.03] [&_svg]:size-6">
                                    @if ($menu->icon)
                                        @include($menu->icon)
                                    @endif
                                </div>

                                <div class="translate-x-0 group-hover:translate-x-1 transition duration-300">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="size-5 text-neutral-300 dark:text-neutral-600 transition-colors {{ $c['arrow_hover'] }}"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                        />

                                    </svg>
                                </div>

                            </div>

                            {{-- CONTENT --}}
                            <div class="mt-10">

                                <h3 class="text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">
                                    Aplikasi {{ $menu->label }}
                                </h3>

                                <p class="mt-1 text-md text-neutral-400 dark:text-neutral-400">
                                    {{ $moduleDesc }}
                                </p>

                            </div>

                        </div>

                    </a>

                @empty
                    <div class="col-span-full rounded-[28px] border border-dashed border-neutral-200 dark:border-neutral-700 p-10 text-center">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">
                            Tidak ada modul tersedia untuk akun Anda.
                        </p>
                    </div>
                @endforelse

            </div>

        </section>

    </div>

    @push('scripts')
        <style>
            @keyframes blob1 {
                0%   { transform: translateX(0px); }
                20%  { transform: translateX(60px); }
                45%  { transform: translateX(-30px); }
                65%  { transform: translateX(80px); }
                80%  { transform: translateX(-50px); }
                100% { transform: translateX(0px); }
            }
            @keyframes blob2 {
                0%   { transform: translateX(0px); }
                25%  { transform: translateX(-70px); }
                50%  { transform: translateX(40px); }
                70%  { transform: translateX(-55px); }
                90%  { transform: translateX(30px); }
                100% { transform: translateX(0px); }
            }
            @keyframes blob3 {
                0%   { transform: translate(-50%, -50%) translateX(0px); }
                30%  { transform: translate(-50%, -50%) translateX(50px); }
                55%  { transform: translate(-50%, -50%) translateX(-60px); }
                75%  { transform: translate(-50%, -50%) translateX(35px); }
                100% { transform: translate(-50%, -50%) translateX(0px); }
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (() => {
                const clock = document.getElementById('dashboard-clock');

                if (clock) {
                    setInterval(() => {
                        const now = new Date();
                        clock.textContent = now.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }, 1000);
                }

                @if(isset($activeElections) && $activeElections->isNotEmpty())
                    const charts = {};
                    
                    @foreach($activeElections as $election)
                        const ctx{{ $election->id }} = document.getElementById('chart-{{ $election->id }}').getContext('2d');
                        charts[{{ $election->id }}] = new Chart(ctx{{ $election->id }}, {
                            type: 'bar',
                            data: {
                                labels: [],
                                datasets: [{
                                    label: 'Perolehan Suara',
                                    data: [],
                                    backgroundColor: 'rgba(59, 130, 246, 0.8)', // blue-500
                                    borderColor: 'rgb(37, 99, 235)', // blue-600
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
                                        ticks: {
                                            stepSize: 1,
                                            precision: 0
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });

                        function fetchElectionData{{ $election->id }}() {
                            fetch(`{{ route('admin.dashboard.data') }}?election_id={{ $election->id }}`)
                                .then(res => res.json())
                                .then(res => {
                                    if (res.success) {
                                        // Update total votes with animation-like effect or directly
                                        document.getElementById('total-votes-{{ $election->id }}').innerText = res.data.total_votes;
                                        
                                        const labels = res.data.candidates.map(c => `Paslon ${c.order_number}: ${c.chairman_name}`);
                                        const data = res.data.candidates.map(c => c.vote_count);
                                        
                                        charts[{{ $election->id }}].data.labels = labels;
                                        charts[{{ $election->id }}].data.datasets[0].data = data;
                                        charts[{{ $election->id }}].update();
                                    }
                                });
                        }

                        // Initial fetch and interval
                        fetchElectionData{{ $election->id }}();
                        setInterval(fetchElectionData{{ $election->id }}, 10000); // 10 seconds
                    @endforeach
                @endif
            })();
        </script>
    @endpush

@endsection