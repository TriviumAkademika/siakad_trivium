<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User</title>
    @vite('resources/css/app.css')
</head>

<body class="p-6 bg-gray-50">

    <div class="mb-4">
        <a href="{{ route('users.create') }}"
            class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
            + Tambah User
        </a>
    </div>

    <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Email</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Role</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Pengguna</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($users as $index => $user)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ $user->email }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 capitalize">{{ $user->role }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">
                        @if ($user->role === 'mahasiswa')
                            {{ $user->mahasiswa->nama ?? '-' }} ({{ $user->mahasiswa->nrp ?? '-' }})
                        @elseif ($user->role === 'dosen')
                            {{ $user->dosen->nama_dosen ?? '-' }}
                        @elseif ($user->role === 'admin')
                            {{ $user->nama_user ?? '-' }}
                        @else
                            -
                        @endif
                    </td>

                    <td class="px-4 py-2 text-sm text-gray-900 space-x-2">
                        <a href="{{ route('users.edit', $user->id_user) }}"
                            class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</a>
                        <form action="{{ route('users.destroy', $user->id_user) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
