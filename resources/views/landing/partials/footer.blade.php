<!-- FOOTER -->
<footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
    <div class="bg-gradient-to-b from-white via-blue-50/30 to-sky-100/40 rounded-[40px] border border-blue-100/80 shadow-[0_10px_30px_-5px_rgba(37,99,235,0.04)] p-8 sm:p-14 lg:p-16 relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start pb-12 border-b border-blue-100/80">
            <div class="lg:col-span-6">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo-light.png') }}" alt="Logo" class="h-8 w-auto">
                </div>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 leading-snug tracking-tight max-w-md">
                    Wujudkan Pemilihan Jujur, Cepat, dan Berintegritas.
                </h3>
                <p class="text-xs sm:text-[13px] text-slate-600 mt-3 max-w-md leading-relaxed font-normal">
                    Platform e-voting kiosk berstandar TPS pertama di Indonesia dengan jaminan 100% anonimitas suara dan verifikasi fisik teruji.
                </p>
            </div>

            <div class="lg:col-span-6 grid grid-cols-2 sm:grid-cols-3 gap-6 text-xs font-semibold">
                <div>
                    <p class="font-extrabold uppercase tracking-wider text-slate-400 mb-3.5 text-[11px]">Navigasi</p>
                    <ul class="space-y-2.5 p-0 list-none text-slate-700">
                        <li><a href="{{ route('login') }}" class="hover:text-blue-600 transition-colors">Masuk Akun</a></li>
                        <li><a href="{{ route('subscribe') }}" class="hover:text-blue-600 transition-colors">Daftar Langganan</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-extrabold uppercase tracking-wider text-slate-400 mb-3.5 text-[11px]">Keamanan</p>
                    <ul class="space-y-2.5 p-0 list-none text-slate-700">
                        <li><span class="text-slate-500">100% Suara Anonim</span></li>
                        <li><span class="text-slate-500">Anti-Double Voting</span></li>
                        <li><span class="text-slate-500">SHA-256 Hashing</span></li>
                    </ul>
                </div>
                <div>
                    <p class="font-extrabold uppercase tracking-wider text-slate-400 mb-3.5 text-[11px]">Bantuan</p>
                    <ul class="space-y-2.5 p-0 list-none text-slate-700">
                        <li><a href="{{ route('subscribe', ['package' => 'starter']) }}" class="hover:text-blue-600 transition-colors">Demo Simulasi</a></li>
                        <li><span class="text-slate-500">Dukungan Teknis</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="text-center pt-8 text-xs font-medium text-slate-400">
            &copy; 2026 SmartVoting — Platform E-Voting Kiosk Modern Berdasarkan Standar TPS Resmi.
        </div>
    </div>
</footer>
