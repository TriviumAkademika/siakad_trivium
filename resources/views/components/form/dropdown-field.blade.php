@props([
    'label',
    'name',
    'options' => [],
    'selected' => null,
    'required' => true,
    'valueField' => 'id',
    'labelFields' => 'name', // bisa string atau array
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
    <select name="{{ $name }}" id="{{ $name }}"
        class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
        {{ $isRequired }}>
        @foreach ($options as $item)
            @php
                // Deteksi jika array biasa atau object
                $value = is_array($item) ? $item[$valueField] : $item->{$valueField};

                if (is_array($labelFields)) {
                    $labelText = collect($labelFields)->map(function ($field) use ($item) {
                        return is_array($item) ? $item[$field] : $item->{$field};
                    })->join(' ');
                } else {
                    $labelText = is_array($item) ? $item[$labelFields] : $item->{$labelFields};
                }

                $isSelected = $value == $selected ? 'selected' : '';
            @endphp
            <option value="{{ $value }}" {{ $isSelected }}>{{ $labelText }}</option>
        @endforeach
    </select>
</div>
