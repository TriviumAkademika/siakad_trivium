@props(['label', 'value'])

<div class="flex flex-col w-full p-4 gap-2 bg-brand-100 rounded-lg shadow">
    <label class="font-semibold text-hitam">{{ $label }}</label>
    <input type="text" value="{{ $value }}" readonly
        class="w-full border-abu rounded-lg p-2 bg-putih text-base text-hitam cursor-not-allowed">
</div>
