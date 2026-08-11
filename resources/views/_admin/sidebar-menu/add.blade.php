@extends('_admin._layout.app')

@section('title', 'Tambah Menu Sidebar')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-paper overflow-hidden rounded-2xl border border-graphite-hairline shadow-sm">
            <div class="px-6 py-4 border-b border-graphite-hairline flex items-center">
                <a href="{{ route('admin.sidebar_menu.index') }}"
                    class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-full bg-paper text-ink hover:bg-vellum focus:outline-hidden transition-colors cursor-pointer">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div class="ms-3">
                    <h2 class="text-xl font-normal text-ink">
                        Tambah Menu Sidebar
                    </h2>
                </div>
            </div>

            <form class="p-6" navigate-form action="{{ route('admin.sidebar_menu.create') }}" method="POST">
                @csrf

                <div class="space-y-4">
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

                    {{-- Group --}}
                    @php
                        $groupOptions = [];
                        foreach ($groups as $g) {
                            $groupOptions[$g->key] = $g->label;
                        }
                    @endphp
                    <x-admin.select
                        id="group"
                        name="group"
                        label="Group"
                        :options="$groupOptions"
                        placeholder="-- Pilih Group --"
                        value="{{ old('group') }}"
                        required="true"
                    />

                    {{-- Parent --}}
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
                            label="Parent Menu"
                            :options="$parentSelectOptions"
                            placeholder="-- Tidak ada (item root) --"
                            value="{{ old('parent_id') }}"
                        />
                        <p class="text-xs text-slate mt-1">Isi jika menu ini adalah child dari accordion.</p>
                    </div>

                    {{-- Route Name --}}
                    <div>
                        <x-admin.input
                            type="text"
                            id="route_name"
                            name="route_name"
                            label="Route Name"
                            value="{{ old('route_name') }}"
                            class="font-mono"
                            placeholder="Contoh: admin.dashboard"
                        />
                        <p class="text-xs text-slate mt-1">Named route Laravel. Kosongkan jika item ini adalah accordion parent tanpa link.</p>
                    </div>

                    {{-- Icon --}}
                    <x-admin.input
                        type="text"
                        id="icon"
                        name="icon"
                        label="Icon (blade include path)"
                        value="{{ old('icon') }}"
                        class="font-mono"
                        placeholder="Contoh: _admin._layout.icons.sidebar.dashboard"
                    />

                    {{-- Sort Order --}}
                    <x-admin.input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        label="Urutan Tampil"
                        value="{{ old('sort_order', 0) }}"
                        min="0"
                    />

                    {{-- Is Active --}}
                    <x-admin.select
                        id="is_active"
                        name="is_active"
                        label="Status"
                        :options="['1' => 'Aktif', '0' => 'Nonaktif']"
                        value="{{ old('is_active', '1') }}"
                    />
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <x-admin.button type="submit" color="primary" class="px-6 py-3">
                        Simpan Menu
                    </x-admin.button>
                    <x-admin.button href="{{ route('admin.sidebar_menu.index') }}" color="outline-secondary" class="px-6 py-3">
                        Batal
                    </x-admin.button>
                </div>
            </form>
        </div>
    </div>
@endsection
