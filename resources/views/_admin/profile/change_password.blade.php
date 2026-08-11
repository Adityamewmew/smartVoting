@extends('_admin._layout.app')

@section('title', 'Ubah Password')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-paper overflow-hidden rounded-2xl border border-graphite-hairline shadow-sm">
            <div class="px-6 py-4 border-b border-graphite-hairline flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-normal text-ink">
                        Ubah Password
                    </h2>
                </div>
            </div>

            <form id="change-password-form" class="p-6" navigate-form
                action="{{ route('admin.profile.do_change_password') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- Current Password --}}
                    <x-admin.input
                        type="password"
                        id="current_password"
                        name="current_password"
                        label="Password Lama"
                        placeholder="Masukkan password lama anda"
                        required
                    />

                    {{-- New Password --}}
                    <x-admin.input
                        type="password"
                        id="password"
                        name="password"
                        label="Password Baru"
                        placeholder="Masukkan password baru anda"
                        required
                    />

                    {{-- Confirm New Password --}}
                    <x-admin.input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        label="Ulangi Password Baru"
                        placeholder="Ulangi password baru anda"
                        required
                    />
                </div>

                {{-- Footer --}}
                <div class="mt-6 flex justify-start gap-x-2">
                    <x-admin.button type="submit" icon='<svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="M12 5v14" /></svg>'>
                        Simpan Perubahan
                    </x-admin.button>
                </div>
            </form>
        </div>
    </div>
@endsection
