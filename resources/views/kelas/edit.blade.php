{{-- EDIT.BLADE.PHP --}}
@extends('master')

@section('title', 'Edit Kelas')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit Kelas</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('kelas.update', $kelas->id_kelas) }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Program Studi --}}
                    <x-form.text-field label="Program Studi" name="prodi" :value="old('prodi', $kelas->prodi)" />

                    {{-- Tahun Masuk --}}
                    <x-form.text-number label="Tahun Masuk" name="tahun_masuk" :value="old('tahun_masuk', $kelas->tahun_masuk)" />

                    {{-- Paralel --}}
                    <x-form.text-field label="Paralel" name="paralel" :value="old('paralel', $kelas->paralel)" />

                    {{-- Wali Kelas --}}
                    <x-form.dropdown-field label="Wali Kelas" name="id_dosen" 
                        :options="$dosen" 
                        valueField="id_dosen" 
                        labelFields="nama_dosen" 
                        :value="old('id_dosen', $kelas->id_dosen)"
                        placeholder="Pilih Wali Kelas" />

                    {{-- Status --}}
                    <x-form.dropdown-field label="Status" name="status" 
                        :options="[
                            ['value' => 'AKTIF', 'label' => 'Aktif'],
                            ['value' => 'LULUS', 'label' => 'Lulus']
                        ]" 
                        valueField="value" 
                        labelFields="label" 
                        :value="old('status', $kelas->status)"
                        placeholder="Pilih Status" />

                    {{-- Button Perbarui --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='{{ route('kelas.index') }}';">
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