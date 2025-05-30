@extends('master')

@section('title', 'Profil Saya')

@section('content')
<div class="flex w-full grow">
    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex flex-col w-full bg-putih">
        {{-- Profil User di Header --}}
        @include('components.header')

        {{-- Toast Notification --}}
        {{-- This component should ideally pick up session flash messages --}}
        {{-- In ProfileController@updatePassword, use `->with('success', 'Password berhasil diperbarui.')` --}}
        <x-notification.toast-notification />

        {{-- Content --}}
        <div class="flex flex-col px-6 pb-6 space-y-6">

            {{-- Card Profil Pengguna --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                {{-- Header Card --}}
                <div class="bg-brand-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-biru-600 rounded-full flex items-center justify-center">
                                {{-- Placeholder for avatar - replace with actual avatar if available --}}
                                {{-- <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" alt="Avatar" class="w-full h-full rounded-full object-cover"> --}}
                                <i class="ph ph-user text-3xl text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-hitam">{{ $user->name }}</h3>
                                <p class="text-gray-600">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-sm font-medium rounded-full capitalize bg-gray-100 text-gray-800">
                            {{ $user->role ?? 'Pengguna' }} {{-- Assuming $user->role exists --}}
                        </span>
                    </div>
                </div>

                {{-- Content Card --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        {{-- Kolom Informasi Umum (jika ada tambahan selain di header) --}}
                        {{-- <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                Informasi Akun Umum
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                        <i class="ph ph-identification-badge text-gray-500 text-lg"></i>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nama Pengguna</label>
                                        <p class="text-hitam">{{ $user->username ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        {{-- Informasi Spesifik Peran --}}
                        @if ($role === 'mahasiswa' && $profileData)
                        <div class="space-y-4 md:col-span-2"> {{-- Make it full width if it's the primary detail section --}}
                            <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                Data Mahasiswa
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-user text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap Mahasiswa</label>
                                            <p class="text-hitam">{{ $profileData->nama }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-identification-card text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">NRP</label>
                                            <p class="text-hitam font-mono">{{ $profileData->nrp }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-gender-intersex text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                                            <p class="text-hitam">{{ $profileData->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-phone text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">No. HP Mahasiswa</label>
                                            <p class="text-hitam">{{ $profileData->no_hp }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                     @if ($profileData->kelas)
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-graduation-cap text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                                            <p class="text-hitam">{{ $profileData->kelas->prodi }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-chalkboard-teacher text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Kelas Paralel</label>
                                            <p class="text-hitam">{{ $profileData->kelas->paralel }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-stack-overflow-logo text-gray-500 text-lg"></i></div> {{-- Or ph-number-square-seven, etc. --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Semester</label>
                                            <p class="text-hitam">{{ $profileData->semester }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-flag text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status Mahasiswa</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($profileData->status == 'AKTIF') bg-green-100 text-green-800
                                                @elseif($profileData->status == 'CUTI') bg-yellow-100 text-yellow-800
                                                @elseif($profileData->status == 'LULUS') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $profileData->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-2 flex items-start gap-3">
                                    <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-map-pin text-gray-500 text-lg"></i></div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                        <p class="text-hitam">{{ $profileData->alamat }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif ($role === 'dosen' && $profileData)
                        <div class="space-y-4 md:col-span-2"> {{-- Make it full width --}}
                            <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                Data Dosen
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-user text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap Dosen</label>
                                            <p class="text-hitam">{{ $profileData->nama_dosen }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-identification-card text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">NIP</label>
                                            <p class="text-hitam font-mono">{{ $profileData->nip }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-phone text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">No. HP Dosen</label>
                                            <p class="text-hitam">{{ $profileData->no_hp }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-flag text-gray-500 text-lg"></i></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status Dosen</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($profileData->status == 'AKTIF') bg-green-100 text-green-800
                                                @elseif($profileData->status == 'CUTI') bg-yellow-100 text-yellow-800
                                                @elseif($profileData->status == 'PENSIUN') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $profileData->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-2 flex items-start gap-3">
                                    <div class="w-6 h-6 flex items-center justify-center mt-0.5"><i class="ph ph-map-pin text-gray-500 text-lg"></i></div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                        <p class="text-hitam">{{ $profileData->alamat }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="md:col-span-2">
                            <p class="text-gray-600">Tidak ada data profil spesifik (Mahasiswa/Dosen) yang dapat ditampilkan.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card Ubah Password --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200"> {{-- Slightly different header for this card --}}
                    <h3 class="text-lg font-semibold text-hitam">Ubah Password</h3>
                </div>
                <div class="p-6">
                    <p class="mb-4 text-sm text-gray-600">
                        Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.
                    </p>
                    <form method="post" action="{{ route('profile.password.update') }}" class="space-y-5 max-w-xl">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-biru-500 focus:border-biru-500 sm:text-sm @error('current_password', 'updatePassword') border-red-500 @enderror">
                            @error('current_password', 'updatePassword')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input id="password" name="password" type="password" autocomplete="new-password"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-biru-500 focus:border-biru-500 sm:text-sm @error('password', 'updatePassword') border-red-500 @enderror">
                            @error('password', 'updatePassword')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-biru-500 focus:border-biru-500 sm:text-sm @error('password_confirmation', 'updatePassword') border-red-500 @enderror">
                            @error('password_confirmation', 'updatePassword')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            {{-- Using your custom button component structure if available, or a styled button --}}
                             <x-button.submit type="submit" usage="primary" icon="ph ph-floppy-disk">
                                Simpan Password
                            </x-button.submit>

                            @if (session('status') === 'password-updated' && !$errors->updatePassword->any())
                                {{-- This message will be shown if toast doesn't pick it up or for immediate feedback --}}
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                                   class="text-sm text-green-600">
                                    Password berhasil diperbarui.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection