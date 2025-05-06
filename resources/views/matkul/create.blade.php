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
  

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded">
  <h2 class="text-lg font-semibold mb-4">Tambah Mata Kuliah</h2>

  <form action="{{ route('matkul.store') }}" method="POST">
    @csrf
    <div class="mb-4">
      <label for="nama_matkul" class="block text-sm font-medium">Nama Mata Kuliah</label>
      <input type="text" name="nama_matkul" id="nama_matkul" class="mt-1 block w-full border rounded px-3 py-2" required>
    </div>

    <div class="mb-4">
      <label for="jenis" class="block text-sm font-medium">Jenis</label>
      <input type="text" name="jenis" id="jenis" class="mt-1 block w-full border rounded px-3 py-2" required>
    </div>

    <div class="mb-4">
      <label for="sks" class="block text-sm font-medium">SKS</label>
      <input type="number" name="sks" id="sks" class="mt-1 block w-full border rounded px-3 py-2" required>
    </div>

    <div class="mb-4">
      <label for="kapasitas_kelas" class="block text-sm font-medium">Kapasitas Kelas</label>
      <input type="number" name="kapasitas_kelas" id="kapasitas_kelas" class="mt-1 block w-full border rounded px-3 py-2" required>
    </div>

    <div class="flex justify-end space-x-2">
      <a href="{{ route('matkul.index') }}" class="px-4 py-2 bg-gray-300 text-sm rounded hover:bg-gray-400">Batal</a>
      <button type="submit" class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">Simpan</button>
    </div>
  </form>
</div>
@endsection

</body>
</html>