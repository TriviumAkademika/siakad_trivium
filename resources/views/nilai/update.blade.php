@extends('master')

@section('title', 'Update Nilai')
    
@section('content')

<div class="flex w-full grow">
    {{-- sidebar --}}
    @include('components.sidebar')

    <div class="flex flex-col w-full bg-putih">
        <h2 class="p-6 text-2xl text-hitam">
            Tambah Jadwal
        </h2>
        {{-- content --}}
        <div class="flex flex-col px-6 pb-6">
            {{-- form --}}
            <form action="{{ route('nilai.update', ['id_mahasiswa' => $mahasiswa->id_mahasiswa, 'id_matkul' => $matkul->id_matkul]) }}" method="POST" class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                @csrf
                @method('PUT')

                {{-- Mahasiswa --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mahasiswa</label>
                    <input type="text" name="nama_mahasiswa" value="{{ $mahasiswa->nama }}" readonly 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100">
                    <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id_mahasiswa }}">
                </div>

                {{-- Matakuliah --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matakuliah</label>
                    <input type="text" name="matakuliah_display" value="{{ $matkul->nama_matkul }}" readonly 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100">
                    <input type="hidden" name="matakuliah_id" value="{{ $matkul->id_matkul }}">
                </div>

                {{-- Field Nilai UTS --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai UTS</label>
                    <select name="nilai_uts" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Nilai UTS</option>
                        <option value="A" {{ ($nilaiUTS->nilai ?? '') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ ($nilaiUTS->nilai ?? '') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ ($nilaiUTS->nilai ?? '') == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ ($nilaiUTS->nilai ?? '') == 'D' ? 'selected' : '' }}>D</option>
                        <option value="E" {{ ($nilaiUTS->nilai ?? '') == 'E' ? 'selected' : '' }}>E</option>
                    </select>
                </div>

                {{-- Field Nilai UAS --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai UAS</label>
                    <select name="nilai_uas" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Nilai UAS</option>
                        <option value="A" {{ ($nilaiUAS->nilai ?? '') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ ($nilaiUAS->nilai ?? '') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ ($nilaiUAS->nilai ?? '') == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ ($nilaiUAS->nilai ?? '') == 'D' ? 'selected' : '' }}>D</option>
                        <option value="E" {{ ($nilaiUAS->nilai ?? '') == 'E' ? 'selected' : '' }}>E</option>
                    </select>
                </div>

                {{-- Button Perbarui --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/nilai-dosen';">
                            Batal
                        </x-button.cancel>

                        <x-button.submit icon="ph ph-floppy-disk">
                            Perbarui
                        </x-button.submit>
                    </div>

            </form>
        </div>
    </div>
</div>
    
@endsection