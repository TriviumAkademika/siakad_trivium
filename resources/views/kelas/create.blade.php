@extends('master')

@section('title', 'Tambah Kelas')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Tambah Kelas</h2>
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
                                    Gagal menambahkan kelas karena:
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

                {{-- Error Message from Session --}}
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
                <form action="{{ route('kelas.store') }}" method="POST"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- Program Studi --}}
                    <div>
                        <x-form.text-field label="Program Studi" name="prodi" value="{{ old('prodi') }}" 
                            placeholder="Contoh: Teknik Informatika, Sistem Informasi" />
                        @error('prodi')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tahun Masuk --}}
                    <div>
                        <x-form.text-number label="Tahun Masuk" name="tahun_masuk" value="{{ old('tahun_masuk') }}" 
                            placeholder="{{ date('Y') }}" />
                        @error('tahun_masuk')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Paralel --}}
                    <div>
                        <x-form.text-field label="Paralel" name="paralel" value="{{ old('paralel') }}" 
                            placeholder="A, B, C, D, dll (huruf kapital)" />
                        @error('paralel')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Wali Kelas --}}
                    <div>
                        <x-form.dropdown-field label="Wali Kelas" name="id_dosen" :options="$dosen" 
                            valueField="id_dosen" labelFields="nama_dosen" selected="{{ old('id_dosen') }}" />
                        @error('id_dosen')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <x-form.dropdown-field label="Status" name="status" 
                            :options="[
                                ['value' => 'AKTIF', 'label' => 'Aktif'],
                                ['value' => 'LULUS', 'label' => 'Lulus']
                            ]" 
                            valueField="value" labelFields="label" selected="{{ old('status', 'AKTIF') }}" />
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-1">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='{{ route('kelas.index') }}';">
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