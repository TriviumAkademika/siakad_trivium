@props([
    'deleteId',
    'deleteRoute',
    'itemName' => 'item ini',
    'title' => 'Hapus Data?',
    'confirmText' => 'Ya, Hapus!',
    'cancelText' => 'Batal',
    'class' =>
        'inline-flex items-center justify-center w-8 h-8 bg-merah-700 hover:bg-merah-800 text-white text-sm rounded transition-colors focus:outline-none focus:ring-2 focus:ring-red-500',
])

<button
    type="button"
    data-id="{{ $deleteId }}"
    data-route="{{ $deleteRoute }}"
    data-name="{{ $itemName }}"
    title="{{ $title }}"
    {{ $attributes->merge(['class' => $class . ' btn-delete']) }}
>
    <i class="ph ph-trash-simple text-sm"></i>
</button>

@push('scripts')
<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;

        const itemName = btn.dataset.name;
        const deleteRoute = btn.dataset.route;
        const title = btn.title || 'Hapus Data?';

        Swal.fire({
            html: `
                <i class="ph ph-warning text-8xl text-merah-700 mb-4"></i>
                <h2 class="text-2xl font-semibold text-hitam mb-1">${title}</h2>
                <p class="text-base text-hitam">Apakah Anda yakin ingin menghapus<br><strong>${itemName}?</strong></p>
            `,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: '{{ $confirmText }}',
            cancelButtonText: '{{ $cancelText }}',
            reverseButtons: true,
            backdrop: false,
            allowOutsideClick: true,
            allowEscapeKey: true,
            allowEnterKey: true,
            customClass: {
                popup: 'shadow-sm border rounded-xl font-jakarta bg-putih',
                confirmButton: 'bg-brand-900 hover:bg-brand-950 text-sm text-putih rounded-lg px-4 py-2',
                cancelButton: 'bg-merah-700 hover:bg-merah-800 text-sm text-putih rounded-lg px-4 py-2',
            },
        }).then(result => {
            if (result.isConfirmed) {
                const loadingSwal = Swal.fire({
                    html: `
                        <i class="ph ph-hourglass text-6xl text-brand-900 mb-2"></i>
                        <p class="text-lg font-medium text-hitam">Menghapus data...</p>
                    `,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    backdrop: false,
                    willOpen: () => {
                        Swal.showLoading();

                        // Hapus backdrop JIKA SweetAlert masih menyisipkan
                        setTimeout(() => {
                            const backdrop = document.querySelector('.swal2-backdrop');
                            if (backdrop) backdrop.remove();
                        }, 10); // tunggu sejenak supaya DOM siap
                    },
                    customClass: {
                        popup: 'shadow-sm border rounded-xl font-jakarta bg-putih',
                    },
                });

                // Submit form setelah swal muncul
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteRoute;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
</script>
@endpush