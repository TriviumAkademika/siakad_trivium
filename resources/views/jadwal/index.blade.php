@extends ('master')

@section('title', 'Jadwal')

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
                    {{-- Button Tambah Jadwal --}}
                    <a href="{{ route('jadwal.create') }}">
                        <x-button.submit icon="ph ph-plus">
                            Tambah Jadwal
                        </x-button.submit>
                    </a>

                    {{-- Tabel Data Jadwal --}}
                    <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Kelas</th>
                                <th class="w-48 px-4 py-3 text-center text-sm font-medium text-hitam">Mata Kuliah</th>
                                <th class="w-40 px-4 py-3 text-center text-sm font-medium text-hitam">Dosen</th>
                                <th class="w-40 px-4 py-3 text-center text-sm font-medium text-hitam">Dosen Pendamping</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Ruangan</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Waktu</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-putih divide-y divide-gray-200">
                            @foreach ($jadwal as $index => $j)
                                <tr>
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $j->kelas->prodi }}-{{ $j->kelas->paralel }}</x-table.table-td>
                                    <x-table.table-td>{{ $j->matkul->jenis }} - {{ $j->matkul->nama_matkul }}</x-table.table-td>
                                    <x-table.table-td>{{ $j->dosen->nama_dosen }}</x-table.table-td>
                                    <x-table.table-td>{{ $j->dosen2 ? $j->dosen2->nama_dosen : '-' }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $j->ruangan->kode_ruangan }}</x-table.table-td>
                                    <x-table.table-td class="text-center">
                                        <p>{{ $j->waktu->hari }}</p>
                                        <p>{{ substr($j->waktu->jam_mulai, 0, 5) }} -
                                            {{ substr($j->waktu->jam_selesai, 0, 5) }}</p>
                                    </x-table.table-td>
                                    <td class="px-2 py-2 text-center text-sm text-hitam">
                                        <div class="flex justify-center items-center space-x-1">
                                            {{-- Button Edit --}}
                                            <a href="{{ route('jadwal.edit', $j->id_jadwal) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-sm rounded hover:bg-biru-700">
                                                <i class="ph ph-pencil-simple"></i>
                                            </a>

                                            {{-- Button Hapus --}}
                                            {{-- <form action="{{ route('jadwal.destroy', $j->id_jadwal) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Anda yakin ingin menghapus jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-merah-500 text-white text-sm rounded hover:bg-merah-600">
                                                    <i class="ph ph-trash-simple"></i>
                                                </button>
                                            </form> --}}
                                        </div>
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