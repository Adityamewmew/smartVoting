@props([
    'text',
    'status' => 'draft',
])

@php
    $statusClasses = match ($status) {
        'draft' => 'glass-card border-[var(--color-brand-brown)]/30 text-[var(--color-brand-brown)] bg-white/50 backdrop-blur-sm',
        'active' => 'glass-button text-white font-semibold shadow-sm',
        'closed' => 'glass-card border-ink/30 text-ink bg-white/50 backdrop-blur-sm',
        'scheduled' => 'glass-card border-[var(--color-brand-yellow)]/50 text-[var(--color-brand-yellow)] bg-white/50 backdrop-blur-sm',
        default => 'glass-card border-slate/30 text-slate bg-white/50 backdrop-blur-sm',
    };
@endphp

<span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-semibold {{ $statusClasses }}">
    {{ $text }}
</span>
