@props([
    'type' => 'button',
    'icon' => null,
    'formId' => null,
    'checkboxName' => 'jadwal_ids[]',
])

<button type="{{ $type }}"
    {{ $attributes->merge([
        'class' =>
            'btn bg-brand-900 hover:bg-brand-950 flex items-center justify-center text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0 transition',
    ]) }}
    onclick="checkAndSubmitForm('{{ $formId }}', '{{ $checkboxName }}')">
    @if ($icon)
        <i class="{{ $icon }} mr-1"></i>
    @endif
    {{ $slot }}
</button>

<script>
    function checkAndSubmitForm(formId, checkboxName) {
        const checked = document.querySelectorAll(`input[name="${checkboxName}"]:checked`);

        if (checked.length === 0) {
            Swal.fire({
                html: `
                    <i class='ph ph-warning text-8xl text-merah-700'></i>
                    <h2 class='text-2xl font-semibold'>Tidak ada jadwal dipilih</h2>
                    <p class='text-base text-hitam'>Silakan pilih setidaknya satu jadwal untuk ditambahkan.</p>
                `,
                showCancelButton: false,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                backdrop: false,
                allowOutsideClick: true,
                allowEscapeKey: true,
                allowEnterKey: true,
                customClass: {
                    popup: 'shadow-sm border rounded-xl font-jakarta bg-putih',
                    confirmButton: 'bg-brand-900 hover:bg-brand-950 text-sm text-putih rounded-lg px-4 py-2',
                },
            });
        } else {
            document.getElementById(formId).submit();
        }
    }
</script>
