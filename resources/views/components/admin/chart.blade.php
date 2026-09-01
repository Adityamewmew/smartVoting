@props([
    'id' => 'chart-' . uniqid(),
    'type' => 'bar',
    'labels' => [],
    'data' => [],
    'height' => 'h-64 sm:h-72 md:h-80',
    'colors' => ['#2563EB', '#059669', '#D97706', '#7C3AED', '#DC2626', '#0891B2'],
    'label' => 'Perolehan Suara',
])

<div {{ $attributes->merge(['class' => "w-full $height relative"]) }}>
    <canvas id="{{ $id }}"></canvas>
</div>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endPushOnce

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('{{ $id }}');
            if (!ctx) return;

            const chartType = '{{ $type }}';
            const labels = @json($labels);
            const rawData = @json($data);
            const colors = @json($colors);
            const bgColors = colors.slice(0, labels.length);

            if (chartType === 'doughnut') {
                const hasData = rawData.length && rawData.some(v => v > 0);
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: hasData ? rawData : [1],
                            backgroundColor: hasData ? bgColors : ['#E2E8F0'],
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
            } else {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: '{{ $label }}',
                            data: rawData,
                            backgroundColor: bgColors,
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
            }
        });
    </script>
@endpush
