<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    @vite('resources/css/app.css')
</head>

<body class="p-6 bg-gray-50">

    <form action="{{ route('users.store') }}" method="POST" class="space-y-4 max-w-xl bg-white p-6 rounded shadow">
        @csrf
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

        <h1 class="text-xl font-semibold mb-4">Tambah User</h1>

        <div>
            <label class="block text-sm">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" value="{{ old('email') }}">
            @error('email')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm">Password</label>
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
            <select name="role" class="w-full border p-2 rounded">
                <option value="">-- Pilih --</option>
                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
            </select>
            @error('role')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field Mahasiswa --}}
        <div id="mahasiswa-fields" class="{{ old('role') != 'mahasiswa' ? 'hidden' : '' }}">
            <label class="block text-sm">Pilih Mahasiswa</label>
            <select name="id_mahasiswa" class="w-full border p-2 rounded">
                <option value="">-- Pilih Mahasiswa --</option>
                @foreach ($mahasiswa as $m)
                    <option value="{{ $m->id_mahasiswa }}"
                        {{ old('id_mahasiswa') == $m->id_mahasiswa ? 'selected' : '' }}>
                        {{ $m->nama }} - {{ $m->nrp }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Field Dosen --}}
        <div id="dosen-fields" class="{{ old('role') != 'dosen' ? 'hidden' : '' }}">
            <label class="block text-sm">Pilih Dosen</label>
            <select name="id_dosen" class="w-full border p-2 rounded">
                <option value="">-- Pilih Dosen --</option>
                @foreach ($dosen as $d)
                    <option value="{{ $d->id_dosen }}" {{ old('id_dosen') == $d->id_dosen ? 'selected' : '' }}>
                        {{ $d->nama_dosen }} - {{ $d->nip }}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Simpan</button>
    </form>

    <script>
        const roleSelect = document.querySelector('select[name="role"]');
        const mahasiswaFields = document.getElementById('mahasiswa-fields');
        const dosenFields = document.getElementById('dosen-fields');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'mahasiswa') {
                mahasiswaFields.classList.remove('hidden');
                dosenFields.classList.add('hidden');
            } else if (this.value === 'dosen') {
                dosenFields.classList.remove('hidden');
                mahasiswaFields.classList.add('hidden');
            } else {
                mahasiswaFields.classList.add('hidden');
                dosenFields.classList.add('hidden');
            }
        });
    </script>

</body>

</html>
