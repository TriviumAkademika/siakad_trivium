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
                {{-- Form --}}
                <form action="{{ route('users.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4 bg-white">
                    @csrf

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="p-4 bg-red-100 text-red-700 rounded">
                            <strong>Ada kesalahan:</strong>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Email --}}
                    <div class="flex w-full">
                        <label for="email" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Email
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                            value="{{ old('email') }}" required>
                    </div>

                    {{-- Password --}}
                    <div class="flex w-full">
                        <label for="password" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Password
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                            required>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="flex w-full">
                        <label for="password_confirmation"
                            class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Konfirmasi Password
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                            required>
                    </div>

                    {{-- Role --}}
                    <div class="flex w-full">
                        <label for="role" class="flex items-center w-1/4 text-base font-medium text-hitam">
                            Role
                            <span class="pl-1 text-error">*</span>
                        </label>
                        <select name="role" id="role"
                            class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                            required>
                            <option value="">-- Pilih --</option>
                            <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    {{-- Admin Field --}}
                    <div id="admin-fields" class="{{ old('role') != 'admin' ? 'hidden' : '' }}">
                        <div class="flex w-full">
                            <label for="nama_user" class="flex items-center w-1/4 text-base font-medium text-hitam">
                                Nama Admin
                                <span class="pl-1 text-error">*</span>
                            </label>
                            <input type="text" name="nama_user" id="nama_user"
                                class="w-full px-4 py-2 border-abu rounded-lg focus:outline-none focus:ring-1 focus:ring-biru-700 font-normal"
                                value="{{ old('nama_user') }}">
                        </div>
                    </div>

                    {{-- Mahasiswa Field --}}
                    <div id="mahasiswa-fields" class="{{ old('role') != 'mahasiswa' ? 'hidden' : '' }}">
                        <div class="flex w-full">
                            <label for="id_mahasiswa"
                                class="flex items-center w-1/4 text-base font-medium text-hitam">Pilih Mahasiswa</label>
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
                    </div>

                    {{-- Dosen Field --}}
                    <div id="dosen-fields" class="{{ old('role') != 'dosen' ? 'hidden' : '' }}">
                        <div class="flex w-full">
                            <label for="id_dosen"
                                class="flex items-center w-1/4 text-base font-medium text-hitam">Pilih Dosen</label>
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
                    </div>

                    {{-- Button Simpan --}}
                    <div class="flex justify-end">
                        <x-button.submit icon="ph ph-floppy-disk">
                            Simpan
                        </x-button.submit>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk toggle field berdasarkan role --}}
    <script>
        const roleSelect = document.getElementById('role');
        const mahasiswaFields = document.getElementById('mahasiswa-fields');
        const dosenFields = document.getElementById('dosen-fields');
        const adminFields = document.getElementById('admin-fields');

        function toggleFields(role) {
            mahasiswaFields.classList.add('hidden');
            dosenFields.classList.add('hidden');
            adminFields.classList.add('hidden');

            if (role === 'mahasiswa') {
                mahasiswaFields.classList.remove('hidden');
            } else if (role === 'dosen') {
                dosenFields.classList.remove('hidden');
            } else if (role === 'admin') {
                adminFields.classList.remove('hidden');
            }
        }

        roleSelect.addEventListener('change', function () {
            toggleFields(this.value);
        });

        window.addEventListener('DOMContentLoaded', function () {
            toggleFields(roleSelect.value);
        });
    </script>
@endsection
