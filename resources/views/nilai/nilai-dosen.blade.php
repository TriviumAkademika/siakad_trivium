@extends('master')

@section('title', 'nilai')

@section('content')
    <div class="flex w-full grow">
        {{-- sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-white">
            {{-- header --}}
            @include('components.header')

            {{-- content --}}
            <div class="flex flex-col px-6 pb-6 space-x-6">
                {{-- Toast Notification --}}
                <x-notification.toast-notification />

                <div class="flex flex-col grow items-end space-y-4">
                <div class="w-full">
                    <form method="GET" action="{{ route('nilai-dosen') }}" class="flex justify-end">
                        <select name="id_matkul" class="w-2/5 p-2 rounded border border-gray-300 focus:ring focus:ring-blue-200" onchange="this.form.submit()">
                            <option value="">Pilih Matakuliah</option>
                            @foreach ($matkuls as $matkul)
                                <option value="{{ $matkul->id_matkul }}" {{ request('id_matkul') == $matkul->id_matkul ? 'selected' : '' }}>
                                    {{ $matkul->nama_matkul }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="flex w-full pb-2">
                    <span id="selected-matkul">
                        @if (request('id_matkul'))
                            Daftar Mahasiswa untuk Matakuliah: {{ $matkuls->where('id_matkul', request('id_matkul'))->first()->nama_matkul ?? '-' }}
                            @else
                                Pilih Matakuliah
                            @endif
                    </span>
                </div>

                {{-- header tabel --}}
                <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                    <thead class="bg-brand-100">
                        <tr>
                            <th class="px-4 py-3 text-center text-sm font-medium text-hitam">NRP</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-hitam">UTS</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-hitam">UAS</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-putih divide-y divide-gray-200">
                        @foreach ($mahasiswa as $mhs)
                            {{-- DEBUG: Tampilkan data nilai untuk setiap mahasiswa --}}
                            @php
                                // dd($mhs->nilai);
                                // Uncomment baris di atas untuk melihat koleksi nilai
                                // untuk mahasiswa ini. Cari item dengan jenis_nilai 'UAS'.
                                // Pastikan array collection tidak kosong dan ada item UAS.
                            @endphp
                            <tr>
                                <td class="px-4 py-2 text-center text-sm text-hitam">{{ $mhs->nrp }}</td>
                                <td class="px-4 py-2 text-center text-sm text-hitam">{{ $mhs->nama }}</td>
                                {{-- Tampilkan nilai UTS --}}
                                <td class="px-4 py-2 text-center text-sm text-hitam">
                                    @php
                                        $nilaiUTS = $mhs->nilai->where('matakuliah_id', request('id_matkul'))->where('jenis_nilai', 'UTS')->first();
                                    @endphp
                                    {{ $nilaiUTS->nilai ?? '-' }}
                                </td>
                                {{-- Tampilkan nilai UAS --}}
                                <td class="px-4 py-2 text-center text-sm text-hitam">
                                    @php
                                        $nilaiUAS = $mhs->nilai->where('matakuliah_id', request('id_matkul'))->where('jenis_nilai', 'UAS')->first();
                                        \Log::info('Menampilkan nilai UAS', [
                                            'mahasiswa_id' => $mhs->id_mahasiswa,
                                            'matakuliah_id' => request('id_matkul'),
                                            'nilai' => $nilaiUAS ? $nilaiUAS->toArray() : null,
                                            'semua_nilai' => $mhs->nilai->toArray()
                                        ]);
                                    @endphp
                                    {{ $nilaiUAS->nilai ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-center text-sm text-hitam">
                                    <div class="flex justify-center items-center space-x-1">
                                        <a href="{{ route('nilai.edit', ['id_mahasiswa' => $mhs->id_mahasiswa, 'id_matkul' => request('id_matkul')]) }}" class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-sm rounded hover:bg-biru-700">
                                            <i class="ph ph-pencil text-xl"></i>
                                        </a>
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
    </div>

@endsection