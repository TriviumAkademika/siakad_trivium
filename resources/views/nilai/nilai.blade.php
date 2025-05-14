@extends ('master')

@section ('title', 'Nilai Mahasiswa')

@section ('content')

  <div class="flex w-full h-full bg-brand50">
      @include('components.sidebar')

    <div class="w-full p-4">
      @include('components.header')

      {{-- Informasi Mahasiswa --}}
      <section class="rounded-lg p-2 grid grid-cols-1 sm:grid-cols-3 bg-brand100 mb-4">
        <div class="flex flex-col w-full p-4 gap-2">
          <label for="">Nama</label>
          <input id="" type="text" value="Salsabila Nurhalimah" class="w-full border-2 border-neutral-500 rounded-lg p-2" readonly>
        </div>
        <div class="relative w-full p-4">
          <label for="semester" class="block mb-2">Semester</label>
          <div class="relative">
            <select id="semester" name="semester" class="appearance-none w-full border-2 border-neutral-500 rounded-lg p-2 pr-10">
              <option value="ganjil">Ganjil</option>
              <option value="genap">Genap</option>
            </select>
            <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-neutral-600"></i>
          </div>
        </div>
        <div class="relative w-full p-4">
          <label for="semester" class="block mb-2">Tahun Ajaran</label>
          <div class="relative">
            <select id="semester" name="semester" class="appearance-none w-full border-2 border-neutral-500 rounded-lg p-2 pr-10">
              <option value="">2023/2024</option>
              <option value="">2024/2025</option>
              <option value="">2026/2026</option>
            </select>
            <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-neutral-600"></i>
          </div>
        </div>
      </section>

      {{-- Tabel Nilai --}}
      <section class="rounded-lg p-2 grid bg-brand100 mb-4">
        <div class="flex gap-4">
          <div class="flex items-center justify-center flex-none w-32 h-6 p-2">
            Kode MK
          </div>
          <div class="flex items-center justify-center flex-grow h-6 p-2">
            Matakuliah
          </div>
          <div class="flex items-center justify-center flex-none w-60 h-6 p-2">
            Nilai
          </div>
        </div>
        {{-- sub header --}}
        <div class="flex gap-4 mt-0.5">
          <div class="flex-none w-32 h-6 p-2"></div> {{--kode mk--}}
          <div class="flex-grow h-6 p-2"></div> {{--mk--}}
          <div class="flex w-60 h-6">
            <div class="flex items-center justify-center w-1/2 border-r border-white">
              Nilai Huruf
            </div>
            <div class="flex items-center justify-center w-1/2">
              Nilai Numerik
            </div>
          </div>
        </div>
      </section>

      {{-- isi tabel --}}
      <section>
        <div class="flex gap-4 border-b-1 p-4">
          <!-- Kode MK -->
          <div class="flex items-center justify-center flex-none w-32 h-6 p-2">
            1234501
          </div>
          <!-- Matakuliah -->
          <div class="flex items-center justify-center flex-grow h-6 p-2">
            Workshop Desain Pengalaman Pengguna
          </div>
          <!-- Nilai -->
          <div class="flex w-60 h-6">
            <div class="flex items-center justify-center w-1/2">
              A
            </div>
            <div class="flex items-center justify-center w-1/2">
              100
            </div>
          </div>
        </div>
      </section>
      
      </div>
    </div>
  </div>