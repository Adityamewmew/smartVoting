@extends('_admin._layout.app')

@section('title', 'Data User')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900 tracking-tight">{{ $page['title'] ?? 'Data User' }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola data tenant user/organisasi, subdomain, dan status operasional sistem e-voting.</p>
            </div>
            <x-admin.button href="{{ route('admin.institutions.add') }}" color="primary" size="md" class="w-full sm:w-auto justify-center">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Tambah User Baru
            </x-admin.button>
        </div>

        {{-- Search / Filter Card --}}
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-xs">
            <form action="{{ route('admin.institutions.index') }}" method="GET" navigate-form
                class="flex flex-col sm:flex-row items-center gap-3">
                <div class="w-full sm:w-80">
                    <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari nama user / organisasi..." size="sm" />
                </div>
                <div class="w-full sm:w-48">
                    @php
                        $statusOptions = [
                            'all' => 'Semua Status',
                            'active' => 'Aktif',
                            'suspended' => 'Ditangguhkan (Suspended)',
                        ];
                    @endphp
                    <x-admin.select :label="null" name="status" :options="$statusOptions" :value="$status ?? 'all'" size="sm" class="cursor-pointer" />
                </div>
                <div class="w-full sm:w-48">
                    @php
                        $typeOptions = [
                            'all' => 'Semua Tipe',
                            'organization' => 'Organisasi / Komunitas',
                            'school' => 'Pendidikan / Sekolah / Kampus',
                            'company' => 'Perusahaan / Lembaga',
                        ];
                    @endphp
                    <x-admin.select :label="null" name="type" :options="$typeOptions" :value="$type ?? 'all'" size="sm" class="cursor-pointer" />
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <x-admin.button type="submit" size="sm" color="primary">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        Cari
                    </x-admin.button>
                    @if (!empty($keywords) || ($status ?? 'all') !== 'all' || ($type ?? 'all') !== 'all')
                        <x-admin.button href="{{ route('admin.institutions.index') }}" size="sm" color="outline-secondary">
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
                        <x-admin.table.th>Nama User / Organisasi</x-admin.table.th>
                        <x-admin.table.th>Tipe</x-admin.table.th>
                        <x-admin.table.th>Status</x-admin.table.th>
                        <x-admin.table.th>Statistik</x-admin.table.th>
                        <x-admin.table.th align="end">Aksi</x-admin.table.th>
                    </tr>
                </x-admin.table.thead>
                <x-admin.table.tbody>
                    @forelse($data as $item)
                        <x-admin.table.tr>
                            <x-admin.table.td>
                                <div class="flex items-center gap-3">
                                    <div class="size-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0 text-blue-600 shadow-2xs">
                                        <svg class="size-4.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16"/><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $item->name }}</div>
                                    </div>
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <x-admin.badge color="gray" class="capitalize">
                                    {{ $item->type ?? 'General' }}
                                </x-admin.badge>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                @if($item->status === 'active')
                                    <x-admin.badge status="active" pulse="true">
                                        Aktif
                                    </x-admin.badge>
                                @else
                                    <x-admin.badge status="inactive">
                                        Ditangguhkan
                                    </x-admin.badge>
                                @endif
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <div class="text-xs space-y-0.5">
                                    <div class="text-gray-600"><span class="font-bold text-gray-900">{{ $item->elections_count ?? 0 }}</span> Pemilihan</div>
                                    <div class="text-gray-500"><span class="font-bold text-gray-800">{{ $item->users_count ?? 0 }}</span> Pengguna</div>
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td align="end">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Edit Button --}}
                                    <x-admin.button href="{{ route('admin.institutions.update', $item->id) }}" size="icon-sm" color="outline-primary" title="Edit">
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </x-admin.button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.institutions.delete', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus institusi ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.button type="submit" size="icon-sm" color="outline-danger" title="Hapus">
                                            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </x-admin.button>
                                    </form>
                                </div>
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @empty
                        <x-admin.table.tr>
                            <x-admin.table.td colspan="5" class="text-center py-12">
                                <x-admin.empty-state
                                    title="Belum ada data user"
                                    description="Daftarkan user baru untuk memulai penggunaan aplikasi e-voting."
                                />
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @endforelse
                </x-admin.table.tbody>
            </x-admin.table>
        </x-admin.table.wrapper>

        @if($data instanceof \Illuminate\Contracts\Pagination\Paginator && $data->hasPages())
            <div class="mt-4">
                {{ $data->links() }}
            </div>
        @endif
    </div>
@endsection
