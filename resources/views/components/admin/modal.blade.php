@props([
    'id',
    'title' => '',
    'icon' => null,
    'size' => 'sm:max-w-xl',
    'formAction' => null,
    'formMethod' => 'POST',
    'formClass' => '',
    'formId' => null,
    'footer' => null,
])


<div id="{{ $id }}"
    class="hs-overlay [--overlay-backdrop:false] hidden size-full fixed top-0 start-0 z-[1100] overflow-x-hidden overflow-y-auto pointer-events-auto bg-gray-900/50 backdrop-blur-xs transition-all duration-300"
    role="dialog" tabindex="-1" aria-labelledby="{{ $id }}-label">
    <div
        class="relative z-10 hs-overlay-open:scale-100 hs-overlay-open:opacity-100 hs-overlay-open:duration-250 scale-95 opacity-0 ease-out transition-all {{ $size }} sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        {{-- Card Container --}}
        <div
            class="w-full flex flex-col bg-white border border-gray-200/80 shadow-2xl rounded-2xl overflow-hidden max-h-[90vh]">

            {{-- Header --}}
            <div
                class="flex justify-between items-center py-3.5 px-4 sm:px-6 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-3 min-w-0">
                    @if ($icon)
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                            {!! $icon !!}
                        </div>
                    @endif
                    <h2 id="{{ $id }}-label" class="font-bold text-base sm:text-lg text-gray-900 truncate">
                        {{ $title }}
                    </h2>
                </div>
                <button type="button"
                    class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 active:scale-95 transition-colors cursor-pointer shrink-0 ml-2"
                    aria-label="Close" data-hs-overlay="#{{ $id }}">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            @if ($formAction)
                <form id="{{ $formId }}" action="{{ $formAction }}"
                    method="{{ strtoupper($formMethod) === 'GET' ? 'GET' : 'POST' }}"
                    {{ $attributes->merge(['class' => $formClass]) }} navigate-form>
                    @csrf
                    @if (!in_array(strtoupper($formMethod), ['GET', 'POST']))
                        @method($formMethod)
                    @endif
            @endif

            {{-- Body --}}
            <div class="p-4 sm:p-6 overflow-y-auto overscroll-contain">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @if ($footer)
                <div
                    class="flex flex-col-reverse sm:flex-row justify-end items-stretch sm:items-center gap-2 py-3.5 px-4 sm:px-6 border-t border-gray-100 bg-gray-50/30">
                    {{ $footer }}
                </div>
            @elseif($formAction)
                <div
                    class="flex flex-col-reverse sm:flex-row justify-end items-stretch sm:items-center gap-2 py-3.5 px-4 sm:px-6 border-t border-gray-100 bg-gray-50/30">
                    <button type="button"
                        class="py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 active:scale-95 transition-colors cursor-pointer"
                        data-hs-overlay="#{{ $id }}">
                        Batal
                    </button>
                    <x-admin.button type="submit" class="font-semibold py-2 px-5 rounded-xl justify-center">
                        Simpan
                    </x-admin.button>
                </div>
            @endif

            @if ($formAction)
                </form>
            @endif
        </div>
    </div>
</div>
