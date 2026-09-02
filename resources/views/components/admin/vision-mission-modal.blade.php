<x-admin.modal id="vision-mission-modal" title="Visi & Misi Pasangan Calon" size="sm:max-w-2xl">
    <div class="space-y-6">
        {{-- Paslon Header Card --}}
        <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50/80 via-indigo-50/40 to-slate-50 border border-blue-100/80 rounded-2xl shadow-2xs">
            <div id="vm-modal-pad" class="size-12 rounded-xl bg-blue-600 text-white font-black text-sm sm:text-base flex items-center justify-center shadow-xs shrink-0">
                01
            </div>
            <div class="min-w-0 grow">
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest block mb-0.5">Kandidat Paslon</span>
                <h3 id="vm-modal-name" class="font-bold text-base sm:text-lg text-gray-900 truncate leading-tight"></h3>
            </div>
        </div>

        {{-- Visi Section --}}
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="size-5 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">
                    <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                </span>
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Visi</h4>
            </div>
            <div class="relative bg-slate-50/70 border border-slate-200/70 rounded-xl p-4 sm:p-6 border-l-4 border-l-blue-500 shadow-2xs">
                <p id="vm-modal-vision" class="text-xs sm:text-sm text-gray-800 leading-relaxed"></p>
            </div>
        </div>

        {{-- Misi Section --}}
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="size-5 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                    <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="m17 5-5-3-5 3"/><path d="m17 19-5 3-5-3"/></svg>
                </span>
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Misi</h4>
            </div>
            <div id="vm-modal-mission-container" class="bg-slate-50/70 border border-slate-200/70 rounded-xl p-4 sm:p-6 shadow-2xs">
                <ul id="vm-modal-mission-list" class="space-y-3"></ul>
            </div>
        </div>
    </div>

    <x-slot:footer>
        <div class="flex justify-end w-full">
            <x-admin.button color="secondary" size="md" class="font-medium" data-hs-overlay="#vision-mission-modal">
                Tutup
            </x-admin.button>
        </div>
    </x-slot:footer>
</x-admin.modal>
