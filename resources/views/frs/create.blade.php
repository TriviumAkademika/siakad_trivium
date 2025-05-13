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
    <h2>Tambah FRS</h2>
    <form action="{{ route('frs.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Mahasiswa</label>
            <select name="id_mahasiswa" class="form-control">
                @foreach ($mahasiswa as $m)
                    <option value="{{ $m->id_mahasiswa }}">{{ $m->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" class="form-control">
        </div>
        <div class="form-group">
            <label>Semester</label>
            <input type="text" name="semester" class="form-control">
        </div>
        <div class="form-group">
            <label>Total SKS</label>
            <input type="number" name="total_sks" class="form-control">
        </div>
        <button class="btn btn-success">Simpan</button>
    </form>


</body>

</html>
