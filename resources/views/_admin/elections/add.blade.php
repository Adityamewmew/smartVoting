@extends('_admin._layout.app')

@section('title', 'Tambah Event Pemilihan')

@section('content')
    <div class="w-full">
        <x-admin.card class="p-0 border-graphite-hairline overflow-hidden shadow-none">
            <div class="px-6 py-4 border-b border-graphite-hairline flex items-center bg-paper">
                <a href="{{ route('admin.elections.index') }}"
                    class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-full bg-paper text-ink hover:bg-vellum focus:outline-hidden transition-colors cursor-pointer">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div class="ms-3">
                    <h2 class="text-xl font-normal text-ink">
                        Tambah Event Pemilihan
                    </h2>
                </div>
            </div>

            <form id="add-form" class="p-6 bg-paper" navigate-form action="{{ route('admin.elections.create') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <x-admin.input 
                        label="Nama Event" 
                        name="name" 
                        :value="old('name')" 
                        placeholder="Contoh: Pemilihan Ketua OSIS 2026" 
                        required="true" 
                    />

                    <x-admin.input 
                        label="Custom Slug / URL" 
                        name="slug" 
                        :value="old('slug')" 
                        placeholder="Contoh: osis-2026" 
                        helper="Kosongkan untuk generate otomatis dari nama event. Spasi otomatis menjadi tanda strip (-)."
                    />

                    <x-admin.textarea 
                        label="Deskripsi" 
                        name="description" 
                        :value="old('description')" 
                        placeholder="Opsional: Penjelasan singkat tentang pemilihan ini" 
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-admin.input 
                            label="Waktu Mulai" 
                            name="start_time" 
                            :value="old('start_time')" 
                            class="datetimepicker" 
                            placeholder="Pilih Waktu Mulai" 
                            required="true" 
                        />

                        <x-admin.input 
                            label="Waktu Selesai" 
                            name="end_time" 
                            :value="old('end_time')" 
                            class="datetimepicker" 
                            placeholder="Pilih Waktu Selesai" 
                            required="true" 
                        />
                    </div>

                    @php
                        $statuses = [
                            'draft' => 'Draft',
                            'scheduled' => 'Scheduled',
                            'active' => 'Active',
                            'closed' => 'Closed',
                        ];
                    @endphp
                    <x-admin.select 
                        label="Status" 
                        name="status" 
                        :options="$statuses" 
                        :value="old('status', 'draft')" 
                        required="true" 
                    />
                </div>

                {{-- Footer --}}
                <div class="mt-8 flex justify-start gap-x-3">
                    <x-admin.button href="{{ route('admin.elections.index') }}" color="secondary">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
