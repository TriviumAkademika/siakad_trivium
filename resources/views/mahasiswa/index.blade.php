@extends('master')

@section('title', 'Mahasiswa')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            {{-- Profil User di Header --}}
            @include('components.header')
            {{-- Content --}}
            <div class="flex flex-row px-6 pb-6 space-x-6">
                <div class="flex flex-col grow space-y-4">
                    <a href="{{ route('mahasiswa.create') }}">
                        <button
                            class="btn bg-brand-900 hover:bg-brand-950 text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0">
                            <i class="ph ph-plus"></i>
                            Tambah Mahasiswa
                        </button>

                    </a>
                    <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">NRP</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Kelas</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Semester</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Gender</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">No HP</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-putih divide-y divide-gray-200">
                            @foreach ($mahasiswa as $index => $m)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-hitam">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-sm text-hitam">{{ $m->nama }}</td>
                                    <td class="px-4 py-2 text-center text-sm text-hitam">{{ $m->nrp }}</td>
                                    <td class="px-4 py-2 text-center text-sm text-hitam">
                                        {{ $m->kelas ? $m->kelas->prodi . ' ' . $m->kelas->paralel : '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center text-sm text-hitam">{{ $m->semester }}</td>
                                    <td class="px-4 py-2 text-center text-sm text-hitam">
                                        {{ $m->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-hitam">{{ $m->no_hp }}</td>
                                    <td class="px-2 py-2 text-center text-sm text-hitam space-x-2">
                                      {{-- Button Edit --}}
                                      <a href="{{ route('mahasiswa.edit', $m->id_mahasiswa) }}" class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                                        <i class="ph ph-pencil-simple"></i>
                                      </a>
                                        <form action="{{ route('mahasiswa.destroy', $m->id_mahasiswa) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                                <i class="ph ph-trash-simple"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
