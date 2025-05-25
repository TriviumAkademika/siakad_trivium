<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite('resources/css/app.css')
  <title>Document</title>
</head>
<body>
  <form action="{{ route('dosen.store') }}" method="POST" class="max-w-md mx-auto p-6 bg-white rounded shadow">
    @csrf
    <h2 class="text-xl font-semibold mb-4">Tambah Dosen</h2>
  
    <div class="mb-4">
      <label class="block mb-1 text-sm font-medium">Nama Dosen</label>
      <input type="text" name="nama_dosen" class="w-full border rounded px-3 py-2" required>
    </div>
  
    <div class="mb-4">
      <label class="block mb-1 text-sm font-medium">NIP</label>
      <input type="text" name="nip" class="w-full border rounded px-3 py-2" required>
    </div>
  
    <div class="mb-4">
      <label class="block mb-1 text-sm font-medium">Alamat</label>
      <textarea name="alamat" class="w-full border rounded px-3 py-2" required></textarea>
    </div>
  
    <div class="mb-4">
      <label class="block mb-1 text-sm font-medium">No HP</label>
      <input type="text" name="no_hp" class="w-full border rounded px-3 py-2" required>
    </div>
  
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
  </form>
  
</body>
</html>