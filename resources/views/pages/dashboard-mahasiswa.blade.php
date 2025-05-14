@extends('master')

@section('title', 'Dashboard')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        {{-- Main --}}
        <div class="flex flex-col w-full bg-amber-200">
            {{-- Profil User di Header --}}
            @include('components.header')
            {{-- Content --}}
            <div class="flex flex-row p-6 space-x-6 bg-putih">
                <div class="flex flex-col w-2/3 space-y-4 bg-pink-300">
                    {{-- Card Informasi Mahasiswa --}}
                    <div class="flex flex-row space-x-4">
                        <div class="flex flex-col w-1/3 p-4 bg-brand200 rounded-3xl">
                            <h4 class="text-base text-hitam">Status</h4>
                            <h1 class="text-3xl text-hitam font-medium">Aktif</h1>
                        </div>
                        <div class="flex flex-col w-1/3 p-4 bg-brand200 rounded-3xl">
                            <h4 class="text-base text-hitam">IP Kumulatif</h4>
                            <h1 class="text-3xl text-hitam font-medium">3.76/4.00</h1>
                        </div>
                        <div class="flex flex-col w-1/3 p-4 bg-brand200 rounded-3xl">
                            <h4 class="text-base text-hitam">SKS</h4>
                            <h1 class="text-3xl text-hitam font-medium">98/108</h1>
                        </div>
                    </div>
                    {{-- Section Jadwal Kuliah --}}
                    <div class="flex flex-col w-full space-y-2">
                        <h3 class="text-xl text-hitam">Jadwal Kuliah</h3>
                        <hr class="border-abu w-full">
                        <div class="flex flex-col w-full rounded-2xl bg-brand50">
                            {{-- Card Jadwal Kuliah per Hari --}}
                            <div class="flex flex-col p-4 rounded-2xl space-y-2 bg-brand50">
                                <h4 class="text-base text-hitam font-semibold">Senin</h4>
                                {{-- Mata Kuliah 1 --}}
                                <div class="flex flex-row justify-between">
                                    <div class="flex flex-col pl-4 border-biru">
                                        <h4 class="text-base text-hitam">Workshop Desain Pengalaman Pengguna</h4>
                                        <p class="text-xs text-hitam">Desy Intan Permatasari</p>
                                        <p class="text-xs text-hitam">Nailussa’ada</p>
                                    </div>
                                    <div class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand200">
                                        <p class="text-xs text-hitam">C 106</p>
                                        <p class="text-xs text-hitam">09:40-12.10</p>
                                    </div>
                                </div>
                                {{-- Mata Kuliah 2 --}}
                                <div class="flex flex-row justify-between">
                                    <div class="flex flex-col pl-4 border-biru">
                                        <h4 class="text-base text-hitam">Workshop Pemrograman Perangkat Bergerak</h4>
                                        <p class="text-xs text-hitam">Prasetyo Wibowo</p>
                                        <p class="text-xs text-hitam">Fadilah Fahrul Hardiansyah</p>
                                    </div>
                                    <div class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand200">
                                        <p class="text-xs text-hitam">C 206</p>
                                        <p class="text-xs text-hitam">13:00 - 15:30</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Section Berita --}}
                <div class="flex flex-col w-1/3 space-y-2 bg-purple-100">
                    <h3 class="text-xl text-hitam">Berita</h3>
                    <hr class="border-abu w-full">
                    {{-- Berita 1 --}}
                    <div class="flex flex-col">
                        <h4 class="text-base text-info">Penerbitan Ijazah Periode April 2025</h4>
                        <p class="text-xs text-hitam">Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque
                            faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis
                            convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus
                            bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper
                            vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                        </br>
                        </br>
                            Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae
                            pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean
                            sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa
                            nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti
                            sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                        </br>
                        </br>
                            Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae
                            pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean
                            sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa
                            nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti
                            sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.</p>
                    </div>
                    {{-- Berita 2 --}}
                    <div class="flex flex-col">
                        <h4 class="text-base text-info">Pengisian Form Rencana Studi (FRS) dan Perwalian Semester Ganjil Tahun Ajaran 2025/2026</h4>
                        <p class="text-xs text-hitam">Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque
                            faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis
                            convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus
                            bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper
                            vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                        </br>
                        </br>
                            Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae
                            pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean
                            sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa
                            nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti
                            sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.
                        </br>
                        </br>
                            Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae
                            pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean
                            sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa
                            nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti
                            sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
