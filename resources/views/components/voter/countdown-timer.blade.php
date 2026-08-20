@props(['election'])

@php
    $now = now();
    $startTime = $election->start_time ? \Carbon\Carbon::parse($election->start_time) : null;
    $endTime = $election->end_time ? \Carbon\Carbon::parse($election->end_time) : null;

    $isDraft = ($election->status === 'draft');
    $isUpcoming = (!$isDraft && $startTime && $now->lt($startTime));
    $isActive = (!$isDraft && $election->status === 'active' && $startTime && $endTime && $now->gte($startTime) && $now->lte($endTime));
    $isEnded = (!$isDraft && ($election->status === 'inactive' || ($endTime && $now->gt($endTime))));

    $targetTimeIso = null;
    $countdownType = null;
    if ($isUpcoming && $startTime) {
        $targetTimeIso = $startTime->toIso8601String();
        $countdownType = 'upcoming';
    } elseif ($isActive && $endTime) {
        $targetTimeIso = $endTime->toIso8601String();
        $countdownType = 'active';
    }
@endphp

@if($targetTimeIso)
    <div id="countdown-wrapper"
         data-target="{{ $targetTimeIso }}"
         data-type="{{ $countdownType }}"
         class="mt-3 mb-2 inline-flex flex-col items-center bg-white/90 backdrop-blur-sm border border-gray-200/90 rounded-2xl p-4 sm:px-6 shadow-sm fade-up d-2">
        <span class="text-xs font-semibold text-gray-500 mb-2.5 flex items-center gap-1.5">
            <svg class="size-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span id="countdown-label">{{ $isUpcoming ? 'Pemilihan Dimulai Dalam' : 'Sisa Waktu Pemungutan Suara' }}</span>
        </span>
        <div class="flex items-center gap-2 sm:gap-3 text-center">
            {{-- Hari --}}
            <div class="flex flex-col items-center">
                <div id="cd-days" class="w-12 sm:w-14 h-11 sm:h-12 rounded-xl bg-gray-50 border border-gray-200/80 flex items-center justify-center text-lg sm:text-xl font-extrabold text-gray-900 shadow-inner">00</div>
                <span class="text-[10px] font-bold text-gray-400 uppercase mt-1">Hari</span>
            </div>
            <span class="text-gray-300 font-bold text-lg mb-4">:</span>
            {{-- Jam --}}
            <div class="flex flex-col items-center">
                <div id="cd-hours" class="w-12 sm:w-14 h-11 sm:h-12 rounded-xl bg-gray-50 border border-gray-200/80 flex items-center justify-center text-lg sm:text-xl font-extrabold text-gray-900 shadow-inner">00</div>
                <span class="text-[10px] font-bold text-gray-400 uppercase mt-1">Jam</span>
            </div>
            <span class="text-gray-300 font-bold text-lg mb-4">:</span>
            {{-- Menit --}}
            <div class="flex flex-col items-center">
                <div id="cd-minutes" class="w-12 sm:w-14 h-11 sm:h-12 rounded-xl bg-gray-50 border border-gray-200/80 flex items-center justify-center text-lg sm:text-xl font-extrabold text-gray-900 shadow-inner">00</div>
                <span class="text-[10px] font-bold text-gray-400 uppercase mt-1">Menit</span>
            </div>
            <span class="text-gray-300 font-bold text-lg mb-4">:</span>
            {{-- Detik --}}
            <div class="flex flex-col items-center">
                <div id="cd-seconds" class="w-12 sm:w-14 h-11 sm:h-12 rounded-xl bg-blue-50 border border-blue-200/80 flex items-center justify-center text-lg sm:text-xl font-extrabold text-blue-600 shadow-inner">00</div>
                <span class="text-[10px] font-bold text-blue-500 uppercase mt-1">Detik</span>
            </div>
        </div>
    </div>
@elseif($isEnded)
    <div class="mt-2 mb-2 inline-flex items-center gap-2 bg-gray-50 border border-gray-200 text-gray-500 text-xs font-semibold px-4 py-2 rounded-xl fade-up d-2">
        <svg class="size-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Waktu pemungutan suara telah berakhir
    </div>
@endif

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var wrapper = document.getElementById('countdown-wrapper');
    if (!wrapper) return;

    var targetTime = new Date(wrapper.getAttribute('data-target')).getTime();
    var daysEl = document.getElementById('cd-days');
    var hoursEl = document.getElementById('cd-hours');
    var minutesEl = document.getElementById('cd-minutes');
    var secondsEl = document.getElementById('cd-seconds');

    function pad(n) { return n < 10 ? '0' + n : n; }

    function updateCountdown() {
        var now = new Date().getTime();
        var diff = targetTime - now;

        if (diff <= 0) {
            if (daysEl) daysEl.textContent = '00';
            if (hoursEl) hoursEl.textContent = '00';
            if (minutesEl) minutesEl.textContent = '00';
            if (secondsEl) secondsEl.textContent = '00';
            clearInterval(timer);
            setTimeout(function () { window.location.reload(); }, 2000);
            return;
        }

        var days = Math.floor(diff / (1000 * 60 * 60 * 24));
        var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((diff % (1000 * 60)) / 1000);

        if (daysEl) daysEl.textContent = pad(days);
        if (hoursEl) hoursEl.textContent = pad(hours);
        if (minutesEl) minutesEl.textContent = pad(minutes);
        if (secondsEl) secondsEl.textContent = pad(seconds);
    }

    updateCountdown();
    var timer = setInterval(updateCountdown, 1000);
});
</script>
@endpush
@endonce
