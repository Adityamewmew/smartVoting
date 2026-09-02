@extends('_admin._layout.app')

@section('title', 'Data ' . $page['title'])

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
            <div>
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900 tracking-tight">Data Pasangan Calon</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola data kandidat, foto paslon, nomor urut, serta visi & misi pemilihan.</p>
            </div>
            <x-admin.button href="{{ route('admin.candidates.add', ['election_id' => $selectedElectionId]) }}" color="primary" size="md" class="w-full sm:w-auto justify-center">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Tambah Kandidat
            </x-admin.button>
        </div>

        {{-- Search / Filter Card --}}
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-xs">
            <form action="{{ route('admin.candidates.index') }}" method="GET" navigate-form
                class="flex flex-col sm:flex-row items-center gap-3">
                
                <!-- Select Event -->
                <div class="w-full sm:w-72">
                    @php
                        $electionOptions = [];
                        foreach ($elections as $election) {
                            $electionOptions[$election->id] = $election->name;
                        }
                    @endphp
                    <x-admin.select
                        name="election_id"
                        placeholder="-- Semua Event Pemilihan --"
                        :options="$electionOptions"
                        :value="$selectedElectionId"
                        onchange="this.form.submit()"
                        size="sm"
                    />
                </div>

                <!-- Search Keyword -->
                <div class="w-full sm:w-72">
                    <x-admin.input name="keywords" :value="$keywords ?? ''" placeholder="Cari nama paslon..." size="sm" />
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <x-admin.button type="submit" size="sm" color="primary">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        Cari
                    </x-admin.button>
                    @if (!empty($keywords) || !empty($selectedElectionId))
                        <x-admin.button href="{{ route('admin.candidates.index') }}" size="sm" color="outline-secondary">
                            Reset
                        </x-admin.button>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table List --}}
        <x-admin.table.wrapper>
            <x-admin.table>
                <x-admin.table.thead>
                    <tr>
                        <x-admin.table.th align="center">No. Urut</x-admin.table.th>
                        <x-admin.table.th>Foto Paslon</x-admin.table.th>
                        <x-admin.table.th>Nama Pasangan Calon</x-admin.table.th>
                        <x-admin.table.th>Event Pemilihan</x-admin.table.th>
                        <x-admin.table.th align="end">Aksi</x-admin.table.th>
                    </tr>
                </x-admin.table.thead>
                <x-admin.table.tbody>
                    @forelse($data as $d)
                        <x-admin.table.tr>
                            <x-admin.table.td innerClass="text-center">
                                <x-admin.badge color="primary" class="font-black text-xs px-2.5 py-1">
                                    {{ str_pad($d->order_number, 2, '0', STR_PAD_LEFT) }}
                                </x-admin.badge>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                @if($d->photo_path)
                                    <img src="{{ Storage::url($d->photo_path) }}" alt="Foto Paslon" class="w-12 h-16 object-cover object-center rounded-lg shadow-2xs border border-gray-200">
                                @else
                                    <div class="w-12 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 text-gray-400">
                                        <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    </div>
                                @endif
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <span class="block text-sm font-bold text-gray-900">{{ $d->chairman_name }}</span>
                                <span class="block text-xs text-gray-500 font-medium mt-0.5">&amp; {{ $d->vice_chairman_name ?: '-' }}</span>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <x-admin.badge color="gray" :text="$d->election_name" />
                            </x-admin.table.td>
                            <x-admin.table.td innerClass="px-6 py-3 flex items-center justify-end gap-x-1.5">
                                <x-admin.button
                                    type="button"
                                    size="icon-sm"
                                    color="outline-secondary"
                                    title="Lihat Visi & Misi"
                                    data-hs-overlay="#vision-mission-modal"
                                    onclick="setVisionMissionData('{{ str_pad($d->order_number, 2, '0', STR_PAD_LEFT) }}', '{{ addslashes($d->chairman_name . ($d->vice_chairman_name ? ' & ' . $d->vice_chairman_name : '')) }}', {{ json_encode($d->vision ?? '') }}, {{ json_encode($d->mission ?? '') }})"
                                    class="hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 cursor-pointer"
                                >
                                    <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </x-admin.button>
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    href="{{ route('admin.candidates.update', $d->id) }}"
                                    title="Edit Paslon"
                                    class="hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200">
                                    @include('_admin._layout.icons.pencil')
                                </x-admin.button>
                                <x-admin.button
                                    size="icon-sm"
                                    color="outline-secondary"
                                    title="Hapus Paslon"
                                    data-hs-overlay="#delete-modal"
                                    onclick="setDeleteData('{{ $d->id }}', 'Kandidat Nomor Urut {{ $d->order_number }}')"
                                    class="hover:bg-red-50 hover:text-red-600 hover:border-red-200">
                                    @include('_admin._layout.icons.trash')
                                </x-admin.button>
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <x-admin.empty-state message="Belum ada kandidat yang terdaftar pada pemilihan ini." />
                            </td>
                        </tr>
                    @endforelse
                </x-admin.table.tbody>
            </x-admin.table>
            
            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator && count($data) > 0 && $data->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    <div class="flex justify-end">
                        {{ $data->links() }}
                    </div>
                </div>
            @endif
        </x-admin.table.wrapper>

        {{-- Vision & Mission Modal Component --}}
        <x-admin.vision-mission-modal />

        {{-- Delete Confirmation Modal --}}
        <x-admin.modal id="delete-modal" title="Hapus Kandidat Paslon" size="sm:max-w-md">
            <div class="text-center py-4">
                <span class="mb-4 inline-flex justify-center items-center size-12 rounded-full bg-red-50 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                </span>
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus <span id="delete-item-name" class="font-bold text-gray-900"></span>?<br>Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <x-slot:footer>
                <x-admin.button color="secondary" size="md" data-hs-overlay="#delete-modal">Batal</x-admin.button>
                <form id="delete-form" method="POST" action="" class="inline m-0 p-0" navigate-form>
                    @csrf
                    @method('DELETE')
                    <x-admin.button type="submit" color="danger" size="md">Ya, Hapus</x-admin.button>
                </form>
            </x-slot:footer>
        </x-admin.modal>
    </div>

    @push('scripts')
        <script>
            window.setVisionMissionData = function(pad, name, vision, mission) {
                const padEl = document.getElementById('vm-modal-pad');
                if (padEl) padEl.textContent = pad;

                const nameEl = document.getElementById('vm-modal-name');
                if (nameEl) nameEl.textContent = name;

                const visionEl = document.getElementById('vm-modal-vision');
                if (visionEl) {
                    if (vision && vision.trim() !== '') {
                        visionEl.textContent = vision.trim().replace(/^["']|["']$/g, '');
                        visionEl.classList.remove('text-gray-400', 'italic');
                        visionEl.classList.add('text-gray-800');
                    } else {
                        visionEl.textContent = 'Belum ada visi yang dicantumkan.';
                        visionEl.classList.add('text-gray-400', 'italic');
                        visionEl.classList.remove('text-gray-800');
                    }
                }

                const missionListEl = document.getElementById('vm-modal-mission-list');
                if (missionListEl) {
                    missionListEl.innerHTML = '';
                    if (!mission || mission.trim() === '') {
                        missionListEl.innerHTML = '<li class="text-xs sm:text-sm text-gray-400 italic">Belum ada misi yang dicantumkan.</li>';
                    } else {
                        const rawLines = mission.split(/\r?\n/).map(l => l.trim()).filter(l => l.length > 0);
                        let itemIndex = 1;
                        rawLines.forEach(line => {
                            const cleanLine = line.replace(/^(\*|\-|\d+[\.\)])\s*/, '').trim();
                            if (cleanLine.length > 0) {
                                const li = document.createElement('li');
                                li.className = 'flex items-start gap-3 text-xs sm:text-sm text-gray-800 leading-relaxed';
                                li.innerHTML = `
                                    <span class="size-5 rounded-full bg-blue-100/80 text-blue-700 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">
                                        ${itemIndex}
                                    </span>
                                    <span class="grow">${cleanLine}</span>
                                `;
                                missionListEl.appendChild(li);
                                itemIndex++;
                            }
                        });
                        if (missionListEl.children.length === 0) {
                            missionListEl.innerHTML = '<li class="text-xs sm:text-sm text-gray-400 italic">Belum ada misi yang dicantumkan.</li>';
                        }
                    }
                }
            };

            window.setDeleteData = function (id, name) {
                document.getElementById('delete-item-name').textContent = name;
                document.getElementById('delete-form').action = '{{ route('admin.candidates.index') }}/delete/' + id;
            };
        </script>
    @endpush
@endsection
