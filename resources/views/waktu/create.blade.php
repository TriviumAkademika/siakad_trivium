@extends('master')

@section('title', 'Tambah Waktu')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah Waktu</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('waktu.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- Nama waktu --}}
                    <div class="flex w-full">
                        <label for="hari" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Hari
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="text" name="hari" id="hari"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>
                    {{-- Nama Gedung --}}
                    <div class="flex w-full">
                        <label for="jam_mulai" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Jam Mulai
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="time" name="jam_mulai" id="jam_mulai"
                            class="w-full px-4 py-2 border-abu focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                    </div>
                    {{-- Kode waktu --}}
                    <div class="flex w-full">
                        <label for="jam_selesai" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Jam Selesai
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="time" name="jam_selesai" id="jam_selesai"
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