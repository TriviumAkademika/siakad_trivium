@extends('master')

@section('title', 'Edit Mahasiswa')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit Mahasiswa</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('mahasiswa.update', $mahasiswa->id_mahasiswa) }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <x-form.text-field label="Nama" name="nama" :value="$mahasiswa->nama" />

                    {{-- NRP --}}
                    <x-form.text-field label="NRP" name="nrp" :value="$mahasiswa->nrp" />

                    {{-- Kelas --}}
                    <x-form.dropdown-field label="Kelas" name="id_kelas" :options="$kelas" :selected="$mahasiswa->id_kelas"
                        valueField="id_kelas" :labelFields="['prodi', 'paralel']" />

                    {{-- Semester --}}
                    <x-form.text-field label="Semester" name="semester" :value="$mahasiswa->semester" />

                    {{-- Gender --}}
                    <x-form.dropdown-field label="Gender" name="gender" :options="[['value' => 'L', 'label' => 'Laki-laki'], ['value' => 'P', 'label' => 'Perempuan']]" :selected="$mahasiswa->gender" valueField="value"
                        labelFields="label" />

                    {{-- No HP --}}
                    <x-form.text-field label="No HP" name="no_hp" :value="$mahasiswa->no_hp" />

                    {{-- Alamat --}}
                    <x-form.textarea-field label="Alamat" name="alamat" :value="$mahasiswa->alamat" />

                    {{-- Button Perbarui --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/mahasiswa';">
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