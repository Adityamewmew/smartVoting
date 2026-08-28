<!-- TOP NAVIGATION BAR -->
<header class="sticky top-3 z-50 w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 pb-2">
    <nav class="bg-white/90 backdrop-blur-md rounded-full px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center border border-blue-100/70 shadow-sm transition-all"
        aria-label="Navigasi Utama">

        <!-- Brand Logo -->
        <a href="#hero" class="flex items-center gap-3 no-underline text-slate-900 group shrink-0">
            <img src="{{ asset('images/logo-light.png') }}" alt="Logo" class="h-7 sm:h-8 w-auto">
        </a>

        <!-- Desktop Links -->
        <div class="hidden lg:flex items-center space-x-6 xl:space-x-8 text-sm font-semibold text-slate-600 whitespace-nowrap">
            <a class="hover:text-blue-600 transition-colors" href="#solutions">Solusi TPS</a>
            <a class="hover:text-blue-600 transition-colors" href="#features">Fitur Unggulan</a>
            <a class="hover:text-blue-600 transition-colors" href="#testimonials">Testimoni</a>
            <a class="hover:text-blue-600 transition-colors" href="#pricing">Paket Harga</a>
        </div>

        <!-- Action Buttons -->
        <div class="hidden lg:flex items-center space-x-2.5 whitespace-nowrap shrink-0">
            <x-admin.button href="{{ route('login') }}" color="outline-secondary" size="sm">
                Masuk Akun
            </x-admin.button>
            <x-admin.button href="{{ route('subscribe') }}" color="primary" size="sm">
                Daftar / Berlangganan
            </x-admin.button>
        </div>

        <!-- Mobile Menu Button -->
        <div class="lg:hidden flex items-center">
            <button type="button" class="text-slate-700 hover:text-blue-600 p-2 rounded-xl border border-gray-200/80 bg-white/50 cursor-pointer" id="mobile-menu-btn" aria-label="Buka Menu">
                <i class="fa-solid fa-bars-staggered text-lg"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Dropdown -->
    <div class="hidden lg:hidden mt-2 bg-white/95 backdrop-blur-md rounded-3xl p-5 border border-blue-100/70 shadow-xl space-y-2" id="mobile-menu">
        <a class="block font-semibold text-slate-700 hover:text-blue-600 py-2 px-3 rounded-xl hover:bg-blue-50/50 transition-colors" href="#solutions">Solusi TPS</a>
        <a class="block font-semibold text-slate-700 hover:text-blue-600 py-2 px-3 rounded-xl hover:bg-blue-50/50 transition-colors" href="#features">Fitur Unggulan</a>
        <a class="block font-semibold text-slate-700 hover:text-blue-600 py-2 px-3 rounded-xl hover:bg-blue-50/50 transition-colors" href="#testimonials">Testimoni</a>
        <a class="block font-semibold text-slate-700 hover:text-blue-600 py-2 px-3 rounded-xl hover:bg-blue-50/50 transition-colors" href="#pricing">Paket Harga</a>
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row gap-2">
            <x-admin.button href="{{ route('login') }}" color="secondary" size="sm" class="w-full justify-center">
                Masuk Akun
            </x-admin.button>
            <x-admin.button href="{{ route('subscribe') }}" color="primary" size="sm" class="w-full justify-center">
                Daftar / Berlangganan
            </x-admin.button>
        </div>
    </div>
</header>
