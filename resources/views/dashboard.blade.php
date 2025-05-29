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
            {{-- CONTENT MAHASISWA DAN DOSEN --}}
            @if (in_array(auth()->user()->role, ['mahasiswa', 'dosen']))
                <div class="flex flex-row px-6 pb-6 space-x-6">

                    {{-- Toast Notification --}}
                    <x-notification.toast-notification />

                    <div class="flex flex-col w-2/3 space-y-4">
                        {{-- Card Informasi Mahasiswa --}}
                        <div class="grid grid-cols-2 space-x-4">
                            @if (auth()->user()->role === 'mahasiswa')
                                <x-card.stat-card title="Status" value="{{ ucwords(auth()->user()->mahasiswa->status) }}"
                                    variant="gradient" />
                            @elseif (auth()->user()->role === 'dosen')
                                <x-card.stat-card title="Status"
                                    value="{{ ucwords(strtolower(auth()->user()->dosen->status)) }}" variant="gradient" />
                            @endif
                        </div>

                        {{-- Section Jadwal Kuliah --}}
                        <div class="flex flex-col w-full space-y-2">
                            <h3 class="text-xl text-hitam">Jadwal Kuliah</h3>
                            <hr class="border-abu w-full">
                            <div class="flex flex-col w-full rounded-2xl space-y-2">
                                @if (auth()->user()->role === 'dosen')
                                    {{-- Card Jadwal Kuliah Statis --}}
                                    <div class="flex flex-col items-center p-8 rounded-2xl space-y-2 bg-brand-50">
                                        <i class="ph ph-calendar-x text-6xl text-hitam"></i>
                                        <h3 class="text-lg font-medium text-gray-900">
                                            Belum ada jadwal mengajar
                                        </h3>
                                        <p class="text-hitam">
                                            Anda belum memiliki jadwal mengajar yang terdaftar.
                                        </p>
                                    </div>
                                @endif
                                @if (auth()->user()->role === 'mahasiswa')
                                    {{-- Card Jadwal Kuliah Statis --}}
                                    {{-- Senin --}}
                                    <div class="flex flex-col p-4 rounded-2xl space-y-2 bg-brand-50">
                                        <h4 class="text-base text-hitam font-semibold">Senin</h4>
                                        {{-- Mata Kuliah 1 --}}
                                        <div class="flex flex-row justify-between">
                                            <div class="flex flex-col pl-4 border-biru">
                                                <h4 class="text-base text-hitam">Workshop Desain Pengalaman Pengguna</h4>
                                                <p class="text-xs text-hitam">Desy Intan Permatasari</p>
                                            </div>
                                            <div
                                                class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand-200">
                                                <p class="text-xs text-hitam">C106</p>
                                                <p class="text-xs text-hitam">09:40-12.10</p>
                                            </div>
                                        </div>
                                        {{-- Mata Kuliah 2 --}}
                                        <div class="flex flex-row justify-between">
                                            <div class="flex flex-col pl-4 border-biru">
                                                <h4 class="text-base text-hitam">Workshop Pemrograman Perangkat Bergerak
                                                </h4>
                                                <p class="text-xs text-hitam">Prasetyo Wibowo</p>
                                                <p class="text-xs text-hitam">Fadilah Fahrul Hardiansyah</p>
                                            </div>
                                            <div
                                                class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand-200">
                                                <p class="text-xs text-hitam">C206</p>
                                                <p class="text-xs text-hitam">13:00 - 15:30</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Section Berita --}}
                    <x-berita />
                </div>
            @endif

            {{-- CONTENT ADMIN --}}
            @if (auth()->user()->role === 'admin')
                <div class="flex flex-col px-6 pb-6 space-y-6">

                    {{-- Toast Notification --}}
                    <x-notification.toast-notification />

                    {{-- Stastistik Pengguna --}}
                    <div class="flex flex-col space-y-6">
                        {{-- Header --}}
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl text-hitam font-semibold">Statistik Pengguna</h3>
                            <div class="text-sm text-gray-500">Data terkini</div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                            <!-- Card User -->
                            <x-card.stat-card title="Total User" value="{{ $totalUser }}" description="User Aktif"
                                variant="gradient" />

                            <!-- Card Mahasiswa -->
                            <x-card.stat-card title="Total Mahasiswa" value="{{ $totalMahasiswa }}"
                                description="Mahasiswa Aktif" variant="gradient" />

                            <!-- Card Dosen -->
                            <x-card.stat-card title="Total Dosen" value="{{ $totalDosen }}" description="Dosen Aktif"
                                variant="gradient" />
                        </div>
                    </div>
                    {{-- Stastistik Pelengkap --}}
                    <div class="flex flex-col space-y-6">
                        {{-- Header --}}
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl text-hitam font-semibold">Statistik Akademik</h3>
                            <div class="text-sm text-gray-500">Data terkini</div>
                        </div>

                        {{-- Cards Grid - Responsive Layout --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 lg:gap-6">

                            {{-- Card Jadwal --}}
                            <x-card.stat-card title="Total Jadwal" value="{{ $totalJadwal }}"
                                description="Perkuliahan Terjadwal" statusColor="blue" status="Aktif" />

                            {{-- Card Mata Kuliah --}}
                            <x-card.stat-card title="Total Mata Kuliah" value="{{ $totalMataKuliah }}"
                                description="Mata Kuliah Saat Ini" variant="default" statusColor="green"
                                status="Tersedia" />

                            {{-- Card Kelas --}}
                            <x-card.stat-card title="Total Kelas" value="{{ $totalKelas }}" description="Kelas Aktif"
                                variant="default" statusColor="purple" status="Berjalan" />

                            {{-- Card Ruangan --}}
                            <x-card.stat-card title="Total Ruangan" value="{{ $totalRuangan }}"
                                description="Ruangan Tersedia" statusColor="indigo" status="Siap" />

                            {{-- Card Waktu --}}
                            <x-card.stat-card title="Total Waktu" value="{{ $totalWaktu }}" description="Jam Perkuliahan"
                                variant="default" statusColor="yellow" status="Terjadwal" />
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
