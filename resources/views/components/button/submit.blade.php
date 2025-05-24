<button type="submit"
    class="btn bg-brand-900 hover:bg-brand-950 flex items-center justify-center text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0 transition">
    @if ($icon)
        <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>