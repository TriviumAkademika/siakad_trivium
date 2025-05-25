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
                {{-- Toast Notification
                <x-notification.toast-notification /> --}}
                <div class="flex flex-col grow items-end space-y-4">
                <div class="w-full">
                    <form method="GET" action="" class="flex justify-end">
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
                            <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-putih divide-y divide-gray-200">
                        @foreach ($mahasiswa as $mhs)
                            <tr>
                                <td class="px-4 py-2 text-center text-sm text-hitam">{{ $mhs->nrp }}</td>
                                <td class="px-4 py-2 text-center text-sm text-hitam">{{ $mhs->nama }}</td>
                                <td class="px-4 py-2 text-center text-sm text-hitam">
                                    <div class="flex justify-center items-center space-x-1">
                                        <a href="{{ route('nilai.updateNilaiForm', ['id_mahasiswa' => $mhs->id_mahasiswa, 'id_matkul' => request('id_matkul')]) }}" class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-sm rounded hover:bg-biru-700">
                                            <i class="ph ph-pencil text-xl"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                
                {{-- <section class="rounded-lg p-2 grid bg-brand-100 mb-4">
                    <div class="flex gap-4 text-sm">
                        <div class="flex-none w-36 p-2 font-semibold text-center">NRP</div>
                        <div class="flex-grow p-2 font-semibold text-center">Nama Mahasiswa</div>
                        <div class="flex-none w-28 p-2 font-semibold text-center">Action</div>
                    </div>
                </section>

                
                <section>
                        @foreach ($mahasiswa as $mhs)
                        <div class="flex gap-4 text-sm">
                            <div class="flex-none w-36 p-2 font-semibold text-center">{{ $mhs->nrp }}</div>
                            <div class="flex-grow p-2 font-semibold text-center">{{ $mhs->nama }}</div>
                            <div class="flex-none w-28 p-2 font-semibold text-center">
                                <a href="{{ route('nilai.updateNilaiForm', ['id_mahasiswa' => $mhs->id_mahasiswa, 'id_matkul' => request('id_matkul')]) }}" class="inline-block text-blue-600 hover:text-blue-800">
                                    <i class="ph ph-pencil text-xl"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                </section> --}}
            </div>
            </div>
        </div>
    </div>
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.getElementById('carouselMatkul');
        const matkulLabel = document.getElementById('selected-matkul-label');
        document.querySelectorAll('.matkul-card').forEach(function(card) {
            card.addEventListener('click', function() {
                document.querySelectorAll('.matkul-card').forEach(function(c) {
                    c.classList.remove('ring', 'ring-blue-500');
                });
                card.classList.add('ring', 'ring-blue-500');
                const namaMatkul = card.querySelector('h2').textContent;
                matkulLabel.textContent = 'Daftar Mahasiswa untuk Matakuliah: ' + namaMatkul;
            });
        });
        document.getElementById('scrollLeft').onclick = function () {
            carousel.scrollBy({ left: -300, behavior: 'smooth' });
        };
        document.getElementById('scrollRight').onclick = function () {
            carousel.scrollBy({ left: 300, behavior: 'smooth' });
        };
    });
</script> --}}

@endsection