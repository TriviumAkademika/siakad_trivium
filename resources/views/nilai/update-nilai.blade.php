@extends('master')

@section('title', 'Update Nilai')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow mt-8">
    <h2 class="text-2xl font-bold mb-4">Update Nilai</h2>
    <form action="{{ route('nilai.updateNilai') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama Mahasiswa</label>
            <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100" value="{{ $mahasiswa->nama }}" readonly>
            <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id_mahasiswa }}">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Matakuliah</label>
            <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100" value="{{ $matkul->nama_matkul }}" readonly>
            <input type="hidden" name="matakuliah_id" value="{{ $matkul->id_matkul }}">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Input Nilai</label>
            <select name="jenis_nilai" class="w-full border rounded px-3 py-2" required>
                <option value="UTS">UTS</option>
                <option value="UAS">UAS</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nilai</label>
            <select name="nilai" class="w-full border rounded px-3 py-2" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
            </select>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
