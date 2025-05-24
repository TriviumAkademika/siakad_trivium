@if ($message)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        x-transition
        class="fixed top-8 right-8 {{ $classes }} px-6 py-4 rounded shadow z-50">
        <span class="text-sm">{{ $message }}</span>
    </div>
@endif