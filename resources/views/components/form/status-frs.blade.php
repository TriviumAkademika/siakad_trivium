@props(['detailId', 'currentStatus' => false]) {{-- Komponen untuk dropdown status FRS --}}

<select name="status" id="status-{{ $detailId }}" onchange="updateStatusFrs({{ $detailId }}, this.value, this)"
    class="text-xs font-medium rounded-md px-2 py-1.5 min-w-[120px] focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition-all duration-200 border
           {{ $currentStatus ? 'bg-green-100 text-green-800 border-green-300' : 'bg-yellow-100 text-yellow-800 border-yellow-300' }}">
    {{-- Opsi Status --}}
    <option value="0" {{ !$currentStatus ? 'selected' : '' }}>Tidak Diterima</option>
    <option value="1" {{ $currentStatus ? 'selected' : '' }}>Diterima</option>
</select>

@once
    @push('scripts')
        <script>
            function updateStatusFrs(detailId, status, selectElement) {
                selectElement.disabled = true; // Menonaktifkan dropdown saat update
                selectElement.style.opacity = '0.6'; // Mengubah opacity untuk efek loading

                fetch(`{{ url('detail-frs') }}/${detailId}/update-status`, {
                        method: 'PATCH', // Memakai PATCH untuk update status
                        headers: {
                            'Content-Type': 'application/json', // Pastikan Content-Type adalah JSON
                            'Accept': 'application/json', // PENTING supaya Laravel response JSON
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: status
                        }) // Kirim status sebagai JSON
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Jaringan bermasalah'); // Tangani error jaringan
                        return response.json(); // Parse response JSON
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
                            throw new Error('Gagal mengupdate status'); // Tangani error jika update gagal
                        }
                    })
                    .catch(error => { /
                        console.error('Error:', error);
                        // Reset dropdown ke status sebelumnya
                        selectElement.value = status == '1' ? '0' : '1'; // Kembalikan ke status sebelumnya

                        fetch(`{{ route('detail-frs.set-session') }}`, { // Set session flash untuk error
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                message: 'Terjadi kesalahan!',
                                type: 'error'
                            })
                        }).then(() => location.reload()); // Reload halaman untuk menampilkan pesan error
                    })
                    .finally(() => {
                        selectElement.disabled = false; // Aktifkan kembali dropdown
                        selectElement.style.opacity = '1'; // Kembalikan opacity ke normal
                    });
            }
        </script>
    @endpush
@endonce
