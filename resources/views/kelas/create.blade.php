{{-- CREATE.BLADE.PHP --}}
@extends('master')

@section('title', 'Tambah Kelas')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah Kelas</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('kelas.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- Program Studi --}}
                    <x-form.text-field label="Program Studi" name="prodi" placeholder="Contoh: D3 TI, S1 SI, D4 RPL"/>

                    {{-- Tahun Masuk --}}
                    <x-form.text-number label="Tahun Masuk" name="tahun_masuk" placeholder="{{ date('Y') }}"/>

                    {{-- Paralel --}}
                    <x-form.text-field label="Paralel" name="paralel" placeholder="A, B, C, dll"/>

                    {{-- Wali Kelas --}}
                    <x-form.dropdown-field label="Wali Kelas" name="id_dosen" 
                        :options="$dosen" 
                        valueField="id_dosen" 
                        labelFields="nama_dosen" 
                        placeholder="Pilih Wali Kelas"/>

                    {{-- Status --}}
                    <x-form.dropdown-field label="Status" name="status" 
                        :options="[
                            ['value' => 'AKTIF', 'label' => 'Aktif'],
                            ['value' => 'LULUS', 'label' => 'Lulus']
                        ]" 
                        valueField="value" 
                        labelFields="label" />

                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='{{ route('kelas.index') }}';">
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