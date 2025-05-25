<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Waktu</title>
  @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-50">

  <form action="{{ route('waktu.update', $waktu->id_waktu) }}" method="POST" class="max-w-md mx-auto bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    <div class="mb-4">
      <label class="block mb-1 text-sm font-medium">Hari</label>
      <input type="text" name="hari" value="{{ $waktu->hari }}" class="w-full border px-3 py-2 rounded" required>
    </div>
    <div class="mb-4">
      <label class="block mb-1 text-sm font-medium">Jam Mulai</label>
      <input type="time" name="jam_mulai" value="{{ $waktu->jam_mulai }}" class="w-full border px-3 py-2 rounded" required>
    </div>
    <div class="mb-4">
      <label class="block mb-1 text-sm font-medium">Jam Selesai</label>
      <input type="time" name="jam_selesai" value="{{ $waktu->jam_selesai }}" class="w-full border px-3 py-2 rounded" required>
    </div>
    <div class="flex justify-end space-x-2">
      <a href="{{ route('waktu.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</a>
      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
    </div>
  </form>

</body>
</html>
