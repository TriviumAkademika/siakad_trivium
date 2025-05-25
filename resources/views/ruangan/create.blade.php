@extends('master')

@section('title', 'Tambah Ruangan')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah ruangan</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('ruangan.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- Nama Ruangan --}}
                    <div class="flex w-full">
                        <label for="nama_ruangan" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Nama Ruangan
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="text" name="nama_ruangan" id="nama_ruangan"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>
                    {{-- Nama Gedung --}}
                    <div class="flex w-full">
                        <label for="nama_gedung" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Nama Gedung
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="text" name="nama_gedung" id="nama_gedung"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>
                    {{-- Kode Ruangan --}}
                    <div class="flex w-full">
                        <label for="kode_ruangan" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Kode Ruangan
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="text" name="kode_ruangan" id="kode_ruangan"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>

                    {{-- Button Simpan --}}
                    <div class="flex justify-end">
                        <x-button.submit icon="ph ph-floppy-disk">
                            Simpan
                        </x-button.submit>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection