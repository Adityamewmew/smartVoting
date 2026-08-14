@extends('_admin._layout.app')

@section('title', 'Detail Pengguna')

@php
    use App\Constants\UserConst;
@endphp

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex items-center gap-3">
            <x-admin.button href="{{ route('admin.users.index') }}" size="icon-md" color="secondary">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Detail Profil Pengguna</h1>
                <p class="text-xs text-gray-500">Informasi lengkap akun dan riwayat aktivitas akun.</p>
            </div>
        </div>

        {{-- Detail Card --}}
        <x-admin.card class="p-6">
            <div class="space-y-6">
                {{-- User Profile Header --}}
                <div class="flex items-center gap-4 p-4 bg-blue-50/40 rounded-xl border border-blue-100">
                    <div class="inline-flex items-center justify-center size-16 rounded-full bg-blue-100 text-blue-700 text-2xl font-black">
                        {{ strtoupper(substr($data->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $data->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $data->email }}</p>
                        <div class="mt-1.5">
                            @php
                                $roleLabels = UserConst::getAccessTypes();
                                $roleName = $roleLabels[$data->access_type] ?? ucfirst($data->access_type);
                                $badgeColor = match((string) $data->access_type) {
                                    '1', 'superadmin' => 'purple',
                                    '2', 'admin' => 'primary',
                                    '3', 'operator' => 'success',
                                    default => 'gray',
                                };
                            @endphp
                            <x-admin.badge :color="$badgeColor" :text="$roleName" />
                        </div>
                    </div>
                </div>

                {{-- Timestamp Information --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-400 font-medium mb-1">Dibuat Pada</p>
                        <p class="text-sm font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y, H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}
                        </p>
                    </div>

                    @if (!empty($data->updated_at))
                        <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-400 font-medium mb-1">Terakhir Diubah</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($data->updated_at)->translatedFormat('d F Y, H:i') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ \Carbon\Carbon::parse($data->updated_at)->diffForHumans() }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="pt-5 border-t border-gray-100 flex justify-end gap-3">
                    <x-admin.button href="{{ route('admin.users.update', $data->id) }}" color="primary">
                        @include('_admin._layout.icons.pencil')
                        Edit Pengguna
                    </x-admin.button>
                </div>
            </div>
        </x-admin.card>
    </div>
@endsection
