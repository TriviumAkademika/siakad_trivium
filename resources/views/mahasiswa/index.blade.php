@extends('master')

@section('title', 'Mahasiswa')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-putih">
            {{-- Profil User di Header --}}
            @include('components.header')

            {{-- Toast Notification --}}
            <x-notification.toast-notification />

            {{-- Content --}}
            <div class="flex flex-row px-6 pb-6 space-x-6">
                <div class="flex flex-col grow items-end space-y-4">
                    {{-- PERMISSION UNTUK ADMIN --}}
                    {{-- Button Tambah Data Mahasiswa --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('mahasiswa.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah Mahasiswa
                            </x-button.submit>
                        </a>
                    @endif


                    {{-- Tabel Data Mahasiswa --}}
                    <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">NRP</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Kelas</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Semester</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Gender</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">No HP</th>
                                {{-- PERMISSION UNTUK ADMIN --}}
                                {{-- Kolom Aksi --}}
                                @if (auth()->user()->role === 'admin')
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="bg-putih divide-y divide-gray-200">
                            @foreach ($mahasiswa as $index => $m)
                                <tr>
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $m->nama }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $m->nrp }}</x-table.table-td>
                                    <x-table.table-td
                                        class="text-center">{{ $m->kelas ? $m->kelas->prodi . ' ' . $m->kelas->paralel : '-' }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $m->semester }}</x-table.table-td>
                                    <x-table.table-td
                                        class="text-center">{{ $m->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</x-table.table-td>
                                    <x-table.table-td>{{ $m->no_hp }}</x-table.table-td>
                                    {{-- PERMISSION UNTUK ADMIN --}}
                                    @if (auth()->user()->role === 'admin')
                                        <td class="px-2 py-2 text-sm text-hitam">
                                            <div class="flex justify-center items-center space-x-1">
                                                {{-- Button Edit --}}
                                                <a href="{{ route('mahasiswa.edit', $m->id_mahasiswa) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-brand-700 hover:bg-brand-800 text-white text-sm rounded">
                                                    <i class="ph ph-pencil-simple"></i>
                                                </a>

                                            {{-- Button Delete --}}
                                            {{-- <form action="{{ route('mahasiswa.destroy', $m->id_mahasiswa) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-merah-500 text-white text-sm rounded hover:bg-merah-600">
                                                    <i class="ph ph-trash-simple"></i>
                                                </button>
                                            </form> --}}
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
