@extends('_admin._layout.app')

@section('title', 'Tambah Kandidat')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-neutral-100 dark:border-neutral-700">
            <div class="p-6 border-b border-neutral-100 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800">
                <h2 class="text-lg font-bold text-neutral-800 dark:text-neutral-200">
                    Form Tambah Kandidat
                </h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('admin.candidates.create') }}" method="POST" enctype="multipart/form-data" navigate-form>
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="election_id" class="block text-sm font-medium mb-2 dark:text-white">Event Pemilihan <span class="text-red-500">*</span></label>
                            <select id="election_id" name="election_id" class="w-full mt-1 border-neutral-200 focus:border-blue-500 focus:ring-blue-500 rounded-lg dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 transition-colors shadow-sm" required>
                                <option value="">-- Pilih Event --</option>
                                @foreach($elections as $election)
                                    <option value="{{ $election->id }}" {{ (old('election_id') ?? $selectedElectionId) == $election->id ? 'selected' : '' }}>
                                        {{ $election->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('election_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="order_number" class="block text-sm font-medium mb-2 dark:text-white">Nomor Urut <span class="text-red-500">*</span></label>
                            <input type="number" id="order_number" name="order_number" min="1" value="{{ old('order_number') }}" required placeholder="Contoh: 1" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            @error('order_number')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="chairman_name" class="block text-sm font-medium mb-2 dark:text-white">Nama Ketua <span class="text-red-500">*</span></label>
                            <input type="text" id="chairman_name" name="chairman_name" value="{{ old('chairman_name') }}" required placeholder="Masukkan nama ketua..." class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            @error('chairman_name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vice_chairman_name" class="block text-sm font-medium mb-2 dark:text-white">Nama Wakil Ketua <span class="text-red-500">*</span></label>
                            <input type="text" id="vice_chairman_name" name="vice_chairman_name" value="{{ old('vice_chairman_name') }}" required placeholder="Masukkan nama wakil ketua..." class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                            @error('vice_chairman_name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vision" class="block text-sm font-medium mb-2 dark:text-white">Visi</label>
                            <textarea id="vision" name="vision" rows="4" class="w-full mt-1 border-neutral-200 focus:border-blue-500 focus:ring-blue-500 rounded-lg dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 transition-colors shadow-sm" placeholder="Masukkan visi paslon...">{{ old('vision') }}</textarea>
                            @error('vision')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mission" class="block text-sm font-medium mb-2 dark:text-white">Misi</label>
                            <textarea id="mission" name="mission" rows="4" class="w-full mt-1 border-neutral-200 focus:border-blue-500 focus:ring-blue-500 rounded-lg dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 transition-colors shadow-sm" placeholder="Masukkan misi paslon...">{{ old('mission') }}</textarea>
                            @error('mission')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="photo" class="block text-sm font-medium mb-2 dark:text-white">Foto Paslon</label>
                            <input type="file" id="photo" name="photo" accept="image/png, image/jpeg, image/jpg" class="w-full mt-1 border border-neutral-200 focus:border-blue-500 focus:ring-blue-500 rounded-lg dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 transition-colors shadow-sm p-2 bg-white dark:bg-neutral-900" />
                            <p class="text-xs text-neutral-500 mt-1">Maksimal 2MB, format JPG/PNG</p>
                            @error('photo')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <x-admin.button href="{{ route('admin.candidates.index', ['election_id' => $selectedElectionId]) }}" color="outline-secondary">Batal</x-admin.button>
                        <x-admin.button type="submit" color="primary">Simpan Data</x-admin.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
