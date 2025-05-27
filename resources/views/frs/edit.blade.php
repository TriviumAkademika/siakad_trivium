@extends('master')

@section('title', 'Edit FRS')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit FRS</h2>

            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('frs.update', $frs->id_frs) }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Mahasiswa --}}
                    <x-form.dropdown-field label="Mahasiswa" name="id_mahasiswa" :options="$mahasiswa"
                        :selected="$frs->id_mahasiswa" valueField="id_mahasiswa" labelFields="nama" />

                    {{-- Tahun Ajaran --}}
                    <x-form.text-field label="Tahun Ajaran" name="tahun_ajaran" type="text" 
                        value="{{ old('tahun_ajaran', $frs->tahun_ajaran) }}" />

                    {{-- Semester (readonly, karena ditentukan dari data mahasiswa) --}}
                    <x-form.text-field label="Semester" name="semester" type="number" 
                        :value="$frs->mahasiswa->semester" readonly />

                    {{-- Total SKS --}}
                    {{-- <x-form.text-field label="Total SKS" name="total_sks" type="number" 
                        value="{{ old('total_sks', $frs->total_sks) }}" /> --}}

                    {{-- IPS --}}
                    {{-- <x-form.text-field label="IPS" name="ips" type="number" step="0.01" 
                        value="{{ old('ips', $frs->ips) }}" :required="false" /> --}}

                    {{-- IPK --}}
                    {{-- <x-form.text-field label="IPK" name="ipk" type="number" step="0.01" 
                        value="{{ old('ipk', $frs->ipk) }}" :required="false" /> --}}

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='{{ route('frs.index') }}';">
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