<!-- TOP NAVIGATION BAR -->
<header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
    <nav class="bg-white rounded-full px-6 sm:px-8 py-3.5 flex justify-between items-center border border-slate-100 shadow-sm"
        aria-label="Navigasi Utama">

        <!-- Brand Logo -->
        <a href="#hero" class="flex items-center gap-3 no-underline text-slate-900 group">
            <img src="{{ asset('images/logo-light.png') }}" alt="Logo" class="h-8 w-auto">
        </a>

        <!-- Desktop Links -->
        <div class="hidden md:flex items-center space-x-8 text-sm font-semibold text-slate-600">
            <a class="hover:text-brand-600 transition-colors" href="#solutions">Solusi TPS</a>
            <a class="hover:text-brand-600 transition-colors" href="#features">Fitur Unggulan</a>
            <a class="hover:text-brand-600 transition-colors" href="#testimonials">Testimoni</a>
            <a class="hover:text-brand-600 transition-colors" href="#pricing">Paket Harga</a>
        </div>

        <!-- Action Buttons -->
        <div class="hidden md:flex items-center space-x-3">
            <x-admin.button href="{{ route('login') }}" color="outline-secondary" size="sm">
                Masuk Akun
            </x-admin.button>
            <x-admin.button href="{{ route('subscribe') }}" color="primary" size="sm">
                Daftar / Berlangganan
            </x-admin.button>
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
        <a class="block font-semibold text-slate-700 hover:text-brand-600 py-2" href="#features">Fitur Unggulan</a>
        <a class="block font-semibold text-slate-700 hover:text-brand-600 py-2" href="#testimonials">Testimoni</a>
        <a class="block font-semibold text-slate-700 hover:text-brand-600 py-2" href="#pricing">Paket Harga</a>
        <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
            <x-admin.button href="{{ route('login') }}" color="secondary" size="sm" class="w-full">
                Masuk Akun
            </x-admin.button>
            <x-admin.button href="{{ route('subscribe') }}" color="primary" size="sm" class="w-full">
                Daftar / Berlangganan
            </x-admin.button>
        </div>
    </div>
</header>
