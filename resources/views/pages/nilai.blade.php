@extends ('master')

@section ('title', 'Nilai Mahasiswa')

@section ('content')

  <div class="flex w-full h-full bg-brand50">
      @include('components.sidebar')

    <div class="w-full">
      @include('components.header')
      <div class="p-4 flex-col">
        <div>
          <section class="flex flex-row h-28 rounded-lg bg-brand100">
            <div class="flex justify-around  gap-16">
              <div>
                <div>
                  <h3>Nama :</h3>
                </div>
                <div  class="badge badge-outline px-5 py-5 rounded-sm text-2xl">
                  Salsabila Nurhalimah
                </div>
              </div>
              <div>
                <div>
                  <h3>Semester :</h3>
                </div>
                <div>
                  <select class="select" name="Semester" id="">
                    <option disabled selected>Pilih semester</option>
                    <option value="">Ganjil</option>
                    <option value="">Genap</option>
                  </select>
                </div>
              </div>
              <div>
                <div>
                  <h3>Tahun Ajaran :</h3>
                </div>
                <div>
                  <select class="select" name="Semester" id="">
                    <option disabled selected>Pilih Tahun Ajaran</option>
                    <option value="">2023/2024</option>
                    <option value="">2024/2025</option>
                    <option value="">2026/2027</option>
                  </select>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="bg-blue-50">
          <h1>INI APA YA?</h1>
        </div>
      </div>
    </div>
  </div>