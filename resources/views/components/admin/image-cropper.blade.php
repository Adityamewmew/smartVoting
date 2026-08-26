@props([
    'name',
    'label' => 'Unggah Foto',
    'value' => null,
    'help' => 'Format JPG, PNG, atau WEBP. Maksimal 2MB.',
    'required' => false,
    'outputWidth' => 708,
    'outputHeight' => 944,
])

@php
    $cleanId = Str::slug($name, '_');
    $hasExistingPhoto = !empty($value);
    $existingPhotoUrl = $hasExistingPhoto ? Storage::url($value) : null;
    $isSquare = ($outputWidth === $outputHeight);
    $ratioText = $isSquare ? 'Auto 1:1' : 'Auto 3:4';
    $descText = $isSquare ? 'Rasio square 1:1 • Otomatis terpotong' : 'Rasio portrait 3:4 • Otomatis terpotong';
    $placeholderText = $isSquare ? 'Square 1:1 • Otomatis crop instan' : 'Portrait 3:4 • Otomatis crop instan';
@endphp

<div class="image-cropper-component w-full" id="cropper-wrapper-{{ $cleanId }}" data-name="{{ $name }}">
    <label class="block text-sm font-semibold text-gray-800 mb-1.5">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    {{-- Hidden standard file input that will submit to backend --}}
    <input 
        type="file" 
        id="file-input-{{ $cleanId }}" 
        name="{{ $name }}" 
        accept="image/png, image/jpeg, image/jpg, image/webp" 
        class="hidden"
        @if($required && !$hasExistingPhoto) required @endif
    />

    {{-- Main Container Card --}}
    <div id="drop-area-{{ $cleanId }}" class="border border-dashed border-gray-300 rounded-2xl p-4 bg-slate-50/50 hover:bg-slate-50 transition-all">
        
        {{-- Preview Box (Shown when photo exists or auto-cropped) --}}
        <div id="preview-container-{{ $cleanId }}" class="{{ $hasExistingPhoto ? 'flex' : 'hidden' }} items-center gap-4">
            <div class="relative size-20 sm:w-20 sm:h-26 rounded-xl overflow-hidden bg-slate-200 border border-gray-200 shadow-2xs shrink-0 flex items-center justify-center">
                <img 
                    id="preview-img-{{ $cleanId }}" 
                    src="{{ $existingPhotoUrl ?? '' }}" 
                    alt="Preview {{ $label }}" 
                    class="size-full object-contain p-1"
                />
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <p id="preview-filename-{{ $cleanId }}" class="text-xs sm:text-sm font-bold text-gray-900 truncate">
                        {{ $hasExistingPhoto ? 'Foto tersimpan' : 'Foto terpilih' }}
                    </p>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                        {{ $ratioText }}
                    </span>
                </div>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ $descText }}</p>
                <div class="mt-2.5">
                    <x-admin.button 
                        type="button" 
                        color="outline-danger" 
                        size="sm"
                        onclick="removePhoto_{{ $cleanId }}()" 
                        icon='<svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>'>
                        Hapus
                    </x-admin.button>
                </div>
            </div>
        </div>

        {{-- Dropzone / Upload Placeholder (Shown when no photo) --}}
        <div 
            id="dropzone-{{ $cleanId }}" 
            onclick="triggerFileInput_{{ $cleanId }}()"
            class="{{ $hasExistingPhoto ? 'hidden' : 'flex' }} flex-col items-center justify-center py-5 px-3 text-center cursor-pointer group rounded-xl transition-all">
            <div class="size-11 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-105 group-hover:bg-blue-100 transition-all border border-blue-100">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
            </div>
            <p class="text-xs sm:text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">
                Ketuk untuk memilih atau drag & drop
            </p>
            <p class="text-[11px] text-gray-400 mt-1">{{ $placeholderText }}</p>
        </div>

    </div>

    @if($help)
        <p class="text-[11px] text-gray-400 mt-1.5">{{ $help }}</p>
    @endif

    @error($name)
        <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
    @enderror
</div>

<script>
(function() {
    const fileInput_{{ $cleanId }} = document.getElementById('file-input-{{ $cleanId }}');
    const previewContainer_{{ $cleanId }} = document.getElementById('preview-container-{{ $cleanId }}');
    const dropzone_{{ $cleanId }} = document.getElementById('dropzone-{{ $cleanId }}');
    const dropArea_{{ $cleanId }} = document.getElementById('drop-area-{{ $cleanId }}');
    const previewImg_{{ $cleanId }} = document.getElementById('preview-img-{{ $cleanId }}');
    const previewFilename_{{ $cleanId }} = document.getElementById('preview-filename-{{ $cleanId }}');

    window.triggerFileInput_{{ $cleanId }} = function() {
        fileInput_{{ $cleanId }}.click();
    };

    function autoCenterCrop_{{ $cleanId }}(imgElement, rawFile) {
        const targetRatio = {{ $outputWidth }} / {{ $outputHeight }}; // 3/4
        const srcWidth = imgElement.naturalWidth || imgElement.width;
        const srcHeight = imgElement.naturalHeight || imgElement.height;
        const srcRatio = srcWidth / srcHeight;

        let cropX = 0, cropY = 0, cropWidth = srcWidth, cropHeight = srcHeight;
        if (srcRatio > targetRatio) {
            cropWidth = Math.round(srcHeight * targetRatio);
            cropX = Math.round((srcWidth - cropWidth) / 2);
        } else {
            cropHeight = Math.round(srcWidth / targetRatio);
            cropY = Math.round((srcHeight - cropHeight) / 2);
        }

        const canvas = document.createElement('canvas');
        canvas.width = {{ $outputWidth }};
        canvas.height = {{ $outputHeight }};
        const ctx = canvas.getContext('2d');
        ctx.drawImage(imgElement, cropX, cropY, cropWidth, cropHeight, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(function(blob) {
            if (!blob) return;

            const originalName = rawFile ? rawFile.name.replace(/\.[^/.]+$/, "") : "photo";
            const fileName = originalName + "_cropped.jpg";
            const croppedFile = new File([blob], fileName, { type: 'image/jpeg' });

            try {
                const dt = new DataTransfer();
                dt.items.add(croppedFile);
                fileInput_{{ $cleanId }}.files = dt.files;
            } catch(e) {}

            const objectUrl = URL.createObjectURL(blob);
            previewImg_{{ $cleanId }}.src = objectUrl;
            previewFilename_{{ $cleanId }}.textContent = rawFile ? rawFile.name : fileName;

            dropzone_{{ $cleanId }}.classList.add('hidden');
            dropzone_{{ $cleanId }}.classList.remove('flex');
            previewContainer_{{ $cleanId }}.classList.remove('hidden');
            previewContainer_{{ $cleanId }}.classList.add('flex');
        }, 'image/jpeg', 0.92);
    }

    function processSelectedFile_{{ $cleanId }}(file) {
        if (!file || !file.type.match(/^image\//)) {
            alert('File yang dipilih harus berupa gambar (JPG, PNG, WEBP).');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                autoCenterCrop_{{ $cleanId }}(img, file);
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }

    fileInput_{{ $cleanId }}.addEventListener('change', function(e) {
        const files = e.target.files;
        if (!files || files.length === 0) return;
        processSelectedFile_{{ $cleanId }}(files[0]);
    });

    // Drag and Drop Events
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea_{{ $cleanId }}.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropArea_{{ $cleanId }}.classList.add('border-blue-500', 'bg-blue-50/60', 'scale-[1.01]');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea_{{ $cleanId }}.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropArea_{{ $cleanId }}.classList.remove('border-blue-500', 'bg-blue-50/60', 'scale-[1.01]');
        });
    });

    dropArea_{{ $cleanId }}.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt ? dt.files : null;
        if (files && files.length > 0) {
            processSelectedFile_{{ $cleanId }}(files[0]);
        }
    });

    window.removePhoto_{{ $cleanId }} = function() {
        fileInput_{{ $cleanId }}.value = '';
        previewImg_{{ $cleanId }}.src = '';
        previewFilename_{{ $cleanId }}.textContent = '';
        previewContainer_{{ $cleanId }}.classList.add('hidden');
        previewContainer_{{ $cleanId }}.classList.remove('flex');
        dropzone_{{ $cleanId }}.classList.remove('hidden');
        dropzone_{{ $cleanId }}.classList.add('flex');
    };
})();
</script>
