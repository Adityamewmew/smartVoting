@extends('_admin._layout.app')

@section('title', 'Event Pemilihan')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900 tracking-tight">{{ $page['title'] ?? 'Event Pemilihan' }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola data pemilihan, jadwal waktu voting, dan tautan halaman publik.</p>
            </div>
            <x-admin.button href="{{ route('admin.elections.add') }}" color="primary" size="md" class="w-full sm:w-auto justify-center">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Tambah Event
            </x-admin.button>
        </div>

        {{-- Search / Filter Card --}}
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-xs">
            <form action="{{ route('admin.elections.index') }}" method="GET" navigate-form
                class="flex flex-col sm:flex-row items-center gap-3">
                <div class="w-full sm:w-80">
                    <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari nama event pemilihan..." size="sm" />
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <x-admin.button type="submit" size="sm" color="primary">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        Cari
                    </x-admin.button>
                    @if (!empty($keywords))
                        <x-admin.button href="{{ route('admin.elections.index') }}" size="sm" color="outline-secondary">
                            Reset
                        </x-admin.button>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table List --}}
        <x-admin.table.wrapper>
            <x-admin.table>
                <x-admin.table.thead>
                    <tr>
                        <x-admin.table.th>Nama & Deskripsi</x-admin.table.th>
                        <x-admin.table.th>Jadwal Pemilihan</x-admin.table.th>
                        <x-admin.table.th align="center">Status</x-admin.table.th>
                        <x-admin.table.th align="end">Aksi</x-admin.table.th>
                    </tr>
                </x-admin.table.thead>
                <x-admin.table.tbody>
                    @forelse($data as $d)
                        <x-admin.table.tr>
                            <x-admin.table.td>
                                <span class="block text-sm font-bold text-gray-900">{{ $d->name }}</span>
                                @if($d->description)
                                    <span class="block text-xs text-gray-500 max-w-md truncate mt-0.5">{{ $d->description }}</span>
                                @endif
                                @if($d->slug)
                                    <a href="{{ url('/' . $d->slug) }}" target="_blank" class="mt-1.5 inline-flex items-center gap-x-1 px-2.5 py-0.5 rounded-md text-xs font-bold text-blue-700 bg-gradient-to-r from-blue-50 to-blue-100/70 border border-blue-200/90 shadow-2xs hover:shadow-xs transition-all">
                                        <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                        /{{ $d->slug }}
                                    </a>
                                @endif
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <div class="text-xs space-y-0.5">
                                    <p class="font-semibold text-gray-900 flex items-center gap-1.5">
                                        <svg class="size-3.5 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                        {{ \Carbon\Carbon::parse($d->date ?? $d->start_time)->locale('id')->isoFormat('D MMMM Y') }}
                                    </p>
                                    <p class="text-gray-500 font-medium pl-5">
                                        {{ \Carbon\Carbon::parse($d->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($d->end_time)->format('H:i') }} WIB
                                    </p>
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td innerClass="text-center">
                                @php
                                    $statusLabels = [
                                        'draft' => 'Draft',
                                        'active' => 'Aktif',
                                        'inactive' => 'Tidak Aktif',
                                    ];
                                @endphp
                                <x-admin.badge :status="$d->status" :text="$statusLabels[$d->status] ?? ucfirst($d->status)" />
                            </x-admin.table.td>
                            <x-admin.table.td innerClass="px-6 py-3 flex items-center justify-end gap-x-1.5">
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    href="{{ route('admin.elections.detail', $d->id) }}"
                                    title="Kelola Paslon & Detail"
                                    class="hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </x-admin.button>
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    href="{{ route('admin.elections.update', $d->id) }}"
                                    title="Edit Event"
                                    class="hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200">
                                    @include('_admin._layout.icons.pencil')
                                </x-admin.button>
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    title="Hapus Event"
                                    data-hs-overlay="#delete-modal"
                                    onclick="setDeleteData('{{ $d->id }}', '{{ addslashes($d->name) }}')"
                                    class="hover:bg-red-50 hover:text-red-600 hover:border-red-200">
                                    @include('_admin._layout.icons.trash')
                                </x-admin.button>
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center">
                                <x-admin.empty-state message="Belum ada event pemilihan yang terdaftar." />
                            </td>
                        </tr>
                    @endforelse
                </x-admin.table.tbody>
            </x-admin.table>
            @if (count($data) > 0 && $data->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    <div class="flex justify-end">
                        {{ $data->links() }}
                    </div>
                </div>
            @endif
        </x-admin.table.wrapper>

        {{-- Delete Confirmation Modal --}}
        <x-admin.modal id="delete-modal" title="Hapus Event Pemilihan" size="sm:max-w-md">
            <div class="text-center py-4">
                <span class="mb-4 inline-flex justify-center items-center size-12 rounded-full bg-red-50 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                </span>
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus event <span id="delete-item-name" class="font-bold text-gray-900"></span>?<br>Semua data paslon dan suara terkait akan dihapus.
                </p>
            </div>
            <x-slot:footer>
                <x-admin.button color="secondary" size="md" data-hs-overlay="#delete-modal">Batal</x-admin.button>
                <form id="delete-form" method="POST" action="" class="inline m-0 p-0" navigate-form>
                    @csrf
                    @method('DELETE')
                    <x-admin.button type="submit" color="danger" size="md">Ya, Hapus</x-admin.button>
                </form>
            </x-slot:footer>
        </x-admin.modal>
    </div>

    <script>
        window.setDeleteData = function (id, name) {
            document.getElementById('delete-item-name').textContent = name;
            document.getElementById('delete-form').action = '{{ route('admin.elections.index') }}/delete/' + id;
        };
    </script>
@endsection
