@extends('_admin._layout.app')

@section('title', 'Tambah Menu Sidebar')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Top Navigation & Title --}}
        <div class="flex items-center gap-3">
            <x-admin.button href="{{ route('admin.sidebar_menu.index') }}" size="icon-md" color="secondary">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </x-admin.button>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Tambah Menu Sidebar</h1>
                <p class="text-xs text-gray-500">Daftarkan item navigasi baru ke dalam struktur sidebar admin.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <x-admin.card class="p-6">
            <form navigate-form action="{{ route('admin.sidebar_menu.create') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-6">
                    {{-- Label --}}
                    <x-admin.input
                        type="text"
                        id="label"
                        name="label"
                        label="Label Menu"
                        value="{{ old('label') }}"
                        placeholder="Contoh: Dashboard"
                        required="true"
                    />

                    {{-- Group & Parent --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        @php
                            $groupOptions = [];
                            foreach ($groups as $g) {
                                $groupOptions[$g->key] = $g->label;
                            }
                        @endphp
                        <x-admin.select
                            id="group"
                            name="group"
                            label="Group Menu"
                            :options="$groupOptions"
                            placeholder="-- Pilih Group --"
                            value="{{ old('group') }}"
                            required="true"
                        />

                        @php
                            $parentSelectOptions = [];
                            foreach ($parentOptions as $opt) {
                                $parentSelectOptions[$opt->id] = '[' . ucfirst($opt->group) . '] ' . $opt->label;
                            }
                        @endphp
                        <div>
                            <x-admin.select
                                id="parent_id"
                                name="parent_id"
                                label="Parent Menu (Opsional)"
                                :options="$parentSelectOptions"
                                placeholder="-- Tidak ada (Item Root) --"
                                value="{{ old('parent_id') }}"
                            />
                            <p class="text-xs text-gray-400 mt-1">Pilih jika menu ini adalah sub-item accordion.</p>
                        </div>
                    </div>

                    {{-- Route Name --}}
                    <div>
                        <x-admin.input
                            type="text"
                            id="route_name"
                            name="route_name"
                            label="Route Name"
                            value="{{ old('route_name') }}"
                            class="font-mono text-xs"
                            placeholder="Contoh: admin.dashboard"
                        />
                        <p class="text-xs text-gray-400 mt-1">Named route Laravel. Kosongkan jika item ini adalah accordion parent tanpa link.</p>
                    </div>

                    {{-- Icon --}}
                    <x-admin.input
                        type="text"
                        id="icon"
                        name="icon"
                        label="Icon (Blade include path)"
                        value="{{ old('icon') }}"
                        class="font-mono text-xs"
                        placeholder="Contoh: _admin._layout.icons.sidebar.dashboard"
                    />

                    {{-- Sort Order & Status --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <x-admin.input
                            type="number"
                            id="sort_order"
                            name="sort_order"
                            label="Urutan Tampil"
                            value="{{ old('sort_order', 0) }}"
                            min="0"
                        />

                        <x-admin.select
                            id="is_active"
                            name="is_active"
                            label="Status Menu"
                            :options="['1' => 'Aktif', '0' => 'Nonaktif']"
                            value="{{ old('is_active', '1') }}"
                        />
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                    <x-admin.button href="{{ route('admin.sidebar_menu.index') }}" color="secondary">
                        Batal
                    </x-admin.button>
                    <x-admin.button type="submit" color="primary">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        Simpan Menu
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
