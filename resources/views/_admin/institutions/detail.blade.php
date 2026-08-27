@extends('_admin._layout.app')

@section('title', 'Detail Institusi: ' . ($data->name ?? ''))

@section('content')
    <div class="space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div class="flex items-center gap-3">
                <x-admin.button href="{{ route('admin.institutions.index') }}" size="icon-md" color="secondary">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </x-admin.button>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ $data->name }}</h1>
                    <p class="text-xs text-gray-500 capitalize">{{ $data->type ?? 'Sekolah / Institusi' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <x-admin.button href="{{ route('admin.institutions.update', $data->id) }}" color="primary" size="md">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    Edit Institusi
                </x-admin.button>
            </div>
        </div>

        {{-- Metrics & Overview Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Profile Card --}}
            <x-admin.card class="p-6 md:col-span-1 space-y-4">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="size-20 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden p-2 shadow-sm mb-3">
                        @if(!empty($data->logo_path))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($data->logo_path) }}" alt="{{ $data->name }}" class="size-full object-contain">
                        @else
                            <svg class="size-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16"/><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></svg>
                        @endif
                    </div>
                    <h2 class="text-base font-bold text-gray-900">{{ $data->name }}</h2>
                    <span class="text-xs font-medium text-gray-500 capitalize mt-0.5">{{ $data->type ?? 'School' }}</span>

                    <div class="mt-3">
                        @if($data->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                Status Aktif
                            </span>
                        @elseif($data->status === 'suspended')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="size-2 rounded-full bg-rose-500"></span>
                                Ditangguhkan (Suspended)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="size-2 rounded-full bg-amber-500"></span>
                                {{ ucfirst($data->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 space-y-2.5 text-xs">
                    <div class="flex justify-between py-1">
                        <span class="text-gray-500">Terdaftar Sejak:</span>
                        <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </x-admin.card>

            {{-- Stats & Registered Users --}}
            <div class="md:col-span-2 space-y-5">
                {{-- Stats Counters --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-2xs">
                        <span class="text-xs font-semibold text-gray-500">Total Pemilihan / Event</span>
                        <div class="text-3xl font-black text-gray-900 mt-1">{{ $data->elections_count ?? 0 }}</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-2xs">
                        <span class="text-xs font-semibold text-gray-500">Pengguna Terdaftar</span>
                        <div class="text-3xl font-black text-gray-900 mt-1">{{ $data->users_count ?? 0 }}</div>
                    </div>
                </div>

                {{-- Users List in this Tenant --}}
                <x-admin.card class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-900">Akun Pengguna Terdaftar di Institusi Ini</h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($data->users ?? [] as $u)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $u->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $u->email }}</div>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200">
                                    {{ $u->access_type == 1 ? 'Admin Sekolah' : 'Operator' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 py-4 text-center">Belum ada akun pengguna untuk institusi ini.</p>
                        @endforelse
                    </div>
                </x-admin.card>
            </div>
        </div>
    </div>
@endsection
