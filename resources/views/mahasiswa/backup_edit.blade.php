<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Mahasiswa</title>
  @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-50">

  <h1 class="text-xl font-bold mb-4">Edit Mahasiswa</h1>

  <form action="{{ route('mahasiswa.update', $mahasiswa->id_mahasiswa) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700">Nama</label>
      <input type="text" name="nama" value="{{ $mahasiswa->nama }}" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">NRP</label>
      <input type="text" name="nrp" value="{{ $mahasiswa->nrp }}" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Kelas</label>
      <select name="id_kelas" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
        @foreach ($kelas as $k)
          <option value="{{ $k->id_kelas }}" {{ $mahasiswa->id_kelas == $k->id_kelas ? 'selected' : '' }}>
           {{ $k->prodi }} {{ $k->paralel }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Semester</label>
      <input type="text" name="semester" value="{{ $mahasiswa->semester }}" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Gender</label>
      <select name="gender" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
        <option value="L" {{ $mahasiswa->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
        <option value="P" {{ $mahasiswa->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Alamat</label>
      <textarea name="alamat" class="mt-1 block w-full border border-gray-300 rounded p-2" rows="3">{{ $mahasiswa->alamat }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">No HP</label>
      <input type="text" name="no_hp" value="{{ $mahasiswa->no_hp }}" class="mt-1 block w-full border border-gray-300 rounded p-2">
    </div>

    <div>
      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
    </div>
  </form>

</body>
</html>
