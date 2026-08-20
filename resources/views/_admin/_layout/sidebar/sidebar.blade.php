@php
    use App\Constants\UserConst;
@endphp

<div id="hs-application-sidebar"
    class="hs-overlay [--auto-close:lg]
  hs-overlay-open:translate-x-0
  -translate-x-full transition-all duration-300 transform
  w-64
  hidden
  fixed top-16 bottom-0 start-0 z-50
  lg:block lg:translate-x-0 lg:end-auto lg:z-40
  bg-white border-r border-gray-100 shadow-xs"
    role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full">
        <div class="px-5 pt-5 pb-2">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Menu Utama</span>
        </div>

        <div
            class="flex-1 overflow-y-auto overscroll-contain [-webkit-overflow-scrolling:touch] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200">
            <nav class="hs-accordion-group px-3 py-2 w-full flex flex-col flex-wrap">
                <ul class="flex flex-col space-y-1">
                    @php
                        $user = Auth::user();
                        $dashboardRoute = match ($user->access_type) {
                            UserConst::SUPERADMIN => 'admin.dashboard',
                            default => 'admin.dashboard',
                        };
                    @endphp

                    @include('_admin._layout.sidebar.sidebar_utama')

                    @if(!empty($sidebarMenus['pengaturan']))
                        <li class="pt-4 pb-1.5 px-3 mt-1">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pengaturan</span>
                        </li>
                        @foreach ($sidebarMenus['pengaturan'] as $menu)
                            @include('_admin._layout.sidebar._menu_item', ['menu' => $menu])
                        @endforeach
                    @endif
                </ul>
            </nav>
        </div>

        <div
            class="p-3 border-t border-gray-100 sticky bottom-0 z-10 bg-white">
            <div class="hs-dropdown relative inline-flex w-full [--placement:top-left]">
                <button id="sidebar-bottom-dropdown" type="button"
                    class="hs-dropdown-toggle w-full group flex items-center gap-x-3 py-2 px-2.5 text-start text-sm rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 hover:border-gray-200 transition-all duration-150 cursor-pointer"
                    aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                    <div class="relative">
                        <img class="shrink-0 size-9 rounded-full ring-2 ring-white shadow-xs"
                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563EB&color=fff&length=2"
                            alt="Avatar">
                        <span
                            class="absolute bottom-0 right-0 size-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div class="grow min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">
                            {{ $user->name }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ UserConst::getAccessTypes()[$user->access_type] ?? 'Unknown' }}
                        </p>
                    </div>
                    <svg class="size-4 text-gray-400 group-hover:text-gray-600 transition-colors"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-lg rounded-xl mb-2 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                    role="menu" aria-orientation="vertical" aria-labelledby="sidebar-bottom-dropdown">
                    <div class="p-1.5 space-y-0.5">
                        <a navigate
                            class="flex items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 transition-colors"
                            href="{{ route('admin.profile.change_password') }}">
                            @include('_admin._layout.icons.sidebar.change-password')
                            Ubah Password
                        </a>
                        <form action="{{ route('logout') }}" method="POST"
                            onsubmit="return confirm('Apakah anda yakin ingin keluar?');">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 focus:outline-hidden focus:bg-red-50 transition-colors">
                                @include('_admin._layout.icons.sidebar.logout')
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebarAccordions = document.querySelectorAll('#hs-application-sidebar .hs-accordion-toggle');
        const scrollContainer = document.querySelector('#hs-application-sidebar .overflow-y-auto');

        if (sidebarAccordions.length > 0 && scrollContainer) {
            sidebarAccordions.forEach(toggle => {
                toggle.addEventListener('click', (e) => {
                    const accordion = toggle.closest('.hs-accordion');

                    if (accordion && !accordion.classList.contains('active')) {
                        // Tunggu animasi expand selesai (Preline biasanya ~300ms)
                        setTimeout(() => {
                            const accordionRect = accordion.getBoundingClientRect();
                            const containerRect = scrollContainer
                                .getBoundingClientRect();

                            // Jika bagian bawah accordion melebihi bagian bawah container yang terlihat
                            if (accordionRect.bottom > containerRect.bottom) {
                                // Hitung selisihnya dan tambahkan sedikit jarak (padding)
                                const scrollDistance = accordionRect.bottom -
                                    containerRect.bottom + 20;

                                scrollContainer.scrollBy({
                                    top: scrollDistance,
                                    behavior: 'smooth'
                                });
                            }
                        }, 350);
                    }
                });
            });
        }

    });
</script>
