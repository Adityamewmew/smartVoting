@extends('_admin._layout.app')

@section('title', 'Tambah Kandidat')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
            class="bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
                <a href="{{ route('admin.candidates.index') }}"
                    class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-xl border border-gray-200 bg-white text-gray-800 shadow-md hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 cursor-pointer">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="90" height="90"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div class="ms-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Tambah Kandidat
                    </h2>
                </div>
            </div>

            <form id="add-form" class="p-6" action="{{ route('admin.candidates.create') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-4">
                    {{-- Pemilihan --}}
                    <div>
                        <label for="election_id" class="block text-sm font-medium mb-2 dark:text-white">Pemilihan <span class="text-red-500">*</span></label>
                        <select id="election_id" name="election_id"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('election_id') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                            required>
                            <option value="">-- Pilih Pemilihan --</option>
                            @foreach ($elections as $e)
                                <option value="{{ $e->id }}" {{ old('election_id') == $e->id ? 'selected' : '' }}>
                                    {{ $e->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('election_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Urut --}}
                    <div>
                        <label for="nomor_urut" class="block text-sm font-medium mb-2 dark:text-white">Nomor Urut <span class="text-red-500">*</span></label>
                        <input type="number" id="nomor_urut" name="nomor_urut" value="{{ old('nomor_urut') }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('nomor_urut') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Contoh: 1" required>
                        @error('nomor_urut')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Ketua --}}
                    <div>
                        <label for="nama_ketua" class="block text-sm font-medium mb-2 dark:text-white">Nama Ketua <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_ketua" name="nama_ketua" value="{{ old('nama_ketua') }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('nama_ketua') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Nama Ketua" required>
                        @error('nama_ketua')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Wakil --}}
                    <div>
                        <label for="nama_wakil" class="block text-sm font-medium mb-2 dark:text-white">Nama Wakil <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_wakil" name="nama_wakil" value="{{ old('nama_wakil') }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('nama_wakil') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Nama Wakil" required>
                        @error('nama_wakil')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Visi --}}
                    <div>
                        <label for="visi" class="block text-sm font-medium mb-2 dark:text-white">Visi <span class="text-red-500">*</span></label>
                        <textarea id="visi" name="visi" rows="3"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('visi') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Visi kandidat" required>{{ old('visi') }}</textarea>
                        @error('visi')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Misi --}}
                    <div>
                        <label for="misi" class="block text-sm font-medium mb-2 dark:text-white">Misi <span class="text-red-500">*</span></label>
                        <textarea id="misi" name="misi" rows="4"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('misi') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Misi kandidat" required>{{ old('misi') }}</textarea>
                        @error('misi')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div>
                        <label for="foto" class="block text-sm font-medium mb-2 dark:text-white">Foto Kandidat <span class="text-red-500">*</span></label>
                        <input type="file" id="foto" name="foto" accept="image/*"
                            class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400
                            file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4 dark:file:bg-neutral-700 dark:file:text-neutral-400 @error('foto') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" required>
                        <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">Max 2MB. Format: jpeg, png, jpg, gif.</p>
                        @error('foto')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="mt-6 flex justify-start gap-x-2">
                    <a href="{{ route('admin.candidates.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        Batal
                    </a>
                    <button type="submit"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none cursor-pointer">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection