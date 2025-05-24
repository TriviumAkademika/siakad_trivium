@extends('master')

@section('title', 'Tambah Mahasiswa')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah Mahasiswa</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('mahasiswa.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- Nama --}}
                    <div class="flex w-full">
                        <label for="nama" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Nama
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="text" name="nama" id="nama"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>

                    {{-- NRP --}}
                    <div class="flex w-full">
                        <label for="nrp" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            NRP
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="text" name="nrp" id="nrp"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>

                    {{-- Kelas --}}
                    <div class="flex w-full">
                        <label for="id_kelas" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Kelas
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <select name="id_kelas" id="id_kelas"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->prodi }} {{ $k->paralel }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Semester --}}
                    <div class="flex w-full">
                        <label for="semester" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Semester
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="text" name="semester" id="semester"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>

                    {{-- Gender --}}
                    <div class="flex w-full">
                        <label for="gender" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Gender
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <select name="gender" id="gender"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    {{-- No HP --}}
                    <div class="flex w-full">
                        <label for="no_hp" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            No HP
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="text" name="no_hp" id="no_hp"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>

                    {{-- Alamat --}}
                    <div class="flex w-full">
                        <label for="alamat" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Alamat
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required></textarea>
                    </div>

                    {{-- Button Simpan --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="btn bg-brand-900 hover:bg-brand-950 text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0 transition">
                            <i class="ph ph-floppy-disk"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
