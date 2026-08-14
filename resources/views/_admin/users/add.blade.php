@extends('_admin._layout.app')

@section('title', 'Tambah Pengguna Baru')

@php
    use App\Constants\UserConst;
@endphp

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex items-center gap-3">
            <x-admin.button href="{{ route('admin.users.index') }}" size="icon-md" color="secondary">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Tambah Pengguna Baru</h1>
                <p class="text-xs text-gray-500">Buat kredensial akun baru dan tetapkan role / hak akses.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <x-admin.card class="p-6">
            <form id="add-form" navigate-form action="{{ route('admin.users.create') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-5">
                    <x-admin.input 
                        label="Nama Lengkap" 
                        name="name" 
                        :value="old('name')" 
                        placeholder="Contoh: John Doe" 
                        required="true" 
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-admin.input 
                            type="email"
                            label="Alamat Email" 
                            name="email" 
                            :value="old('email')" 
                            placeholder="nama@email.com" 
                            required="true" 
                        />

                        @php
                            $accessTypes = UserConst::getAppAccessTypes();
                        @endphp
                        <x-admin.select 
                            label="Hak Akses" 
                            name="access_type" 
                            :options="$accessTypes" 
                            :value="old('access_type')" 
                            required="true" 
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-admin.input 
                            type="password"
                            label="Password" 
                            name="password" 
                            placeholder="Minimal 6 karakter" 
                            required="true" 
                            autocomplete="new-password"
                        />

                        <x-admin.input 
                            type="password"
                            label="Konfirmasi Password" 
                            name="password_confirmation" 
                            placeholder="Ulangi password di atas" 
                            required="true" 
                            autocomplete="new-password"
                        />
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.users.index') }}" color="secondary">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        Simpan Data
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
