@props([
    'onboarding' => [
        'steps' => [],
        'completed_count' => 0,
        'total_steps' => 4,
        'progress_percentage' => 0,
        'all_completed' => false,
    ],
])

@php
    $steps = $onboarding['steps'] ?? [];
    $completedCount = $onboarding['completed_count'] ?? 0;
    $totalSteps = $onboarding['total_steps'] ?? 4;
    $percentage = $onboarding['progress_percentage'] ?? 0;
    
    // Cari langkah pertama yang belum selesai
    $nextActiveStep = collect($steps)->firstWhere('is_completed', false)['step'] ?? null;
@endphp

<x-admin.card {{ $attributes->merge(['class' => 'p-6 sm:p-8 overflow-hidden']) }}>
    {{-- Header & Progress Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">Langkah Persiapan Pemilihan</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Selesaikan tahapan berikut untuk memulai pemungutan suara di institusi Anda.</p>
        </div>

        <div class="bg-gray-50/80 p-4 rounded-2xl border border-gray-200/80 min-w-[240px] shrink-0">
            <div class="flex items-center justify-between text-xs font-bold text-gray-700 mb-2">
                <span>Progres Persiapan</span>
                <span class="text-blue-600 font-black">{{ $completedCount }}/{{ $totalSteps }} ({{ $percentage }}%)</span>
            </div>
            <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-full rounded-full transition-all duration-700 ease-out" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    </div>

    {{-- 4 Step Cards in Clean Uniform 2x2 Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($steps as $st)
            @php
                $isCompleted = $st['is_completed'] ?? false;
                $isCurrentNext = ($st['step'] === $nextActiveStep);
            @endphp
            <div class="p-4 sm:p-6 rounded-2xl border border-gray-200/80 bg-white shadow-2xs hover:shadow-xs transition-all duration-200 flex flex-col justify-between">
                
                {{-- Step Body --}}
                <div class="flex items-start gap-4 mb-6">
                    @if($isCompleted)
                        {{-- Completed: Emerald Button Style --}}
                        <span class="size-9 rounded-xl bg-gradient-to-b from-emerald-500 to-emerald-600 text-white font-bold text-sm shrink-0 shadow-md shadow-emerald-500/25 border-t border-white/25 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                    @elseif($isCurrentNext)
                        {{-- Active Next: Primary Blue Button Style --}}
                        <span class="size-9 rounded-xl bg-gradient-to-b from-blue-500 to-blue-600 text-white font-bold text-sm shrink-0 shadow-md shadow-blue-500/25 border-t border-white/25 flex items-center justify-center">
                            {{ $st['step'] }}
                        </span>
                    @else
                        {{-- Inactive: Secondary Button Style --}}
                        <span class="size-9 rounded-xl bg-gradient-to-b from-white to-gray-50 text-gray-700 font-bold text-sm shrink-0 border border-gray-200/90 shadow-2xs flex items-center justify-center">
                            {{ $st['step'] }}
                        </span>
                    @endif

                    <div class="space-y-0.5 flex-1 min-w-0">
                        <h3 class="text-sm font-bold text-gray-900 truncate">
                            {{ $st['title'] }}
                        </h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ $st['desc'] }}
                        </p>
                    </div>
                </div>

                {{-- Step Footer: Action Button Only --}}
                <div class="flex items-center justify-end pt-3.5 border-t border-gray-100">
                    <x-admin.button 
                        :href="$st['action_url']" 
                        :color="$isCurrentNext ? 'primary' : 'secondary'" 
                        size="sm"
                    >
                        <span>{{ $st['action_text'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </x-admin.button>
                </div>
            </div>
        @endforeach
    </div>
</x-admin.card>
