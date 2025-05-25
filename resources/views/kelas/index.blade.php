@extends('master')

@section('title', 'Kelas')

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
                    {{-- Button Tambah Data kelas --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('kelas.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah Kelas
                            </x-button.submit>
                        </a>
                    @endif


                    {{-- Tabel Data kelas --}}
                    <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Dosen Wali</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Tahun Masuk</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Prodi</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Paralel</th>
                                {{-- PERMISSION UNTUK ADMIN --}}
                                {{-- Kolom Aksi --}}
                                @if (auth()->user()->role === 'admin')
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Action</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="bg-putih divide-y divide-gray-200">
                            @foreach ($kelas as $index => $k)
                                <tr>
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $k->dosen->nama_dosen }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $k->tahun_masuk }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $k->prodi }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $k->paralel   }}</x-table.table-td>
                                    {{-- PERMISSION UNTUK ADMIN --}}
                                    @if (auth()->user()->role === 'admin')
                                        <td class="px-2 py-2 text-sm text-hitam">
                                            <div class="flex justify-center items-center space-x-1">
                                                {{-- Button Edit --}}
                                                <a href="{{ route('kelas.edit', $k->id_kelas) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-brand-700 hover:bg-brand-800 text-white text-sm rounded">
                                                    <i class="ph ph-pencil-simple"></i>
                                                </a>

                                            {{-- Button Delete --}}
                                            {{-- <form action="{{ route('kelas.destroy', $m->id_kelas) }}" method="POST"
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
