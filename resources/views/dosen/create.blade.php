@extends('master')

@section('title', 'Tambah Dosen')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah Dosen</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('dosen.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- Nama --}}
                    <x-form.text-field label="Nama Dosen" name="nama_dosen" />

                    {{-- NRP --}}
                    <x-form.text-number label="NIP" name="nip" />

                    {{-- Semester --}}
                    <x-form.text-field label="Alamat" name="alamat" />

                    {{-- No HP --}}
                    <x-form.text-number label="No HP" name="no_hp" />

                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/dosen';">
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