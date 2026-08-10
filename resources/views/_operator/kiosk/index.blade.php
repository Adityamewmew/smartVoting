@extends('_admin._layout.operator')

@section('title', 'Manajemen Bilik Suara')

@section('content')
    <x-admin.page-header title="Manajemen Bilik Suara" subtitle="Daftar event pemilihan yang sedang aktif" />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($data as $election)
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 shadow-sm flex flex-col">
                <h3 class="text-lg font-bold text-gray-800 dark:text-neutral-200">{{ $election->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-400 mt-1 mb-4">{{ $election->description ?? 'Tidak ada deskripsi' }}</p>
                
                <div class="flex-grow">
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div class="bg-gray-50 dark:bg-neutral-900 rounded-lg p-3 text-center border border-gray-100 dark:border-neutral-800">
                            <span class="block text-xs text-gray-500 dark:text-neutral-400 font-semibold mb-1">Total Suara</span>
                            <span class="block text-xl font-black text-blue-600 dark:text-blue-500">{{ $election->total_votes }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-neutral-900 rounded-lg p-3 text-center border border-gray-100 dark:border-neutral-800">
                            <span class="block text-xs text-gray-500 dark:text-neutral-400 font-semibold mb-1">Sesi Aktif</span>
                            <span class="block text-xl font-black {{ $election->active_sessions > 0 ? 'text-amber-500' : 'text-gray-700 dark:text-neutral-300' }}">{{ $election->active_sessions }}</span>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('operator.kiosk.generate', $election->id) }}" method="POST" target="_blank">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none shadow-md shadow-blue-500/20">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        Buka Bilik Suara
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-full">
                <x-admin.empty-state title="Tidak ada event aktif" message="Saat ini tidak ada event pemilihan yang berstatus aktif." />
            </div>
        @endforelse
    </div>
@endsection
