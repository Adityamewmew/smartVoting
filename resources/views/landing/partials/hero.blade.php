<!-- HERO SECTION -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-16" id="hero">
    <div class="dotted-canvas rounded-[40px] border border-slate-200/90 shadow-container-outer p-8 sm:p-14 lg:p-20 relative overflow-hidden text-center">

        <!-- Top Left Floating Element -->
        <div class="hidden lg:block absolute top-12 left-10 text-left z-20 animate-float-slow">
            <div class="post-it p-5 w-52 relative">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 pin-head"></div>
                <p class="text-xs font-semibold text-slate-800 leading-snug font-sans m-0">
                    Verifikasi absensi fisik pemilih di meja TPS, lalu buka 1 sesi bilik suara anonim sekali pakai.
                </p>
            </div>
        </div>

        <!-- Top Right Floating Element -->
        <div class="hidden lg:block absolute top-12 right-10 text-left z-20 animate-float-alt">
            <div class="squircle-tile w-16 h-16 ml-auto -mb-6 relative z-30 bg-white">
                <i class="fa-solid fa-stopwatch text-2xl text-slate-700"></i>
            </div>
            <div class="folder-tab p-5 w-60 bg-white/95 backdrop-blur-sm relative z-20">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-extrabold text-slate-900">Jadwal Sesi</span>
                    <x-admin.badge status="active" size="sm">Aktif</x-admin.badge>
                </div>
                <p class="text-xs font-bold text-slate-800 m-0">Pemilihan Ketua OSIS 2026</p>
                <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5">
                    <i class="fa-regular fa-clock text-brand-600"></i> 08:00 - 14:00 WIB
                </p>
            </div>
        </div>

        <!-- Center App Logo Tile -->
        <div class="flex justify-center mb-6 relative z-10">
            <div class="squircle-tile px-6 py-3.5 bg-white shadow-squircle-3d flex items-center justify-center">
                <img src="{{ asset('images/logo-light.png') }}" alt="Logo Starter Kit" class="h-9 w-auto object-contain">
            </div>
        </div>

        <!-- Headline -->
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-slate-950 tracking-tight leading-[1.1] max-w-3xl mx-auto mb-6 relative z-10">
            Pilih, Kelola, dan Pantau <br />
            <span class="text-slate-400 font-extrabold">Dalam Satu Sistem Terpadu.</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-base sm:text-lg text-slate-600 max-w-xl mx-auto mb-10 leading-relaxed relative z-10">
            Digitalisasi pemungutan suara di bilik TPS dengan verifikasi absensi fisik terbukti, 100% anonimitas pemilih, dan rekapitulasi instan.
        </p>

        <!-- CTA Action -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center relative z-10">
            <x-admin.button href="{{ route('subscribe', ['package' => 'pro']) }}" color="primary" size="lg" icon='<i class="fa-solid fa-rocket text-sm"></i>'>
                <span>Mulai Berlangganan Sekarang</span>
            </x-admin.button>
            <x-admin.button href="{{ route('login') }}" color="secondary" size="lg" icon='<i class="fa-solid fa-lock text-slate-500"></i>'>
                <span>Masuk Dashboard Institusi</span>
            </x-admin.button>
        </div>

    </div>
</section>
