@extends('master')

@section('title', 'Edit Mata Kuliah')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit Mata Kuliah</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('matkul.update', $matkul->id_matkul) }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama Mata Kuliah --}}
                    <x-form.text-field label="Nama Mata Kuliah" name="nama_matkul" 
                        value="{{ old('nama_matkul', $matkul->nama_matkul) }}" 
                        placeholder="Contoh: Pemrograman Web, Basis Data"/>

                    {{-- Jenis --}}
                    <x-form.dropdown-field label="Jenis" name="jenis" 
                        :options="[
                            ['value' => 'Wajib', 'label' => 'Wajib'],
                            ['value' => 'Pilihan', 'label' => 'Pilihan']
                        ]" 
                        valueField="value" 
                        labelFields="label" 
                        placeholder="Pilih Jenis Mata Kuliah"
                        :selected="old('jenis', $matkul->jenis)"/>

                    {{-- SKS --}}
                    <x-form.text-number label="SKS" name="sks" 
                        value="{{ old('sks', $matkul->sks) }}" 
                        placeholder="Contoh: 2, 3, 4"/>

                    {{-- Kapasitas Kelas --}}
                    <x-form.text-number label="Kapasitas Kelas" name="kapasitas_kelas" 
                        value="{{ old('kapasitas_kelas', $matkul->kapasitas_kelas) }}" 
                        placeholder="Contoh: 30, 35, 40"/>

                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='{{ route('matkul.index') }}';">
                            Batal
                        </x-button.cancel>
                        <x-button.submit icon="ph ph-floppy-disk">
                            Update
                        </x-button.submit>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection