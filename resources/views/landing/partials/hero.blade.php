<!-- HERO SECTION -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-2 pb-8" id="hero">
    <div class="bg-gradient-to-b from-sky-100/70 via-blue-50/40 to-white rounded-[40px] border border-blue-100/80 shadow-[0_20px_50px_-15px_rgba(37,99,235,0.08)] p-8 sm:p-14 lg:p-20 relative overflow-hidden text-center">

        <!-- Top Left Floating Element (Sticky Note) -->
        <div class="hidden lg:block absolute top-10 left-10 text-left z-20 transition-transform duration-300 hover:scale-105 hover:-rotate-1">
            <div class="relative bg-gradient-to-br from-yellow-300 via-amber-300 to-yellow-400 text-slate-800 p-5 w-52 rounded-xl shadow-[0_16px_32px_-6px_rgba(202,138,4,0.35),0_6px_12px_rgba(0,0,0,0.06)] -rotate-3 border border-yellow-200/60">
                <!-- Red Push Pin 3D -->
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-gradient-to-br from-red-400 via-red-500 to-red-700 shadow-[0_3px_6px_rgba(0,0,0,0.35),inset_0_1px_1px_rgba(255,255,255,0.7)] border border-red-600"></div>
                <p class="text-xs font-semibold text-slate-800 leading-snug font-sans m-0">
                    Verifikasi absensi fisik pemilih di meja TPS, lalu buka 1 sesi bilik suara anonim sekali pakai.
                </p>
            </div>
        </div>

        <!-- Top Right Floating Element (Folder Tab & Stopwatch Badge) -->
        <div class="hidden lg:block absolute top-8 right-10 text-left z-20 transition-transform duration-300 hover:scale-105">
            <!-- Overlapping Stopwatch Squircle Badge -->
            <div class="w-14 h-14 ml-auto -mb-5 mr-3 relative z-30 bg-gradient-to-b from-white to-slate-50 rounded-2xl border border-blue-100/80 shadow-[0_10px_25px_-4px_rgba(15,23,42,0.12),0_4px_8px_rgba(0,0,0,0.04)] flex items-center justify-center text-slate-700">
                <i class="fa-solid fa-stopwatch text-2xl text-slate-700"></i>
            </div>
            <!-- Folder Tab Body -->
            <div class="relative p-5 w-60 bg-white/95 backdrop-blur-md rounded-2xl rounded-tl-none border border-blue-100/80 shadow-[0_16px_35px_-8px_rgba(15,23,42,0.1),0_4px_12px_rgba(0,0,0,0.04)] z-20">
                <!-- Top Tab Notch -->
                <div class="absolute -top-3.5 left-0 w-20 h-4 bg-white border-t border-l border-r border-blue-100/80 rounded-t-xl"></div>
                <div class="flex items-center justify-between mb-2 relative z-10">
                    <span class="text-xs font-extrabold text-slate-900">Jadwal Sesi</span>
                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 rounded-lg shadow-2xs">Aktif</span>
                </div>
                <p class="text-xs font-bold text-slate-800 m-0">Pemilihan Ketua OSIS 2026</p>
                <p class="text-[11px] text-slate-500 mt-1.5 flex items-center gap-1.5 m-0">
                    <i class="fa-regular fa-clock text-blue-600"></i> 08:00 - 14:00 WIB
                </p>
            </div>
        </div>

        <!-- Center App Logo Tile -->
        <div class="flex justify-center mb-6 relative z-10">
            <div class="px-6 py-3.5 bg-gradient-to-b from-white to-slate-50 rounded-2xl border border-blue-100/80 shadow-[0_12px_28px_-6px_rgba(15,23,42,0.1),inset_0_1px_1px_rgba(255,255,255,1)] flex items-center justify-center transition-transform hover:scale-105 duration-200">
                <img src="{{ asset('images/logo-light.png') }}" alt="SmartVoting" class="h-9 w-auto object-contain">
            </div>
        </div>

        <!-- Headline -->
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-slate-950 tracking-tight leading-[1.1] max-w-4xl mx-auto mb-6 relative z-10">
            Pilih, Kelola, dan Pantau <br />
            <span class="text-slate-500 font-black">Hasil Pemilihan Terpadu.</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed font-normal relative z-10">
            Solusi e-voting modern untuk sekolah, kampus, dan organisasi. Dilengkapi bilik suara sentuh, verifikasi fisik teruji, serta rekapitulasi suara instan dan transparan.
        </p>

        <!-- CTA Action -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center relative z-10">
            <x-admin.button href="{{ route('auth.google') }}" color="primary" size="lg" icon='<svg class="size-4.5 inline-block" viewBox="0 0 24 24"><path fill="#ffffff" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/><path fill="#ffffff" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.34 24 12 24z"/><path fill="#ffffff" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/><path fill="#ffffff" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/></svg>'>
                <span>Coba Aplikasi Sekarang</span>
            </x-admin.button>
            <x-admin.button href="{{ route('login') }}" color="secondary" size="lg" icon='<i class="fa-solid fa-arrow-right-to-bracket text-slate-500"></i>'>
                <span>Masuk ke Akun</span>
            </x-admin.button>
        </div>

    </div>
</section>
