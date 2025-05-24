@extends('master')

@section('title', 'Dashboard')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        {{-- Main --}}
        <div class="flex flex-col w-full bg-putih">
            {{-- Profil User di Header --}}
            @include('components.header')
            {{-- Content --}}
            <div class="flex flex-row px-6 pb-6 space-x-6">

                {{-- Toast Notification --}}
                <x-notification.toast-notification />

                <div class="flex flex-col w-2/3 space-y-4">

                    {{-- Card Informasi Mahasiswa --}}
                    <div class="flex flex-row space-x-4">
                        <div class="flex flex-col w-1/3 p-4 bg-brand-200 rounded-3xl">
                            <h4 class="text-base text-hitam">Status</h4>
                            <h1 class="text-3xl text-hitam font-medium">Aktif</h1>
                        </div>
                        <div class="flex flex-col w-1/3 p-4 bg-brand-200 rounded-3xl">
                            <h4 class="text-base text-hitam">IP Kumulatif</h4>
                            <h1 class="text-3xl text-hitam font-medium">3.76/4.00</h1>
                        </div>
                        <div class="flex flex-col w-1/3 p-4 bg-brand-200 rounded-3xl">
                            <h4 class="text-base text-hitam">SKS</h4>
                            <h1 class="text-3xl text-hitam font-medium">98/108</h1>
                        </div>
                    </div>
                    
                    {{-- Section Jadwal Kuliah --}}
                    <div class="flex flex-col w-full space-y-2">
                        <h3 class="text-xl text-hitam">Jadwal Kuliah</h3>
                        <hr class="border-abu w-full">
                        <div class="flex flex-col w-full rounded-2xl space-y-2">
                            {{-- Card Jadwal Kuliah per Hari --}}
                            {{-- Senin --}}
                            <div class="flex flex-col p-4 rounded-2xl space-y-2 bg-brand-50">
                                <h4 class="text-base text-hitam font-semibold">Senin</h4>
                                {{-- Mata Kuliah 1 --}}
                                <div class="flex flex-row justify-between">
                                    <div class="flex flex-col pl-4 border-biru">
                                        <h4 class="text-base text-hitam">Workshop Desain Pengalaman Pengguna</h4>
                                        <p class="text-xs text-hitam">Desy Intan Permatasari</p>
                                        <p class="text-xs text-hitam">Nailussa’ada</p>
                                    </div>
                                    <div class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand-200">
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
                                    <div class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand-200">
                                        <p class="text-xs text-hitam">C 206</p>
                                        <p class="text-xs text-hitam">13:00 - 15:30</p>
                                    </div>
                                </div>
                            </div>
                            {{-- Selasa --}}
                            <div class="flex flex-col p-4 rounded-2xl space-y-2 bg-brand-50">
                                <h4 class="text-base text-hitam font-semibold">Selasa</h4>
                                {{-- Mata Kuliah 1 --}}
                                <div class="flex flex-row justify-between">
                                    <div class="flex flex-col pl-4 border-biru">
                                        <h4 class="text-base text-hitam">Workshop Pemrograman Framework</h4>
                                        <p class="text-xs text-hitam">Yanuar Risah Prayogi</p>
                                    </div>
                                    <div class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand-200">
                                        <p class="text-xs text-hitam">C 303</p>
                                        <p class="text-xs text-hitam">10:30-13.50</p>
                                    </div>
                                </div>
                                {{-- Mata Kuliah 2 --}}
                                <div class="flex flex-row justify-between">
                                    <div class="flex flex-col pl-4 border-biru">
                                        <h4 class="text-base text-hitam">Workshop Administrasi Jaringan</h4>
                                        <p class="text-xs text-hitam">Idris Winarno</p>
                                    </div>
                                    <div class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand-200">
                                        <p class="text-xs text-hitam">C 307</p>
                                        <p class="text-xs text-hitam">13:50 - 15:30</p>
                                    </div>
                                </div>
                            </div>
                            {{-- Rabu --}}
                            <div class="flex flex-col p-4 rounded-2xl space-y-2 bg-brand-50">
                                <h4 class="text-base text-hitam font-semibold">Rabu</h4>
                                {{-- Mata Kuliah 1 --}}
                                <div class="flex flex-row justify-between">
                                    <div class="flex flex-col pl-4 border-biru">
                                        <h4 class="text-base text-hitam">Workshop Pengembangan Perangkat Lunak Berbasis
                                            Agile</h4>
                                        <p class="text-xs text-hitam">Desy Intan Permatasari</p>
                                        <p class="text-xs text-hitam">Adam Shidqul Aziz</p>
                                    </div>
                                    <div class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand-200">
                                        <p class="text-xs text-hitam">C 206</p>
                                        <p class="text-xs text-hitam">08:50-11:20</p>
                                    </div>
                                </div>
                                {{-- Mata Kuliah 2 --}}
                                <div class="flex flex-row justify-between">
                                    <div class="flex flex-col pl-4 border-biru">
                                        <h4 class="text-base text-hitam">Praktik Kecerdasan Buatan</h4>
                                        <p class="text-xs text-hitam">Nur Rosyid Mubtadai</p>
                                        <p class="text-xs text-hitam">Renovita Edelani</p>
                                    </div>
                                    <div class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand-200">
                                        <p class="text-xs text-hitam">C 203</p>
                                        <p class="text-xs text-hitam">11:20-13:50</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Section Berita --}}
                <div class="flex flex-col w-1/3 space-y-2">
                    <h3 class="text-xl text-hitam">Berita</h3>
                    <hr class="border-abu w-full">
                    {{-- Berita 1 --}}
                    <div class="flex flex-col">
                        <h4 class="text-base text-info">Penerbitan Ijazah Periode April 2025</h4>
                        <p class="text-justify text-xs text-hitam">Diberitahukan kepada seluruh Lulusan Periode Ijazah April
                            2025 (Peserta Yudisium Bulan Februari 2025 dan Maret 2025) yang telah memenuhi ketentuan
                            administrasi dan keuangan, pengunduhan dan pengambilan Ijazah dapat dilakukan dengan jadwal
                            berikut:
                        </p>
                        <ol class="list-decimal pl-4">
                            <li class="text-justify text-xs text-hitam">
                                ljazah digital dapat diunduh mulai tanggal 26 April 2025, pukul 13:00 WIB melalui website
                                Trivium Akademika.
                            </li>
                            <li class="text-justify text-xs text-hitam">
                                Pengambilan ijazah cetak dapat dilakukan pada tanggal 5-9 Mei 2025 pukul 09:00- 14:30 WIB di
                                Gedung Perpustakaan Lantai 1, Gedung Pascasarjana.
                            </li>
                            <li class="text-justify text-xs text-hitam">
                                Lulusan yang ingin mengambil dokumen ijazah harus terlebih dahulu melakukan booking jadwal
                                secara online melalui website Trivium Akademika mulai tanggal 28 April 2025.
                            </li>
                        </ol>
                        <p class="text-justify text-xs text-hitam">
                            Untuk pengambilan di luar jadwal, lulusan agar menghubungi Helpdesk di
                            laman https://helpdesk.trivium.ac.id/. Demikian informasi ini disampaikan untuk menjadi
                            perhatian seluruh Lulusan periode Yudisium Trivium Akademika Bulan Februari 2025 dan Maret 2025.
                            Terima kasih atas perhatian dan kerjasama yang diberikan.
                        </p>
                    </div>
                    {{-- Berita 2 --}}
                    <div class="flex flex-col">
                        <h4 class="text-base text-info">Pengisian Form Rencana Studi (FRS) dan Perwalian Semester Ganjil
                            Tahun Ajaran 2025/2026</h4>
                        <p class="text-justify text-xs text-hitam">Diberitahukan kepada seluruh Mahasiswa/i Aktif Tahun
                            Ajaran 2025/2026 yang telah melakukan Daftar Ulang, bahwa pengisian Form Rencana Studi (FRS)
                            dapat dilakukan dengan jadwal berikut:
                        </p>
                        <ol class="list-decimal pl-4">
                            <li class="text-justify text-xs text-hitam">
                                Pengisian FRS dapat dilakukan mulai tanggal 4 Agustus 2025 pukul 00.00 WIB dan ditutup pada
                                tanggal 12 Agustus 2025 pukul 00.00 WIB melalui website Trivium.
                            </li>
                            <li class="text-justify text-xs text-hitam">
                                Mahasiswa/i yang terlambat melakukan pengisian FRS diwajibkan untuk menghubungi Dosen Wali
                                guna mendapatkan arahan lebih lanjut.
                            </li>
                        </ol>
                        <p class="text-justify text-xs text-hitam">
                            Demikian informasi ini disampaikan untuk menjadi perhatian seluruh Mahasiswa/i Aktif Tahun
                            Ajaran 2025/2026. Terima kasih atas perhatian dan kerjasama yang diberikan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
