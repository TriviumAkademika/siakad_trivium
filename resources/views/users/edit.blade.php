@extends('master')

@section('title', 'Edit User')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit User</h2>

            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="ph ph-warning-circle text-red-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Gagal mengupdate user karena:
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="ph ph-check-circle text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Error Message --}}
                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="ph ph-warning-circle text-red-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('users.update', $user->id_user) }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4 bg-white">
                    @csrf
                    @method('PUT')

                    {{-- Role (Read Only) --}}
                    <div>
                        <div class="flex w-full">
                            <label class="flex items-center w-1/4 text-base font-medium text-hitam">
                                Role
                            </label>
                            <div class="w-full px-4 py-2 bg-gray-50 border-abu rounded-lg font-normal text-gray-600">
                                {{ ucfirst($user->getRoleNames()->first()) }}
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <div class="flex w-full items-start">
                            <label for="email" class="flex items-center w-1/4 text-base font-medium text-hitam">
                                Email
                                <span class="pl-1 text-error">*</span>
                            </label>
                            <div class="flex flex-col w-full gap-y-1">
                                <input type="email" name="email" id="email"
                                    class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                    value="{{ old('email', $user->email) }}" placeholder="Masukkan alamat email" required>
                                {{-- Validasi email akan dilakukan berdasarkan role pengguna. --}}
                                <div class="mt-1 text-xs text-gray-500">
                                    @php
                                        $currentRole = $user->getRoleNames()->first();
                                    @endphp
                                    @if ($currentRole === 'admin')
                                        Format: nama@trivium.ac.id
                                    @elseif($currentRole === 'dosen')
                                        Format: nama@lecture.trivium.ac.id
                                    @elseif($currentRole === 'mahasiswa')
                                        Format: nama@student.trivium.ac.id
                                    @endif
                                </div>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex w-full items-start">
                            <label for="password" class="flex items-center w-1/4 text-base font-medium text-hitam">
                                Password Baru
                            </label>
                            <div class="flex flex-col w-full gap-y-1">
                                <input type="password" name="password" id="password"
                                    class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                    placeholder="Password baru">
                                {{-- Validasi minimal 6 karakter dan kosongkan jika tidak ingin mengubah --}}
                                <p class="text-xs text-gray-500">Minimal 6 karakter (Kosongkan jika tidak ingin
                                    mengubah)</p>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <div class="flex w-full">
                            <label for="password_confirmation"
                                class="flex items-center w-1/4 text-base font-medium text-hitam">
                                Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                placeholder="Masukkan ulang password baru">
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Admin Field --}}
                    @if ($user->getRoleNames()->first() === 'admin')
                        <div>
                            <div class="flex w-full">
                                <label for="nama_user" class="flex items-center w-1/4 text-base font-medium text-hitam">
                                    Nama Admin
                                    <span class="pl-1 text-error">*</span>
                                </label>
                                <input type="text" name="nama_user" id="nama_user"
                                    class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                    value="{{ old('nama_user', $user->name) }}" placeholder="Masukkan nama lengkap admin"
                                    required>
                            </div>
                            @error('nama_user')
                                <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    {{-- Mahasiswa Field (Read Only) --}}
                    @if ($user->getRoleNames()->first() === 'mahasiswa' && $user->mahasiswa)
                        <div>
                            <div class="flex w-full">
                                <label class="flex items-center w-1/4 text-base font-medium text-hitam">
                                    Mahasiswa
                                </label>
                                <div class="w-full px-4 py-2 bg-gray-50 border-abu rounded-lg font-normal text-gray-600">
                                    {{ $user->mahasiswa->nama }} - {{ $user->mahasiswa->nrp }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Dosen Field (Read Only) --}}
                    @if ($user->getRoleNames()->first() === 'dosen' && $user->dosen)
                        <div>
                            <div class="flex w-full">
                                <label class="flex items-center w-1/4 text-base font-medium text-hitam">
                                    Dosen
                                </label>
                                <div class="w-full px-4 py-2 bg-gray-50 border-abu rounded-lg font-normal text-gray-600">
                                    {{ $user->dosen->nama_dosen }} - {{ $user->dosen->nip }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Button Update --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/users';">
                            Batal
                        </x-button.cancel>
                        <x-button.submit icon="ph ph-floppy-disk">
                            Update
                        </x-button.submit>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk validasi email domain --}}
    <script>
        const emailInput = document.getElementById('email');
        const currentRole = '{{ $user->getRoleNames()->first() }}';

        function validateEmailDomain() {
            const email = emailInput.value;
            let expectedDomain = '';

            switch (currentRole) {
                case 'admin':
                    expectedDomain = '@trivium.ac.id';
                    break;
                case 'dosen':
                    expectedDomain = '@lecture.trivium.ac.id';
                    break;
                case 'mahasiswa':
                    expectedDomain = '@student.trivium.ac.id';
                    break;
            }

            if (email && !email.endsWith(expectedDomain)) {
                emailInput.setCustomValidity(`Email harus menggunakan domain ${expectedDomain}`);
            } else {
                emailInput.setCustomValidity('');
            }
        }

        emailInput.addEventListener('input', validateEmailDomain);
        emailInput.addEventListener('blur', validateEmailDomain);
    </script>
@endsection
