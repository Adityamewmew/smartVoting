@extends('landing.layout')

@section('title', 'Tagihan #' . $invoice->invoice_number)

@section('content')
<div class="min-h-screen py-10 px-4 sm:px-6 flex flex-col justify-center items-center">
    <div class="w-full max-w-xl">
        {{-- Brand Logo --}}
        <div class="text-center mb-6">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 no-underline text-gray-900 group">
                <div class="grid grid-cols-2 gap-1 w-6 h-6">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                </div>
                <span class="font-extrabold text-2xl tracking-tight text-gray-900">Smart<span class="text-blue-600">Voting</span></span>
            </a>
        </div>

        {{-- Invoice Card --}}
        <x-admin.card class="p-6 sm:p-8 rounded-3xl space-y-6">
            {{-- Header Status --}}
            <div class="text-center pb-6 border-b border-gray-100">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold mb-3 {{ $invoice->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                    <span class="size-2 rounded-full {{ $invoice->status === 'paid' ? 'bg-emerald-500' : 'bg-amber-500 animate-ping' }}"></span>
                    {{ $invoice->status === 'paid' ? 'Pembayaran Berhasil / Lunas' : 'Menunggu Pembayaran' }}
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Tagihan Langganan</h1>
                <p class="text-xs font-mono text-gray-500 mt-1">{{ $invoice->invoice_number }}</p>
            </div>

            {{-- Breakdown --}}
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200/80 space-y-3 text-xs">
                <div class="flex justify-between items-center text-gray-600">
                    <span class="font-medium">Institusi / Sekolah:</span>
                    <span class="font-bold text-gray-900 text-right">{{ $invoice->institution_name }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span class="font-medium">Paket Langganan:</span>
                    <span class="font-bold text-gray-900 uppercase tracking-wide">{{ $invoice->package_name }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span class="font-medium">Metode Gateway:</span>
                    <span class="font-semibold text-gray-800">Mayar Payment Gateway</span>
                </div>
                
                {{-- Separator --}}
                <div class="border-t border-dashed border-gray-300 my-2"></div>

                <div class="flex justify-between items-center text-sm">
                    <span class="font-bold text-gray-800">Total Nominal:</span>
                    <span class="font-mono font-black text-blue-600 text-lg">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Status Action Alert / Buttons --}}
            @if($invoice->status === 'paid')
                <div class="p-5 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-center space-y-3">
                    <div class="inline-flex p-2 bg-emerald-100 text-emerald-600 rounded-full">
                        <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-emerald-900 text-sm">Akun Institusi Telah Aktif</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">
                            Silakan masuk menggunakan email dan password admin yang telah didaftarkan.
                        </p>
                    </div>
                    <div class="pt-2">
                        <x-admin.button href="{{ route('login') }}" color="primary" class="font-bold">
                            <span>Masuk ke Dashboard Admin</span>
                            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </x-admin.button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @if(!empty($invoice->payment_url))
                        <a href="{{ $invoice->payment_url }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all">
                            <span>Bayar Sekarang via Mayar</span>
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                        </a>
                    @endif

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200/80 text-[11px] text-gray-600 space-y-1.5">
                        <p class="font-bold text-gray-800">Catatan Proses Aktivasi:</p>
                        <p>1. Selesaikan pembayaran melalui tautan Mayar di atas atau konfirmasi ke Superadmin.</p>
                        <p>2. Setelah pembayaran disetujui, akun Admin Institusi akan otomatis aktif dan dapat login.</p>
                    </div>

                    <div class="flex items-center justify-center gap-4 text-xs font-semibold pt-2">
                        <a href="javascript:location.reload()" class="text-blue-600 hover:text-blue-700 transition-colors inline-flex items-center gap-1">
                            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                            <span>Cek Status Terbaru</span>
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                            Sudah disetujui? Masuk Login
                        </a>
                    </div>
                </div>
            @endif
        </x-admin.card>
    </div>
</div>
@endsection
