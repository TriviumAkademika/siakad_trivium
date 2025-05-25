@extends('master')

@section('title', 'Edit Dosen')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit Dosen</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('dosen.update', $dosen->id_dosen) }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <x-form.text-field label="Nama Dosen" name="nama_dosen" :value="$dosen->nama_dosen" />

                    {{-- NRP --}}
                    <x-form.text-number label="NIP" name="nip" :value="$dosen->nip" />

                    {{-- Alamat --}}
                    <x-form.textarea-field label="Alamat" name="alamat" :value="$dosen->alamat" />

                    {{-- No HP --}}
                    <x-form.text-number label="No HP" name="no_hp" :value="$dosen->no_hp" />

                    {{-- Button Perbarui --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/dosen';">
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
