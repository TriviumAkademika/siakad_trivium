@extends('master')

@section('title', 'FRS')

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
                    {{-- Button Tambah Mahasiswa yang Bisa Mengisi FRS --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('frs.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah FRS
                            </x-button.submit>
                        </a>
                    @endif

                    {{-- Tabel Data FRS --}}
                    <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-hitam">#</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Nama Mahasiswa</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Tahun Ajaran</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Semester</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Total SKS</th>
                                {{-- PERMISSION UNTUK ADMIN --}}
                                {{-- Kolom Aksi --}}
                                @if (auth()->user()->role === 'admin')
                                    <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="bg-putih divide-y divide-gray-100">
                            @foreach ($frs as $index => $item)
                                <tr onclick="window.location='{{ route('detail-frs.index', $item->id_frs) }}'"
                                    class="hover:bg-gray-100 cursor-pointer">
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $item->mahasiswa->nama }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $item->tahun_ajaran }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $item->semester }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $item->total_sks }}</x-table.table-td>
                                    {{-- PERMISSION UNTUK ADMIN --}}
                                    @if (auth()->user()->role === 'admin')
                                        <td class="px-4 py-2 text-sm text-center text-hitam">
                                            <div class="flex justify-center items-center space-x-2"
                                                onclick="event.stopPropagation()">
                                                {{-- Button Edit --}}
                                                <a href="{{ route('frs.edit', $item->id_frs) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-brand-700 hover:bg-brand-800 text-white text-sm rounded">
                                                    <i class="ph ph-pencil-simple"></i>
                                                </a>

                                                {{-- Button Delete --}}
                                                {{-- <form action="{{ route('frs.destroy', $item->id_frs) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                                    Delete
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