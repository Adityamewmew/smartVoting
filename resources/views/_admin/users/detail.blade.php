@extends('_admin._layout.app')

@section('title', 'Detail Pengguna')

@php
    use App\Constants\UserConst;
@endphp

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-admin.card class="p-0 border-graphite-hairline overflow-hidden shadow-none">
            <div class="px-6 py-4 border-b border-graphite-hairline flex items-center bg-paper">
                <a href="{{ route('admin.users.index') }}"
                    class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-full bg-paper text-ink hover:bg-vellum focus:outline-hidden transition-colors cursor-pointer">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div class="ms-3">
                    <h2 class="text-xl font-normal text-ink">
                        Detail Data Pengguna
                    </h2>
                </div>
            </div>

            <div class="p-6 bg-paper">
                <div class="flex items-center gap-x-6 mb-8">
                    <div class="inline-flex items-center justify-center size-20 rounded-full bg-vellum text-ink text-3xl font-normal border border-graphite-hairline">
                        {{ strtoupper(substr($data->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-normal text-ink">{{ $data->name }}</h3>
                        <p class="text-slate font-normal">{{ $data->email }}</p>
                        <div class="mt-2">
                            <x-admin.badge text="{{ UserConst::getAccessTypes()[$data->access_type] ?? '-' }}" status="draft" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="p-4 bg-vellum rounded-xl border border-graphite-hairline">
                        <p class="text-xs text-slate uppercase tracking-wide font-normal mb-1">Dibuat Pada</p>
                        <p class="text-sm font-normal text-ink">
                            {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y, H:i') }}
                        </p>
                        <p class="text-xs text-slate mt-0.5">
                            {{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}
                        </p>
                    </div>

                    @if (!empty($data->updated_at))
                        <div class="p-4 bg-vellum rounded-xl border border-graphite-hairline">
                            <p class="text-xs text-slate uppercase tracking-wide font-normal mb-1">Terakhir Diubah</p>
                            <p class="text-sm font-normal text-ink">
                                {{ \Carbon\Carbon::parse($data->updated_at)->translatedFormat('d F Y, H:i') }}
                            </p>
                            <p class="text-xs text-slate mt-0.5">
                                {{ \Carbon\Carbon::parse($data->updated_at)->diffForHumans() }}
                            </p>
                        </div>
                    @endif
                </div>

                <div class="mt-8 pt-6 border-t border-graphite-hairline flex justify-end gap-3">
                    <x-admin.button href="{{ route('admin.users.update', $data->id) }}" color="primary">
                        @include('_admin._layout.icons.pencil')
                        Edit Pengguna
                    </x-admin.button>
                </div>
            </div>
        </x-admin.card>
    </div>
@endsection
