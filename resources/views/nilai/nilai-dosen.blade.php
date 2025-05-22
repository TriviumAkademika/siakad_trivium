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
                    <div class="bg-brand-50 shadow rounded-lg p-4 w-full">
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
                </div>
            @endforeach
        </div>
      </div>

      {{-- Tabel MHS --}}
      <div class="w-full mt-4">
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
                  <a href="{{ route('mahasiswa.edit', $mhs->id_mahasiswa) }}" class="inline-block text-blue-600 hover:text-blue-800">
                    <i class="ph ph-eye text-xl"></i>
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
        document.getElementById('scrollLeft').onclick = function () {
            carousel.scrollBy({ left: -300, behavior: 'smooth' });
        };
        document.getElementById('scrollRight').onclick = function () {
            carousel.scrollBy({ left: 300, behavior: 'smooth' });
        };
    });
</script>
@endsection