<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Ruangan</title>
  @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-50">
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Edit Ruangan</h2>
    <form action="{{ route('ruangan.update', $ruangan->id_ruangan) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-4">
        <label class="block mb-1 text-sm">Nama Ruangan</label>
        <input type="text" name="nama_ruangan" value="{{ $ruangan->nama_ruangan }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
      <div class="mb-4">
        <label class="block mb-1 text-sm">Nama Gedung</label>
        <input type="text" name="nama_gedung" value="{{ $ruangan->nama_gedung }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
      <div class="mb-4">
        <label class="block mb-1 text-sm">Kode Ruangan</label>
        <input type="text" name="kode_ruangan" value="{{ $ruangan->kode_ruangan }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
      </div>
      <div class="flex justify-end space-x-2">
        <a href="{{ route('ruangan.index') }}" class="px-4 py-2 bg-gray-300 text-sm rounded hover:bg-gray-400">Batal</a>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">Update</button>
      </div>
    </form>
  </div>
</body>
</html>
