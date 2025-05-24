@extends ('master')

@section('title', 'Daftar Jadwal Kuliah')

@section('content')
<div class="flex w-full h-full bg-brand50">
    @include('components.sidebar')

    <div class="w-full p-4">
        @include('components.header')

        {{-- Tombol Tambah --}}
        <div class="mb-4">
            <a href="{{ route('jadwal.create') }}" class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                + Tambah Jadwal
            </a>
        </div>

        {{-- Header Tabel --}}
        <section class="rounded-lg p-2 grid bg-brand100 mb-4">
            <div class="grid grid-cols-8 gap-4 text-sm">
                <div class="flex items-center justify-center p-2 font-semibold">#</div>
                <div class="flex items-center justify-center p-2 font-semibold">Kelas</div>
                <div class="flex items-center justify-center p-2 font-semibold">Mata Kuliah</div>
                <div class="flex items-center justify-center p-2 font-semibold">Dosen</div>
                <div class="flex items-center justify-center p-2 font-semibold">Dosen Pendamping</div>
                <div class="flex items-center justify-center p-2 font-semibold">Waktu</div>
                <div class="flex items-center justify-center p-2 font-semibold">Ruangan</div>
                <div class="flex items-center justify-center p-2 font-semibold">Aksi</div>
            </div>
        </section>

        {{-- Isi Tabel --}}
        <section>
            @foreach ($jadwal as $index => $j)
            <div class="grid grid-cols-8 gap-4 p-4 text-sm items-center border-b border-gray-200">
                <div class="flex items-center justify-center p-2">
                    {{ $index + 1 }}
                </div>
                <div class="flex items-center justify-center p-2">
                    {{ $j->kelas->prodi }}-{{ $j->kelas->paralel }}
                </div>
                <div class="flex items-center justify-center p-2">
                    {{ $j->matkul->jenis }} - {{ $j->matkul->nama_matkul }}
                </div>
                <div class="flex items-center justify-center p-2">
                    {{ $j->dosen->nama_dosen }}
                </div>
                <div class="flex items-center justify-center p-2">
                    {{ $j->dosen2 ? $j->dosen2->nama_dosen : '-' }}
                </div>
                <div class="flex items-center justify-center p-2">
                    {{ $j->waktu->hari }}, {{ substr($j->waktu->jam_mulai, 0, 5) }} - {{ substr($j->waktu->jam_selesai, 0, 5) }}
                </div>
                <div class="flex items-center justify-center p-2">
                    {{ $j->ruangan->kode_ruangan }}
                </div>
                <div class="flex items-center justify-center p-2 space-x-2">
                    <a href="{{ route('jadwal.edit', $j->id_jadwal) }}" class="inline-block px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                        Edit
                    </a>
                    {{-- <form action="{{ route('jadwal.destroy', $j->id_jadwal) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus jadwal ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                            Delete
                        </button>
                    </form> --}}
                </div>
            </div>
            @endforeach
        </section>
    </div>
</div>
@endsection
