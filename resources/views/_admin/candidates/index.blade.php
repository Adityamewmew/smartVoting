@extends('_admin._layout.app')

@section('title', 'Data ' . $page['title'])

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-semibold text-ink">Data {{ $page['title'] }}</h2>
        <x-admin.button href="{{ route('admin.candidates.add', ['election_id' => $selectedElectionId]) }}" class="font-bold" size="sm">
            @include('_admin._layout.icons.add')
            Tambah Kandidat
        </x-admin.button>
    </div>

    <div class="mb-6">
        <p class="text-xs font-semibold text-slate mb-3 uppercase tracking-wider">Pencarian Data</p>
        <form action="{{ route('admin.candidates.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row items-center gap-3">
            
            <!-- Select Event -->
            <div class="w-full sm:w-64">
                <select name="election_id" class="w-full py-2.5 sm:py-2 px-4 sm:text-sm block border border-gray-200 rounded-lg focus:border-[var(--color-brand-yellow)] focus:ring-[var(--color-brand-yellow)] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-gray-400 glass-input" onchange="this.form.submit()">
                    <option value="">-- Semua Event Pemilihan --</option>
                    @foreach($elections as $election)
                        <option value="{{ $election->id }}" {{ $selectedElectionId == $election->id ? 'selected' : '' }}>
                            {{ $election->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Keyword -->
            <div class="w-full sm:w-64">
                <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari kandidat..." size="sm" />
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2">
                <x-admin.button type="submit" size="sm" color="primary">
                    @include('_admin._layout.icons.search')
                    Cari
                </x-admin.button>
                @if (!empty($keywords) || !empty($selectedElectionId))
                    <x-admin.button href="{{ route('admin.candidates.index') }}" size="sm" color="outline-secondary">
                        @include('_admin._layout.icons.reset')
                        Reset
                    </x-admin.button>
                @endif
            </div>
        </form>
    </div>

    <x-admin.table.wrapper>
        <x-admin.table>
            <x-admin.table.thead>
                <tr>
                    <x-admin.table.th>No. Urut</x-admin.table.th>
                    <x-admin.table.th>Foto</x-admin.table.th>
                    <x-admin.table.th>Nama Paslon</x-admin.table.th>
                    <x-admin.table.th>Event</x-admin.table.th>
                    <x-admin.table.th>Visi & Misi</x-admin.table.th>
                    <x-admin.table.th align="end"></x-admin.table.th>
                </tr>
            </x-admin.table.thead>
            <x-admin.table.tbody>
                @forelse($data as $d)
                    <x-admin.table.tr class="hover:bg-vellum/50 transition">
                        <x-admin.table.td>
                            <span class="block text-2xl font-normal text-ink">{{ $d->order_number }}</span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            @if($d->photo_path)
                                <img src="{{ Storage::url($d->photo_path) }}" alt="Foto Paslon" class="w-16 h-16 object-cover rounded-xl shadow-none border border-graphite-hairline">
                            @else
                                <div class="w-16 h-16 bg-vellum rounded-xl flex items-center justify-center border border-graphite-hairline">
                                    <span class="text-slate text-[10px] uppercase font-normal tracking-wider">No Image</span>
                                </div>
                            @endif
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="block text-sm font-normal text-ink">K: {{ $d->chairman_name }}</span>
                            <span class="block text-sm text-slate font-normal mt-1">W: {{ $d->vice_chairman_name }}</span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-normal bg-brand-yellow/10 text-yellow-700">
                                {{ $d->election_name }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <div class="max-w-xs text-xs text-slate space-y-1">
                                @if($d->vision)
                                    <p class="truncate"><span class="font-normal text-ink">Visi:</span> {{ $d->vision }}</p>
                                @endif
                                @if($d->mission)
                                    <p class="truncate"><span class="font-normal text-ink">Misi:</span> {{ $d->mission }}</p>
                                @endif
                            </div>
                        </x-admin.table.td>
                        <x-admin.table.td innerClass="px-6 py-1.5 flex items-center justify-end gap-x-1">
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-normal rounded-full border border-graphite-hairline bg-paper text-slate hover:bg-vellum hover:text-ink focus:outline-none transition-colors"
                                href="{{ route('admin.candidates.update', $d->id) }}" title="Edit">
                                @include('_admin._layout.icons.pencil')
                            </a>
                            <button type="button"
                                class="inline-flex items-center justify-center size-8 text-sm font-normal rounded-full border border-graphite-hairline bg-paper text-slate hover:bg-ink hover:text-paper hover:border-ink focus:outline-none transition-colors cursor-pointer"
                                title="Delete" data-hs-overlay="#delete-modal"
                                onclick="setDeleteData('{{ $d->id }}', 'Kandidat Nomor Urut {{ $d->order_number }}')">
                                @include('_admin._layout.icons.trash')
                            </button>
                        </x-admin.table.td>
                    </x-admin.table.tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-neutral-500">
                            <x-admin.empty-state title="Belum ada Kandidat" message="Silakan tambahkan kandidat untuk event pemilihan ini." />
                        </td>
                    </tr>
                @endforelse
            </x-admin.table.tbody>
        </x-admin.table>
        
        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator && count($data) > 0 && $data->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                <div class="flex justify-end">
                    {{ $data->links() }}
                </div>
            </div>
        @endif
    </x-admin.table.wrapper>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto"
        role="dialog" tabindex="-1" aria-labelledby="delete-modal-label">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div
                class="relative flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
                <div class="absolute top-2 end-2">
                    <button type="button"
                        class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600"
                        aria-label="Close" data-hs-overlay="#delete-modal">
                        <span class="sr-only">Close</span>
                        @include('_admin._layout.icons.close_modal')
                    </button>
                </div>

                <div class="p-4 sm:p-10 text-center overflow-y-auto">
                    <!-- Icon -->
                    <span
                        class="mb-4 inline-flex justify-center items-center size-14 rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100">
                        @include('_admin._layout.icons.warning_modal')
                    </span>
                    <!-- End Icon -->

                    <h3 id="delete-modal-label" class="mb-2 text-xl font-bold text-gray-800 dark:text-neutral-200">
                        Hapus Kandidat Paslon
                    </h3>
                    <p class="text-gray-500 dark:text-neutral-500">
                        Apakah Anda yakin ingin menghapus <span id="delete-item-name"
                            class="font-semibold text-gray-800 dark:text-neutral-200"></span>?
                        <br>Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="mt-6 flex justify-center gap-x-4">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                            data-hs-overlay="#delete-modal">
                            Batal
                        </button>
                        <a id="delete-btn" href="#"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
                            Ya, Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setDeleteData(id, name) {
            document.getElementById('delete-item-name').textContent = name;
            document.getElementById('delete-btn').href = '{{ url('admin/candidates/delete') }}/' + id;
        }
    </script>
@endsection
