@props([
    'label',
    'name',
    'value' => '',
    'type' => 'time',
    'required' => true,
])

@php
    $isRequired = $required ? 'required' : '';
@endphp

<div class="flex w-full">
    <label for="{{ $name }}" class="flex items-center w-1/4 text-base font-medium text-hitam">
        {{ $label }}
        @if ($required)
            <span class="pl-1 text-error">*</span>
        @endif
    </label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}"
        class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
        {{ $isRequired }}>
</div>