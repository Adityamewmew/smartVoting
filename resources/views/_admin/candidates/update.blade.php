@extends('_admin._layout.app')

@section('title', 'Ubah Kandidat')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex items-center gap-3">
            <x-admin.button href="{{ route('admin.elections.detail', ['id' => $data->election_id, 'tab' => 'paslon']) }}" size="icon-md" color="secondary">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Ubah Data Kandidat</h1>
                <p class="text-xs text-gray-500">Perbarui profil pasangan calon, foto paslon, atau visi & misi.</p>
            </div>
        </div>
        
        {{-- Form Card --}}
        <x-admin.card class="p-6">
            <form action="{{ route('admin.candidates.doUpdate', $data->id) }}" method="POST" enctype="multipart/form-data" navigate-form class="space-y-6">
                @csrf
                
                <div class="space-y-5">
                    @php
                        $electionOptions = [];
                        foreach($elections as $election) {
                            $electionOptions[$election->id] = $election->name;
                        }
                    @endphp
                    <x-admin.select 
                        label="Event Pemilihan" 
                        name="election_id" 
                        :options="$electionOptions" 
                        placeholder="-- Pilih Event Pemilihan --" 
                        :value="old('election_id', $data->election_id)" 
                        required="true"
                        :error="$errors->first('election_id')"
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-admin.input 
                            type="number"
                            label="Nomor Urut Paslon" 
                            name="order_number" 
                            :value="old('order_number') ?? $data->order_number" 
                            placeholder="Contoh: 1" 
                            required="true" 
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-admin.input 
                            label="Nama Calon Ketua" 
                            name="chairman_name" 
                            :value="old('chairman_name') ?? $data->chairman_name" 
                            placeholder="Masukkan nama calon ketua..." 
                            required="true" 
                        />

                        <x-admin.input 
                            label="Nama Calon Wakil Ketua" 
                            name="vice_chairman_name" 
                            :value="old('vice_chairman_name') ?? $data->vice_chairman_name" 
                            placeholder="Masukkan nama calon wakil ketua..." 
                            required="true" 
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label for="photo" class="block text-sm font-medium text-gray-700">Ganti Foto Calon Ketua</label>
                            <input 
                                type="file"
                                id="photo"
                                name="photo" 
                                accept="image/png, image/jpeg, image/jpg, image/webp"
                                class="block w-full text-xs text-gray-500 file:me-4 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-lg cursor-pointer bg-white"
                            />
                            <p class="text-xs text-gray-400">Portrait, maks 700px (auto-convert ke 354x472 px). Kosongkan jika tidak diubah.</p>
                            @error('photo')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                            @if($data->photo_path)
                                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-3 mt-2">
                                    <img src="{{ Storage::url($data->photo_path) }}" alt="Foto Calon Ketua" class="w-12 h-16 object-cover object-center rounded-lg border border-gray-200 shadow-2xs">
                                    <div>
                                        <p class="text-xs font-bold text-gray-700">Foto Ketua Saat Ini</p>
                                        <p class="text-[11px] text-gray-400">Tersimpan di storage aplikasi.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label for="vice_chairman_photo" class="block text-sm font-medium text-gray-700">Ganti Foto Calon Wakil</label>
                            <input 
                                type="file"
                                id="vice_chairman_photo"
                                name="vice_chairman_photo" 
                                accept="image/png, image/jpeg, image/jpg, image/webp"
                                class="block w-full text-xs text-gray-500 file:me-4 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-lg cursor-pointer bg-white"
                            />
                            <p class="text-xs text-gray-400">Portrait, maks 700px (auto-convert ke 354x472 px). Kosongkan jika tidak diubah.</p>
                            @error('vice_chairman_photo')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                            @if(!empty($data->vice_chairman_photo_path))
                                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-3 mt-2">
                                    <img src="{{ Storage::url($data->vice_chairman_photo_path) }}" alt="Foto Calon Wakil" class="w-12 h-16 object-cover object-center rounded-lg border border-gray-200 shadow-2xs">
                                    <div>
                                        <p class="text-xs font-bold text-gray-700">Foto Wakil Saat Ini</p>
                                        <p class="text-[11px] text-gray-400">Tersimpan di storage aplikasi.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <x-admin.markdown-editor 
                        label="Visi Paslon" 
                        name="vision" 
                        :value="old('vision', $data->vision)" 
                        placeholder="Tuliskan visi pasangan calon (format Markdown didukung)..." 
                    />

                    <x-admin.markdown-editor 
                        label="Misi Paslon" 
                        name="mission" 
                        :value="old('mission', $data->mission)" 
                        placeholder="Tuliskan butir-butir misi (format Markdown didukung, gunakan * atau 1. untuk poin)..." 
                    />
                </div>

                {{-- Action Buttons --}}
                <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.elections.detail', ['id' => $data->election_id, 'tab' => 'paslon']) }}" color="secondary">
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
