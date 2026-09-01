@props(['message' => 'Data tidak ditemukan'])

<div class="flex flex-col items-center justify-center py-8">
    <img src="{{ asset('admin/images/empty-data.webp') }}" alt="Empty Data" class="w-32 h-auto mb-4">
    <p class="text-sm text-gray-500">{{ $message }}</p>
    {{ $slot }}
</div>
