@extends ('master')

@section('title', 'Daftar Dosen')

@section('content')

<div class="flex w-full h-full bg-putih">
    @include('components.sidebar')

    <div class="w-full p-4">
        @include('components.header')

        {{-- Tabel Dosen --}}
        <section class="rounded-lg p-2 grid bg-brand100 mb-4">
            <div class="grid grid-cols-6 gap-4 text-sm">
                <div class="flex items-center justify-center p-2 font-semibold">ID Dosen</div>
                <div class="flex items-center justify-center p-2 font-semibold">Nama Dosen</div>
                <div class="flex items-center justify-center p-2 font-semibold">NIP</div>
                <div class="flex items-center justify-center p-2 font-semibold">Alamat</div>
                <div class="flex items-center justify-center p-2 font-semibold">No HP</div>
                
                {{-- Hanya tampil jika role = admin --}}
                @role('admin')
                    <div class="flex items-center justify-center p-2 font-semibold">Action</div>
                @endrole
            </div>
        </section>

        {{-- isi tabel --}}
        <section>
            @foreach ($dosen as $index => $d)
                <div class="grid grid-cols-6 gap-4 p-4 text-sm items-center">
                    <div class="flex items-center justify-center p-2">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex items-center justify-center p-2">
                        {{ $d->nama_dosen }}
                    </div>
                    <div class="flex items-center justify-center p-2">
                        {{ $d->nip }}
                    </div>
                    <div class="flex items-center justify-center p-2">
                        {{ $d->alamat }}
                    </div>
                    <div class="flex items-center justify-center p-2">
                        {{ $d->no_hp }}
                    </div>

                    {{-- Tombol hanya untuk admin --}}
                    @role('admin')
                        <div class="flex items-center justify-center p-2">
                            <a href="{{ route('dosen.edit', $d->id_dosen) }}"
                                class="inline-block px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</a>
                        </div>
                    @endrole
                </div>
            @endforeach
        </section>

        {{-- Tombol Tambah hanya untuk admin --}}
        @role('admin')
            <div class="mt-4">
                <a href="{{ route('dosen.create') }}"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                    + Tambah Dosen
                </a>
            </div>
        @endrole

    </div>
</div>
</div>
@endsection
