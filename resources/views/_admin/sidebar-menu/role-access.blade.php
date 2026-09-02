@extends('_admin._layout.app')

@section('title', 'Hak Akses per Role')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-100 shadow-xs">
            <div class="flex items-center gap-3">
                <x-admin.button href="{{ route('admin.sidebar_menu.index') }}" size="icon-md" color="secondary">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </x-admin.button>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Hak Akses Menu: {{ $roleName }}</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Centang dan sesuaikan menu navigasi yang dapat diakses oleh role ini.</p>
                </div>
            </div>
        </div>

        {{-- Role tabs --}}
        <div class="flex flex-wrap gap-2 p-1.5 bg-gray-100/80 rounded-xl border border-gray-200/60 inline-flex">
            @foreach ($accessTypes as $typeValue => $typeLabel)
                <a navigate href="{{ route('admin.sidebar_menu.role_access', $typeValue) }}"
                    class="py-2 px-4 inline-flex items-center text-xs font-bold rounded-lg transition-all
                    {{ $typeValue === $accessType
                        ? 'bg-blue-600 text-white shadow-xs'
                        : 'text-gray-600 hover:text-gray-900 hover:bg-white/60' }}">
                    {{ $typeLabel }}
                </a>
            @endforeach
        </div>

        <form navigate-form action="{{ route('admin.sidebar_menu.doRoleAccess', $accessType) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @php
                    $groupsKeyed = collect($groups)->keyBy('key');
                @endphp

                @foreach ($menusByGroup as $groupKey => $menus)
                    @php
                        $groupObj = $groupsKeyed[$groupKey] ?? null;
                        $groupLabel = $groupObj ? $groupObj->label : ucfirst($groupKey);
                        $groupId = 'group-' . $groupKey;
                    @endphp

                    <div class="bg-white overflow-hidden shadow-xs rounded-2xl border border-gray-100">
                        {{-- Group header --}}
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/70 flex items-center justify-between">
                            <div class="flex items-center gap-x-2">
                                <x-admin.badge color="primary" :text="$groupLabel" />
                                <span class="text-xs text-gray-500 font-medium">
                                    {{ count($menus) }} menu
                                </span>
                            </div>
                            {{-- Select all toggle --}}
                            <label class="flex items-center gap-x-2 cursor-pointer text-xs font-semibold text-gray-600 hover:text-gray-900">
                                <input type="checkbox" class="group-select-all shrink-0 size-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                    data-group="{{ $groupId }}">
                                <span>Pilih semua</span>
                            </label>
                        </div>

                        <div class="p-4 space-y-2.5" id="{{ $groupId }}">
                            @forelse ($menus as $menu)
                                {{-- Parent menu --}}
                                <div class="rounded-xl border transition-colors {{ $menu->is_enabled ? 'border-blue-200 bg-blue-50/40' : 'border-gray-100 bg-white' }}">
                                    <label class="flex items-center gap-x-3 px-4 py-3 cursor-pointer rounded-xl">
                                        <input type="checkbox" name="menu_ids[]" value="{{ $menu->id }}"
                                            class="menu-checkbox shrink-0 size-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                            data-group="{{ $groupId }}"
                                            {{ $menu->is_enabled ? 'checked' : '' }}>
                                        <div class="flex items-center gap-x-2.5 flex-1 min-w-0">
                                            @if ($menu->icon)
                                                <span class="shrink-0 inline-flex items-center justify-center size-4 text-gray-500 [&>svg]:size-4">
                                                    @include($menu->icon)
                                                </span>
                                            @endif
                                            <span class="text-sm font-bold text-gray-900 truncate">
                                                {{ $menu->label }}
                                            </span>
                                            @if (! $menu->route_name)
                                                <span class="inline-flex shrink-0 items-center px-1.5 py-0.2 rounded text-[10px] font-semibold bg-gray-100 text-gray-600">
                                                    accordion
                                                </span>
                                            @endif
                                        </div>
                                        @if ($menu->route_name)
                                            <span class="text-xs font-mono text-gray-400 hidden sm:block">
                                                {{ $menu->route_name }}
                                            </span>
                                        @endif
                                    </label>

                                    {{-- Children --}}
                                    @if (! empty($menu->children) && count($menu->children) > 0)
                                        <div class="pb-2.5 px-4 space-y-1 border-t border-dashed border-gray-200/60 pt-2">
                                            @foreach ($menu->children as $child)
                                                <label class="flex items-center gap-x-3 px-3 py-2 cursor-pointer rounded-lg transition-all
                                                    {{ $child->is_enabled ? 'bg-blue-50 text-blue-950 font-medium' : 'hover:bg-slate-50 text-slate-700' }}">
                                                    <div class="size-4 shrink-0 flex items-center justify-center">
                                                        <span class="size-1.5 rounded-full {{ $child->is_enabled ? 'bg-blue-600' : 'bg-slate-300' }}"></span>
                                                    </div>
                                                    <input type="checkbox" name="menu_ids[]" value="{{ $child->id }}"
                                                        class="menu-checkbox shrink-0 size-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                                        data-group="{{ $groupId }}"
                                                        {{ $child->is_enabled ? 'checked' : '' }}>
                                                    <span class="text-xs flex-1">
                                                        {{ $child->label }}
                                                    </span>
                                                    @if ($child->route_name)
                                                        <span class="text-[11px] font-mono {{ $child->is_enabled ? 'text-blue-700' : 'text-slate-400' }} hidden sm:block">
                                                            {{ $child->route_name }}
                                                        </span>
                                                    @endif
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 py-4 text-center">
                                    Belum ada menu di group ini.
                                </p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-3 pt-4">
                <x-admin.button type="submit" color="primary" size="md">
                    Simpan Hak Akses
                </x-admin.button>
                <x-admin.button href="{{ route('admin.sidebar_menu.index') }}" color="secondary" size="md">
                    Batal
                </x-admin.button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Select all / deselect all per group
        document.querySelectorAll('.group-select-all').forEach(function(selectAll) {
            const groupId = selectAll.dataset.group;

            // Set initial state based on whether all checked
            const checkboxes = document.querySelectorAll(`.menu-checkbox[data-group="${groupId}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            const noneChecked = Array.from(checkboxes).every(cb => !cb.checked);
            selectAll.checked = allChecked;
            selectAll.indeterminate = !allChecked && !noneChecked;

            selectAll.addEventListener('change', function() {
                document.querySelectorAll(`.menu-checkbox[data-group="${groupId}"]`).forEach(function(cb) {
                    cb.checked = selectAll.checked;
                    updateRowHighlight(cb);
                });
            });
        });

        // Update "select all" state when individual checkbox changes
        document.querySelectorAll('.menu-checkbox').forEach(function(cb) {
            cb.addEventListener('change', function() {
                updateRowHighlight(cb);
                syncSelectAll(cb.dataset.group);
            });
        });

        function updateRowHighlight(cb) {
            const container = cb.closest('.rounded-xl');
            if (container) {
                if (cb.checked) {
                    container.classList.add('border-blue-200', 'bg-blue-50/40');
                    container.classList.remove('border-gray-100', 'bg-white');
                } else {
                    container.classList.remove('border-blue-200', 'bg-blue-50/40');
                    container.classList.add('border-gray-100', 'bg-white');
                }
            }
        }

        function syncSelectAll(groupId) {
            const selectAll = document.querySelector(`.group-select-all[data-group="${groupId}"]`);
            if (!selectAll) return;
            const checkboxes = document.querySelectorAll(`.menu-checkbox[data-group="${groupId}"]`);
            const checked = Array.from(checkboxes).filter(cb => cb.checked).length;
            selectAll.checked = checked === checkboxes.length;
            selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
        }
    </script>
@endpush
