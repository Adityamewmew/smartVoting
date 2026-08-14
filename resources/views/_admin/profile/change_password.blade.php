@extends('_admin._layout.app')

@section('title', 'Ubah Password')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div>
            <h1 class="text-xl font-bold text-gray-900">Ubah Password</h1>
            <p class="text-xs text-gray-500 mt-0.5">Perbarui kata sandi akun administrator Anda secara berkala untuk menjaga keamanan.</p>
        </div>

        {{-- Form Card --}}
        <x-admin.card class="p-6">
            <form id="change-password-form" navigate-form action="{{ route('admin.profile.do_change_password') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-5">
                    {{-- Current Password --}}
                    <x-admin.input
                        type="password"
                        id="current_password"
                        name="current_password"
                        label="Password Saat Ini"
                        placeholder="Masukkan password saat ini"
                        required
                    />

                    {{-- New Password --}}
                    <x-admin.input
                        type="password"
                        id="password"
                        name="password"
                        label="Password Baru"
                        placeholder="Minimal 6 karakter"
                        required
                    />

                    {{-- Confirm New Password --}}
                    <x-admin.input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        label="Konfirmasi Password Baru"
                        placeholder="Ulangi password baru"
                        required
                    />
                </div>

                {{-- Action Buttons --}}
                <div class="pt-5 border-t border-gray-100 flex justify-end">
                    <x-admin.button type="submit" color="primary">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Perubahan
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
