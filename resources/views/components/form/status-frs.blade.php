@props(['detailId', 'currentStatus' => false])

<select name="status" id="status-{{ $detailId }}" onchange="updateStatusFrs({{ $detailId }}, this.value, this)"
    class="text-xs font-medium rounded-md px-2 py-1.5 min-w-[120px] focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition-all duration-200 border
           {{ $currentStatus ? 'bg-green-100 text-green-800 border-green-300' : 'bg-yellow-100 text-yellow-800 border-yellow-300' }}">
    <option value="0" {{ !$currentStatus ? 'selected' : '' }}>Tidak Diterima</option>
    <option value="1" {{ $currentStatus ? 'selected' : '' }}>Diterima</option>
</select>

@once
    @push('scripts')
        <script>
            function updateStatusFrs(detailId, status, selectElement) {
                selectElement.disabled = true;
                selectElement.style.opacity = '0.6';

                fetch(`{{ url('detail-frs') }}/${detailId}/update-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',  // PENTING supaya Laravel response JSON
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update warna dropdown berdasarkan status
                        selectElement.classList.remove(
                            'bg-green-100', 'text-green-800', 'border-green-300',
                            'bg-yellow-100', 'text-yellow-800', 'border-yellow-300'
                        );

                        if (data.status) { // true = diterima
                            selectElement.classList.add('bg-green-100', 'text-green-800', 'border-green-300');
                        } else {
                            selectElement.classList.add('bg-yellow-100', 'text-yellow-800', 'border-yellow-300');
                        }

                        // Bersihin class ganda
                        selectElement.className = selectElement.className.replace(/\s+/g, ' ').trim();

                        // Set session flash untuk toast via AJAX
                        fetch(`{{ route('detail-frs.set-session') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                message: data.message,
                                type: 'success'
                            })
                        }).then(() => location.reload());
                    } else {
                        throw new Error('Gagal mengupdate status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Reset dropdown ke status sebelumnya
                    selectElement.value = status == '1' ? '0' : '1';

                    fetch(`{{ route('detail-frs.set-session') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: 'Terjadi kesalahan!',
                            type: 'error'
                        })
                    }).then(() => location.reload());
                })
                .finally(() => {
                    selectElement.disabled = false;
                    selectElement.style.opacity = '1';
                });
            }
        </script>
    @endpush
@endonce