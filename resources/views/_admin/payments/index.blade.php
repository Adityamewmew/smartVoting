@extends('_admin._layout.app')

@section('title', 'Daftar Tagihan & Pembayaran')

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Pembayaran & Billing</h1>
                <p class="text-xs text-gray-500 mt-1">Kelola tagihan, invoice paket langganan, dan integrasi pembayaran Mayar.</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <x-admin.button href="{{ route('admin.payments.add') }}" color="primary" size="md">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Buat Tagihan Baru
                </x-admin.button>
            </div>
        </div>

        {{-- Filter & Search --}}
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200/80 shadow-xs">
            <form action="{{ route('admin.payments.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <div class="sm:col-span-5">
                    <x-admin.input 
                        name="keywords" 
                        :value="$keywords ?? ''" 
                        placeholder="Cari no. invoice, paket, nama institusi..." 
                    />
                </div>
                <div class="sm:col-span-3">
                    <select name="status" class="py-2 px-3.5 block w-full rounded-xl border border-gray-200/90 text-sm focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15">
                        <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Menunggu Bayar (Pending)</option>
                        <option value="paid" {{ ($status ?? '') === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        <option value="failed" {{ ($status ?? '') === 'failed' ? 'selected' : '' }}>Gagal (Failed)</option>
                        <option value="expired" {{ ($status ?? '') === 'expired' ? 'selected' : '' }}>Kadaluarsa (Expired)</option>
                    </select>
                </div>
                <div class="sm:col-span-4 flex items-center gap-2">
                    <x-admin.button type="submit" size="md" color="primary" class="w-full sm:w-auto">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        Cari
                    </x-admin.button>
                    @if (!empty($keywords) || ($status ?? 'all') !== 'all')
                        <x-admin.button href="{{ route('admin.payments.index') }}" size="md" color="outline-secondary">
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
                        <x-admin.table.th>Invoice & Paket</x-admin.table.th>
                        <x-admin.table.th>Institusi / Sekolah</x-admin.table.th>
                        <x-admin.table.th>Nominal</x-admin.table.th>
                        <x-admin.table.th>Status</x-admin.table.th>
                        <x-admin.table.th>Metode & Tanggal</x-admin.table.th>
                        <x-admin.table.th align="end">Aksi</x-admin.table.th>
                    </tr>
                </x-admin.table.thead>
                <x-admin.table.tbody>
                    @forelse($data as $item)
                        <x-admin.table.tr>
                            <x-admin.table.td>
                                <div>
                                    <span class="font-mono font-bold text-blue-600 text-xs">{{ $item->invoice_number }}</span>
                                    <div class="font-semibold text-gray-900 text-sm mt-0.5">{{ $item->package_name }}</div>
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <span class="font-bold text-gray-800">{{ $item->institution_name }}</span>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <span class="font-mono font-bold text-gray-900">Rp {{ number_format($item->amount, 0, ',', '.') }}</span>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                @if($item->status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                        Lunas
                                    </span>
                                @elseif($item->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="size-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu Bayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="size-1.5 rounded-full bg-rose-500"></span>
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <div class="text-xs text-gray-700 font-semibold capitalize">{{ $item->payment_method ?? 'Mayar' }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}</div>
                            </x-admin.table.td>
                            <x-admin.table.td align="end">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($item->status === 'pending')
                                        <form action="{{ route('admin.payments.confirm', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi tagihan ini sudah lunas?')">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Konfirmasi Lunas">
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if(!empty($item->payment_url))
                                        <a href="{{ $item->payment_url }}" target="_blank" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Buka Link Mayar">
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                        </a>
                                    @endif

                                    <a href="{{ route('admin.payments.detail', $item->id) }}" class="p-1.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>

                                    <form action="{{ route('admin.payments.delete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus tagihan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @empty
                        <x-admin.empty-state 
                            title="Belum Ada Tagihan"
                            description="Belum ada riwayat tagihan atau pembayaran yang tercatat."
                            button-text="Buat Tagihan Baru"
                            button-url="{{ route('admin.payments.add') }}"
                        />
                    @endforelse
                </x-admin.table.tbody>
            </x-admin.table>
        </x-admin.table.wrapper>

        @if(count($data) > 0 && method_exists($data, 'hasPages') && $data->hasPages())
            <div class="p-4 bg-white rounded-2xl border border-gray-200/80">
                {{ $data->links() }}
            </div>
        @endif
    </div>
@endsection
