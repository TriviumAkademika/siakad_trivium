<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <title>Jadwal Kuliah</title>
</head>
<body class="p-6 bg-gray-50">

  <div class="mb-4">
    <a href="{{ route('jadwal.create') }}" class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
      + Tambah Jadwal
    </a>
  </div>

  <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2 text-sm font-semibold text-left">#</th>
        <th class="px-4 py-2 text-sm font-semibold text-left">Kelas</th>
        <th class="px-4 py-2 text-sm font-semibold text-left">Mata Kuliah</th>
        <th class="px-4 py-2 text-sm font-semibold text-left">Dosen</th>
        <th class="px-4 py-2 text-sm font-semibold text-left">Dosen Pendamping</th>
        <th class="px-4 py-2 text-sm font-semibold text-left">Waktu</th>
        <th class="px-4 py-2 text-sm font-semibold text-left">Ruangan</th>
        <th class="px-4 py-2 text-sm font-semibold text-left">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($jadwal as $index => $j)
      <tr>
        <td class="px-4 py-2 text-sm text-gray-700">{{ $index + 1 }}</td>
        <td class="px-4 py-2 text-sm text-gray-700">{{ $j->kelas->prodi }}-{{ $j->kelas->paralel }}</td>
        <td class="px-4 py-2 text-sm text-gray-700">{{ $j->matkul->jenis }} - {{ $j->matkul->nama_matkul }}</td>
        <td class="px-4 py-2 text-sm text-gray-700">{{ $j->dosen->nama_dosen }}</td>
        <td class="px-4 py-2 text-sm text-gray-700">{{ $j->dosen2 ? $j->dosen2->nama_dosen : '-' }}</td>
        <td class="px-4 py-2 text-sm text-gray-700">
            {{ $j->waktu->hari }}, {{ substr($j->waktu->jam_mulai, 0, 5) }} - {{ substr($j->waktu->jam_selesai, 0, 5) }}
        </td>
        <td class="px-4 py-2 text-sm text-gray-700">{{ $j->ruangan->kode_ruangan }}</td>
        <td class="px-4 py-2 text-sm text-gray-700 space-x-2">
          <a href="{{ route('jadwal.edit', $j->id_jadwal) }}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</a>
          <form action="{{ route('jadwal.destroy', $j->id_jadwal) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus jadwal ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

</body>
</html>
