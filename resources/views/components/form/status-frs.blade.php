@props(['detailId', 'currentStatus' => false])

<select name="status" id="status-{{ $detailId }}" onchange="updateStatusFrs({{ $detailId }}, this.value, this)"
    class="text-xs font-medium rounded-md px-2 py-1.5 min-w-[120px] focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition-all duration-200 border
               {{ $currentStatus ? 'bg-green-100 text-green-800 border-green-300' : 'bg-yellow-100 text-yellow-800 border-yellow-300' }}">
    <option value="0" {{ !$currentStatus ? 'selected' : '' }}>Belum Diterima</option>
    <option value="1" {{ $currentStatus ? 'selected' : '' }}>Diterima</option>
</select>

@once
    @push('scripts')
        <script>
            // Toast configuration
            if (typeof window.Toast === 'undefined') {
                window.Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }

            function updateStatusFrs(detailId, status, selectElement) {
                // Disable dropdown sementara
                selectElement.disabled = true;
                selectElement.style.opacity = '0.6';

                fetch(`{{ url('detail-frs') }}/${detailId}/update-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update warna dropdown berdasarkan status
                            selectElement.classList.remove(
                                'bg-green-100', 'text-green-800', 'border-green-300',
                                'bg-yellow-100', 'text-yellow-800', 'border-yellow-300'
                            );

                            if (status == '1') {
                                selectElement.classList.add('bg-green-100', 'text-green-800', 'border-green-300');
                            } else {
                                selectElement.classList.add('bg-yellow-100', 'text-yellow-800', 'border-yellow-300');
                            }

                            // Clean up class duplicates
                            selectElement.className = selectElement.className.replace(/\s+/g, ' ').trim();

                            window.Toast.fire({
                                icon: 'success',
                                title: 'Status berhasil diupdate!'
                            });
                        } else {
                            // Kembalikan ke nilai sebelumnya
                            selectElement.value = status == '1' ? '0' : '1';
                            window.Toast.fire({
                                icon: 'error',
                                title: 'Gagal mengupdate status!'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        selectElement.value = status == '1' ? '0' : '1';
                        window.Toast.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan!'
                        });
                    })
                    .finally(() => {
                        selectElement.disabled = false;
                        selectElement.style.opacity = '1';
                    });
            }
        </script>
    @endpush
@endonce
