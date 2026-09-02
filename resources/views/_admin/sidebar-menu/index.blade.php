@extends('_admin._layout.app')

@section('title', 'Manajemen Menu Sidebar')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Manajemen Menu Sidebar</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola navigasi menu, grup modul, routing, dan permission hak akses.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <x-admin.button href="{{ route('admin.sidebar_menu.role_access', 1) }}" color="secondary" size="md">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                    Akses per Role
                </x-admin.button>
                <x-admin.button href="{{ route('admin.sidebar_menu.add') }}" color="primary" size="md">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Tambah Menu
                </x-admin.button>
            </div>
        </div>

        {{-- Search / Filter Card --}}
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-xs">
            <form action="{{ route('admin.sidebar_menu.index') }}" method="GET" navigate-form
                class="flex flex-col sm:flex-row items-center gap-3">
                <div class="w-full sm:w-80">
                    <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari label menu..." size="sm" />
                </div>
                <div class="w-full sm:w-60">
                    @php
                        $groupOptions = ['' => 'Semua Group'] + collect($groups)->pluck('label', 'key')->toArray();
                    @endphp
                    <x-admin.select :label="null" name="group" :options="$groupOptions" :value="$group ?? ''" size="sm" class="cursor-pointer" />
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <x-admin.button type="submit" size="sm" color="primary">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        Cari
                    </x-admin.button>
                    @if (!empty($keywords) || !empty($group))
                        <x-admin.button href="{{ route('admin.sidebar_menu.index') }}" size="sm" color="outline-secondary">
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
                        <x-admin.table.th>Label Menu</x-admin.table.th>
                        <x-admin.table.th>Group</x-admin.table.th>
                        <x-admin.table.th>Parent</x-admin.table.th>
                        <x-admin.table.th>Route Name</x-admin.table.th>
                        <x-admin.table.th align="center">Urutan</x-admin.table.th>
                        <x-admin.table.th align="center">Hak Akses</x-admin.table.th>
                        <x-admin.table.th align="center">Status</x-admin.table.th>
                        <x-admin.table.th align="end">Aksi</x-admin.table.th>
                    </tr>
                </x-admin.table.thead>
                <x-admin.table.tbody>
                    @forelse($data as $d)
                        <x-admin.table.tr>
                            <x-admin.table.td>
                                <div class="flex items-center gap-2">
                                    @if ($d->icon)
                                        <span class="shrink-0 size-4 text-gray-400 [&>svg]:size-4">
                                            @include($d->icon)
                                        </span>
                                    @endif
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $d->label }}
                                    </span>
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                @php
                                    $groupObj = collect($groups)->firstWhere('key', $d->group);
                                @endphp
                                <x-admin.badge color="primary" :text="$groupObj->label ?? ucfirst($d->group)" />
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <span class="text-xs text-gray-500">
                                    {{ $d->parent_label ?? '-' }}
                                </span>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <span class="text-xs font-mono text-gray-600 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-200">
                                    {{ $d->route_name ?? '-' }}
                                </span>
                            </x-admin.table.td>
                            <x-admin.table.td innerClass="text-center font-bold text-xs text-gray-600">
                                {{ $d->sort_order }}
                            </x-admin.table.td>
                            <x-admin.table.td innerClass="text-center">
                                <x-admin.badge :color="$d->access_count > 0 ? 'success' : 'gray'" :text="$d->access_count . ' role'" />
                            </x-admin.table.td>
                            <x-admin.table.td innerClass="text-center">
                                <x-admin.badge :color="$d->is_active ? 'success' : 'danger'" :text="$d->is_active ? 'Aktif' : 'Nonaktif'" />
                            </x-admin.table.td>
                            <x-admin.table.td innerClass="px-6 py-3 flex items-center justify-end gap-x-1.5">
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    href="{{ route('admin.sidebar_menu.access', $d->id) }}"
                                    title="Kelola Akses Menu"
                                    class="hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                                </x-admin.button>
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    href="{{ route('admin.sidebar_menu.update', $d->id) }}"
                                    title="Edit Menu"
                                    class="hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200">
                                    @include('_admin._layout.icons.pencil')
                                </x-admin.button>
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    title="Hapus Menu"
                                    data-hs-overlay="#delete-modal"
                                    onclick="setDeleteData('{{ $d->id }}', '{{ $d->label }}')"
                                    class="hover:bg-red-50 hover:text-red-600 hover:border-red-200">
                                    @include('_admin._layout.icons.trash')
                                </x-admin.button>
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center">
                                <x-admin.empty-state message="Belum ada menu sidebar yang terdaftar." />
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

        {{-- Delete Modal --}}
        <x-admin.modal id="delete-modal" title="Hapus Menu Sidebar" size="sm:max-w-md">
            <div class="text-center py-4">
                <span class="mb-4 inline-flex justify-center items-center size-12 rounded-full bg-red-50 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                </span>
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus menu <strong id="delete-name" class="font-bold text-gray-900"></strong>?<br>
                    Menu ini tidak akan tampil lagi di sidebar navigasi.
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

    @push('scripts')
        <script>
            window.setDeleteData = function (id, name) {
                document.getElementById('delete-name').textContent = name;
                document.getElementById('delete-form').action = `/admin/sidebar-menu/delete/${id}`;
            };
        </script>
    @endpush
@endsection
