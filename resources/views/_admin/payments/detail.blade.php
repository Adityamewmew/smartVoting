@extends('_admin._layout.app')

@section('title', 'Detail Tagihan #' . ($data->invoice_number ?? ''))

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Header & Back --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <x-admin.button href="{{ route('admin.payments.index') }}" color="outline-secondary" size="sm">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali
                </x-admin.button>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Invoice {{ $data->invoice_number }}</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Diterbitkan pada {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($data->status === 'pending')
                    <form action="{{ route('admin.payments.confirm', $data->id) }}" method="POST" onsubmit="return confirm('Konfirmasi tagihan ini sudah dibayar?')">
                        @csrf
                        <x-admin.button type="submit" color="primary" size="sm">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Konfirmasi Lunas
                        </x-admin.button>
                    </form>
                @endif
                <x-admin.button href="{{ route('admin.payments.update', $data->id) }}" color="outline-secondary" size="sm">
                    Edit
                </x-admin.button>
            </div>
        </div>

        {{-- Invoice Card --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
            {{-- Top Banner --}}
            <div class="p-6 sm:p-8 bg-gray-50/70 border-b border-gray-200/80 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Institusi Penerima</span>
                    <h2 class="text-lg font-bold text-gray-900 mt-0.5">{{ $data->institution_name }}</h2>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Status Pembayaran</span>
                    <div class="mt-1">
                        @if($data->status === 'paid')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                Lunas (Paid)
                            </span>
                        @elseif($data->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="size-2 rounded-full bg-amber-500"></span>
                                Menunggu Pembayaran
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="size-2 rounded-full bg-rose-500"></span>
                                {{ ucfirst($data->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Breakdown --}}
            <div class="p-6 sm:p-8 space-y-6">
                <div class="border border-gray-100 rounded-xl p-5 bg-gray-50/40">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200/60">
                        <span class="text-sm text-gray-600">Paket Layanan</span>
                        <span class="text-sm font-bold text-gray-900">{{ $data->package_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200/60">
                        <span class="text-sm text-gray-600">Metode Pembayaran</span>
                        <span class="text-sm font-semibold text-gray-900 capitalize">{{ $data->payment_method ?? 'Mayar Gateway' }}</span>
                    </div>
                    @if($data->paid_at)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200/60">
                            <span class="text-sm text-gray-600">Waktu Pembayaran</span>
                            <span class="text-sm font-semibold text-emerald-700">{{ \Carbon\Carbon::parse($data->paid_at)->translatedFormat('d F Y, H:i:s') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center pt-3 text-base">
                        <span class="font-bold text-gray-900">Total Nominal</span>
                        <span class="font-mono font-extrabold text-blue-600 text-lg">Rp {{ number_format($data->amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if(!empty($data->payment_url))
                    <div class="p-4 rounded-xl border border-blue-100 bg-blue-50/50 flex items-center justify-between gap-4">
                        <div>
                            <h4 class="text-xs font-bold text-blue-900">Tautan Pembayaran Mayar</h4>
                            <p class="text-xs text-blue-700 font-mono mt-0.5 truncate max-w-md">{{ $data->payment_url }}</p>
                        </div>
                        <a href="{{ $data->payment_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-xs">
                            Buka Link Mayar
                            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                        </a>
                    </div>
                @endif

                @if(!empty($data->notes))
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Catatan Tambahan</h4>
                        <p class="text-sm text-gray-700 bg-gray-50 p-3.5 rounded-xl border border-gray-200/70">{{ $data->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
