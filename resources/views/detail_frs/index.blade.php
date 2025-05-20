@extends('master')

@section('title', 'Detail FRS Mahasiswa')

@section('content')
    <div class="flex w-full h-full bg-brand50">
        @include('components.sidebar')

        <div class="w-full p-4">
            @include('components.header')

            <h2 class="text-2xl font-semibold mb-6">Detail FRS Mahasiswa</h2>

            {{-- Informasi Mahasiswa --}}
            <section class="rounded-lg p-2 grid grid-cols-1 sm:grid-cols-3 bg-brand100 mb-6 gap-4">
                <div class="flex flex-col w-full p-4 gap-2 bg-brand-200 rounded-lg shadow">
                    <label class="font-semibold">Nama</label>
                    <input type="text" value="{{ $frs->mahasiswa->nama }}" readonly
                        class="w-full border-2 border-neutral-500 rounded-lg p-2 bg-gray-50 cursor-not-allowed">
                </div>
                <div class="flex flex-col w-full p-4 gap-2 bg-white rounded-lg shadow">
                    <label class="font-semibold">Semester</label>
                    <input type="text" value="{{ $frs->semester }}" readonly
                        class="w-full border-2 border-neutral-500 rounded-lg p-2 bg-gray-50 cursor-not-allowed">
                </div>
                <div class="flex flex-col w-full p-4 gap-2 bg-white rounded-lg shadow">
                    <label class="font-semibold">Tahun Ajaran</label>
                    <input type="text" value="{{ $frs->tahun_ajaran }}" readonly
                        class="w-full border-2 border-neutral-500 rounded-lg p-2 bg-gray-50 cursor-not-allowed">
                </div>
            </section>

            {{-- Table Detail FRS --}}
            <section class="rounded-lg p-2 grid bg-brand100">
                {{-- Header Tabel --}}
                <div class="flex gap-4 font-semibold mb-2">
                    <div class="flex items-center justify-center flex-none w-32 h-8 p-2 border border-white rounded-t">
                        Matkul</div>
                    <div class="flex items-center justify-center flex-grow h-8 p-2 border border-white rounded-t">Dosen
                    </div>
                    <div class="flex items-center justify-center flex-none w-24 h-8 p-2 border border-white rounded-t">
                        Ruangan</div>
                    <div class="flex items-center justify-center flex-none w-48 h-8 p-2 border border-white rounded-t">Waktu
                    </div>
                    <div class="flex items-center justify-center flex-none w-32 h-8 p-2 border border-white rounded-t">
                        Status</div>
                    <div class="flex items-center justify-center flex-none w-32 h-8 p-2 border border-white rounded-t">Aksi
                    </div>
                </div>

                {{-- Isi tabel --}}
                @foreach ($frs->detailFrs as $detail)
                    <div class="flex gap-4 border-b border-gray-300 p-4 items-center">
                        <div class="flex items-center justify-center flex-none w-32">
                            {{ $detail->jadwal->matkul->nama_matkul }}</div>
                        <div class="flex items-center justify-center flex-grow">{{ $detail->jadwal->dosen->nama_dosen }}
                        </div>
                        <div class="flex items-center justify-center flex-none w-24">
                            {{ $detail->jadwal->ruangan->kode_ruangan }}</div>
                        <div class="flex items-center justify-center flex-none w-48">
                            {{ $detail->jadwal->waktu->hari }} ({{ $detail->jadwal->waktu->jam_mulai }} -
                            {{ $detail->jadwal->waktu->jam_selesai }})
                        </div>
                        <div class="flex items-center justify-center flex-none w-32">
                            {{ $detail->status ? 'Diterima' : 'Belum Diterima' }}

                            <form action="{{ route('detail-frs.update-status', $detail->id_detail_frs) }}" method="POST"
                                class="inline-block ml-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs text-blue-600 hover:underline">
                                    {{ $detail->status ? 'Batalkan' : 'Terima' }}
                                </button>
                            </form>
                        </div>
                        <div class="flex items-center justify-center flex-none w-32">
                            <form action="{{ route('detail-frs.destroy', $detail->id_detail_frs) }}" method="POST"
                                onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </section>

            {{-- Tombol Modal --}}
            <div class="mt-6" x-data="{ open: false }">
                <button @click="open = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Tambah Jadwal
                </button>

                {{-- Modal --}}
                <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                    style="display: none" @click.away="open = false">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 relative">
                        <button @click="open = false"
                            class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">✕</button>
                        <h3 class="text-xl font-semibold mb-4">Pilih Jadwal</h3>

                        <form method="POST" action="{{ route('detail-frs.store') }}">
                            @csrf
                            <input type="hidden" name="id_frs" value="{{ $frs->id_frs }}">

                            <div class="overflow-y-auto max-h-[60vh] border rounded">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-2 border">Pilih</th>
                                            <th class="px-2 py-2 border">Matkul</th>
                                            <th class="px-2 py-2 border">Dosen</th>
                                            <th class="px-2 py-2 border">Hari</th>
                                            <th class="px-2 py-2 border">Jam</th>
                                            <th class="px-2 py-2 border">Ruangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jadwals as $jadwal)
                                            <tr class="hover:bg-gray-50 border-t">
                                                <td class="px-2 py-2 border">
                                                    <input type="checkbox" name="jadwal_ids[]"
                                                        value="{{ $jadwal->id_jadwal }}">
                                                </td>
                                                <td class="px-2 py-2 border">{{ $jadwal->matkul->nama_matkul }}</td>
                                                <td class="px-2 py-2 border">{{ $jadwal->dosen->nama_dosen }}</td>
                                                <td class="px-2 py-2 border">{{ $jadwal->waktu->hari }}</td>
                                                <td class="px-2 py-2 border">{{ $jadwal->waktu->jam_mulai }} -
                                                    {{ $jadwal->waktu->jam_selesai }}</td>
                                                <td class="px-2 py-2 border">{{ $jadwal->ruangan->kode_ruangan }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 text-right">
                                <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Tambah ke
                                    FRS</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
