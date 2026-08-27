@extends('_admin._layout.app')

@section('title', 'Data Institusi & Sekolah')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900 tracking-tight">{{ $page['title'] ?? 'Institusi & Sekolah' }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola data tenant sekolah/kampus, subdomain, dan status operasional sistem e-voting.</p>
            </div>
            <x-admin.button href="{{ route('admin.institutions.add') }}" color="primary" size="md" class="w-full sm:w-auto justify-center">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Tambah Institusi Baru
            </x-admin.button>
        </div>

        {{-- Search / Filter Card --}}
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-xs">
            <form action="{{ route('admin.institutions.index') }}" method="GET" navigate-form
                class="flex flex-col sm:flex-row items-center gap-3">
                <div class="w-full sm:w-80">
                    <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari nama institusi..." size="sm" />
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
                            'school' => 'Sekolah (SMA/SMK/SMP)',
                            'campus' => 'Perguruan Tinggi',
                            'organization' => 'Organisasi / Komunitas',
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
                        <x-admin.table.th>Nama Institusi</x-admin.table.th>
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
                                    <div class="size-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 overflow-hidden p-1 shadow-2xs">
                                        @if(!empty($item->logo_path))
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($item->logo_path) }}" alt="{{ $item->name }}" class="size-full object-contain">
                                        @else
                                            <svg class="size-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16"/><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $item->name }}</div>
                                    </div>
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <span class="capitalize text-xs font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200">
                                    {{ $item->type ?? 'School' }}
                                </span>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                @if($item->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                                        <span class="size-1.5 rounded-full bg-rose-500"></span>
                                        Ditangguhkan
                                    </span>
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
                                    title="Belum ada data institusi"
                                    description="Daftarkan sekolah atau institusi baru untuk memulai penggunaan aplikasi e-voting."
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
