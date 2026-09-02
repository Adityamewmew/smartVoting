@extends('_admin._layout.app')

@section('title', 'Tambah Kandidat')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex items-center gap-3">
            <x-admin.button href="{{ $selectedElectionId ? route('admin.elections.detail', ['id' => $selectedElectionId, 'tab' => 'paslon']) : route('admin.elections.index') }}" size="icon-md" color="secondary">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Tambah Kandidat Paslon</h1>
                <p class="text-xs text-gray-500">Daftarkan pasangan calon baru, nomor urut, dan visi misi.</p>
            </div>
        </div>
        
        {{-- Form Card --}}
        <x-admin.card class="p-6">
            <form action="{{ route('admin.candidates.create') }}" method="POST" enctype="multipart/form-data" navigate-form class="space-y-6">
                @csrf
                
                @php
                    $electionOptions = [];
                    foreach($elections as $election) {
                        $electionOptions[$election->id] = $election->name;
                    }
                @endphp

                <div class="space-y-6">
                    {{-- 1. Event & Nomor Urut --}}
                    <div class="p-4 sm:p-6 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">1. Event Pemilihan & Nomor Urut</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-start">
                            <div class="sm:col-span-2">
                                <x-admin.select 
                                    label="Event Pemilihan" 
                                    name="election_id" 
                                    :options="$electionOptions" 
                                    placeholder="-- Pilih Event Pemilihan --" 
                                    :value="old('election_id', $selectedElectionId)" 
                                    required="true"
                                    :error="$errors->first('election_id')"
                                />
                            </div>
                            <div>
                                <x-admin.input 
                                    type="number"
                                    label="Nomor Urut Paslon" 
                                    name="order_number" 
                                    :value="old('order_number')" 
                                    placeholder="Contoh: 1" 
                                    required="true" 
                                />
                            </div>
                        </div>
                    </div>

                    {{-- 2. Data Pasangan Calon (Grid 2 Kolom) --}}
                    <div>
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">2. Profil Calon Ketua & Wakil</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            {{-- Kolom Calon Ketua --}}
                            <div class="p-4 sm:p-6 rounded-2xl border border-blue-100 bg-blue-50/30 space-y-4">
                                <div class="flex items-center gap-2 pb-2 border-b border-blue-100/80">
                                    <span class="size-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold shadow-2xs">K</span>
                                    <span class="font-bold text-sm text-slate-900">Calon Ketua (Wajib)</span>
                                </div>
                                <x-admin.input 
                                    label="Nama Lengkap Ketua" 
                                    name="chairman_name" 
                                    :value="old('chairman_name')" 
                                    placeholder="Nama calon ketua..." 
                                    required="true" 
                                />
                                <x-admin.image-cropper 
                                    name="photo" 
                                    label="Foto Calon Ketua" 
                                    help="Portrait 3:4, maks 2MB" 
                                />
                            </div>

                            {{-- Kolom Calon Wakil --}}
                            <div class="p-4 sm:p-6 rounded-2xl border border-slate-200 bg-slate-50/40 space-y-4">
                                <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80">
                                    <span class="size-6 rounded-lg bg-slate-700 text-white flex items-center justify-center text-xs font-bold shadow-2xs">W</span>
                                    <span class="font-bold text-sm text-slate-900">Calon Wakil Ketua (Opsional)</span>
                                </div>
                                <x-admin.input 
                                    label="Nama Lengkap Wakil" 
                                    name="vice_chairman_name" 
                                    :value="old('vice_chairman_name')" 
                                    placeholder="Nama calon wakil..." 
                                />
                                <x-admin.image-cropper 
                                    name="vice_chairman_photo" 
                                    label="Foto Calon Wakil" 
                                    help="Portrait 3:4, maks 2MB" 
                                />
                            </div>
                        </div>
                    </div>

                    {{-- 3. Visi & Misi --}}
                    <div class="p-4 sm:p-6 rounded-2xl border border-slate-200/80 bg-white space-y-6">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">3. Visi & Misi Paslon</h3>
                        <x-admin.markdown-editor 
                            label="Visi Paslon" 
                            name="vision" 
                            :value="old('vision')" 
                            placeholder="Tuliskan visi pasangan calon..." 
                        />
                        <x-admin.markdown-editor 
                            label="Misi Paslon" 
                            name="mission" 
                            :value="old('mission')" 
                            placeholder="Tuliskan butir-butir misi (gunakan * atau 1. untuk poin)..." 
                        />
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ $selectedElectionId ? route('admin.elections.detail', ['id' => $selectedElectionId, 'tab' => 'paslon']) : route('admin.elections.index') }}" color="secondary">
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
