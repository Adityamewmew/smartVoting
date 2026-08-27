<!DOCTYPE html>
<html class="scroll-smooth" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>SmartVoting — Platform E-Voting Kiosk Modern, Aman & Terpercaya</title>
    <meta name="description"
        content="Sistem e-voting digital berbasis bilik suara kiosk fisik untuk OSIS, BEM, dan Institusi. 100% suara anonim, anti-double voting, dan rekapitulasi real-time." />

    {{-- Favicon --}}
    @include('_admin._layout.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">

    <!-- Styles / Scripts Starterkit -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/admin-custom.css', 'resources/js/admin-custom.js'])

    <style>
        body {
            font-family: 'Geist', ui-sans-serif, system-ui;
            background-color: #F8FAFC;
            color: #0F172A;
            overflow-x: hidden;
        }

        .dotted-canvas {
            background-color: #FFFFFF;
            background-image: radial-gradient(#CBD5E1 1.25px, transparent 1.25px);
            background-size: 22px 22px;
        }

        .dotted-canvas-subtle {
            background-color: #F8FAFC;
            background-image: radial-gradient(#E2E8F0 1.2px, transparent 1.2px);
            background-size: 20px 20px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 28px;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 9999px;
            border: none;
            background: linear-gradient(180deg, #3B82F6 0%, #2563EB 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.35), 0 8px 20px 0 rgba(37, 99, 235, 0.38);
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: linear-gradient(180deg, #60A5FA 0%, #3B82F6 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 12px 28px 0 rgba(37, 99, 235, 0.5);
            transform: translateY(-2px);
        }

        .btn-primary:active {
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.25);
            transform: translateY(1px);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            color: #334155;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 9999px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03), inset 0 1px 0 #FFFFFF;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #F1F5F9;
            border-color: #CBD5E1;
            color: #0F172A;
            transform: translateY(-1px);
        }

        .squircle-tile {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 22px;
            background: linear-gradient(145deg, #FFFFFF 0%, #F1F5F9 100%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.12), 0 4px 10px -2px rgba(15, 23, 42, 0.04), inset 0 2px 0 rgba(255, 255, 255, 0.95), inset 0 -2px 0 rgba(0, 0, 0, 0.05);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }

        .squircle-tile:hover {
            transform: translateY(-5px) scale(1.04);
            box-shadow: 0 20px 36px -8px rgba(15, 23, 42, 0.18), inset 0 2px 0 rgba(255, 255, 255, 1);
        }

        .section-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 18px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 9999px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03), inset 0 1px 0 #FFFFFF;
            margin-bottom: 16px;
        }

        .folder-tab {
            position: relative;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06);
        }

        .folder-tab::before {
            content: '';
            position: absolute;
            top: -12px;
            left: 20px;
            width: 70px;
            height: 14px;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        .post-it {
            background: linear-gradient(135deg, #FEF08A 0%, #FDE047 100%);
            box-shadow: 0 12px 24px -6px rgba(202, 138, 4, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            transform: rotate(-3.5deg);
            transition: transform 0.25s ease;
        }

        .post-it:hover {
            transform: rotate(0deg) scale(1.02);
        }

        .pin-head {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, #EF4444 0%, #B91C1C 100%);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }
    </style>
</head>

<body class="antialiased selection:bg-brand-500 selection:text-white">

    <!-- TOP NAVIGATION BAR -->
    <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
        <nav class="bg-white rounded-full px-6 sm:px-8 py-3.5 flex justify-between items-center border border-slate-100 shadow-sm"
            aria-label="Navigasi Utama">

            <!-- Brand Logo -->
            <a href="#hero" class="flex items-center gap-3 no-underline text-slate-900 group">
                <div class="grid grid-cols-2 gap-1 w-6 h-6">
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-600 group-hover:scale-110 transition-transform"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-900 group-hover:scale-110 transition-transform"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-900 group-hover:scale-110 transition-transform"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-900 group-hover:scale-110 transition-transform"></span>
                </div>
                <span class="font-extrabold text-xl tracking-tight text-slate-900">Smart<span class="text-brand-600">Voting</span></span>
                <span class="ml-1 bg-brand-50 border border-brand-200 text-brand-700 text-[10px] font-bold px-2 py-0.5 rounded-full hidden sm:inline-block">v5 • Multi-Tenant SaaS</span>
            </a>

            <!-- Desktop Links -->
            <div class="hidden md:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                <a class="hover:text-brand-600 transition-colors" href="#solutions">Solusi TPS</a>
                <a class="hover:text-brand-600 transition-colors" href="#features">Fitur Kiosk</a>
                <a class="hover:text-brand-600 transition-colors" href="#testimonials">Testimoni</a>
                <a class="hover:text-brand-600 transition-colors" href="#pricing">Paket Harga</a>
            </div>

            <!-- Action Buttons -->
            <div class="hidden md:flex items-center space-x-3">
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-brand-600 px-4 py-2 transition-colors">
                    Masuk Akun
                </a>
                <a href="{{ route('subscribe') }}" class="btn-primary py-2.5 px-6 text-xs font-bold">
                    Daftar / Berlangganan
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button type="button" class="text-slate-700 hover:text-brand-600 p-2" id="mobile-menu-btn" aria-label="Buka Menu">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
            </div>
        </nav>

        <!-- Mobile Dropdown -->
        <div class="hidden md:hidden mt-3 bg-white rounded-3xl p-6 border border-slate-100 shadow-xl space-y-3" id="mobile-menu">
            <a class="block font-semibold text-slate-700 hover:text-brand-600 py-2" href="#solutions">Solusi TPS</a>
            <a class="block font-semibold text-slate-700 hover:text-brand-600 py-2" href="#features">Fitur Kiosk</a>
            <a class="block font-semibold text-slate-700 hover:text-brand-600 py-2" href="#testimonials">Testimoni</a>
            <a class="block font-semibold text-slate-700 hover:text-brand-600 py-2" href="#pricing">Paket Harga</a>
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="btn-secondary w-full text-center text-xs">Masuk Akun</a>
                <a href="{{ route('subscribe') }}" class="btn-primary w-full text-center text-xs">Daftar / Berlangganan</a>
            </div>
        </div>
    </header>

    <main>
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
                    <div class="squircle-tile w-14 h-14 mt-4 -ml-2 bg-white">
                        <div class="w-8 h-8 rounded-xl bg-brand-600 text-white flex items-center justify-center text-sm font-bold shadow-squircle-blue">
                            <i class="fa-solid fa-check"></i>
                        </div>
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
                            <span class="text-[10px] bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded-md border border-emerald-200">Aktif</span>
                        </div>
                        <p class="text-xs font-bold text-slate-800 m-0">Pemilihan Ketua OSIS 2026</p>
                        <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5">
                            <i class="fa-regular fa-clock text-brand-600"></i> 08:00 - 14:00 WIB
                        </p>
                    </div>
                </div>

                <!-- Center App Logo Tile -->
                <div class="flex justify-center mb-6 relative z-10">
                    <div class="squircle-tile w-20 h-20 bg-white shadow-squircle-3d">
                        <div class="grid grid-cols-2 gap-1.5 w-8 h-8">
                            <span class="w-3.5 h-3.5 rounded-full bg-brand-600 shadow-sm"></span>
                            <span class="w-3.5 h-3.5 rounded-full bg-slate-900 shadow-sm"></span>
                            <span class="w-3.5 h-3.5 rounded-full bg-slate-900 shadow-sm"></span>
                            <span class="w-3.5 h-3.5 rounded-full bg-slate-900 shadow-sm"></span>
                        </div>
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
                    <a href="{{ route('subscribe', ['package' => 'pro']) }}" class="btn-primary">
                        <i class="fa-solid fa-rocket text-sm"></i>
                        <span>Mulai Berlangganan Sekarang</span>
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary">
                        <i class="fa-solid fa-lock text-slate-500"></i>
                        <span>Masuk Dashboard Institusi</span>
                    </a>
                </div>

            </div>
        </section>

        <!-- SOLUTIONS SECTION -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20" id="solutions">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="section-pill">Solusi TPS Lapangan</div>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-950 tracking-tight">
                    Selesaikan Tantangan Terbesar Pemilihan Anda
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16 max-w-5xl mx-auto text-center md:text-left">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-base flex-shrink-0 font-bold">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">Cegah Suara Ganda & Bot</h3>
                        <p class="text-xs text-slate-600 leading-relaxed m-0">Verifikasi fisik di meja TPS memastikan hanya pemilih sah yang dibukakan bilik suara token sekali pakai.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center text-base flex-shrink-0 font-bold">
                        <i class="fa-solid fa-user-secret"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">100% Suara Rahasia & Bebas</h3>
                        <p class="text-xs text-slate-600 leading-relaxed m-0">Data pilihan dienkripsi tanpa menyimpan biodata pemilih pada tabel suara.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-base flex-shrink-0 font-bold">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">Rekapitulasi Detik Itu Juga</h3>
                        <p class="text-xs text-slate-600 leading-relaxed m-0">Begitu sesi berakhir, grafik perolehan suara langsung tersedia untuk Berita Acara resmi PDF.</p>
                    </div>
                </div>
            </div>
        </section>

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
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-card flex flex-col justify-between">
                    <div>
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-slate-900 m-0">Paket Uji Coba / Trial</h3>
                            <p class="text-xs text-slate-500 mt-1">Cocok untuk uji coba sistem dan simulasi 1 pemilihan.</p>
                        </div>
                        <div class="mb-6">
                            <span class="text-4xl font-black text-slate-900">Gratis</span>
                            <span class="text-xs text-slate-500">/ 14 hari trial</span>
                        </div>
                        <a href="{{ route('subscribe', ['package' => 'starter']) }}" class="btn-secondary w-full text-center text-xs font-bold mb-6">
                            Mulai Trial Gratis
                        </a>
                        <ul class="space-y-3 text-xs text-slate-600 font-medium p-0 list-none">
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> 1 Event Pemilihan Aktif</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Maksimal 3 Paslon</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Layar Kiosk TPS Sentuh</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Rekapitulasi Standar</li>
                        </ul>
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="rounded-3xl p-8 text-white shadow-2xl flex flex-col justify-between relative transform lg:-translate-y-4"
                    style="background: linear-gradient(180deg, #2563EB 0%, #1D4ED8 100%);">
                    <div class="squircle-tile w-12 h-12 bg-white text-amber-500 absolute -top-5 right-6 shadow-squircle-3d">
                        <i class="fa-solid fa-bolt text-lg"></i>
                    </div>

                    <div>
                        <div class="mb-6">
                            <span class="text-[10px] font-black uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full inline-block mb-2">Paling Populer</span>
                            <h3 class="text-xl font-black text-white m-0">Paket Sekolah & OSIS</h3>
                            <p class="text-xs text-blue-100 mt-1">Solusi penuh pemilihan umum sekolah dan kampus 1 tahun penuh.</p>
                        </div>
                        <div class="mb-6">
                            <span class="text-4xl font-black text-white">Rp 1.500.000</span>
                            <span class="text-xs text-blue-200">/ tahun</span>
                        </div>
                        <a href="{{ route('subscribe', ['package' => 'pro']) }}"
                            class="bg-white text-brand-700 hover:bg-slate-50 font-black py-3.5 px-6 rounded-full text-xs text-center w-full block shadow-lg transition-all mb-6">
                            Daftar Paket Pro
                        </a>
                        <ul class="space-y-3 text-xs text-white font-medium p-0 list-none">
                            <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Multi-Event Tanpa Batas</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Paslon & Foto HD Tanpa Batas</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Multi-Bilik Kiosk TPS Serentak</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Live Polling Telemetri Real-Time</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-300"></i> Cetak Berita Acara PDF Resmi</li>
                        </ul>
                    </div>
                </div>

                <!-- Custom / Enterprise Plan -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-card flex flex-col justify-between">
                    <div>
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-slate-900 m-0">Paket Kampus & Organisasi</h3>
                            <p class="text-xs text-slate-500 mt-1">Untuk pemilu raya universitas multi-fakultas atau korporat.</p>
                        </div>
                        <div class="mb-6">
                            <span class="text-4xl font-black text-slate-900">Rp 3.500.000</span>
                            <span class="text-xs text-slate-500">/ tahun</span>
                        </div>
                        <a href="{{ route('subscribe', ['package' => 'enterprise']) }}" class="btn-secondary w-full text-center text-xs font-bold mb-6">
                            Daftar Paket Enterprise
                        </a>
                        <ul class="space-y-3 text-xs text-slate-600 font-medium p-0 list-none">
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Integrasi Server Khusus</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Pendampingan Teknis Hari-H</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Kustomisasi Domain & Logo</li>
                            <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-600"></i> Audit Keamanan & Database Terisolasi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div class="dotted-canvas rounded-[40px] border border-slate-200/90 shadow-container-outer p-8 sm:p-14 lg:p-16 relative overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start pb-12 border-b border-slate-200/80">
                    <div class="lg:col-span-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="grid grid-cols-2 gap-1 w-6 h-6">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                            </div>
                            <span class="font-black text-2xl tracking-tight text-slate-900">Smart<span class="text-brand-600">Voting</span></span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 leading-snug max-w-md">
                            Wujudkan Pemilihan Jujur, Cepat, dan Berintegritas.
                        </h3>
                        <p class="text-xs text-slate-500 mt-3 max-w-md">
                            Platform e-voting kiosk standar TPS pertama di Indonesia dengan garansi anonimitas dan verifikasi fisik terbukti.
                        </p>
                    </div>

                    <div class="lg:col-span-6 grid grid-cols-2 sm:grid-cols-3 gap-6 text-xs font-semibold">
                        <div>
                            <p class="font-extrabold uppercase tracking-wider text-slate-400 mb-3">Navigasi</p>
                            <ul class="space-y-2 p-0 list-none text-slate-700">
                                <li><a href="{{ route('login') }}" class="hover:text-brand-600 transition-colors">→ Masuk Akun</a></li>
                                <li><a href="{{ route('subscribe') }}" class="hover:text-brand-600 transition-colors">→ Daftar Langganan</a></li>
                            </ul>
                        </div>
                        <div>
                            <p class="font-extrabold uppercase tracking-wider text-slate-400 mb-3">Keamanan</p>
                            <ul class="space-y-2 p-0 list-none text-slate-700">
                                <li><span class="text-slate-500">→ 100% Suara Anonim</span></li>
                                <li><span class="text-slate-500">→ Anti-Double Voting</span></li>
                                <li><span class="text-slate-500">→ SHA-256 Hashing</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="text-center pt-8 text-xs font-semibold text-slate-400">
                    © 2026 SmartVoting — Platform E-Voting Kiosk Modern Berdasarkan Standar TPS Resmi.
                </div>
            </div>
        </footer>
    </main>

    <script>
        const mBtn = document.getElementById('mobile-menu-btn');
        const mMenu = document.getElementById('mobile-menu');
        if (mBtn && mMenu) {
            mBtn.addEventListener('click', () => mMenu.classList.toggle('hidden'));
        }
    </script>
</body>
</html>
