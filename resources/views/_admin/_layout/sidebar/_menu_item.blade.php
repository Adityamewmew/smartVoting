{{--
    Partial: sidebar menu item
    Variables:
    - $menu: object with id, label, route_name, icon, children (array)
    - $routeParams: optional array of route parameters
--}}
@php
    $routeParams = $routeParams ?? [];
    $hasChildren = !empty($menu->children) && count($menu->children) > 0;
    $accordionId = 'db-menu-accordion-' . $menu->id;

    // Check if a route name is active (exact, resource sub-routes, or named sub-routes)
    $checkRouteActive = function (string $routeName, array $siblingRoutes = []): bool {
        try {
            if (request()->routeIs($routeName)) {
                return true;
            }

            // .index routes cover sibling pages (detail, add, …) under the same resource,
            // unless a more specific sibling route matches the current page.
            if (str_ends_with($routeName, '.index')) {
                $resourcePrefix = substr($routeName, 0, -strlen('.index'));
                if (request()->routeIs($resourcePrefix . '.*')) {
                    foreach ($siblingRoutes as $siblingRoute) {
                        if ($siblingRoute !== $routeName && $siblingRoute !== '' && request()->routeIs($siblingRoute . '*')) {
                            return false;
                        }
                    }

                    return true;
                }

                return false;
            }

            // Non-index routes with a meaningful prefix (3+ segments)
            if (substr_count($routeName, '.') >= 2) {
                return request()->routeIs($routeName . '.*') || request()->routeIs($routeName . '*');
            }
        } catch (\Exception $e) {
            // ignore
        }

        return false;
    };

    $siblingRoutes = $hasChildren
        ? collect($menu->children)->pluck('route_name')->filter()->values()->all()
        : [];

    // Determine if this menu item (or its children) is active
    $isActive = false;
    if (!$hasChildren && $menu->route_name) {
        $isActive = $checkRouteActive($menu->route_name);
    } elseif ($hasChildren) {
        foreach ($menu->children as $child) {
            if ($child->route_name && $checkRouteActive($child->route_name, $siblingRoutes)) {
                $isActive = true;
                break;
            }
        }
    }

    $activeClass = 'bg-gradient-to-r from-blue-50 to-blue-100/60 text-blue-700 font-bold border border-blue-200/80 shadow-2xs shadow-blue-500/10';
    $inactiveClass = 'text-gray-600 hover:bg-gray-100/70 hover:text-gray-900 border border-transparent font-medium';
@endphp

@if (!$hasChildren)
    {{-- Simple link item --}}
    <li>
        @if ($menu->route_name)
            @php
                try {
                    $url = route($menu->route_name, $routeParams);
                } catch (\Exception $e) {
                    $url = '#';
                }
            @endphp
            <a navigate
                class="group flex items-center gap-x-3 py-2 px-3 {{ $isActive ? $activeClass : $inactiveClass }} text-sm rounded-xl transition-all duration-150"
                href="{{ $url }}">
                @if ($menu->icon)
                    <span class="relative shrink-0 {{ $isActive ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                        @include($menu->icon)
                    </span>
                @endif
                <span class="relative truncate">{{ $menu->label }}</span>
            </a>
        @else
            <span
                class="group flex items-center gap-x-3 py-2 px-3 {{ $inactiveClass }} text-sm rounded-xl transition-all duration-150">
                @if ($menu->icon)
                    <span class="relative shrink-0 text-gray-400">
                        @include($menu->icon)
                    </span>
                @endif
                <span class="relative truncate">{{ $menu->label }}</span>
            </span>
        @endif
    </li>
@else
    {{-- Accordion item with children --}}
    <li class="hs-accordion {{ $isActive ? 'active' : '' }}" id="{{ $accordionId }}">
        <button type="button"
            class="hs-accordion-toggle group w-full text-start flex items-center justify-between gap-x-3 py-2 px-3 text-sm rounded-xl transition-all duration-150 cursor-pointer {{ $isActive ? $activeClass : $inactiveClass }}"
            aria-expanded="{{ $isActive ? 'true' : 'false' }}"
            aria-controls="{{ $accordionId }}-child">
            <div class="flex items-center gap-x-3 min-w-0">
                @if ($menu->icon)
                    <span class="relative shrink-0 {{ $isActive ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                        @include($menu->icon)
                    </span>
                @endif
                <span class="truncate">{{ $menu->label }}</span>
            </div>
            <svg class="hs-accordion-active:block hidden size-4 transition-transform duration-150 text-gray-400"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m18 15-6-6 6-6" />
            </svg>
            <svg class="hs-accordion-active:hidden block size-4 transition-transform duration-150 text-gray-400"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6" />
            </svg>
        </button>

        <div id="{{ $accordionId }}-child"
            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-200 {{ $isActive ? 'block' : 'hidden' }}"
            role="region" aria-labelledby="{{ $accordionId }}">
            <ul class="ps-6 pt-1.5 space-y-1">
                @foreach ($menu->children as $child)
                    @php
                        $childActive = $child->route_name
                            ? $checkRouteActive($child->route_name, $siblingRoutes)
                            : false;
                        try {
                            $childUrl = $child->route_name ? route($child->route_name, $routeParams) : '#';
                        } catch (\Exception $e) {
                            $childUrl = '#';
                        }
                    @endphp
                    <li>
                        <a navigate
                            class="group flex items-center gap-x-2.5 py-1.5 px-3 text-xs rounded-lg {{ $childActive ? 'bg-blue-50 text-blue-700 font-semibold border border-blue-100/60' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-all duration-150"
                            href="{{ $childUrl }}">
                            <span
                                class="size-1.5 rounded-full {{ $childActive ? 'bg-blue-600' : 'bg-gray-300 group-hover:bg-gray-400' }} transition-colors"></span>
                            <span class="truncate">{{ $child->label }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </li>
@endif
