@extends('_admin._layout.app')

@section('title', 'Tambah Kandidat')

@section('content')
    <div class="w-full">
        <x-admin.card class="p-0 border-graphite-hairline overflow-hidden shadow-none">
            <div class="px-6 py-4 border-b border-graphite-hairline flex items-center bg-paper">
                <a href="{{ route('admin.candidates.index', ['election_id' => $selectedElectionId]) }}"
                    class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-full bg-paper text-ink hover:bg-vellum focus:outline-hidden transition-colors cursor-pointer">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div class="ms-3">
                    <h2 class="text-xl font-normal text-ink">
                        Tambah Kandidat
                    </h2>
                </div>
            </div>
            
            <form action="{{ route('admin.candidates.create') }}" method="POST" enctype="multipart/form-data" navigate-form class="p-6 bg-paper">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="election_id" class="text-sm text-ink font-normal pb-3 block">Event Pemilihan <span class="text-red-500">*</span></label>
                        <select id="election_id" name="election_id" class="py-2.5 sm:py-3 px-4 block w-full border border-graphite-hairline rounded-[8px] focus:border-[var(--color-brand-yellow)] focus:ring-[var(--color-brand-yellow)] dark:bg-neutral-900 dark:border-neutral-700 text-ink font-normal bg-paper transition-colors shadow-none" required>
                            <option value="">-- Pilih Event --</option>
                            @foreach($elections as $election)
                                <option value="{{ $election->id }}" {{ (old('election_id') ?? $selectedElectionId) == $election->id ? 'selected' : '' }}>
                                    {{ $election->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('election_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-admin.input 
                            type="number"
                            label="Nomor Urut" 
                            name="order_number" 
                            :value="old('order_number')" 
                            placeholder="Contoh: 1" 
                            required="true"
                        />

                        <x-admin.input 
                            type="file"
                            label="Foto Paslon" 
                            name="photo" 
                            accept="image/png, image/jpeg, image/jpg"
                            class="p-2"
                        />
                    </div>

                    <x-admin.input 
                        label="Nama Ketua" 
                        name="chairman_name" 
                        :value="old('chairman_name')" 
                        placeholder="Masukkan nama ketua..." 
                        required="true"
                    />

                    <x-admin.input 
                        label="Nama Wakil Ketua" 
                        name="vice_chairman_name" 
                        :value="old('vice_chairman_name')" 
                        placeholder="Masukkan nama wakil ketua..." 
                        required="true"
                    />

                    <x-admin.textarea 
                        label="Visi" 
                        name="vision" 
                        rows="4"
                        :value="old('vision')" 
                        placeholder="Masukkan visi paslon..." 
                    />

                    <x-admin.textarea 
                        label="Misi" 
                        name="mission" 
                        rows="4"
                        :value="old('mission')" 
                        placeholder="Masukkan misi paslon..." 
                    />
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <x-admin.button href="{{ route('admin.candidates.index', ['election_id' => $selectedElectionId]) }}" color="secondary">Batal</x-admin.button>
                    <x-admin.button type="submit" color="primary">Simpan Data</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
