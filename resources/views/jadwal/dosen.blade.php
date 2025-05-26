@extends('master')

@section('title', 'Jadwal Saya')

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

                    {{-- Header --}}
                    <div class="flex justify-between items-center w-full">
                        <div class="flex items-center gap-4">
                            <h1 class="text-2xl font-bold text-hitam">Jadwal Mengajar Saya</h1>
                        </div>
                    </div>

                    {{-- Tabel Data Jadwal --}}
                    @if ($jadwal->count() > 0)
                        <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                            <thead class="bg-brand-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-hitam">#</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-hitam">Kelas</th>
                                    <th class="w-48 px-4 py-3 text-center text-sm font-semibold text-hitam">Mata Kuliah</th>
                                    <th class="w-40 px-4 py-3 text-center text-sm font-semibold text-hitam">Dosen Utama</th>
                                    <th class="w-40 px-4 py-3 text-center text-sm font-semibold text-hitam">Dosen Pendamping</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-hitam">Ruangan</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-hitam">Waktu</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-hitam">Role</th>
                                </tr>
                            </thead>

                            <tbody class="bg-putih divide-y divide-gray-200">
                                @foreach ($jadwal as $index => $j)
                                    <tr class="hover:bg-gray-100">
                                        <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                        <x-table.table-td>{{ $j->kelas->prodi }}-{{ $j->kelas->paralel }}</x-table.table-td>
                                        <x-table.table-td>{{ $j->matkul->jenis }} - {{ $j->matkul->nama_matkul }}</x-table.table-td>
                                        <x-table.table-td>{{ $j->dosen->nama_dosen }}</x-table.table-td>
                                        <x-table.table-td>{{ $j->dosen2 ? $j->dosen2->nama_dosen : '-' }}</x-table.table-td>
                                        <x-table.table-td class="text-center">{{ $j->ruangan->kode_ruangan }}</x-table.table-td>
                                        <x-table.table-td class="text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($j->waktu->hari == 'Senin') bg-red-100 text-red-800
                                                    @elseif($j->waktu->hari == 'Selasa') bg-orange-100 text-orange-800
                                                    @elseif($j->waktu->hari == 'Rabu') bg-yellow-100 text-yellow-800
                                                    @elseif($j->waktu->hari == 'Kamis') bg-green-100 text-green-800
                                                    @elseif($j->waktu->hari == 'Jumat') bg-blue-100 text-blue-800
                                                    @else bg-purple-100 text-purple-800 @endif">
                                                    {{ $j->waktu->hari }}
                                                </span>
                                                <span class="text-sm mt-1">
                                                    {{ substr($j->waktu->jam_mulai, 0, 5) }} -
                                                    {{ substr($j->waktu->jam_selesai, 0, 5) }}
                                                </span>
                                            </div>
                                        </x-table.table-td>
                                        <x-table.table-td class="text-center">
                                            @php
                                                $currentDosen = auth()->user()->email;
                                                $dosenEmail = \App\Models\Dosen::where('email', $currentDosen)->first();
                                                $isMainDosen = $dosenEmail && $j->id_dosen == $dosenEmail->id_dosen;
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                {{ $isMainDosen ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $isMainDosen ? 'Pengampu Utama' : 'Pengampu Pendamping' }}
                                            </span>
                                        </x-table.table-td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        {{-- Pesan jika tidak ada data --}}
                        <div class="w-full text-center py-12 bg-white rounded-lg shadow">
                            <i class="ph ph-calendar-blank text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada jadwal mengajar</h3>
                            <p class="text-gray-500">Anda belum memiliki jadwal mengajar yang terdaftar</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection