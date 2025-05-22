@extends('master')

@section('title', 'Tambah Mahasiswa')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h1 class="p-6 text-2xl text-hitam">Tambah Mahasiswa</h1>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6 space-x-6">

                <form action="{{ route('mahasiswa.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4 ">
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
                            required>
                        </textarea>
                    </div>

                    {{-- <div class="flex">
                    Nama
                    <div class="space-y-1">
                        <label for="nama" class="block text-sm font-medium text-hitam">
                            Nama
                            <span class="text-error">*</span>
                        </label>
                        <input type="text" name="nama" id="nama"
                            class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>

                    NRP
                    <div class="space-y-1">
                        <label for="nrp" class="block text-sm font-medium text-hitam">
                            NRP
                            <span class="text-error">*</span>
                        </label>
                        <input type="text" name="nrp" id="nrp"
                            class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>

                    Kelas
                    <div class="space-y-1">
                        <label for="id_kelas" class="block text-sm font-medium text-hitam">
                            Kelas
                            <span class="text-error">*</span>
                        </label>
                        <select name="id_kelas" id="id_kelas"
                            class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->prodi }} {{ $k->paralel }}</option>
                            @endforeach
                        </select>
                    </div>

                    Semester
                    <div class="space-y-1">
                        <label for="semester" class="block text-sm font-medium text-hitam">
                            Semester
                            <span class="text-error">*</span>
                        </label>
                        <input type="text" name="semester" id="semester"
                            class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>

                    Gender
                    <div class="space-y-1">
                        <label for="gender" class="block text-sm font-medium text-hitam">
                            Gender
                            <span class="text-error">*</span>
                        </label>
                        <select name="gender" id="gender"
                            class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    Alamat
                    <div class="space-y-1">
                        <label for="alamat" class="block text-sm font-medium text-hitam">
                            Alamat
                            <span class="text-error">*</span>
                        </label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required></textarea>
                    </div>

                    No HP
                    <div class="space-y-1">
                        <label for="no_hp" class="block text-sm font-medium text-hitam">
                            No HP
                            <span class="text-error">*</span>
                        </label>
                        <input type="text" name="no_hp" id="no_hp"
                            class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal">
                    </div>
                    </div> --}}

                    {{-- Button Simpan --}}
                    <div class="flex justify-end">
                        <a href="{{ route('mahasiswa.store') }}">
                            <button type="submit"
                                class="btn bg-brand-900 hover:bg-brand-950 text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0 transition">
                                <i class="ph ph-floppy-disk"></i>
                                Simpan
                            </button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
