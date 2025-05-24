<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Ruangan</title>
  @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-50">
  <div class="mb-4">
    <a href="{{ route('ruangan.create') }}" class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
      + Tambah Ruangan
    </a>
  </div>

  <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Ruangan</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Gedung</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Kode Ruangan</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Action</th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
      @foreach ($ruangan as $index => $r)
        <tr>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $index + 1 }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $r->nama_ruangan }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $r->nama_gedung }}</td>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $r->kode_ruangan }}</td>
          <td class="px-4 py-2 space-x-2">
            <a href="{{ route('ruangan.edit', $r->id_ruangan) }}" class="inline-block px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</a>
            {{-- <form action="{{ route('ruangan.destroy', $r->id_ruangan) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
            </form> --}}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
