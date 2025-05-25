@extends('master')

@section('title', 'Edit Waktu')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit Waktu</h2>
            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Form --}}
                <form action="{{ route('waktu.update', $waktu->id_waktu) }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Hari --}}
                    <x-form.text-field label="Hari" name="hari" :value="$waktu->hari" />

                    {{-- Jam Mulai --}}
                    <x-form.text-time label="Jam Mulai" name="jam_mulai" :value="$waktu->jam_mulai" />

                    {{-- Jam Selesai --}}
                    <x-form.text-time label="Jam Selesai" name="jam_selesai" :value="$waktu->jam_selesai" />

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
