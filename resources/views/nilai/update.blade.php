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
                <x-form.text-field label="Nama Mahasiswa" name="nama_mahasiswa" :value="$mahasiswa->nama" :attributes="['readonly' => true]" />
                <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id_mahasiswa }}">

                {{-- Matakuliah --}}
                <x-form.text-field label="Matakuliah" name="matakuliah_id" :value="$matkul->nama_matkul" :attributes="['readonly' => true]" />
                <input type="hidden" name="matakuliah_id" value="{{ $matkul->id_matkul }}">

                {{-- Field Nilai UTS --}}
                <x-form.dropdown-field
                    label="Nilai UTS"
                    name="nilai_uts"
                    :options="[
                        ['id' => '', 'name' => 'Pilih Nilai UTS'],
                        ['id' => 'A', 'name' => 'A'],
                        ['id' => 'B', 'name' => 'B'],
                        ['id' => 'C', 'name' => 'C'],
                        ['id' => 'D', 'name' => 'D'],
                        ['id' => 'E', 'name' => 'E']
                    ]"
                    :selected="$nilaiUTS->nilai ?? ''"
                    :required="false"
                />

                {{-- Field Nilai UAS --}}
                <x-form.dropdown-field
                    label="Nilai UAS"
                    name="nilai_uas"
                    :options="[
                         ['id' => '', 'name' => 'Pilih Nilai UAS'],
                        ['id' => 'A', 'name' => 'A'],
                        ['id' => 'B', 'name' => 'B'],
                        ['id' => 'C', 'name' => 'C'],
                        ['id' => 'D', 'name' => 'D'],
                        ['id' => 'E', 'name' => 'E']
                    ]"
                    :selected="$nilaiUAS->nilai ?? ''"
                    :required="false"
                />

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