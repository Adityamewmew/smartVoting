<!-- CTA GET STARTED SECTION -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" id="cta-start">
    <div class="rounded-[40px] p-8 sm:p-14 lg:p-16 text-white text-center shadow-2xl relative overflow-hidden"
        style="background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #1d4ed8 100%);">
        
        <!-- Subtle background glow -->
        <div class="absolute -top-24 -right-24 size-96 bg-sky-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 size-96 bg-blue-600/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-3xl mx-auto relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/15 text-blue-100 backdrop-blur-xs mb-4 border border-white/20">
                <i class="fa-solid fa-bolt text-amber-300"></i>
                <span>Akses Cepat &amp; Praktis</span>
            </span>

            <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight mb-4">
                Siap Selenggarakan Pemilihan yang Cepat, Transparan, &amp; Modern?
            </h2>

            <p class="text-sm sm:text-base text-blue-100 max-w-2xl mx-auto mb-8 leading-relaxed font-normal">
                Mulai buat event pemilihan digital pertama institusi Anda sekarang. Dilengkapi bilik suara sentuh, verifikasi fisik teruji, serta rekapitulasi real-time instan.
            </p>

            <!-- Feature Pills -->
            <div class="flex flex-wrap justify-center items-center gap-3 sm:gap-4 mb-8 text-xs font-semibold text-blue-50">
                <span class="flex items-center gap-2 bg-white/10 px-3.5 py-1.5 rounded-xl border border-white/10">
                    <i class="fa-solid fa-circle-check text-emerald-300"></i> 100% Suara Anonim
                </span>
                <span class="flex items-center gap-2 bg-white/10 px-3.5 py-1.5 rounded-xl border border-white/10">
                    <i class="fa-solid fa-circle-check text-emerald-300"></i> Setup TPS &lt; 5 Menit
                </span>
                <span class="flex items-center gap-2 bg-white/10 px-3.5 py-1.5 rounded-xl border border-white/10">
                    <i class="fa-solid fa-circle-check text-emerald-300"></i> Rekapitulasi Real-Time
                </span>
            </div>

            <!-- CTA Button -->
            <div class="flex justify-center items-center">
                <x-admin.button href="{{ route('auth.google') }}" color="secondary" size="lg" class="bg-white text-blue-700 hover:bg-slate-50 font-black px-8 py-3.5 shadow-xl hover:scale-105 transition-all duration-200" icon='<svg class="size-5 inline-block" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.34 24 12 24z"/><path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/></svg>'>
                    <span>Coba Aplikasi Sekarang</span>
                </x-admin.button>
            </div>
        </div>
    </div>
</section>
