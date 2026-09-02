<!-- SOLUTIONS SECTION -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" id="solutions">
    <div class="text-center max-w-3xl mx-auto mb-10">
        <h2 class="text-3xl sm:text-5xl font-black text-slate-950 tracking-tight leading-tight">
            Hadirkan Pemilihan Jujur, Aman, dan Bebas Manipulasi
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 max-w-5xl mx-auto text-center md:text-left">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-base flex-shrink-0 font-bold">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Verifikasi Sah di Meja TPS</h3>
                <p class="text-xs text-slate-600 leading-relaxed m-0">Memastikan hanya pemilih terdaftar yang diberikan akses bilik suara satu kali pakai.</p>
            </div>
        </div>

        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center text-base flex-shrink-0 font-bold">
                <i class="fa-solid fa-user-secret"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-1">100% Suara Rahasia &amp; Anonim</h3>
                <p class="text-xs text-slate-600 leading-relaxed m-0">Pilihan suara dienkripsi tanpa menyimpan identitas pemilih pada rekaman suara.</p>
            </div>
        </div>

        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-base flex-shrink-0 font-bold">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Rekapitulasi Suara Seketika</h3>
                <p class="text-xs text-slate-600 leading-relaxed m-0">Perolehan suara terhitung otomatis dan siap dicetak menjadi Berita Acara resmi PDF.</p>
            </div>
        </div>
    </div>

    <!-- Interactive Admin Dashboard Mockup Showcase -->
    <div class="max-w-6xl mx-auto mt-8 relative text-left">
        <!-- Outer Glassmorphism Card Container -->
        <div class="bg-white/95 rounded-[32px] border border-blue-100/70 shadow-[0_20px_50px_-15px_rgba(15,23,42,0.06)] p-6 sm:p-8 space-y-6">

            {{-- 1. Header & Quick Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/70 p-4 sm:p-6 rounded-2xl border border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-2xs">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 m-0">Pemilihan Ketua & Wakil Ketua 2026</h3>
                            <x-admin.badge status="active" size="sm" pulse="true">Aktif</x-admin.badge>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-2 m-0">
                            <span class="inline-flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg> 29 August 2026</span>
                            <span>•</span>
                            <span class="inline-flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> 08:00 - 14:00 WIB</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <x-admin.button color="outline-primary" size="sm" icon='<i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>'>
                        Buka Landing Slug
                    </x-admin.button>
                    <x-admin.button color="secondary" size="sm" icon='<i class="fa-solid fa-print text-[11px]"></i>'>
                        Cetak Laporan
                    </x-admin.button>
                </div>
            </div>

            {{-- Top KPI Summary Cards Grid (4 Cards) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- KPI 1: Total Suara -->
                <div class="bg-gradient-to-br from-blue-50/60 via-white to-white rounded-2xl p-4 sm:p-6 border border-blue-100 flex flex-col justify-between shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">TOTAL SUARA</span>
                        <x-admin.badge color="blue" size="sm" pulse="true">LIVE</x-admin.badge>
                    </div>
                    <div class="my-3">
                        <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">197</span>
                        <span class="text-xs text-slate-400 font-medium ml-1">suara masuk</span>
                    </div>
                    <p class="text-[11px] text-slate-400 flex items-center gap-1.5 m-0">
                        <i class="fa-solid fa-circle-check text-blue-500 text-xs"></i>
                        Akumulasi seluruh bilik suara
                    </p>
                </div>

                <!-- KPI 2: Paslon Terdepan -->
                <div class="bg-gradient-to-br from-emerald-50/60 via-white to-white rounded-2xl p-4 sm:p-6 border border-emerald-100 flex flex-col justify-between shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">PASLON TERDEPAN</span>
                        <x-admin.badge color="emerald" size="sm">54.3%</x-admin.badge>
                    </div>
                    <div class="my-2 flex items-center gap-2.5">
                        <span class="bg-blue-600 text-white font-extrabold text-xs px-2 py-0.5 rounded-lg shadow-xs">01</span>
                        <div class="truncate">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate leading-tight m-0">Aria Setiawan</h4>
                            <span class="text-[11px] text-slate-500 truncate block">&amp; Budi Santoso</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 m-0">107 suara diperoleh (Unggul #1)</p>
                </div>

                <!-- KPI 3: Bilik Suara -->
                <div class="bg-gradient-to-br from-indigo-50/40 via-white to-white rounded-2xl p-4 sm:p-6 border border-indigo-100 flex flex-col justify-between shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">BILIK SUARA</span>
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    </div>
                    <div class="my-3">
                        <span class="text-3xl sm:text-4xl font-black text-indigo-600 tracking-tight">4</span>
                        <span class="text-xs text-slate-400 font-medium ml-1">bilik aktif</span>
                    </div>
                    <p class="text-[11px] text-slate-400 m-0">Dari total 4 sesi bilik TPS tercatat</p>
                </div>

                <!-- KPI 4: Status Event -->
                <div class="bg-gradient-to-br from-slate-50/60 via-white to-white rounded-2xl p-4 sm:p-6 border border-slate-200 flex flex-col justify-between shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">STATUS EVENT</span>
                        <x-admin.badge status="active" size="sm">Aktif</x-admin.badge>
                    </div>
                    <div class="my-2">
                        <p class="text-xs sm:text-sm font-bold text-slate-900 m-0">29 Agu 2026</p>
                        <p class="text-xs text-slate-500 font-medium mt-0.5 m-0">08:00 - 14:00 WIB</p>
                    </div>
                    <p class="text-[11px] text-slate-400 m-0">Slug: <span class="font-mono text-slate-600 font-bold">pemilu-2026</span></p>
                </div>
            </div>

            {{-- 4. Distribusi Perolehan Suara (Bar Chart) --}}
            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h4 class="text-sm sm:text-base font-bold text-slate-900 m-0">Distribusi Perolehan Suara</h4>
                        <p class="text-xs text-slate-500 mt-0.5 m-0">Perbandingan jumlah suara sah antar pasangan calon.</p>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                        Bar Chart
                    </span>
                </div>
                <!-- Mockup Bar Chart Graphic -->
                <div class="w-full h-56 flex items-end justify-around gap-6 pt-8 px-4 sm:px-8 border-b border-slate-100">
                    <!-- Bar Paslon 01 -->
                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                        <span class="text-xs font-extrabold text-blue-600">107 (54.3%)</span>
                        <div class="w-full max-w-[110px] bg-gradient-to-t from-blue-600 to-blue-500 rounded-t-xl transition-all shadow-sm group-hover:opacity-90" style="height: 75%"></div>
                        <div class="text-center mt-1">
                            <span class="block text-xs font-bold text-slate-900">01. Aria Setiawan</span>
                            <span class="block text-[11px] text-slate-500">&amp; Budi Santoso</span>
                        </div>
                    </div>
                    <!-- Bar Paslon 02 -->
                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                        <span class="text-xs font-extrabold text-slate-700">62 (31.5%)</span>
                        <div class="w-full max-w-[110px] bg-gradient-to-t from-slate-700 to-slate-500 rounded-t-xl transition-all shadow-sm group-hover:opacity-90" style="height: 48%"></div>
                        <div class="text-center mt-1">
                            <span class="block text-xs font-bold text-slate-900">02. Maya Wijaya</span>
                            <span class="block text-[11px] text-slate-500">&amp; Hendra Kusuma</span>
                        </div>
                    </div>
                    <!-- Bar Paslon 03 -->
                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                        <span class="text-xs font-extrabold text-slate-500">28 (14.2%)</span>
                        <div class="w-full max-w-[110px] bg-gradient-to-t from-slate-400 to-slate-300 rounded-t-xl transition-all shadow-sm group-hover:opacity-90" style="height: 25%"></div>
                        <div class="text-center mt-1">
                            <span class="block text-xs font-bold text-slate-900">03. Rahmat Hidayat</span>
                            <span class="block text-[11px] text-slate-500">&amp; Siti Aminah</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Tabel Ringkasan Kandidat Menggunakan Komponen Admin Table --}}
            <div class="space-y-3 pt-2">
                <div class="flex items-center justify-between px-1">
                    <div>
                        <h4 class="text-sm sm:text-base font-bold text-slate-900 m-0">Ringkasan Kandidat &amp; Pangsa Suara</h4>
                        <p class="text-xs text-slate-500 mt-0.5 m-0">Tabel data perolehan suara sah terintegrasi sistem admin.</p>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                        Live Data Table
                    </span>
                </div>

                <x-admin.table.wrapper>
                    <x-admin.table.table>
                        <x-admin.table.thead>
                            <x-admin.table.th align="center">No. Urut</x-admin.table.th>
                            <x-admin.table.th>Pasangan Calon</x-admin.table.th>
                            <x-admin.table.th>Perolehan Suara</x-admin.table.th>
                            <x-admin.table.th>Grafik Persentase</x-admin.table.th>
                            <x-admin.table.th align="end">Persentase</x-admin.table.th>
                        </x-admin.table.thead>
                        <x-admin.table.tbody>
                            <x-admin.table.tr>
                                <x-admin.table.td innerClass="px-6 py-4 text-center">
                                    <span class="bg-blue-600 text-white font-extrabold text-xs px-2.5 py-0.5 rounded-lg shadow-xs">01</span>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 font-extrabold text-xs flex items-center justify-center">01</div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-xs m-0">Aria Setiawan</p>
                                             <p class="text-[11px] text-slate-500 m-0">&amp; Budi Santoso</p>
                                        </div>
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <span class="font-bold text-slate-900 text-xs">107 Suara</span>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="w-40 bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-blue-600 h-full rounded-full" style="width: 54.3%"></div>
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td innerClass="px-6 py-4 text-right">
                                    <span class="font-black text-blue-600 text-xs">54.3%</span>
                                </x-admin.table.td>
                            </x-admin.table.tr>

                            <x-admin.table.tr>
                                <x-admin.table.td innerClass="px-6 py-4 text-center">
                                    <span class="bg-slate-900 text-white font-extrabold text-xs px-2.5 py-0.5 rounded-lg shadow-xs">02</span>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 font-extrabold text-xs flex items-center justify-center">02</div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-xs m-0">Maya Wijaya</p>
                                            <p class="text-[11px] text-slate-500 m-0">&amp; Hendra Kusuma</p>
                                        </div>
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <span class="font-bold text-slate-900 text-xs">62 Suara</span>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="w-40 bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-slate-700 h-full rounded-full" style="width: 31.5%"></div>
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td innerClass="px-6 py-4 text-right">
                                    <span class="font-black text-slate-700 text-xs">31.5%</span>
                                </x-admin.table.td>
                            </x-admin.table.tr>

                            <x-admin.table.tr>
                                <x-admin.table.td innerClass="px-6 py-4 text-center">
                                    <span class="bg-slate-900 text-white font-extrabold text-xs px-2.5 py-0.5 rounded-lg shadow-xs">03</span>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 font-extrabold text-xs flex items-center justify-center">03</div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-xs m-0">Rahmat Hidayat</p>
                                            <p class="text-[11px] text-slate-500 m-0">&amp; Siti Aminah</p>
                                        </div>
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <span class="font-bold text-slate-900 text-xs">28 Suara</span>
                                </x-admin.table.td>
                                <x-admin.table.td>
                                    <div class="w-40 bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-amber-500 h-full rounded-full" style="width: 14.2%"></div>
                                    </div>
                                </x-admin.table.td>
                                <x-admin.table.td innerClass="px-6 py-4 text-right">
                                    <span class="font-black text-amber-600 text-xs">14.2%</span>
                                </x-admin.table.td>
                            </x-admin.table.tr>
                        </x-admin.table.tbody>
                    </x-admin.table.table>
                </x-admin.table.wrapper>
            </div>

        </div>
    </div>
</section>
