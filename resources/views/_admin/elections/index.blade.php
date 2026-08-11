@extends('_admin._layout.app')

@section('title', 'Event Pemilihan')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-semibold text-ink">{{ $page['title'] ?? 'Event Pemilihan' }}</h2>
        <x-admin.button href="{{ route('admin.elections.add') }}" class="font-bold" size="sm">
            @include('_admin._layout.icons.add')
            Tambah Event
        </x-admin.button>
    </div>

    <div class="mb-6">
        <p class="text-xs font-semibold text-slate mb-3 uppercase tracking-wider">Pencarian Data</p>
        <form action="{{ route('admin.elections.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row items-center gap-3">
            <div class="w-full sm:w-64">
                <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari nama pemilihan..." size="sm" />
            </div>
            <div class="flex items-center gap-2">
                <x-admin.button type="submit" size="sm" color="primary">
                    @include('_admin._layout.icons.search')
                    Cari
                </x-admin.button>
                @if (!empty($keywords))
                    <x-admin.button href="{{ route('admin.elections.index') }}" size="sm" color="outline-secondary">
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
                    <x-admin.table.th>Nama & Deskripsi</x-admin.table.th>
                    <x-admin.table.th>Jadwal</x-admin.table.th>
                    <x-admin.table.th>Status</x-admin.table.th>
                    <x-admin.table.th align="end"></x-admin.table.th>
                </tr>
            </x-admin.table.thead>
            <x-admin.table.tbody>
                @forelse($data as $d)
                    <x-admin.table.tr class="hover:bg-vellum/50 transition">
                        <x-admin.table.td>
                            <span class="block text-sm font-normal text-ink">{{ $d->name }}</span>
                            @if($d->description)
                                <span class="block text-xs text-slate max-w-xs truncate">{{ $d->description }}</span>
                            @endif
                            @if($d->slug)
                                <a href="{{ url('/pemilihan/' . $d->slug) }}" target="_blank" class="mt-1 inline-flex items-center gap-x-1 text-xs font-normal text-slate hover:text-ink hover:underline">
                                    <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                    /pemilihan/{{ $d->slug }}
                                </a>
                            @endif
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="block text-sm text-ink">
                                {{ \Carbon\Carbon::parse($d->start_time)->format('d M Y H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($d->end_time)->format('d M Y H:i') }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            @php
                                $statusLabels = [
                                    'draft' => 'Draft',
                                    'scheduled' => 'Terjadwal',
                                    'active' => 'Aktif',
                                    'closed' => 'Ditutup',
                                ];
                            @endphp
                            <x-admin.badge :status="$d->status" :text="$statusLabels[$d->status] ?? ucfirst($d->status)" />
                        </x-admin.table.td>
                        <x-admin.table.td innerClass="px-6 py-1.5 flex items-center justify-end gap-x-1">
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-normal rounded-full border border-graphite-hairline bg-paper text-slate hover:bg-vellum hover:text-ink focus:outline-none transition-colors"
                                href="{{ route('admin.elections.detail', $d->id) }}" title="Lihat Detail">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-normal rounded-full border border-graphite-hairline bg-paper text-slate hover:bg-vellum hover:text-ink focus:outline-none transition-colors"
                                href="{{ route('admin.elections.update', $d->id) }}" title="Edit">
                                @include('_admin._layout.icons.pencil')
                            </a>
                            <button type="button"
                                class="inline-flex items-center justify-center size-8 text-sm font-normal rounded-full border border-graphite-hairline bg-paper text-slate hover:bg-ink hover:text-paper hover:border-ink focus:outline-none transition-colors cursor-pointer"
                                title="Delete" data-hs-overlay="#delete-modal"
                                onclick="setDeleteData('{{ $d->id }}', '{{ addslashes($d->name) }}')">
                                @include('_admin._layout.icons.trash')
                            </button>
                        </x-admin.table.td>
                    </x-admin.table.tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-neutral-500">
                            <x-admin.empty-state />
                        </td>
                    </tr>
                @endforelse
            </x-admin.table.tbody>
        </x-admin.table>
        @if (count($data) > 0 && $data->hasPages())
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
                        Hapus Event Pemilihan
                    </h3>
                    <p class="text-gray-500 dark:text-neutral-500">
                        Apakah Anda yakin ingin menghapus event <span id="delete-item-name"
                            class="font-semibold text-gray-800 dark:text-neutral-200"></span>?
                        <br>Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="mt-6 flex justify-center gap-x-4">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                            data-hs-overlay="#delete-modal">
                            Batal
                        </button>
                        <form id="delete-form" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setDeleteData(id, name) {
            document.getElementById('delete-item-name').textContent = name;
            document.getElementById('delete-form').action = '{{ url('admin/elections/delete') }}/' + id;
        }
    </script>
@endsection
