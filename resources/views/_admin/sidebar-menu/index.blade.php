@extends('_admin._layout.app')

@section('title', 'Manajemen Menu Sidebar')

@section('content')
    <div class="mb-6">
        <form action="{{ route('admin.sidebar_menu.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row items-center gap-3">
            <div class="w-full sm:w-64">
                <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari label menu..." size="sm" />
            </div>
            <div class="w-full sm:w-48">
                @php
                    $groupOptions = ['' => 'Semua Group'] + collect($groups)->pluck('label', 'key')->toArray();
                @endphp
                <x-admin.select :label="null" name="group" :options="$groupOptions" :value="$group ?? ''" size="sm" class="cursor-pointer" />
            </div>
            <div class="flex items-center gap-2">
                <x-admin.button type="submit" size="sm" color="primary">
                    @include('_admin._layout.icons.search')
                    Cari
                </x-admin.button>
                @if (!empty($keywords) || !empty($group))
                    <x-admin.button href="{{ route('admin.sidebar_menu.index') }}" size="sm" color="outline-secondary">
                        @include('_admin._layout.icons.reset')
                        Reset
                    </x-admin.button>
                @endif
            
                <a navigate href="{{ route('admin.sidebar_menu.role_access', 1) }}"
            class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:border-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400 dark:hover:bg-indigo-900/30">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            Akses per Role
        </a>
        <x-admin.button href="{{ route('admin.sidebar_menu.add') }}" class="font-bold">
            @include('_admin._layout.icons.add')
            Tambah Menu
        </x-admin.button>
            </div>
        </form>
    </div>

    <x-admin.table.wrapper>
        <x-admin.table>
            <x-admin.table.thead>
                <tr>
                    <x-admin.table.th>Label Menu</x-admin.table.th>
                    <x-admin.table.th>Group</x-admin.table.th>
                    <x-admin.table.th>Parent</x-admin.table.th>
                    <x-admin.table.th>Route</x-admin.table.th>
                    <x-admin.table.th>Urutan</x-admin.table.th>
                    <x-admin.table.th>Akses</x-admin.table.th>
                    <x-admin.table.th>Status</x-admin.table.th>
                    <x-admin.table.th align="end"></x-admin.table.th>
                </tr>
            </x-admin.table.thead>
            <x-admin.table.tbody>
                @forelse($data as $d)
                    <x-admin.table.tr>
                        <x-admin.table.td>
                            <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                {{ $d->label }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            @php
                                $colorClasses = [
                                    'blue'    => 'bg-amber-100 text-[var(--color-brand-brown)] dark:bg-blue-900/30 dark:text-amber-400',
                                    'purple'  => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                    'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                    'orange'  => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                ];
                                $groupObj = collect($groups)->firstWhere('key', $d->group);
                                $colorClass = $colorClasses[$groupObj->color ?? ''] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                                {{ $groupObj->label ?? ucfirst($d->group) }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="text-sm text-gray-500 dark:text-neutral-400">
                                {{ $d->parent_label ?? '-' }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="text-xs font-mono text-gray-500 dark:text-neutral-400">
                                {{ $d->route_name ?? '-' }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="text-sm text-gray-600 dark:text-neutral-400">{{ $d->sort_order }}</span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $d->access_count > 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                                {{ $d->access_count }} role
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            @if ($d->is_active)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    Nonaktif
                                </span>
                            @endif
                        </x-admin.table.td>
                        <x-admin.table.td innerClass="px-6 py-1.5 flex items-center justify-end gap-x-1">
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:border-indigo-300 focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:border-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400"
                                href="{{ route('admin.sidebar_menu.access', $d->id) }}" title="Kelola Akses">
                                @include('_admin._layout.icons.sidebar.change-password')
                            </a>
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-[var(--color-brand-yellow)]/30 bg-amber-50 text-[var(--color-brand-yellow)] hover:bg-amber-100 hover:border-[var(--color-brand-yellow)]/50 focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-500"
                                href="{{ route('admin.sidebar_menu.update', $d->id) }}" title="Edit">
                                @include('_admin._layout.icons.pencil')
                            </a>
                            <button type="button"
                                class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 hover:border-red-300 focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:border-red-800 dark:bg-red-900/20 dark:text-red-500 cursor-pointer"
                                title="Hapus" data-hs-overlay="#delete-modal"
                                onclick="setDeleteData('{{ $d->id }}', '{{ $d->label }}')">
                                @include('_admin._layout.icons.trash')
                            </button>
                        </x-admin.table.td>
                    </x-admin.table.tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-neutral-500">
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

    {{-- Delete Modal --}}
    <x-admin.modal id="delete-modal" title="Hapus Menu Sidebar" size="sm:max-w-md">
        <div class="text-center py-4">
            <span class="mb-4 inline-flex justify-center items-center size-14 rounded-full border-4 border-red-50 bg-red-100 text-red-500">
                @include('_admin._layout.icons.trash')
            </span>
            <p class="text-slate">
                Apakah Anda yakin ingin menghapus menu <strong id="delete-name" class="font-semibold text-ink"></strong>?
            </p>
        </div>
        <x-slot:footer class="flex justify-end gap-x-2">
            <x-admin.button color="outline-secondary" data-hs-overlay="#delete-modal">Batal</x-admin.button>
            <form id="delete-form" method="POST" action="" class="inline m-0 p-0" navigate-form>
                @csrf
                @method('DELETE')
                <x-admin.button type="submit" color="danger">Hapus</x-admin.button>
            </form>
        </x-slot:footer>
    </x-admin.modal>
@endsection

@push('scripts')
    <script>
        window.setDeleteData = function (id, name) {
            document.getElementById('delete-name').textContent = name;
            document.getElementById('delete-form').action = `/admin/sidebar-menu/delete/${id}`;
        };
    </script>
@endpush
