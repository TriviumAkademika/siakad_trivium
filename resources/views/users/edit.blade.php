<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    @vite('resources/css/app.css')
</head>

<body class="p-6 bg-gray-50">

    <form action="{{ route('users.update', $user->id_user) }}" method="POST"
        class="space-y-4 max-w-xl bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-100 text-red-700 rounded">
                <strong>Ada kesalahan:</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="text-xl font-semibold mb-4">Edit User</h1>

        <div>
            <label class="block text-sm">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded"
                value="{{ old('email', $user->email) }}">
            @error('email')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm">Password (Kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="w-full border p-2 rounded">
            @error('password')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block text-sm">Role</label>
            <input type="hidden" name="role" value="{{ $user->role }}">
            <input type="text" class="w-full border p-2 rounded bg-gray-100" value="{{ ucfirst($user->role) }}"
                readonly>
            <p class="text-xs text-gray-500 mt-1">Role tidak dapat diubah.</p>

        </div>

        {{-- Field Mahasiswa --}}
        @if ($user->role === 'mahasiswa' && $user->mahasiswa)
            <div>
                <label class="block text-sm">Mahasiswa</label>
                <input type="text" class="w-full border p-2 rounded bg-gray-100"
                    value="{{ $user->mahasiswa->nama }} - {{ $user->mahasiswa->nrp }}" readonly>
            </div>
        @endif

        {{-- Field Dosen --}}
        @if ($user->role === 'dosen' && $user->dosen)
            <div>
                <label class="block text-sm">Dosen</label>
                <input type="text" class="w-full border p-2 rounded bg-gray-100"
                    value="{{ $user->dosen->nama_dosen }} - {{ $user->dosen->nip }}" readonly>
            </div>
        @endif

        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update</button>
    </form>

</body>

</html>
