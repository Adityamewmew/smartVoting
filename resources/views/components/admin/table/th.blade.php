@props(['align' => 'start'])

<th scope="col" {{ $attributes->merge(['class' => 'px-6 py-4 text-' . $align]) }}>
    @if ($slot->isNotEmpty())
        <span class="font-semibold text-xs uppercase text-gray-500 tracking-wider">
            {{ $slot }}
        </span>
    @endif
</th>
