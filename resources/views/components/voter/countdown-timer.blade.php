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
         class="mt-3 mb-2 inline-flex flex-col items-center bg-gradient-to-b from-white via-white to-slate-50/80 border border-slate-200/90 rounded-3xl p-4 sm:p-5 shadow-[0_12px_28px_-6px_rgba(15,23,42,0.06),inset_0_1px_1px_rgba(255,255,255,1)] fade-up d-2">
        
        {{-- Status Header Badge --}}
        <div class="mb-3.5 flex items-center gap-2">
            @if($isActive)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80 shadow-2xs">
                    <span class="size-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span class="size-2 rounded-full bg-emerald-500 -ml-3.5"></span>
                    <span id="countdown-label">Sisa Waktu Pemungutan Suara</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200/80 shadow-2xs">
                    <svg class="size-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span id="countdown-label">Pemilihan Dimulai Dalam</span>
                </span>
            @endif
        </div>

        {{-- Skeuomorphic Digit Blocks --}}
        <div class="flex items-center gap-2 sm:gap-3 text-center">
            {{-- Hari --}}
            <div class="flex flex-col items-center">
                <div id="cd-days" class="w-13 sm:w-15 h-12 sm:h-14 rounded-2xl bg-gradient-to-b from-white to-slate-100 border border-slate-200/90 flex items-center justify-center text-xl sm:text-2xl font-black text-slate-900 font-mono shadow-[0_3px_6px_rgba(0,0,0,0.03),inset_0_1px_0_rgba(255,255,255,1)] tracking-tight">00</div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1.5">Hari</span>
            </div>
            <span class="text-slate-300 font-black text-xl mb-5">:</span>
            
            {{-- Jam --}}
            <div class="flex flex-col items-center">
                <div id="cd-hours" class="w-13 sm:w-15 h-12 sm:h-14 rounded-2xl bg-gradient-to-b from-white to-slate-100 border border-slate-200/90 flex items-center justify-center text-xl sm:text-2xl font-black text-slate-900 font-mono shadow-[0_3px_6px_rgba(0,0,0,0.03),inset_0_1px_0_rgba(255,255,255,1)] tracking-tight">00</div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1.5">Jam</span>
            </div>
            <span class="text-slate-300 font-black text-xl mb-5">:</span>
            
            {{-- Menit --}}
            <div class="flex flex-col items-center">
                <div id="cd-minutes" class="w-13 sm:w-15 h-12 sm:h-14 rounded-2xl bg-gradient-to-b from-white to-slate-100 border border-slate-200/90 flex items-center justify-center text-xl sm:text-2xl font-black text-slate-900 font-mono shadow-[0_3px_6px_rgba(0,0,0,0.03),inset_0_1px_0_rgba(255,255,255,1)] tracking-tight">00</div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1.5">Menit</span>
            </div>
            <span class="text-slate-300 font-black text-xl mb-5">:</span>
            
            {{-- Detik --}}
            <div class="flex flex-col items-center">
                <div id="cd-seconds" class="w-13 sm:w-15 h-12 sm:h-14 rounded-2xl bg-gradient-to-b from-blue-50/80 to-blue-100/60 border border-blue-200 flex items-center justify-center text-xl sm:text-2xl font-black text-blue-700 font-mono shadow-[0_3px_6px_rgba(37,99,235,0.06),inset_0_1px_0_rgba(255,255,255,1)] tracking-tight">00</div>
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1.5">Detik</span>
            </div>
        </div>
    </div>
@elseif($isEnded)
    <div class="mt-2 mb-2 inline-flex items-center gap-2 bg-gradient-to-b from-slate-50 to-slate-100 border border-slate-200/90 text-slate-600 text-xs font-bold px-4 py-2.5 rounded-2xl shadow-xs fade-up d-2">
        <svg class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
