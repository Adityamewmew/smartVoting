@extends('_admin._layout.app')

@section('title', 'Pengguna Aplikasi')

@php
    use App\Constants\UserConst;
@endphp

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900 tracking-tight">{{ $page['title'] ?? 'Pengguna Aplikasi' }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola akun administrator, panitia, saksi, dan hak akses sistem.</p>
            </div>
            <x-admin.button href="{{ route('admin.users.add') }}" color="primary" size="md" class="w-full sm:w-auto justify-center">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Tambah Pengguna
            </x-admin.button>
        </div>

        {{-- Search / Filter Card --}}
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-xs">
            <form action="{{ route('admin.users.index') }}" method="GET" navigate-form
                class="flex flex-col sm:flex-row items-center gap-3">
                <div class="w-full sm:w-80">
                    <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari nama atau email..." size="sm" />
                </div>
                <div class="w-full sm:w-60">
                    @php
                        $accessTypeOptions = ['all' => 'Semua Hak Akses'] + UserConst::getAppAccessTypes();
                    @endphp
                    <x-admin.select :label="null" name="access_type" :options="$accessTypeOptions" :value="$access_type ?? 'all'" size="sm" class="cursor-pointer" />
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <x-admin.button type="submit" size="sm" color="primary">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        Cari
                    </x-admin.button>
                    @if (!empty($keywords) || ($access_type ?? 'all') !== 'all')
                        <x-admin.button href="{{ route('admin.users.index') }}" size="sm" color="outline-secondary">
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
                        <x-admin.table.th>Nama & Email</x-admin.table.th>
                        <x-admin.table.th>Hak Akses</x-admin.table.th>
                        <x-admin.table.th align="end">Aksi</x-admin.table.th>
                    </tr>
                </x-admin.table.thead>
                <x-admin.table.tbody>
                    @forelse($data as $d)
                        <x-admin.table.tr>
                            <x-admin.table.td>
                                <div class="flex items-center gap-x-3">
                                    <span class="inline-flex items-center justify-center size-9 rounded-full bg-blue-50 text-blue-700 font-bold text-xs border border-blue-100">
                                        {{ strtoupper(substr($d->name, 0, 2)) }}
                                    </span>
                                    <div class="grow">
                                        <span class="block text-sm font-bold text-gray-900">{{ $d->name }}</span>
                                        <span class="block text-xs text-gray-500">{{ $d->email }}</span>
                                    </div>
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                @php
                                    $roleLabels = UserConst::getAccessTypes();
                                    $roleName = $roleLabels[$d->access_type] ?? ucfirst($d->access_type);
                                    $badgeColor = match((string) $d->access_type) {
                                        '1', 'superadmin' => 'purple',
                                        '2', 'admin' => 'primary',
                                        '3', 'operator' => 'success',
                                        default => 'gray',
                                    };
                                @endphp
                                <x-admin.badge :color="$badgeColor" :text="$roleName" />
                            </x-admin.table.td>
                            <x-admin.table.td innerClass="px-6 py-3 flex items-center justify-end gap-x-1.5">
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    href="{{ route('admin.users.detail', $d->id) }}"
                                    title="Lihat Detail"
                                    class="hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </x-admin.button>
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    href="{{ route('admin.users.update', $d->id) }}"
                                    title="Edit Pengguna"
                                    class="hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200">
                                    @include('_admin._layout.icons.pencil')
                                </x-admin.button>
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    title="Reset Password"
                                    data-hs-overlay="#reset-modal"
                                    onclick="setResetData('{{ $d->id }}', '{{ $d->name }}')"
                                    class="hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                                </x-admin.button>
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    title="Hapus Pengguna"
                                    data-hs-overlay="#delete-modal"
                                    onclick="setDeleteData('{{ $d->id }}', '{{ $d->name }}')"
                                    class="hover:bg-red-50 hover:text-red-600 hover:border-red-200">
                                    @include('_admin._layout.icons.trash')
                                </x-admin.button>
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center">
                                <x-admin.empty-state message="Belum ada data pengguna yang terdaftar." />
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
        <x-admin.modal id="delete-modal" title="Hapus Pengguna" size="sm:max-w-md">
            <div class="text-center py-4">
                <span class="mb-4 inline-flex justify-center items-center size-12 rounded-full bg-red-50 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                </span>
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus akun <span id="delete-item-name" class="font-bold text-gray-900"></span>?<br>Tindakan ini tidak dapat dibatalkan.
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

        {{-- Reset Password Modal --}}
        <x-admin.modal id="reset-modal" title="Reset Password Pengguna" size="sm:max-w-md">
            <div class="text-center py-4">
                <span class="mb-4 inline-flex justify-center items-center size-12 rounded-full bg-amber-50 text-amber-600">
                    <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/>
                        <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/>
                    </svg>
                </span>
                <p class="text-sm text-gray-600">
                    Password akan direset menjadi default: <span class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">smart123</span><br>
                    Apakah Anda yakin ingin melanjutkan?
                </p>
            </div>
            <x-slot:footer>
                <x-admin.button color="secondary" size="md" data-hs-overlay="#reset-modal">Batal</x-admin.button>
                <form id="reset-form" method="POST" action="" class="inline m-0 p-0" navigate-form>
                    @csrf
                    <x-admin.button type="submit" color="primary" size="md">Ya, Reset Password</x-admin.button>
                </form>
            </x-slot:footer>
        </x-admin.modal>
    </div>

    <script>
        window.setDeleteData = function (id, name) {
            document.getElementById('delete-item-name').textContent = name;
            document.getElementById('delete-form').action = '{{ route('admin.users.index') }}/delete/' + id;
        };

        window.setResetData = function (id, name) {
            document.getElementById('reset-form').action = '{{ route('admin.users.index') }}/reset-password/' + id;
        };
    </script>
@endsection
