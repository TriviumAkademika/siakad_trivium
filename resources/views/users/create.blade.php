@extends('master')

@section('title', 'Tambah User')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah User</h2>

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
                                    Gagal menambahkan user karena:
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
                <form action="{{ route('users.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4 bg-white">
                    @csrf

                    {{-- Role --}}
                    <div>
                        <div class="flex w-full">
                            <label for="role" class="flex items-center w-1/4 text-base font-medium text-hitam">
                                Role
                                <span class="pl-1 text-error">*</span>
                            </label>
                            <select name="role" id="role"
                                class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                required>
                                <option value="">-- Pilih Role --</option>
                                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        @error('role')
                            <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <div class="flex w-full">
                            <label for="email" class="flex items-center w-1/4 text-base font-medium text-hitam">
                                Email
                                <span class="pl-1 text-error">*</span>
                            </label>
                            <div class="w-full">
                                <input type="email" name="email" id="email"
                                    class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                    value="{{ old('email') }}" placeholder="Masukkan alamat email" required>
                                <div id="email-hint" class="mt-1 text-xs text-gray-500">
                                    Pilih role terlebih dahulu untuk melihat format email
                                </div>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex w-full">
                            <label for="password" class="flex items-center w-1/4 text-base font-medium text-hitam">
                                Password
                                <span class="pl-1 text-error">*</span>
                            </label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                placeholder="Minimal 6 karakter" required>
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
                                <span class="pl-1 text-error">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                placeholder="Masukkan ulang password" required>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Admin Field --}}
                    <div id="admin-fields" class="{{ old('role') != 'admin' ? 'hidden' : '' }}">
                        <div>
                            <div class="flex w-full">
                                <label for="nama_user" class="flex items-center w-1/4 text-base font-medium text-hitam">
                                    Nama Admin
                                    <span class="pl-1 text-error">*</span>
                                </label>
                                <input type="text" name="nama_user" id="nama_user"
                                    class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                    value="{{ old('nama_user') }}" placeholder="Masukkan nama lengkap admin">
                            </div>
                            @error('nama_user')
                                <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Mahasiswa Field --}}
                    <div id="mahasiswa-fields" class="{{ old('role') != 'mahasiswa' ? 'hidden' : '' }}">
                        <div>
                            <div class="flex w-full">
                                <label for="id_mahasiswa"
                                    class="flex items-center w-1/4 text-base font-medium text-hitam">
                                    Pilih Mahasiswa
                                    <span class="pl-1 text-error">*</span>
                                </label>
                                <select name="id_mahasiswa" id="id_mahasiswa"
                                    class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal">
                                    <option value="">-- Pilih Mahasiswa --</option>
                                    @foreach ($mahasiswa as $m)
                                        <option value="{{ $m->id_mahasiswa }}"
                                            {{ old('id_mahasiswa') == $m->id_mahasiswa ? 'selected' : '' }}>
                                            {{ $m->nama }} - {{ $m->nrp }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('id_mahasiswa')
                                <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Dosen Field --}}
                    <div id="dosen-fields" class="{{ old('role') != 'dosen' ? 'hidden' : '' }}">
                        <div>
                            <div class="flex w-full">
                                <label for="id_dosen"
                                    class="flex items-center w-1/4 text-base font-medium text-hitam">
                                    Pilih Dosen
                                    <span class="pl-1 text-error">*</span>
                                </label>
                                <select name="id_dosen" id="id_dosen"
                                    class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal">
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach ($dosen as $d)
                                        <option value="{{ $d->id_dosen }}"
                                            {{ old('id_dosen') == $d->id_dosen ? 'selected' : '' }}>
                                            {{ $d->nama_dosen }} - {{ $d->nip }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('id_dosen')
                                <p class="mt-1 ml-[25%] text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/users';">
                            Batal
                        </x-button.cancel>
                        <x-button.submit icon="ph ph-floppy-disk">
                            Simpan
                        </x-button.submit>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk toggle field berdasarkan role dan validasi email --}}
    <script>
        const roleSelect = document.getElementById('role');
        const emailInput = document.getElementById('email');
        const emailHint = document.getElementById('email-hint');
        const mahasiswaFields = document.getElementById('mahasiswa-fields');
        const dosenFields = document.getElementById('dosen-fields');
        const adminFields = document.getElementById('admin-fields');

        function toggleFields(role) {
            // Hide all fields
            mahasiswaFields.classList.add('hidden');
            dosenFields.classList.add('hidden');
            adminFields.classList.add('hidden');

            // Show relevant field and update email hint
            if (role === 'mahasiswa') {
                mahasiswaFields.classList.remove('hidden');
                emailHint.textContent = 'Format: nama@student.trivium.ac.id';
                emailInput.placeholder = 'contoh: john.doe@student.trivium.ac.id';
            } else if (role === 'dosen') {
                dosenFields.classList.remove('hidden');
                emailHint.textContent = 'Format: nama@lecture.trivium.ac.id';
                emailInput.placeholder = 'contoh: john.doe@lecture.trivium.ac.id';
            } else if (role === 'admin') {
                adminFields.classList.remove('hidden');
                emailHint.textContent = 'Format: nama@trivium.ac.id';
                emailInput.placeholder = 'contoh: john.doe@trivium.ac.id';
            } else {
                emailHint.textContent = 'Pilih role terlebih dahulu untuk melihat format email';
                emailInput.placeholder = 'Masukkan alamat email';
            }

            // Clear email validation
            emailInput.setCustomValidity('');
        }

        function validateEmailDomain() {
            const role = roleSelect.value;
            const email = emailInput.value;
            
            if (!role || !email) return;

            let expectedDomain = '';
            switch(role) {
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

            if (!email.endsWith(expectedDomain)) {
                emailInput.setCustomValidity(`Email harus menggunakan domain ${expectedDomain}`);
            } else {
                emailInput.setCustomValidity('');
            }
        }

        // Event listeners
        roleSelect.addEventListener('change', function () {
            toggleFields(this.value);
            validateEmailDomain();
        });

        emailInput.addEventListener('input', validateEmailDomain);
        emailInput.addEventListener('blur', validateEmailDomain);

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', function () {
            toggleFields(roleSelect.value);
        });
    </script>
@endsection