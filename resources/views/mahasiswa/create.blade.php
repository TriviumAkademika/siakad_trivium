@extends('master')

@section('title', 'Tambah Mahasiswa')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full">
            <h1 class="p-6 text-2xl text-hitam">Tambah Mahasiswa</h1>
            <div class="flex flex-col px-6 pb-6 space-x-6">
                <form action="{{ route('mahasiswa.store') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Nama --}}
                    <label class="block text-sm font-medium text-hitam">
                      Nama
                      <input type="text" name="nama" class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal" required>
                    </label>

                    {{-- NRP --}}
                    <label class="block text-sm font-medium text-hitam">
                      NRP
                      <input type="text" name="nrp" class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal" required>
                    </label>

                    {{-- Kelas --}}
                    <label class="block text-sm font-medium text-hitam">Kelas
                      <select name="id_kelas" class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal" required>
                        <option value="">-- Pilih Kelas --</option>
                          @foreach ($kelas as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->prodi }} {{ $k->paralel }}</option>
                          @endforeach
                      </select>
                    </label>

                    {{-- Semester --}}
                    <label class="block text-sm font-medium text-hitam">Semester
                      <input type="text" name="semester" class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal" required>
                    </label>

                    {{-- Gender --}}
                    <label class="block text-sm font-medium text-hitam">Gender
                      <select name="gender" class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                      </select>
                    </label>

                    {{-- Alamat --}}
                    <label class="block text-sm font-medium text-hitam">Alamat
                      <textarea name="alamat" class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal" rows="3"></textarea>
                    </label>

                    {{-- No HP --}}
                    <label class="block text-sm font-medium text-hitam">No HP
                      <input type="text" name="no_hp" class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal">
                    </label>

                    {{-- Button Simpan --}}
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                      Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
