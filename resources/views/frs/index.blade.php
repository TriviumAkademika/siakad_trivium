@extends('master')

@section('title', 'Data FRS')

@section('content')
<div class="flex w-full h-full bg-brand50">
    @include('components.sidebar')

    <div class="w-full p-4">
        @include('components.header')

        <h2 class="text-2xl font-semibold mb-4">Data FRS</h2>

        <div class="mb-4">
            <a href="{{ route('frs.create') }}" class="px-4 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                Tambah FRS
            </a>
        </div>

        {{-- Header --}}
        <section class="rounded-lg p-2 grid bg-brand100 mb-4">
            <div class="grid grid-cols-5 gap-4 text-sm font-semibold">
                <div class="flex items-center justify-center p-2">Nama Mahasiswa</div>
                <div class="flex items-center justify-center p-2">Tahun Ajaran</div>
                <div class="flex items-center justify-center p-2">Semester</div>
                <div class="flex items-center justify-center p-2">Total SKS</div>
                <div class="flex items-center justify-center p-2">Action</div>
            </div>
        </section>

        {{-- Data Rows --}}
        <section>
            @foreach ($frs as $item)
            <div 
              onclick="window.location='{{ route('frs.show', $item->id_frs) }}'" 
              class="grid grid-cols-5 gap-4 p-4 text-sm items-center border-b border-gray-200 cursor-pointer hover:bg-brand200"
              title="Klik untuk detail"
            >
                <div class="flex items-center justify-center p-2">{{ $item->mahasiswa->nama }}</div>
                <div class="flex items-center justify-center p-2">{{ $item->tahun_ajaran }}</div>
                <div class="flex items-center justify-center p-2">{{ $item->semester }}</div>
                <div class="flex items-center justify-center p-2">{{ $item->total_sks }}</div>
                <div class="flex items-center justify-center p-2 space-x-2">
                    <a href="{{ route('frs.edit', $item->id_frs) }}" class="inline-block px-3 py-1 bg-yellow-400 text-white text-xs rounded hover:bg-yellow-500" onclick="event.stopPropagation()">
                        Edit
                    </a>
                    <form action="{{ route('frs.destroy', $item->id_frs) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?')" onclick="event.stopPropagation()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </section>
    </div>
</div>
@endsection
