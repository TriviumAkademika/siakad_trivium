<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Detail FRS</title>
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="max-w-7xl mx-auto px-4 py-8" x-data="{ open: false }">
        <h2 class="text-2xl font-bold mb-4">Detail FRS Mahasiswa</h2>

        <!-- Card Informasi FRS -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <p><strong>Nama:</strong> {{ $frs->mahasiswa->nama }}</p>
            <p><strong>Tahun Ajaran:</strong> {{ $frs->tahun_ajaran }}</p>
            <p><strong>Semester:</strong> {{ $frs->semester }}</p>
            <p><strong>Total SKS:</strong> {{ $frs->total_sks }}</p>
        </div>

        <!-- Tombol Modal -->
        <button @click="open = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            Tambah Jadwal
        </button>

        <!-- Table Detail FRS -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border">Matkul</th>
                        <th class="px-4 py-2 border">Dosen</th>
                        <th class="px-4 py-2 border">Ruangan</th>
                        <th class="px-4 py-2 border">Waktu</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($frs->detailFrs as $detail)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $detail->jadwal->matkul->nama_matkul }}</td>
                            <td class="px-4 py-2 border">{{ $detail->jadwal->dosen->nama_dosen }}</td>
                            <td class="px-4 py-2 border">{{ $detail->jadwal->ruangan->kode_ruangan }}</td>
                            <td class="px-4 py-2 border">
                                {{ $detail->jadwal->waktu->hari }}
                                ({{ $detail->jadwal->waktu->jam_mulai }} - {{ $detail->jadwal->waktu->jam_selesai }})
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $detail->status ? 'Diterima' : 'Belum Diterima' }}

                                <form action="{{ route('detail-frs.update-status', $detail->id_detail_frs) }}"
                                    method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-blue-600 hover:underline">
                                        {{ $detail->status ? 'Batalkan' : 'Terima' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-2 border">
                                <form action="{{ route('detail-frs.destroy', $detail->id_detail_frs) }}" method="POST"
                                    onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 relative">
                <!-- Tombol Tutup -->
                <button @click="open = false"
                    class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">✕</button>
                <h3 class="text-xl font-semibold mb-4">Pilih Jadwal</h3>

                <form method="POST" action="{{ route('detail-frs.store') }}">
                    @csrf
                    <input type="hidden" name="id_frs" value="{{ $frs->id_frs }}">

                    <div class="overflow-y-auto max-h-[60vh] border rounded">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 py-2 border">Pilih</th>
                                    <th class="px-2 py-2 border">Matkul</th>
                                    <th class="px-2 py-2 border">Dosen</th>
                                    <th class="px-2 py-2 border">Hari</th>
                                    <th class="px-2 py-2 border">Jam</th>
                                    <th class="px-2 py-2 border">Ruangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwals as $jadwal)
                                    <tr class="hover:bg-gray-50 border-t">
                                        <td class="px-2 py-2 border">
                                            <input type="checkbox" name="jadwal_ids[]"
                                                value="{{ $jadwal->id_jadwal }}">
                                        </td>
                                        <td class="px-2 py-2 border">{{ $jadwal->matkul->nama_matkul }}</td>
                                        <td class="px-2 py-2 border">{{ $jadwal->dosen->nama_dosen }}</td>
                                        <td class="px-2 py-2 border">{{ $jadwal->waktu->hari }}</td>
                                        <td class="px-2 py-2 border">
                                            {{ $jadwal->waktu->jam_mulai }} - {{ $jadwal->waktu->jam_selesai }}
                                        </td>
                                        <td class="px-2 py-2 border">{{ $jadwal->ruangan->kode_ruangan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-right">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Tambah ke FRS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
