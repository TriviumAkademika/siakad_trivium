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
                            <div class="flex flex-col w-full rounded-2xl space-y-4">

                                {{-- LOGIKA JADWAL UNTUK DOSEN --}}
                                @if (auth()->user()->role === 'dosen')
                                    @php
                                        $dosen = auth()->user()->dosen;
                                        $jadwalDosen = $dosen
                                            ? \App\Models\Jadwal::with(['matkul', 'ruangan', 'waktu', 'kelas'])
                                                ->where('id_dosen', $dosen->id_dosen)
                                                ->get()
                                                ->groupBy('hari')
                                            : collect();
                                    @endphp

                                    @if ($jadwalDosen->isEmpty())
                                        <div class="flex flex-col items-center p-8 rounded-2xl space-y-2 bg-brand-50">
                                            <i class="ph ph-calendar-x text-6xl text-hitam"></i>
                                            <h3 class="text-lg font-medium text-gray-900">Belum ada jadwal mengajar</h3>
                                            <p class="text-hitam">Anda belum memiliki jadwal mengajar yang terdaftar.</p>
                                        </div>
                                    @else
                                        @foreach ($jadwalDosen as $hari => $daftarJadwal)
                                            <div class="flex flex-col p-4 rounded-2xl space-y-3 bg-brand-50 mb-2">
                                                <h4 class="text-base text-hitam font-semibold">{{ ucfirst($hari) }}</h4>
                                                @foreach ($daftarJadwal as $jadwal)
                                                    <div
                                                        class="flex flex-row justify-between items-center border-b border-gray-100 pb-2 last:border-none last:pb-0">
                                                        <div class="flex flex-col pl-4 border-l-4 border-biru">
                                                            <h4 class="text-base text-hitam font-medium">
                                                                {{ $jadwal->matkul->nama_matkul ?? ($jadwal->matkul->nama ?? '-') }}
                                                            </h4>
                                                            <p class="text-xs text-gray-500">
                                                                Kelas:
                                                                @if (isset($jadwal->kelas))
                                                                    {{ $jadwal->kelas->prodi }} -
                                                                    {{ $jadwal->kelas->paralel }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="flex flex-col w-32 p-2 rounded-lg items-center justify-center bg-brand-200">
                                                            <p class="text-xs text-hitam font-semibold">
                                                                {{ $jadwal->ruangan->nama_ruangan ?? ($jadwal->id_ruangan ?? '-') }}
                                                            </p>
                                                            <p class="text-xs text-hitam">
                                                                {{ $jadwal->waktu->jam_mulai ?? '' }} -
                                                                {{ $jadwal->waktu->jam_selesai ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    @endif
                                @endif

                                {{-- LOGIKA JADWAL UNTUK MAHASISWA --}}
                                {{-- LOGIKA JADWAL UNTUK MAHASISWA --}}
                                @if (auth()->user()->role === 'mahasiswa')
                                    {{-- Kita langsung pakai $jadwalMahasiswa yang dikirim dari DashboardController --}}
                                    @if (!isset($jadwalMahasiswa) || $jadwalMahasiswa->isEmpty())
                                        <div class="flex flex-col items-center p-8 rounded-2xl space-y-2 bg-brand-50">
                                            <i class="ph ph-calendar-x text-6xl text-hitam"></i>
                                            <h3 class="text-lg font-medium text-gray-900">Belum ada jadwal kuliah</h3>
                                            <p class="text-hitam">Jadwal kuliah kosong atau FRS belum di-approve oleh Dosen
                                                Wali.</p>
                                        </div>
                                    @else
                                        {{-- Looping Hari --}}
                                        @foreach ($jadwalMahasiswa as $hari => $daftarJadwal)
                                            <div class="flex flex-col p-4 rounded-2xl space-y-3 bg-brand-50 mb-4">
                                                <h4 class="text-base text-hitam font-semibold">{{ ucfirst($hari) }}</h4>

                                                {{-- Looping Jadwal pada hari tersebut --}}
                                                @foreach ($daftarJadwal as $jadwal)
                                                    <div
                                                        class="flex flex-row justify-between items-center border-b border-gray-100 pb-2 last:border-none last:pb-0">
                                                        <div class="flex flex-col pl-4 border-l-4 border-biru">
                                                            <h4 class="text-base text-hitam font-medium">
                                                                {{ $jadwal->matkul->nama_matkul ?? ($jadwal->matkul->nama ?? '-') }}
                                                            </h4>
                                                            <p class="text-xs text-gray-500">
                                                                {{ $jadwal->dosen->nama_dosen ?? 'Dosen Tidak Diketahui' }}
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="flex flex-col w-32 p-2 rounded-lg items-center justify-center bg-brand-200">
                                                            <p class="text-xs text-hitam font-semibold">
                                                                {{ $jadwal->ruangan->nama_ruangan ?? ($jadwal->id_ruangan ?? '-') }}
                                                            </p>
                                                            <p class="text-xs text-hitam">
                                                                {{ $jadwal->waktu->jam_mulai ?? '' }} -
                                                                {{ $jadwal->waktu->jam_selesai ?? '' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    @endif
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
                            <x-card.stat-card title="Total User" value="{{ $totalUser }}" description="User Aktif"
                                variant="gradient" />

                            <x-card.stat-card title="Total Mahasiswa" value="{{ $totalMahasiswa }}"
                                description="Mahasiswa Aktif" variant="gradient" />

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
