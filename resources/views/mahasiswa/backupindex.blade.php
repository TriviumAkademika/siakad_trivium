<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Mahasiswa</title>
  @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-50">

  <div class="mb-4">
    <a href="{{ route('mahasiswa.create') }}" class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
      + Tambah Mahasiswa
    </a>
  </div>

  <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">NRP</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Kelas</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Semester</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Gender</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">No HP</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Action</th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
      @foreach ($mahasiswa as $index => $m)
        <tr>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $index + 1 }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $m->nama }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $m->nrp }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">
            {{ $m->kelas ? $m->kelas->prodi . ' ' . $m->kelas->paralel : '-' }}
          </td>          
          <td class="px-4 py-2 text-sm text-gray-900">{{ $m->semester }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $m->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $m->no_hp }}</td>
          <td class="px-4 py-2 text-sm text-gray-900 space-x-2">
            <a href="{{ route('mahasiswa.edit', $m->id_mahasiswa) }}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</a>
            <form action="{{ route('mahasiswa.destroy', $m->id_mahasiswa) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
