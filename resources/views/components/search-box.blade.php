{{-- resources/views/components/search-box.blade.php --}}

@props([
    'placeholder' => 'Cari data...',
    'inputId' => 'searchInput',
    'value' => '',
    'icon' => 'ph ph-magnifying-glass'
])

<div class="relative flex-1 max-w-md">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i class="{{ $icon }} text-gray-400"></i>
    </div>
    <input type="text" 
           id="{{ $inputId }}"
           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-biru-500 focus:border-biru-500 text-sm" 
           placeholder="{{ $placeholder }}"
           value="{{ $value }}"
           {{ $attributes }}>
</div>