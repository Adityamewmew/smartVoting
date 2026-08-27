@extends('_admin._layout.app')

@section('title', 'Tambah Institusi / Sekolah Baru')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex items-center gap-3">
            <x-admin.button href="{{ route('admin.institutions.index') }}" size="icon-md" color="secondary">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Tambah Institusi / Sekolah Baru</h1>
                <p class="text-xs text-gray-500 mt-0.5">Daftarkan institusi baru dan buat akun administrator pertama secara otomatis.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <x-admin.card class="p-6 sm:p-8">
            <form id="add-institution-form" navigate-form action="{{ route('admin.institutions.create') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Bagian 1: Data Institusi --}}
                <div class="space-y-5">
                    <x-admin.input 
                        label="Nama Institusi / Sekolah" 
                        name="name" 
                        :value="old('name')" 
                        placeholder="Contoh: SMK Negeri 1 Jakarta" 
                        required="true" 
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-start">
                        @php
                            $types = [
                                'school' => 'Sekolah (SMA/SMK/SMP)',
                                'campus' => 'Perguruan Tinggi',
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

                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Logo Institusi (Opsional)
                            </label>
                            <input 
                                type="file" 
                                name="logo" 
                                id="logo" 
                                accept="image/*"
                                class="py-1.5 px-3 block w-full text-sm text-gray-500 rounded-xl border border-gray-200/90 bg-white shadow-2xs file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer focus:border-blue-500 focus:ring-3 focus:ring-blue-500/15 transition-all"
                            />
                            @error('logo')
                                <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Divider Pemisah Antara Data Institusi & Kredensial Admin --}}
                <div class="border-t border-gray-100 my-6"></div>

                {{-- Bagian 2: Kredensial Administrator --}}
                <div class="space-y-5">
                    <x-admin.input 
                        label="Nama Administrator / PIC" 
                        name="admin_name" 
                        :value="old('admin_name')" 
                        placeholder="Contoh: Bpk. Haryono (Admin SMKN 1)" 
                        required="true" 
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-admin.input 
                            type="email"
                            label="Email Login Admin" 
                            name="admin_email" 
                            :value="old('admin_email')" 
                            placeholder="admin@gmail.com" 
                            required="true" 
                        />

                        <x-admin.input 
                            type="password"
                            label="Password Admin" 
                            name="admin_password" 
                            placeholder="Minimal 6 karakter" 
                            required="true" 
                        />
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.institutions.index') }}" color="outline-secondary">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary" class="font-bold">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan & Daftarkan Institusi
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
