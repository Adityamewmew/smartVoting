@extends('_admin._layout.app')

@section('title', 'Pengguna Aplikasi')

@php
    use App\Constants\UserConst;
@endphp

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-semibold text-ink">{{ $page['title'] ?? 'Pengguna Aplikasi' }}</h2>
        <x-admin.button href="{{ route('admin.users.add') }}" class="font-bold" size="sm">
            @include('_admin._layout.icons.add')
            Tambah Data
        </x-admin.button>
    </div>

    <div class="mb-6">
        <p class="text-xs font-semibold text-slate mb-3 uppercase tracking-wider">Pencarian Data</p>
        <form action="{{ route('admin.users.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row items-center gap-3">
            <div class="w-full sm:w-64">
                <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Nama atau Email" size="sm" />
            </div>
            <div class="w-full sm:w-48">
                @php
                    $accessTypeOptions = ['all' => 'Semua Hak Akses'] + UserConst::getAppAccessTypes();
                @endphp
                <x-admin.select :label="null" name="access_type" :options="$accessTypeOptions" :value="$access_type ?? 'all'" size="sm"
                    class="cursor-pointer" />
            </div>
            <div class="flex items-center gap-2">
                <x-admin.button type="submit" size="sm" color="primary">
                    @include('_admin._layout.icons.search')
                    Cari
                </x-admin.button>
                @if (!empty($keywords) || ($access_type ?? 'all') !== 'all')
                    <x-admin.button href="{{ route('admin.users.index') }}" size="sm" color="outline-secondary">
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
                    <x-admin.table.th>Nama</x-admin.table.th>
                    <x-admin.table.th>Hak Akses</x-admin.table.th>
                    <x-admin.table.th align="end"></x-admin.table.th>
                </tr>
            </x-admin.table.thead>
            <x-admin.table.tbody>
                @forelse($data as $d)
                    <x-admin.table.tr class="hover:bg-vellum/50 transition">
                        <x-admin.table.td>
                            <div class="flex items-center gap-x-3">
                                <span class="inline-flex items-center justify-center size-9.5 rounded-full bg-vellum border border-graphite-hairline">
                                    <span class="font-normal text-sm text-ink">
                                        {{ strtoupper(substr($d->name, 0, 1)) }}
                                    </span>
                                </span>
                                <div class="grow">
                                    <span class="block text-sm font-normal text-ink">{{ $d->name }}</span>
                                    <span class="block text-sm text-slate font-normal">{{ $d->email }}</span>
                                </div>
                            </div>
                        </x-admin.table.td>
                        <x-admin.table.td>
                            <span class="text-sm font-normal text-ink">
                                {{ UserConst::getAccessTypes()[$d->access_type] ?? '-' }}
                            </span>
                        </x-admin.table.td>
                        <x-admin.table.td innerClass="px-6 py-1.5 flex items-center justify-end gap-x-1">
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-normal rounded-full border border-graphite-hairline bg-paper text-slate hover:bg-vellum hover:text-ink focus:outline-none transition-colors"
                                href="{{ route('admin.users.detail', $d->id) }}" title="View">
                                @include('_admin._layout.icons.view_detail')
                            </a>
                            <a navigate
                                class="inline-flex items-center justify-center size-8 text-sm font-normal rounded-full border border-graphite-hairline bg-paper text-slate hover:bg-vellum hover:text-ink focus:outline-none transition-colors"
                                href="{{ route('admin.users.update', $d->id) }}" title="Edit">
                                @include('_admin._layout.icons.pencil')
                            </a>
                            <button type="button"
                                class="inline-flex items-center justify-center size-8 text-sm font-normal rounded-full border border-graphite-hairline bg-paper text-slate hover:bg-vellum hover:text-ink focus:outline-none transition-colors cursor-pointer"
                                title="Reset Password" data-hs-overlay="#reset-modal"
                                onclick="setResetData('{{ $d->id }}', '{{ $d->name }}')">
                                @include('_admin._layout.icons.sidebar.change-password')
                            </button>
                            <button type="button"
                                class="inline-flex items-center justify-center size-8 text-sm font-normal rounded-full border border-graphite-hairline bg-paper text-slate hover:bg-ink hover:text-paper hover:border-ink focus:outline-none transition-colors cursor-pointer"
                                title="Delete" data-hs-overlay="#delete-modal"
                                onclick="setDeleteData('{{ $d->id }}', '{{ $d->name }}')">
                                @include('_admin._layout.icons.trash')
                            </button>
                        </x-admin.table.td>
                    </x-admin.table.tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-neutral-500">
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

    <x-admin.modal id="delete-modal" title="Hapus Pengguna" size="sm:max-w-md">
        <div class="text-center py-4">
            <span class="mb-4 inline-flex justify-center items-center size-14 rounded-full border-4 border-red-50 bg-red-100 text-red-500">
                @include('_admin._layout.icons.warning_modal')
            </span>
            <p class="text-slate">
                Apakah Anda yakin ingin menghapus data ini?<br>Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <x-slot:footer class="flex justify-end gap-x-2">
            <x-admin.button color="outline-secondary" data-hs-overlay="#delete-modal">Batal</x-admin.button>
            <form id="delete-form" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <x-admin.button type="submit" color="danger">Ya, Hapus</x-admin.button>
            </form>
        </x-slot:footer>
    </x-admin.modal>

    <x-admin.modal id="reset-modal" title="Reset Password" size="sm:max-w-md">
        <div class="text-center py-4">
            <span class="mb-4 inline-flex justify-center items-center size-14 rounded-full border-4 border-amber-50 bg-amber-100 text-amber-500">
                <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/>
                    <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/>
                </svg>
            </span>
            <p class="text-slate">
                Password akan direset menjadi default: <span class="font-bold text-ink">smart123</span><br>
                Apakah Anda yakin ingin melanjutkan?
            </p>
        </div>
        <x-slot:footer class="flex justify-end gap-x-2">
            <x-admin.button color="outline-secondary" data-hs-overlay="#reset-modal">Batal</x-admin.button>
            <form id="reset-form" method="POST" class="inline">
                @csrf
                <x-admin.button type="submit" color="primary">Ya, Reset Password</x-admin.button>
            </form>
        </x-slot:footer>
    </x-admin.modal>

    <script>
        function setDeleteData(id, name) {
            document.getElementById('delete-form').action = '{{ url('admin/users/delete') }}/' + id;
        }

        function setResetData(id, name) {
            document.getElementById('reset-user-name').textContent = name;
            document.getElementById('reset-form').action = '{{ url('admin/users/reset-password') }}/' + id;
        }
    </script>
@endsection
