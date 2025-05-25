@extends('master')

@section('title', 'Edit Profile')

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
            <div class="flex flex-row px-6 pb-6 space-x-6">
                <div class="flex flex-col grow items-end space-y-4">

                    {{-- Judul --}}
                    <div class="flex justify-between items-center w-full">
                        <h1 class="text-xl">Edit Profile</h1>
                        <a href="{{ route('profile.index') }}">
                            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                                Kembali
                            </button>
                        </a>
                    </div>

                    {{-- Form Card --}}
                    <div class="w-full bg-putih shadow rounded-lg overflow-hidden">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Header Card --}}
                            <div class="bg-brand-100 px-6 py-4">
                                <h2 class="text-lg font-medium text-hitam">Edit Informasi Profile</h2>
                            </div>

                            {{-- Content Card --}}
                            <div class="px-6 py-4 space-y-6">

                                {{-- Basic Account Info --}}
                                <div>
                                    <h3 class="text-md font-medium text-gray-900 mb-3">Informasi Akun</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                            <input type="email" id="email" name="email" 
                                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                   value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                            <input type="password" id="password" name="password" 
                                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                   placeholder="Kosongkan jika tidak ingin mengubah">
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                   placeholder="Kosongkan jika tidak mengubah password">
                                        </div>
                                    </div>
                                </div>

                                {{-- Role Specific Fields --}}
                                @if($user->getRoleNames()->first() === 'admin')
                                    <hr>
                                    <div>
                                        <h3 class="text-md font-medium text-gray-900 mb-3">Data Admin</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="name" class="block text-sm font-medium text-gray-700">Nama *</label>
                                                <input type="text" id="name" name="name" 
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                       value="{{ old('name', $user->name) }}" required>
                                                @error('name')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                @elseif($user->getRoleNames()->first() === 'mahasiswa' && $user->mahasiswa)
                                    <hr>
                                    <div>
                                        <h3 class="text-md font-medium text-gray-900 mb-3">Data Mahasiswa</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama *</label>
                                                <input type="text" id="nama" name="nama" 
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                       value="{{ old('nama', $user->mahasiswa->nama) }}" required>
                                                @error('nama')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="nrp" class="block text-sm font-medium text-gray-700">NRP *</label>
                                                <input type="text" id="nrp" name="nrp" 
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                       value="{{ old('nrp', $user->mahasiswa->nrp) }}" required>
                                                @error('nrp')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="id_kelas" class="block text-sm font-medium text-gray-700">Kelas *</label>
                                                <select id="id_kelas" name="id_kelas" 
                                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500" required>
                                                    <option value="">Pilih Kelas</option>
                                                    @foreach($kelas as $k)
                                                        <option value="{{ $k->id_kelas }}" 
                                                                {{ old('id_kelas', $user->mahasiswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                                            {{ $k->nama_kelas }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_kelas')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="semester" class="block text-sm font-medium text-gray-700">Semester *</label>
                                                <input type="text" id="semester" name="semester" 
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                       value="{{ old('semester', $user->mahasiswa->semester) }}" required>
                                                @error('semester')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender *</label>
                                                <select id="gender" name="gender" 
                                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500" required>
                                                    <option value="">Pilih Gender</option>
                                                    <option value="L" {{ old('gender', $user->mahasiswa->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="P" {{ old('gender', $user->mahasiswa->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                                @error('gender')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP *</label>
                                                <input type="text" id="no_hp" name="no_hp" 
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                       value="{{ old('no_hp', $user->mahasiswa->no_hp) }}" required>
                                                @error('no_hp')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="md:col-span-2">
                                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat *</label>
                                                <textarea id="alamat" name="alamat" rows="3" 
                                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500" required>{{ old('alamat', $user->mahasiswa->alamat) }}</textarea>
                                                @error('alamat')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                @elseif($user->getRoleNames()->first() === 'dosen' && $user->dosen)
                                    <hr>
                                    <div>
                                        <h3 class="text-md font-medium text-gray-900 mb-3">Data Dosen</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="nama_dosen" class="block text-sm font-medium text-gray-700">Nama Dosen *</label>
                                                <input type="text" id="nama_dosen" name="nama_dosen" 
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                       value="{{ old('nama_dosen', $user->dosen->nama_dosen) }}" required>
                                                @error('nama_dosen')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="nip" class="block text-sm font-medium text-gray-700">NIP *</label>
                                                <input type="text" id="nip" name="nip" 
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                       value="{{ old('nip', $user->dosen->nip) }}" required>
                                                @error('nip')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                                <select id="status" name="status" 
                                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500" required>
                                                    <option value="">Pilih Status</option>
                                                    <option value="AKTIF" {{ old('status', $user->dosen->status) == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                                                    <option value="CUTI" {{ old('status', $user->dosen->status) == 'CUTI' ? 'selected' : '' }}>CUTI</option>
                                                    <option value="PENSIUN" {{ old('status', $user->dosen->status) == 'PENSIUN' ? 'selected' : '' }}>PENSIUN</option>
                                                    <option value="TIDAK AKTIF" {{ old('status', $user->dosen->status) == 'TIDAK AKTIF' ? 'selected' : '' }}>TIDAK AKTIF</option>
                                                </select>
                                                @error('status')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="no_hp_dosen" class="block text-sm font-medium text-gray-700">No. HP *</label>
                                                <input type="text" id="no_hp_dosen" name="no_hp" 
                                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500"
                                                       value="{{ old('no_hp', $user->dosen->no_hp) }}" required>
                                                @error('no_hp')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="md:col-span-2">
                                                <label for="alamat_dosen" class="block text-sm font-medium text-gray-700">Alamat *</label>
                                                <textarea id="alamat_dosen" name="alamat" rows="3" 
                                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500" required>{{ old('alamat', $user->dosen->alamat) }}</textarea>
                                                @error('alamat')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Submit Button --}}
                                <div class="flex justify-end pt-4 space-x-3">
                                    <a href="{{ route('profile.index') }}">
                                        <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                                            Batal
                                        </button>
                                    </a>
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-500 rounded-lg hover:bg-brand-600">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection