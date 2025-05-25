<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Tambah Kelas</title>
  @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-50">

  <div class="max-w-lg mx-auto bg-white shadow rounded p-6">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Tambah Kelas</h2>
    
    <form action="{{ route('kelas.store') }}" method="POST">
      @csrf

      <div class="mb-4">
        <label for="id_dosen" class="block text-sm font-semibold text-gray-700">Dosen Wali</label>
        <select name="id_dosen" id="id_dosen" class="mt-1 p-2 w-full border rounded">
          @foreach ($dosen as $d)
            <option value="{{ $d->id_dosen }}">{{ $d->nama_dosen }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-4">
        <label for="tahun_masuk" class="block text-sm font-semibold text-gray-700">Tahun Masuk</label>
        <input type="number" name="tahun_masuk" id="tahun_masuk" class="mt-1 p-2 w-full border rounded" required>
      </div>

      <div class="mb-4">
        <label for="prodi" class="block text-sm font-semibold text-gray-700">Prodi</label>
        <input type="text" name="prodi" id="prodi" class="mt-1 p-2 w-full border rounded" required>
      </div>

      <div class="mb-4">
        <label for="paralel" class="block text-sm font-semibold text-gray-700">Paralel</label>
        <input type="text" name="paralel" id="paralel" class="mt-1 p-2 w-full border rounded" required>
      </div>

      <button type="submit" class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
        Simpan
      </button>
    </form>
  </div>

</body>
</html>
