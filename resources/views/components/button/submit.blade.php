@props([
    'type' => 'submit', // 'submit' | 'button' | 'link'
    'icon' => null,
    'href' => '#', // untuk type='link'
])

@if ($type === 'link')
    <a href="{{ $href }}"
        {{ $attributes->merge([
            'class' =>
                'btn bg-brand-900 hover:bg-brand-950 flex items-center justify-center text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0 transition',
        ]) }}>
        @if ($icon)
            <i class="{{ $icon }} mr-1"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}"
        {{ $attributes->merge([
            'class' =>
                'btn bg-brand-900 hover:bg-brand-950 flex items-center justify-center text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0 transition',
        ]) }}>
        @if ($icon)
            <i class="{{ $icon }} mr-1"></i>
        @endif
        {{ $slot }}
    </button>
@endif