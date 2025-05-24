@extends ('master')

@section('title', 'Daftar Mata Kuliah')

@section('content')
    <div class="flex w-full h-full bg-brand50">
        @include('components.sidebar')

        <div class="w-full p-4">
            @include('components.header')

            {{-- Tombol Tambah --}}
            <div class="mb-4">
                <a href="{{ route('matkul.create') }}"
                    class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                    + Tambah Mata Kuliah
                </a>
            </div>

            {{-- Header Tabel --}}
            <section class="rounded-lg p-2 grid bg-brand100 mb-4">
                <div class="grid grid-cols-6 gap-4 text-sm">
                    <div class="flex items-center justify-center p-2 font-semibold">#</div>
                    <div class="flex items-center justify-center p-2 font-semibold">Nama Matkul</div>
                    <div class="flex items-center justify-center p-2 font-semibold">Jenis</div>
                    <div class="flex items-center justify-center p-2 font-semibold">SKS</div>
                    <div class="flex items-center justify-center p-2 font-semibold">Kapasitas</div>
                    @if (['auth', 'verified', 'role:admin'])
                        <div class="flex items-center justify-center p-2 font-semibold">Action</div>
                    @endif

                </div>
            </section>

            {{-- Isi Tabel --}}
            <section>
                @foreach ($matkul as $index => $m)
                    <div class="grid grid-cols-6 gap-4 p-4 text-sm items-center">
                        <div class="flex items-center justify-center p-2">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex items-center justify-center p-2">
                            {{ $m->nama_matkul }}
                        </div>
                        <div class="flex items-center justify-center p-2">
                            {{ $m->jenis }}
                        </div>
                        <div class="flex items-center justify-center p-2">
                            {{ $m->sks }}
                        </div>
                        <div class="flex items-center justify-center p-2">
                            {{ $m->kapasitas_kelas }}
                        </div>
                        @if (auth()->user()->role === 'admin')
                            <div class="flex items-center justify-center p-2 space-x-2">
                                <a href="{{ route('matkul.edit', $m->id_matkul) }}"
                                    class="inline-block px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                                    Edit
                                </a>
                                <form action="{{ route('matkul.destroy', $m->id_matkul) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                @endforeach
            </section>
        </div>
    </div>
@endsection
