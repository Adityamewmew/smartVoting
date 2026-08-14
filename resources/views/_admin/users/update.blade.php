@extends('_admin._layout.app')

@section('title', 'Update User')

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
                <h1 class="text-xl font-bold text-gray-900">Ubah Data Pengguna</h1>
                <p class="text-xs text-gray-500">Perbarui profil nama, email, atau hak akses akun pengguna.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <x-admin.card class="p-6">
            <form id="update-form" navigate-form action="{{ route('admin.users.doUpdate', $data->id) }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-5">
                    <x-admin.input 
                        label="Nama Lengkap" 
                        name="name" 
                        :value="$data->name ?? ''" 
                        placeholder="Contoh: John Doe" 
                        required="true" 
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-admin.input 
                            type="email"
                            label="Alamat Email" 
                            name="email" 
                            :value="$data->email ?? ''" 
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
                            :value="$data->access_type ?? ''" 
                            required="true" 
                        />
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.users.index') }}" color="secondary">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Perubahan
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
