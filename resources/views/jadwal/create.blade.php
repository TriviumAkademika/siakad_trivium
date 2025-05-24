@extends('master')

@section('title', 'Tambah Jadwal')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah Jadwal</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('jadwal.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- Kelas --}}
                    <x-form.dropdown-field label="Kelas" name="id_kelas" :options="$kelas" valueField="id_kelas"
                        :labelFields="['prodi', 'paralel']" />

                    {{-- Mata Kuliah --}}
                    <x-form.dropdown-field label="Mata Kuliah" name="id_matkul" :options="$matkul" valueField="id_matkul"
                        :labelFields="['jenis', 'nama_matkul']" />

                    {{-- Dosen --}}
                    <x-form.dropdown-field label="Dosen" name="id_dosen" :options="$dosen" valueField="id_dosen"
                        labelFields="nama_dosen" />

                    {{-- Dosen Pendamping (Opsional) --}}
                    <x-form.dropdown-field label="Dosen Pendamping" name="id_dosen_2" :options="$dosen"
                        valueField="id_dosen" labelFields="nama_dosen" :required="false" />

                    {{-- Ruangan --}}
                    <x-form.dropdown-field label="Ruangan" name="id_ruangan" :options="$ruangan" valueField="id_ruangan"
                        labelFields="kode_ruangan" />

                    {{-- Waktu --}}
                    <x-form.dropdown-field label="Waktu" name="id_waktu" :options="$waktu" valueField="id_waktu"
                        :labelFields="['hari', 'jam_mulai', 'jam_selesai']" />

                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x">
                            Batal
                        </x-button.cancel>
                        <x-button.submit icon="ph ph-floppy-disk">
                            Simpan
                        </x-button.submit>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection