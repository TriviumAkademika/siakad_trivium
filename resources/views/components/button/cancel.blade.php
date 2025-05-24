<button type="button"
    class="btn bg-merah-700 hover:bg-merah-800 flex items-center justify-center text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0 transition"
    onclick="window.history.back()">
    @if ($icon)
        <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>
