@props([
    'label' => null,
    'name',
    'id' => null,
    'value' => null,
    'placeholder' => 'Tulis konten dalam format Markdown...',
    'required' => false,
    'minHeight' => '160px',
    'hint' => null,
    'error' => null,
])

@php
    $editorId = $id ?? $name . '_' . str_replace('-', '_', \Illuminate\Support\Str::uuid());
    $editorValue = $value ?? old($name);
@endphp

<div class="space-y-1.5 markdown-editor-wrapper" id="wrapper-{{ $editorId }}">
    @if ($label)
        <label for="{{ $editorId }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <textarea
            id="{{ $editorId }}"
            name="{{ $name }}"
            class="hidden"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {!! $attributes->except('class') !!}>{{ $editorValue }}</textarea>
    </div>

    @if ($hint)
        <p class="text-xs text-gray-400 mt-1">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

@once
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <style>
        .EasyMDEContainer {
            font-family: inherit;
        }
        .EasyMDEContainer .editor-toolbar {
            border: 1px solid #E2E8F0;
            border-bottom: 1px solid #F1F5F9;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            background-color: #F8FAFC;
            padding: 4px 8px;
            opacity: 1;
        }
        .EasyMDEContainer .editor-toolbar button {
            color: #475569 !important;
            border-radius: 0.375rem;
            width: 28px;
            height: 28px;
            margin: 2px 1px;
            transition: all 0.15s ease;
        }
        .EasyMDEContainer .editor-toolbar button:hover,
        .EasyMDEContainer .editor-toolbar button.active {
            background-color: #E2E8F0 !important;
            color: #0F172A !important;
            border-color: transparent !important;
        }
        .EasyMDEContainer .editor-toolbar i.separator {
            border-right-color: #CBD5E1;
            margin: 0 4px;
        }
        .EasyMDEContainer .CodeMirror {
            border: 1px solid #E2E8F0;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            font-family: inherit;
            font-size: 0.875rem;
            color: #1E293B;
            background: #FFFFFF;
            min-height: var(--mde-min-height, 160px);
            padding: 4px;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.03);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .EasyMDEContainer .CodeMirror-focused {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .EasyMDEContainer .editor-preview,
        .EasyMDEContainer .editor-preview-side {
            background: #FFFFFF;
            padding: 1rem;
            font-family: inherit;
            font-size: 0.875rem;
            color: #334155;
            line-height: 1.6;
        }
        .EasyMDEContainer .editor-preview ul,
        .EasyMDEContainer .editor-preview-side ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin: 0.5rem 0;
        }
        .EasyMDEContainer .editor-preview ol,
        .EasyMDEContainer .editor-preview-side ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
            margin: 0.5rem 0;
        }
        .EasyMDEContainer .editor-statusbar {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
@endpush
@endonce

@push('scripts')
<script>
    (function () {
        function initEditor() {
            var el = document.getElementById('{{ $editorId }}');
            if (!el || el._easymde) return;

            if (typeof EasyMDE === 'undefined') {
                setTimeout(initEditor, 100);
                return;
            }

            var easyMDE = new EasyMDE({
                element: el,
                placeholder: '{{ addslashes($placeholder) }}',
                spellChecker: false,
                status: false,
                minHeight: '{{ $minHeight }}',
                toolbar: [
                    'bold', 'italic', 'heading', '|',
                    'quote', 'unordered-list', 'ordered-list', '|',
                    'preview', 'guide'
                ],
                autoDownloadFontAwesome: false
            });

            el._easymde = easyMDE;

            // Sync on change & form submission
            easyMDE.codemirror.on('change', function () {
                el.value = easyMDE.value();
            });

            var form = el.closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    el.value = easyMDE.value();
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initEditor);
        } else {
            initEditor();
        }
    })();
</script>
@endpush
