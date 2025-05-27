@extends('master')

@section('title', 'Tambah Dosen')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah Dosen</h2>
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
                                    Gagal menambahkan dosen karena:
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
                <form action="{{ route('dosen.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- Nama Dosen --}}
                    <div>
                        <x-form.text-field label="Nama Dosen" name="nama_dosen" value="{{ old('nama_dosen') }}" placeholder="Masukkan nama lengkap tanpa gelar" />
                        @error('nama_dosen')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIP --}}
                    <div>
                        <x-form.text-number label="NIP" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 198501012010011001" />
                        @error('nip')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <x-form.textarea-field label="Alamat" name="alamat" value="{{ old('alamat') }}" placeholder="Masukkan alamat lengkap" />
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <x-form.text-number label="No HP" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" />
                        @error('no_hp')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <x-form.dropdown-field label="Status" name="status" 
                            :options="[
                                ['value' => 'AKTIF', 'label' => 'Aktif'],
                                ['value' => 'CUTI', 'label' => 'Cuti'],
                                ['value' => 'PENSIUN', 'label' => 'Pensiun'],
                                ['value' => 'NONAKTIF', 'label' => 'Non Aktif']
                            ]" 
                            valueField="value" 
                            labelFields="label" 
                            selected="{{ old('status') }}" />
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='/dosen';">
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
@endsection