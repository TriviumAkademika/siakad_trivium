@extends('master')

@section('title', 'Edit Ruangan')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit Ruangan</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('ruangan.update', $ruangan->id_ruangan) }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama Ruangan --}}
                    <x-form.text-field label="Nama Ruangan" name="nama_ruangan" :value="$ruangan->nama_ruangan" />

                    {{-- Nama Gedung --}}
                    <x-form.text-field label="Nama Gedung" name="nama_gedung" :value="$ruangan->nama_gedung" />

                    {{-- Kode Ruangan --}}
                    <x-form.text-field label="Kode Ruangan" name="kode_ruangan" :value="$ruangan->kode_ruangan" />



                    {{-- Button Perbarui --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x">
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
