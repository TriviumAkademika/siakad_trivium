@extends('master')

@section('title', 'Detail Dosen')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-putih">
            {{-- Profil User di Header --}}
            @include('components.header')

            {{-- Toast Notification --}}
            <x-notification.toast-notification />

            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Header dengan tombol kembali --}}
                {{-- <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('dosen.index') }}" 
                           class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="ph ph-arrow-left text-lg"></i>
                        </a>
                        <h2 class="text-2xl font-semibold text-hitam">Detail Dosen</h2>
                    </div>
                </div> --}}

                {{-- Card Detail Dosen --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    {{-- Header Card --}}
                    <div class="bg-brand-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-biru-600 rounded-full flex items-center justify-center">
                                    <i class="ph ph-user text-2xl text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-hitam">{{ $dosen->nama_dosen }}</h3>
                                    <p class="text-gray-600">NIP: {{ $dosen->nip }}</p>
                                </div>
                            </div>

                            {{-- Status Badge --}}
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full
                                @if ($dosen->status == 'AKTIF') bg-green-100 text-green-800
                                @elseif($dosen->status == 'CUTI') bg-yellow-100 text-yellow-800
                                @elseif($dosen->status == 'PENSIUN') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $dosen->status }}
                            </span>
                        </div>
                    </div>

                    {{-- Content Card --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Informasi Personal --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                    Informasi Personal
                                </h4>

                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-user text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                            <p class="text-hitam">{{ $dosen->nama_dosen }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-identification-card text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">NIP</label>
                                            <p class="text-hitam font-mono">{{ $dosen->nip }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-phone text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">No HP</label>
                                            <p class="text-hitam">{{ $dosen->no_hp }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Informasi Alamat & Status --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                    Informasi Alamat & Status
                                </h4>

                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-map-pin text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                            <p class="text-hitam">{{ $dosen->alamat }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-flag text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                                @if ($dosen->status == 'AKTIF') bg-green-100 text-green-800
                                                @elseif($dosen->status == 'CUTI') bg-yellow-100 text-yellow-800
                                                @elseif($dosen->status == 'PENSIUN') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $dosen->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    <i class="ph ph-info mr-1"></i>
                                    Data dosen dapat diubah melalui tombol edit di samping
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('dosen.index') }}"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
                                        <i class="ph ph-list mr-2"></i>
                                        Kembali ke Daftar
                                    </a>

                                    <x-button.submit type="link" href="{{ route('dosen.edit', $dosen->id_dosen) }}"
                                        icon="ph ph-pencil-simple">
                                        Edit Dosen
                                    </x-button.submit>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
