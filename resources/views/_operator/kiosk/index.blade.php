@extends('_admin._layout.operator')

@section('title', 'Manajemen Bilik Suara')

@section('content')
    <x-admin.page-header title="Manajemen Bilik Suara" subtitle="Daftar event pemilihan yang sedang aktif" />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($data as $election)
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 shadow-sm flex flex-col">
                <h3 class="text-lg font-bold text-gray-800 dark:text-neutral-200">{{ $election->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-400 mt-1 mb-4">{{ $election->description ?? 'Tidak ada deskripsi' }}</p>
                
                <div class="flex-grow">
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div class="bg-gray-50 dark:bg-neutral-900 rounded-lg p-3 text-center border border-gray-100 dark:border-neutral-800">
                            <span class="block text-xs text-gray-500 dark:text-neutral-400 font-semibold mb-1">Total Suara</span>
                            <span class="block text-xl font-black text-blue-600 dark:text-blue-500">{{ $election->total_votes }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-neutral-900 rounded-lg p-3 text-center border border-gray-100 dark:border-neutral-800">
                            <span class="block text-xs text-gray-500 dark:text-neutral-400 font-semibold mb-1">Sesi Aktif</span>
                            <span class="block text-xl font-black {{ $election->active_sessions > 0 ? 'text-amber-500' : 'text-gray-700 dark:text-neutral-300' }}">{{ $election->active_sessions }}</span>
                        </div>
                    </div>
                </div>
                
                {{-- Tombol lihat paslon --}}
                <button
                    type="button"
                    onclick="showCandidates({{ $election->id }}, '{{ addslashes($election->name) }}')"
                    class="mb-3 w-full py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors"
                >
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Lihat Daftar Paslon
                </button>

                {{-- Tombol buka bilik --}}
                <form action="{{ route('operator.kiosk.generate', $election->id) }}" method="POST" target="_blank">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none shadow-md shadow-blue-500/20">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        Buka Bilik Suara
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-full">
                <x-admin.empty-state title="Tidak ada event aktif" message="Saat ini tidak ada event pemilihan yang berstatus aktif." />
            </div>
        @endforelse
    </div>

    {{-- Modal Detail Paslon --}}
    <div id="modal-candidates" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div id="modal-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeCandidatesModal()"></div>

        {{-- Modal Panel --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-neutral-800 rounded-2xl shadow-2xl overflow-hidden transform transition-all">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
                    <div>
                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-0.5">Daftar Paslon</p>
                        <h3 id="modal-election-name" class="text-lg font-bold text-gray-900 dark:text-white"></h3>
                    </div>
                    <button onclick="closeCandidatesModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-200 transition-colors p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700">
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4">
                    {{-- Loading state --}}
                    <div id="modal-loading" class="flex flex-col items-center justify-center py-10 gap-3">
                        <div class="size-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                        <p class="text-sm text-gray-500">Memuat data paslon...</p>
                    </div>

                    {{-- Content --}}
                    <div id="modal-content" class="hidden divide-y divide-gray-100 dark:divide-neutral-700"></div>

                    {{-- Empty state --}}
                    <div id="modal-empty" class="hidden text-center py-10">
                        <div class="mx-auto flex items-center justify-center size-14 rounded-full bg-gray-100 dark:bg-neutral-700 mb-3">
                            <svg class="size-7 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-neutral-400">Belum ada paslon terdaftar pada event ini.</p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-neutral-900 border-t border-gray-100 dark:border-neutral-700">
                    <button onclick="closeCandidatesModal()" class="w-full py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const candidatesUrl = '{{ route("operator.kiosk.candidates", ["electionId" => "__ID__"]) }}';

    function showCandidates(electionId, electionName) {
        const modal = document.getElementById('modal-candidates');
        const modalTitle = document.getElementById('modal-election-name');
        const loadingEl = document.getElementById('modal-loading');
        const contentEl = document.getElementById('modal-content');
        const emptyEl = document.getElementById('modal-empty');

        // Reset state
        modalTitle.textContent = electionName;
        loadingEl.classList.remove('hidden');
        contentEl.classList.add('hidden');
        emptyEl.classList.add('hidden');
        contentEl.innerHTML = '';

        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Fetch candidates
        const url = candidatesUrl.replace('__ID__', electionId);
        fetch(url, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
            loadingEl.classList.add('hidden');
            const candidates = response.data?.candidates ?? [];

            if (candidates.length === 0) {
                emptyEl.classList.remove('hidden');
                return;
            }

            candidates.forEach((c, index) => {
                const row = document.createElement('div');
                row.className = 'flex items-start gap-4 py-4 first:pt-0 last:pb-0';
                row.innerHTML = `
                    <div class="flex-shrink-0 flex items-center justify-center size-11 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 font-extrabold text-lg">
                        ${c.order_number}
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-0.5">Paslon No. ${c.order_number}</p>
                        <p class="font-bold text-gray-900 dark:text-white text-base">${c.chairman_name}</p>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">Wakil: ${c.vice_chairman_name}</p>
                    </div>
                `;
                contentEl.appendChild(row);
            });

            contentEl.classList.remove('hidden');
        })
        .catch(() => {
            loadingEl.classList.add('hidden');
            contentEl.innerHTML = '<p class="text-center text-sm text-red-500 py-6">Gagal memuat data paslon. Coba lagi.</p>';
            contentEl.classList.remove('hidden');
        });
    }

    function closeCandidatesModal() {
        document.getElementById('modal-candidates').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeCandidatesModal();
    });
</script>
@endpush
