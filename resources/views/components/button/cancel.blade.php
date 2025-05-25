@props([
    'icon' => '',
    'title' => 'Apakah Anda yakin ingin keluar?',
    'text' => 'Semua perubahan yang belum disimpan akan hilang.',
    'confirmText' => 'Keluar',
    'cancelText' => 'Batal',
    'onConfirm' => '',
])

<button type="button"
    class="btn bg-merah-700 hover:bg-merah-800 flex items-center justify-center text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0 transition"
    {{ $attributes }} x-data
    @click="
        Swal.fire({
            html: `
                <i class='ph ph-warning text-8xl text-merah-700'></i>
                <h2 class='text-2xl font-semibold'>{{ $title }}</h2>
                <p class='text-base text-hitam'>{{ $text }}</p>
            `,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: '{{ $confirmText }}',
            cancelButtonText: '{{ $cancelText }}',
            backdrop: false,
            allowOutsideClick: true,
            allowEscapeKey: true,
            allowEnterKey: true,
            focusCancel: true,
            customClass: {
                popup: 'shadow-sm border rounded-xl font-jakarta bg-putih',
                confirmButton: 'bg-brand-900 hover:bg-brand-950 text-sm text-putih rounded-lg px-4 py-2',
                cancelButton: 'bg-merah-700 hover:bg-merah-800 text-sm text-putih rounded-lg px-4 py-2',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                {{ $onConfirm }}
            }
        });
    ">
    @if ($icon)
        <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>