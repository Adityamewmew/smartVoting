<!-- FOOTER -->
<footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="dotted-canvas rounded-[40px] border border-slate-200/90 shadow-container-outer p-8 sm:p-14 lg:p-16 relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start pb-12 border-b border-slate-200/80">
            <div class="lg:col-span-6">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo-light.png') }}" alt="Logo" class="h-8 w-auto">
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
                        <li><a href="{{ route('login') }}" class="hover:text-brand-600 transition-colors">Masuk Akun</a></li>
                        <li><a href="{{ route('subscribe') }}" class="hover:text-brand-600 transition-colors">Daftar Langganan</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-extrabold uppercase tracking-wider text-slate-400 mb-3">Keamanan</p>
                    <ul class="space-y-2 p-0 list-none text-slate-700">
                        <li><span class="text-slate-500">100% Suara Anonim</span></li>
                        <li><span class="text-slate-500">Anti-Double Voting</span></li>
                        <li><span class="text-slate-500">SHA-256 Hashing</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="text-center pt-8 text-xs font-semibold text-slate-400">
            &copy; 2026 SmartVoting — Platform E-Voting Kiosk Modern Berdasarkan Standar TPS Resmi.
        </div>
    </div>
</footer>
