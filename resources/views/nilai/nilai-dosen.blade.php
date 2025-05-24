@extends ('master')

@section ('title', 'Nilai Mahasiswa')

@section ('content')

  <div class="flex w-full h-full bg-white">
      @include('components.sidebar')

    <div class="w-full max-w-6xl mx-auto p-4">
      @include('components.header')
      {{-- Carousel Matkul --}}
      <div class="relative w-full">
        {{-- Carousel --}}
        <div id="carouselMatkul"
            class="w-full overflow-x-auto scroll-smooth flex space-x-4"
            style="scrollbar-width: thin;">
            @foreach($matkuls as $matkul)
                <div class="w-64 flex-shrink-0 carousel-item py-4">
                    <a href="?id_matkul={{ $matkul->id_matkul }}">
                        <div class="bg-brand-50 shadow rounded-lg p-4 w-full cursor-pointer matkul-card {{ request('id_matkul') == $matkul->id_matkul ? 'ring ring-blue-500' : '' }}" data-matkul-id="{{ $matkul->id_matkul }}">
                            <p class="text-sm text-gray-500 mb-2">
                                Matakuliah {{ $matkul->jenis }} - {{ $matkul->sks }} SKS
                            </p>
                            <h2 class="font-bold">{{ $matkul->nama_matkul }}</h2>
                            <p class="text-sm mt-2 text-gray-700">
                                @php
                                    $dosen = \App\Models\Dosen::inRandomOrder()->first();
                                @endphp
                                {{ $dosen->nama_dosen ?? '-' }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
      </div>

      {{-- Tabel MHS --}}
      <div class="w-full mt-4">
        <div class="mb-2">
          <span id="selected-matkul-label" class="font-semibold text-lg text-gray-700">
            @if(request('id_matkul'))
                Daftar Mahasiswa untuk Matakuliah: {{ $matkuls->where('id_matkul', request('id_matkul'))->first()->nama_matkul ?? '-' }}
            @else
                Pilih salah satu matakuliah di atas
            @endif
          </span>
        </div>
        <table class="w-full rounded-lg bg-brand-100 mb-4">
          <thead>
            <tr>
              <th class="w-32 p-2 text-center">NRP</th>
              <th class="p-2 text-center">Nama</th>
              <th class="w-32 p-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($mahasiswas as $mhs)
              <tr class="bg-white border-b">
                <td class="p-2 text-center">{{ $mhs->nrp }}</td>
                <td class="p-2 text-center">{{ $mhs->nama }}</td>
                <td class="p-2 text-center">
                  <a href="{{ route('nilai.updateNilaiForm', ['id_mahasiswa' => $mhs->id_mahasiswa, 'id_matkul' => request('id_matkul')]) }}" class="inline-block text-blue-600 hover:text-blue-800">
                    <i class="ph ph-pencil text-xl"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

<script>
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
</script>
@endsection