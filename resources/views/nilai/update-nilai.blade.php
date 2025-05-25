@extends('master')

@section('title', 'Nilai Mahasiswa')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-white">
    <div class="bg-blue-100 rounded-2xl shadow max-w-lg w-full p-8">
        <h2 class="text-2xl font-bold mb-6">Nilai Mahasiswa</h2>
        <form action="{{ route('nilai.updateNilai') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-2">Nama Mahasiswa</label>
                <input type="text" class="w-full border rounded px-3 py-2 bg-white" value="{{ $mahasiswa->nama }}" readonly>
                <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id_mahasiswa }}">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-2">Matakuliah</label>
                <input type="text" class="w-full border rounded px-3 py-2 bg-white" value="{{ $matkul->nama_matkul }}" readonly>
                <input type="hidden" name="matakuliah_id" value="{{ $matkul->id_matkul }}">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-2">Input Nilai</label>
                <select name="jenis_nilai" class="w-full border rounded px-3 py-2 bg-white" required>
                    <option value="UTS">UTS</option>
                    <option value="UAS">UAS</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block font-semibold mb-2">Nilai</label>
                <select name="nilai" class="w-full border rounded px-3 py-2 bg-white" required>
                    <option value="">Nilai</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">Simpan</button>
        </form>
    </div>
</div>
@endsection
