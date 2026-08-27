@extends('_admin._layout.app')

@section('title', 'Edit Tagihan #' . ($data->invoice_number ?? ''))

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Header & Back --}}
        <div class="flex items-center gap-4">
            <x-admin.button href="{{ route('admin.payments.index') }}" color="outline-secondary" size="sm">
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Edit Tagihan {{ $data->invoice_number }}</h1>
                <p class="text-xs text-gray-500 mt-0.5">Institusi: <span class="font-semibold text-gray-700">{{ $data->institution_name }}</span></p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200/80 shadow-xs">
            <form action="{{ route('admin.payments.doUpdate', $data->id) }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <x-admin.input 
                        name="package_name" 
                        label="Nama Paket / Layanan" 
                        :value="old('package_name', $data->package_name)"
                        required 
                    />
                </div>

                <div>
                    <x-admin.input 
                        type="number"
                        name="amount" 
                        label="Nominal Pembayaran (Rp)" 
                        :value="old('amount', $data->amount)"
                        required 
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                        Status Pembayaran <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" class="py-2.5 px-3.5 block w-full rounded-xl border border-gray-200/90 text-sm focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15" required>
                        <option value="pending" {{ old('status', $data->status) === 'pending' ? 'selected' : '' }}>Menunggu Bayar (Pending)</option>
                        <option value="paid" {{ old('status', $data->status) === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        <option value="failed" {{ old('status', $data->status) === 'failed' ? 'selected' : '' }}>Gagal (Failed)</option>
                        <option value="expired" {{ old('status', $data->status) === 'expired' ? 'selected' : '' }}>Kadaluarsa (Expired)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                        Catatan / Keterangan
                    </label>
                    <textarea 
                        name="notes" 
                        rows="3" 
                        class="py-2.5 px-3.5 block w-full rounded-xl border border-gray-200/90 text-sm focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15">{{ old('notes', $data->notes) }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.payments.index') }}" color="outline-secondary" size="md">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary" size="md">
                        Simpan Perubahan
                    </x-admin.button>
                </div>
            </form>
        </div>
    </div>
@endsection
