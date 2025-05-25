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

                    {{-- Nama Dosen Wali --}}
                    <x-form.dropdown-field label="Dosen Wali" name="id_dosen" :options="$dosen" valueField="id_dosen"
                        :labelFields="['nama_dosen']" />

                    {{-- Tahun Masuk --}}
                    <x-form.text-field label="Tahun Masuk" name="tahun_masuk" placeholder="2020" />

                    {{-- Prodi --}}
                    <x-form.text-number label="Prodi" name="prodi" />

                    {{-- Paralel --}}
                    <x-form.text-number label="paralel" name="paralel" />


                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/kelas';">
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
