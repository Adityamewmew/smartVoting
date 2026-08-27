@extends('_admin._layout.app')

@section('title', 'Buat Tagihan Pembayaran Baru')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Header & Back --}}
        <div class="flex items-center gap-4">
            <x-admin.button href="{{ route('admin.payments.index') }}" color="outline-secondary" size="sm">
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Buat Tagihan Baru</h1>
                <p class="text-xs text-gray-500 mt-0.5">Generate invoice tagihan untuk institusi dan siapkan link pembayaran Mayar.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200/80 shadow-xs">
            <form action="{{ route('admin.payments.create') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                        Pilih Institusi / Sekolah <span class="text-rose-500">*</span>
                    </label>
                    <select name="institution_id" class="py-2.5 px-3.5 block w-full rounded-xl border border-gray-200/90 text-sm focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15" required>
                        <option value="">-- Pilih Institusi --</option>
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->id }}" {{ old('institution_id') == $inst->id ? 'selected' : '' }}>
                                {{ $inst->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('institution_id')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-admin.input 
                        name="package_name" 
                        label="Nama Paket / Layanan" 
                        placeholder="Contoh: Paket Pemilu Sekolah 1 Tahun (Pro)"
                        :value="old('package_name')"
                        required 
                    />
                </div>

                <div>
                    <x-admin.input 
                        type="number"
                        name="amount" 
                        label="Nominal Pembayaran (Rp)" 
                        placeholder="Contoh: 1500000"
                        :value="old('amount')"
                        required 
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-admin.input 
                            name="customer_name" 
                            label="Nama Kontak Pembayar (Opsional)" 
                            placeholder="Contoh: Kepala Sekolah / Bendahara"
                            :value="old('customer_name')"
                        />
                    </div>
                    <div>
                        <x-admin.input 
                            type="email"
                            name="customer_email" 
                            label="Email Notifikasi Mayar (Opsional)" 
                            placeholder="Contoh: admin@sekolah.sch.id"
                            :value="old('customer_email')"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                        Catatan / Deskripsi Tambahan
                    </label>
                    <textarea 
                        name="notes" 
                        rows="3" 
                        class="py-2.5 px-3.5 block w-full rounded-xl border border-gray-200/90 text-sm focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15"
                        placeholder="Keterangan tagihan atau batas waktu pembayaran...">{{ old('notes') }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.payments.index') }}" color="outline-secondary" size="md">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary" size="md">
                        Simpan & Terbitkan Invoice
                    </x-admin.button>
                </div>
            </form>
        </div>
    </div>
@endsection
