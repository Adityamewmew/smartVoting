@extends('landing.layout')

@section('title', 'Pendaftaran Institusi Baru')

@section('content')
<div class="min-h-screen py-10 px-4 sm:px-6 flex flex-col justify-center items-center">
    <div class="w-full max-w-2xl">
        {{-- Brand Logo & Title --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 no-underline text-gray-900 group">
                <div class="grid grid-cols-2 gap-1 w-6 h-6">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                </div>
                <span class="font-extrabold text-2xl tracking-tight text-gray-900">Smart<span class="text-blue-600">Voting</span></span>
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight mt-3">Pendaftaran Institusi Baru</h1>
            <p class="text-xs text-gray-500 mt-1">Daftarkan sekolah atau organisasi Anda untuk memulai pemilihan digital.</p>
        </div>

        {{-- Flash Error --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <p class="font-bold mb-1">Terdapat kesalahan pada formulir:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Container Card --}}
        <x-admin.card class="p-6 sm:p-10 rounded-3xl space-y-8">
            <form action="{{ route('subscribe.post') }}" method="POST" class="space-y-6">
                @csrf

                {{-- 1. Pilihan Paket --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-3">
                        Pilihan Paket Berlangganan
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="border-2 rounded-2xl p-4 cursor-pointer transition-all flex flex-col justify-between hover:border-blue-400 {{ ($package ?? 'pro') === 'starter' ? 'border-blue-600 bg-blue-50/40' : 'border-gray-200' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-900">Trial / Uji Coba</span>
                                <input type="radio" name="package" value="starter" class="text-blue-600 focus:ring-blue-500" {{ ($package ?? 'pro') === 'starter' ? 'checked' : '' }} />
                            </div>
                            <span class="text-lg font-black text-gray-900 mt-2">Gratis</span>
                            <span class="text-[11px] text-gray-500">14 hari trial</span>
                        </label>

                        <label class="border-2 rounded-2xl p-4 cursor-pointer transition-all flex flex-col justify-between hover:border-blue-400 {{ ($package ?? 'pro') === 'pro' ? 'border-blue-600 bg-blue-50/40' : 'border-gray-200' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-blue-700">Sekolah & OSIS</span>
                                <input type="radio" name="package" value="pro" class="text-blue-600 focus:ring-blue-500" {{ ($package ?? 'pro') === 'pro' ? 'checked' : '' }} />
                            </div>
                            <span class="text-lg font-black text-gray-900 mt-2">Rp 1.500.000</span>
                            <span class="text-[11px] text-gray-500">per tahun</span>
                        </label>

                        <label class="border-2 rounded-2xl p-4 cursor-pointer transition-all flex flex-col justify-between hover:border-blue-400 {{ ($package ?? 'pro') === 'enterprise' ? 'border-blue-600 bg-blue-50/40' : 'border-gray-200' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-900">Kampus / Corp</span>
                                <input type="radio" name="package" value="enterprise" class="text-blue-600 focus:ring-blue-500" {{ ($package ?? 'pro') === 'enterprise' ? 'checked' : '' }} />
                            </div>
                            <span class="text-lg font-black text-gray-900 mt-2">Rp 3.500.000</span>
                            <span class="text-[11px] text-gray-500">per tahun</span>
                        </label>
                    </div>
                </div>

                {{-- 2. Data Institusi --}}
                <div class="pt-4 border-t border-gray-100 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">Data Institusi / Sekolah</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                        <x-admin.input 
                            label="Nama Institusi / Sekolah" 
                            name="institution_name" 
                            :value="old('institution_name')" 
                            placeholder="Contoh: SMA Negeri 1 Jakarta" 
                            required="true" 
                        />

                        @php
                            $types = [
                                'school' => 'Sekolah (SMP/SMA/SMK)',
                                'campus' => 'Kampus / Universitas',
                                'organization' => 'Organisasi / Komunitas',
                            ];
                        @endphp
                        <x-admin.select 
                            label="Tipe Institusi" 
                            name="type" 
                            :options="$types" 
                            :value="old('type', 'school')" 
                            required="true" 
                        />
                    </div>
                </div>

                {{-- 3. Akun Administrator --}}
                <div class="pt-4 border-t border-gray-100 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">Akun Administrator Sekolah</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                        <x-admin.input 
                            label="Nama Lengkap Admin" 
                            name="admin_name" 
                            :value="old('admin_name')" 
                            placeholder="Contoh: Budi Santoso" 
                            required="true" 
                        />

                        <x-admin.input 
                            label="No. WhatsApp / HP" 
                            name="phone" 
                            :value="old('phone')" 
                            placeholder="Contoh: 081234567890" 
                            required="true" 
                        />
                    </div>

                    <x-admin.input 
                        type="email" 
                        label="Email Login Admin" 
                        name="email" 
                        :value="old('email')" 
                        placeholder="Contoh: admin@sman1jakarta.sch.id" 
                        required="true" 
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                        <x-admin.input 
                            type="password" 
                            label="Password" 
                            name="password" 
                            placeholder="Minimal 8 karakter" 
                            required="true" 
                        />

                        <x-admin.input 
                            type="password" 
                            label="Konfirmasi Password" 
                            name="password_confirmation" 
                            placeholder="Ulangi password" 
                            required="true" 
                        />
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <a href="{{ url('/') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800">
                        &larr; Kembali ke Beranda
                    </a>
                    <x-admin.button type="submit" color="primary" class="w-full sm:w-auto font-bold">
                        <span>Lanjut ke Pembayaran</span>
                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>

        <p class="text-center text-xs text-gray-400 mt-6">
            Sudah punya akun institusi? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection
