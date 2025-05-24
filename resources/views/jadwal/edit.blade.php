@extends('master')

@section('title', 'Edit Jadwal')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit Jadwal</h2>
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('jadwal.update', $jadwal->id_jadwal) }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Kelas --}}
                    <x-form.dropdown-field label="Kelas" name="id_kelas" :options="$kelas" :selected="$jadwal->id_kelas" valueField="id_kelas"
                        :labelFields="['prodi', 'paralel']" />


                    {{-- Mata Kuliah --}}
                    <x-form.dropdown-field label="Mata Kuliah" name="id_matkul" :options="$matkul" :selected="$jadwal->id_matkul"
                        valueField="id_matkul" :labelFields="['jenis', 'nama_matkul']" />

                    {{-- Dosen --}}
                    <x-form.dropdown-field label="Dosen" name="id_dosen" :options="$dosen" :selected="$jadwal->id_dosen"
                        valueField="id_dosen" labelFields="nama_dosen" />

                    {{-- Dosen Pendamping (Opsional) --}}
                    <x-form.dropdown-field label="Dosen Pendamping" name="id_dosen_2" :options="$dosen" :selected="$jadwal->id_dosen_2"
                        valueField="id_dosen" labelFields="nama_dosen" :optionalOption="['value' => '', 'label' => 'Tidak Ada']" :required="false" />

                    {{-- Ruangan --}}
                    <x-form.dropdown-field label="Ruangan" name="id_ruangan" :options="$ruangan" :selected="$jadwal->id_ruangan"
                        valueField="id_ruangan" labelFields="kode_ruangan" />

                    {{-- Waktu --}}
                    <x-form.dropdown-field label="Waktu" name="id_waktu" :options="$waktu" :selected="$jadwal->id_waktu"
                        valueField="id_waktu" :labelFields="['hari', 'jam_mulai', 'jam_selesai']" />

                    {{-- Button Perbarui --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/jadwal';">
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