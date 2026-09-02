@extends('_admin._layout.app')

@section('title', 'Ubah Event Pemilihan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex items-center gap-3">
            <x-admin.button href="{{ route('admin.elections.index') }}" size="icon-md" color="secondary">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Ubah Event Pemilihan</h1>
                <p class="text-xs text-gray-500">Perbarui informasi jadwal, status, atau tautan custom slug.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <x-admin.card class="p-6">
            <form id="update-form" navigate-form action="{{ route('admin.elections.doUpdate', $data->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="space-y-6">
                    <x-admin.input 
                        label="Nama Event" 
                        name="name" 
                        :value="old('name', $data->name)" 
                        placeholder="Contoh: Pemilihan Ketua & Wakil Ketua 2026" 
                        required="true" 
                    />

                    <div>
                        <x-admin.input 
                            label="Custom Slug / URL" 
                            name="slug" 
                            :value="old('slug', $data->slug)" 
                            placeholder="Contoh: pemilu-2026" 
                            required="true"
                        />
                        <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Spasi otomatis dikonversi menjadi tanda strip (-).
                        </p>
                    </div>

                    <x-admin.image-cropper 
                        name="logo" 
                        label="Logo Pemilihan" 
                        :value="$data->logo_path ?? null"
                        outputWidth="500" 
                        outputHeight="500" 
                        help="Format JPG, PNG, atau WEBP. Maksimal 2MB. Logo akan tampil di landing page dan bilik pemilihan."
                    />

                    <x-admin.markdown-editor 
                        label="Deskripsi Pemilihan" 
                        name="description" 
                        :value="old('description', $data->description)" 
                        placeholder="Tuliskan penjelasan mengenai pemilihan ini (format Markdown didukung)..." 
                    />

                    <x-admin.input 
                        label="Tanggal Pemilihan" 
                        name="date" 
                        :value="old('date', $data->date ?? \Carbon\Carbon::parse($data->start_time)->format('Y-m-d'))" 
                        class="datepicker" 
                        placeholder="Pilih Tanggal Pemilihan" 
                        required="true" 
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <x-admin.input 
                            type="time"
                            label="Waktu Mulai (WIB)" 
                            name="start_time" 
                            :value="old('start_time', \Carbon\Carbon::parse($data->start_time)->format('H:i'))" 
                            placeholder="08:00" 
                            required="true" 
                        />

                        <x-admin.input 
                            type="time"
                            label="Waktu Selesai (WIB)" 
                            name="end_time" 
                            :value="old('end_time', \Carbon\Carbon::parse($data->end_time)->format('H:i'))" 
                            placeholder="16:00" 
                            required="true" 
                        />
                    </div>

                    @php
                        $statuses = [
                            'draft' => 'Draft',
                            'active' => 'Aktif (Active)',
                        ];
                    @endphp
                    <x-admin.select 
                        label="Status Pemilihan" 
                        name="status" 
                        :options="$statuses" 
                        :value="old('status', $data->status === 'inactive' ? 'draft' : $data->status)" 
                        required="true" 
                    />
                </div>

                {{-- Action Buttons --}}
                <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.elections.index') }}" color="secondary">
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
