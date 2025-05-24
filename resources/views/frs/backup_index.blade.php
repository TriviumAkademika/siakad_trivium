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
    <h2>Data FRS</h2>
    <a href="{{ route('frs.create') }}" class="btn btn-primary mb-3">Tambah FRS</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>Tahun Ajaran</th>
                <th>Semester</th>
                <th>Total SKS</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($frs as $item)
                <tr>
                    <td>{{ $item->mahasiswa->nama }}</td>
                    <td>{{ $item->tahun_ajaran }}</td>
                    <td>{{ $item->semester }}</td>
                    <td>{{ $item->total_sks }}</td>
                    <td>
                        <a href="{{ route('frs.edit', $item->id_frs) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('frs.destroy', $item->id_frs) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Hapus data ini?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
