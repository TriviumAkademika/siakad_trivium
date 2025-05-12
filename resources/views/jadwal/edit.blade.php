<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Jadwal</title>
  @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-50">

  <form action="{{ route('jadwal.update', $jadwal->id_jadwal) }}" method="POST" class="bg-white p-6 rounded shadow-md space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium">Kelas</label>
      <select name="id_kelas" class="w-full border rounded px-3 py-2">
        @foreach ($kelas as $k)
          <option value="{{ $k->id_kelas }}" @selected($jadwal->id_kelas == $k->id_kelas)>
            {{ $k->prodi }} - {{ $k->paralel }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium">Mata Kuliah</label>
      <select name="id_matkul" class="w-full border rounded px-3 py-2">
        @foreach ($matkul as $m)
          <option value="{{ $m->id_matkul }}" @selected($jadwal->id_matkul == $m->id_matkul)>
            {{ $m->jenis }} - {{ $m->nama_matkul }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium">Dosen</label>
      <select name="id_dosen" class="w-full border rounded px-3 py-2">
        @foreach ($dosen as $d)
          <option value="{{ $d->id_dosen }}" @selected($jadwal->id_dosen == $d->id_dosen)>
            {{ $d->nama_dosen }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium">Waktu</label>
      <select name="id_waktu" class="w-full border rounded px-3 py-2">
        @foreach ($waktu as $w)
          <option value="{{ $w->id_waktu }}" @selected($jadwal->id_waktu == $w->id_waktu)>
            {{ $w->hari }} ({{ $w->jam_mulai }} - {{ $w->jam_selesai }})
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium">Ruangan</label>
      <select name="id_ruangan" class="w-full border rounded px-3 py-2">
        @foreach ($ruangan as $r)
          <option value="{{ $r->id_ruangan }}" @selected($jadwal->id_ruangan == $r->id_ruangan)>
            {{ $r->kode_ruangan }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update</button>
    </div>
  </form>

</body>
</html>
