@extends('_admin._layout.app')

@section('title', 'Detail Kandidat')

@section('content')
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div class="md:col-span-2 lg:col-span-2">
            <div
                class="bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center justify-between">
                    <div class="flex items-center">
                        <a navigate href="{{ route('admin.candidates.index') }}"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 cursor-pointer">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                            Kembali
                        </a>
                        <div class="ms-4 border-l border-gray-200 dark:border-neutral-700 pl-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                Detail Kandidat
                            </h2>
                        </div>
                    </div>
                    <div>
                        <a navigate href="{{ route('admin.candidates.update', $data->id) }}"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none cursor-pointer">
                            @include('_admin._layout.icons.pencil')
                            Edit
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Nomor Urut</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-neutral-200">
                                {{ $data->nomor_urut }}
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Nama Ketua</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-neutral-200">
                                {{ $data->nama_ketua }}
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Nama Wakil</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-neutral-200">
                                {{ $data->nama_wakil }}
                            </dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Visi</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200 whitespace-pre-wrap">{{ $data->visi }}</dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-500">Misi</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200 whitespace-pre-wrap">{{ $data->misi }}</dd>
                        </div>

                    </dl>
                </div>
            </div>
        </div>

        <div class="md:col-span-1 lg:col-span-1 space-y-4">
            {{-- Foto Section --}}
            <div class="bg-white shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-4">
                        Foto Kandidat
                    </h3>
                    @if ($data->foto_path)
                        <img src="{{ asset('storage/' . $data->foto_path) }}" alt="Foto Kandidat" class="w-full h-auto object-cover rounded-lg border border-gray-200 dark:border-neutral-700">
                    @else
                        <div class="flex items-center justify-center w-full h-48 bg-gray-100 rounded-lg border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700">
                            <span class="text-gray-500 dark:text-neutral-500">Tidak ada foto</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Audit Section --}}
            <div class="bg-white shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-4">
                        Informasi Sistem
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-x-3">
                            <div class="w-8 h-8 flex justify-center items-center rounded-full bg-gray-100 dark:bg-neutral-700">
                                @include('_admin._layout.icons.calendar')
                            </div>
                            <div class="grow">
                                <span class="block text-sm text-gray-500 dark:text-neutral-500">Dibuat Pada</span>
                                <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                    {{ \Carbon\Carbon::parse($data->created_at)->isoFormat('d MMMM YYYY, HH:mm') }}
                                </span>
                            </div>
                        </li>
                        <li class="flex items-center gap-x-3">
                            <div class="w-8 h-8 flex justify-center items-center rounded-full bg-gray-100 dark:bg-neutral-700">
                                @include('_admin._layout.icons.pencil')
                            </div>
                            <div class="grow">
                                <span class="block text-sm text-gray-500 dark:text-neutral-500">Terakhir Diperbarui</span>
                                <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                    {{ $data->updated_at ? \Carbon\Carbon::parse($data->updated_at)->isoFormat('d MMMM YYYY, HH:mm') : '-' }}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection