<!-- PRICING & SUBSCRIPTION SECTION -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20" id="pricing">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <div class="section-pill">Paket Berlangganan</div>
        <h2 class="text-3xl sm:text-5xl font-black text-slate-950 tracking-tight">
            Pilihan Paket Sesuai Kebutuhan Institusi
        </h2>
        <p class="text-xs sm:text-sm text-slate-600 mt-2">Daftar sekarang, selesaikan pembayaran via Mayar, dan langsung kelola pemilihan Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch max-w-6xl mx-auto">
        <!-- Starter Plan -->
        <x-admin.card class="p-8 rounded-3xl border border-slate-200 shadow-card flex flex-col justify-between">
            <div>
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900 m-0">Paket Uji Coba / Trial</h3>
                    <p class="text-xs text-slate-500 mt-1">Cocok untuk uji coba sistem dan simulasi 1 pemilihan.</p>
                </div>
                <div class="mb-6">
                    <span class="text-4xl font-black text-slate-900">Gratis</span>
                    <span class="text-xs text-slate-500">/ 14 hari trial</span>
                </div>
                <x-admin.button href="{{ route('subscribe', ['package' => 'starter']) }}" color="secondary" size="md" class="w-full mb-6">
                    Mulai Trial Gratis
                </x-admin.button>
                <ul class="space-y-3 text-xs text-slate-600 font-medium p-0 list-none">
                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> 1 Event Pemilihan Aktif</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Maksimal 3 Paslon</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Layar Kiosk TPS Sentuh</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Rekapitulasi Standar</li>
                </ul>
            </div>
        </x-admin.card>

        <!-- Pro Plan -->
        <div class="rounded-3xl p-8 text-white shadow-2xl flex flex-col justify-between relative transform lg:-translate-y-4"
            style="background: linear-gradient(180deg, #2563EB 0%, #1D4ED8 100%);">
            <div class="squircle-tile w-12 h-12 bg-white text-amber-500 absolute -top-5 right-6 shadow-squircle-3d">
                <i class="fa-solid fa-bolt text-lg"></i>
            </div>

            <div>
                <div class="mb-6">
                    <span class="text-[10px] font-black uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full inline-block mb-2">Paling Populer</span>
                    <h3 class="text-xl font-black text-white m-0">Paket Sekolah &amp; OSIS</h3>
                    <p class="text-xs text-blue-100 mt-1">Solusi penuh pemilihan umum sekolah dan kampus 1 tahun penuh.</p>
                </div>
                <div class="mb-6">
                    <span class="text-4xl font-black text-white">Rp 1.500.000</span>
                    <span class="text-xs text-blue-200">/ tahun</span>
                </div>
                <x-admin.button href="{{ route('subscribe', ['package' => 'pro']) }}" color="secondary" size="lg" class="w-full bg-white text-blue-700 hover:bg-slate-50 font-black mb-6">
                    Daftar Paket Pro
                </x-admin.button>
                <ul class="space-y-3 text-xs text-white font-medium p-0 list-none">
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Multi-Event Tanpa Batas</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Paslon &amp; Foto HD Tanpa Batas</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Multi-Bilik Kiosk TPS Serentak</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Live Polling Telemetri Real-Time</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Cetak Berita Acara PDF Resmi</li>
                </ul>
            </div>
        </div>

        <!-- Custom / Enterprise Plan -->
        <x-admin.card class="p-8 rounded-3xl border border-slate-200 shadow-card flex flex-col justify-between">
            <div>
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900 m-0">Paket Kampus &amp; Organisasi</h3>
                    <p class="text-xs text-slate-500 mt-1">Untuk pemilu raya universitas multi-fakultas atau korporat.</p>
                </div>
                <div class="mb-6">
                    <span class="text-4xl font-black text-slate-900">Rp 3.500.000</span>
                    <span class="text-xs text-slate-500">/ tahun</span>
                </div>
                <x-admin.button href="{{ route('subscribe', ['package' => 'enterprise']) }}" color="secondary" size="md" class="w-full mb-6">
                    Daftar Paket Enterprise
                </x-admin.button>
                <ul class="space-y-3 text-xs text-slate-600 font-medium p-0 list-none">
                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Integrasi Server Khusus</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Pendampingan Teknis Hari-H</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Kustomisasi Domain &amp; Logo</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Audit Keamanan &amp; Database Terisolasi</li>
                </ul>
            </div>
        </x-admin.card>
    </div>
</section>
