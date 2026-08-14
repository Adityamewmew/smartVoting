@extends('_admin._layout.app')

@section('title', 'Kelola Akses Menu')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex items-center gap-3">
            <x-admin.button href="{{ route('admin.sidebar_menu.index') }}" size="icon-md" color="secondary">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Hak Akses: {{ $data->label }}</h1>
                <p class="text-xs text-gray-500">Pilih role pengguna yang dapat melihat menu ini di sidebar.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <x-admin.card class="p-6">
            <form navigate-form action="{{ route('admin.sidebar_menu.doAccess', $data->id) }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-3">
                    @foreach ($accessTypes as $typeValue => $typeLabel)
                        <label class="flex items-center gap-x-3.5 p-4 rounded-xl border cursor-pointer transition-all
                            {{ in_array($typeValue, $accesses) ? 'border-blue-200 bg-blue-50/40 text-blue-900' : 'border-gray-100 bg-white hover:bg-gray-50 text-gray-700' }}">
                            <input type="checkbox" name="access_types[]" value="{{ $typeValue }}"
                                class="shrink-0 size-4.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                {{ in_array($typeValue, $accesses) ? 'checked' : '' }}>
                            <div>
                                <span class="block text-sm font-bold text-gray-900">
                                    {{ $typeLabel }}
                                </span>
                                <span class="block text-xs text-gray-400 mt-0.5">
                                    Role Access ID: {{ $typeValue }}
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.sidebar_menu.index') }}" color="secondary">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary">
                        Simpan Hak Akses
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
