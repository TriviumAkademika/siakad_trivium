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
                    <div class="flex items-center justify-center p-2 font-semibold">
                        ID Dosen
                    </div>
                    <div class="flex items-center justify-center p-2 font-semibold">
                        Nama Dosen
                    </div>
                    <div class="flex items-center justify-center p-2 font-semibold">
                        NIP
                    </div>
                    <div class="flex items-center justify-center p-2 font-semibold">
                        Alamat
                    </div>
                    <div class="flex items-center justify-center p-2 font-semibold">
                        No HP
                    </div>
                    <div class="flex items-center justify-center p-2 font-semibold">
                        Action
                    </div>
                </div>
            </section>


            {{-- isi tabel --}}
            <section>
                @foreach ($dosen as $index => $d)
                    <div class="grid grid-cols-6 gap-4 p-4 text-sm items-center">
                        <!-- Kode MK -->

                        <div class="flex items-center justify-center p-2">
                            {{ $index + 1 }}
                        </div>
                        <!-- Matakuliah -->
                        <div class="flex items-center justify-center p-2">
                            {{ $d->nama_dosen }}
                        </div>
                        <!-- Matakuliah -->
                        <div class="flex items-center justify-center p-2">
                            {{ $d->nip }}
                        </div>
                        <!-- Matakuliah -->
                        <div class="flex items-center justify-center p-2">
                            {{ $d->alamat }}
                        </div>
                        <!-- Matakuliah -->
                        <div class="flex items-center justify-center p-2">
                            {{ $d->no_hp }}
                        </div>
                        <!-- Matakuliah -->
                        <div class="flex items-center justify-center p-2">
                            <a href="{{ route('dosen.edit', $d->id_dosen) }}"
                                class="inline-block px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</a>
                            <form action="{{ route('dosen.destroy', $d->id_dosen) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </section>

        </div>
    </div>
    </div>
