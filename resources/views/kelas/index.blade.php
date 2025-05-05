<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite('resources/css/app.css')
  <title>Daftar Kelas</title>
 
</head>
<body class="p-6 bg-gray-50">

  <div class="mb-4">
    <a href="{{ route('kelas.create') }}" class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
      + Tambah Kelas
    </a>
  </div>

  <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Dosen</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tahun Masuk</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Prodi</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Paralel</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Action</th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
      @foreach ($kelas as $index => $k)
        <tr>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $index + 1 }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $k->dosen->nama_dosen }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $k->tahun_masuk }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $k->prodi }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $k->paralel }}</td>
          <td class="px-4 py-2 text-sm text-gray-900 space-x-2">
            <a href="{{ route('kelas.edit', $k->id_kelas) }}" class="inline-block px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</a>
            <form action="{{ route('kelas.destroy', $k->id_kelas) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
