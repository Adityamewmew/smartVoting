@extends('_admin._layout.app')

@section('title', 'Edit User')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex items-center gap-3">
            <x-admin.button href="{{ route('admin.institutions.index') }}" size="icon-md" color="secondary">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Edit User</h1>
                <p class="text-xs text-gray-500">Perbarui data user, tipe, atau status operasional.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <x-admin.card class="p-6 sm:p-8">
            <form id="update-institution-form" navigate-form action="{{ route('admin.institutions.doUpdate', $data->id) }}" method="POST" class="space-y-6">
                @csrf

                <x-admin.input 
                    label="Nama User / Organisasi" 
                    name="name" 
                    :value="old('name', $data->name)" 
                    placeholder="Contoh: Asosiasi / Organisasi Anda" 
                    required="true" 
                />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 items-start">
                    @php
                        $types = [
                            'organization' => 'Organisasi / Komunitas / Asosiasi',
                            'school' => 'Pendidikan / Sekolah / Kampus',
                            'company' => 'Perusahaan / Lembaga',
                        ];
                    @endphp
                    <x-admin.select 
                        label="Tipe Organisasi" 
                        name="type" 
                        :options="$types" 
                        :value="old('type', $data->type ?? 'organization')" 
                        required="true" 
                    />

                    @php
                        $statusList = [
                            'active' => 'Aktif (Dapat digunakan)',
                            'suspended' => 'Ditangguhkan (Suspended)',
                            'pending' => 'Menunggu Pembayaran (Pending)',
                            'inactive' => 'Nonaktif',
                        ];
                    @endphp
                    <x-admin.select 
                        label="Status Operasional" 
                        name="status" 
                        :options="$statusList" 
                        :value="old('status', $data->status ?? 'active')" 
                        required="true" 
                    />
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.institutions.index') }}" color="outline-secondary">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary" class="font-bold">
                        Simpan Perubahan
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
