@props(['class' => ''])

<td {{ $attributes->merge(['class' => "px-4 py-2 text-sm text-hitam $class"]) }}>
    {{ $slot }}
</td>