@extends('master')

@section('title', 'Tambah FRS')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah FRS</h2>

            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('frs.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- Mahasiswa --}}
                    <x-form.dropdown-field label="Mahasiswa" name="id_mahasiswa" :options="$mahasiswa" valueField="id_mahasiswa"
                        labelFields="nama" defaultOption="Pilih Mahasiswa"/>

                    {{-- Tahun Ajaran --}}
                    <x-form.dropdown-field label="Tahun Ajaran" name="tahun_ajaran" :options="$tahunAjaranList" :selected="old('tahun_ajaran', '2024/2025')" />

                    {{-- Semester --}}
                    {{-- <x-form.text-field label="Semester" name="semester" :value="old('semester')" readonly /> --}}

                    {{-- Total SKS --}}
                    {{-- <x-form.text-field label="Total SKS" name="total_sks" type="number" min="0" /> --}}

                    {{-- IPS --}}
                    {{-- <x-form.text-field label="IPS" name="ips" type="number" min="0" max="4"
                        step="0.01" /> --}}

                    {{-- IPK --}}
                    {{-- <x-form.text-field label="IPK" name="ipk" type="number" min="0" max="4"
                        step="0.01" /> --}}

                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/frs';">
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
