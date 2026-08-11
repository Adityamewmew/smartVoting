@extends('_admin._layout.app')

@section('title', 'Tambah Pengguna Baru')

@php
    use App\Constants\UserConst;
@endphp

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-admin.card class="p-0 border-graphite-hairline overflow-hidden shadow-none">
            <div class="px-6 py-4 border-b border-graphite-hairline flex items-center bg-paper">
                <a href="{{ route('admin.users.index') }}"
                    class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-full bg-paper text-ink hover:bg-vellum focus:outline-hidden transition-colors cursor-pointer">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div class="ms-3">
                    <h2 class="text-xl font-normal text-ink">
                        Tambah Pengguna Baru
                    </h2>
                </div>
            </div>

            <form id="add-form" class="p-6 bg-paper" navigate-form action="{{ route('admin.users.create') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <x-admin.input 
                        label="Nama Lengkap" 
                        name="name" 
                        :value="old('name')" 
                        placeholder="Contoh: John Doe" 
                        required="true" 
                    />

                    <x-admin.input 
                        type="email"
                        label="Email Address" 
                        name="email" 
                        :value="old('email')" 
                        placeholder="Contoh: john@example.com" 
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

                    <x-admin.input 
                        type="password"
                        label="Password" 
                        name="password" 
                        placeholder="Masukkan password" 
                        required="true" 
                        autocomplete="new-password"
                    />

                    <x-admin.input 
                        type="password"
                        label="Konfirmasi Password" 
                        name="password_confirmation" 
                        placeholder="Ulangi password" 
                        required="true" 
                        autocomplete="new-password"
                    />
                </div>

                {{-- Footer --}}
                <div class="mt-8 flex justify-start gap-x-3">
                    <x-admin.button href="{{ route('admin.users.index') }}" color="secondary">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Simpan Data
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
