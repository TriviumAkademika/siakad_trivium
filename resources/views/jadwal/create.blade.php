@extends('master')

@section('title', 'Tambah Jadwal')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            {{-- Header --}}
            {{-- @include('components.header') --}}

            {{-- Toast Notification --}}
            <x-notification.toast-notification />

            <div class="px-6 py-6">
                <h2 class="text-2xl text-hitam mb-6">Tambah Jadwal</h2>
                
                {{-- Form --}}
                <form action="{{ route('jadwal.store') }}" method="POST" id="jadwalForm"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf

                    {{-- General Error Messages --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="ph ph-warning-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Terdapat masalah dengan data yang dimasukkan:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Kelas --}}
                    <div class="space-y-2">
                        <x-form.dropdown-field label="Kelas" name="id_kelas" :options="$kelas" valueField="id_kelas"
                            :labelFields="['prodi', 'paralel']" />
                        @error('id_kelas')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Mata Kuliah --}}
                    <div class="space-y-2">
                        <x-form.dropdown-field label="Mata Kuliah" name="id_matkul" :options="$matkul" valueField="id_matkul"
                            :labelFields="['jenis', 'nama_matkul']" />
                        @error('id_matkul')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Dosen --}}
                    <div class="space-y-2">
                        <x-form.dropdown-field label="Dosen" name="id_dosen" :options="$dosen" valueField="id_dosen"
                            labelFields="nama_dosen" />
                        @error('id_dosen')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Dosen Pendamping (Opsional) --}}
                    <div class="space-y-2">
                        <x-form.dropdown-field label="Dosen Pendamping" name="id_dosen_2" :options="$dosen"
                            valueField="id_dosen" labelFields="nama_dosen" :required="false" />
                        @error('id_dosen_2')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-sm text-gray-500">
                            <i class="ph ph-info mr-1"></i>
                            Opsional. Pilih dosen pendamping jika diperlukan (harus berbeda dengan dosen utama).
                        </p>
                    </div>

                    {{-- Ruangan --}}
                    <div class="space-y-2">
                        <x-form.dropdown-field label="Ruangan" name="id_ruangan" :options="$ruangan" valueField="id_ruangan"
                            labelFields="kode_ruangan" />
                        @error('id_ruangan')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Waktu --}}
                    <div class="space-y-2">
                        <x-form.dropdown-field label="Waktu" name="id_waktu" :options="$waktu" valueField="id_waktu"
                            :labelFields="['hari', 'jam_mulai', 'jam_selesai']" />
                        @error('id_waktu')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Validation Rules Info --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ph ph-info text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Aturan Penjadwalan:
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Satu ruangan tidak dapat digunakan pada waktu yang sama</li>
                                        <li>Satu dosen tidak dapat mengajar pada waktu yang sama</li>
                                        <li>Satu kelas tidak dapat memiliki mata kuliah yang sama lebih dari sekali</li>
                                        <li>Satu kelas tidak dapat memiliki jadwal pada waktu yang sama</li>
                                        <li>Dosen utama dan dosen pendamping harus berbeda</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Button Simpan --}}
                    <div class="flex justify-end gap-x-2 pt-4">
                        <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='{{ route('jadwal.index') }}';">
                            Batal
                        </x-button.cancel>
                        <x-button.submit icon="ph ph-floppy-disk" id="submitBtn">
                            Simpan
                        </x-button.submit>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk validasi real-time --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('jadwalForm');
            const dosenSelect = document.querySelector('select[name="id_dosen"]');
            const dosen2Select = document.querySelector('select[name="id_dosen_2"]');
            const kelasSelect = document.querySelector('select[name="id_kelas"]');
            const matkulSelect = document.querySelector('select[name="id_matkul"]');
            const ruanganSelect = document.querySelector('select[name="id_ruangan"]');
            const waktuSelect = document.querySelector('select[name="id_waktu"]');
            const submitBtn = document.getElementById('submitBtn');

            // Function to show/hide warning messages
            function showWarning(element, message) {
                // Remove existing warning
                const existingWarning = element.parentNode.querySelector('.warning-message');
                if (existingWarning) {
                    existingWarning.remove();
                }

                // Add new warning
                const warningDiv = document.createElement('div');
                warningDiv.className = 'warning-message text-sm text-yellow-600 flex items-center mt-1';
                warningDiv.innerHTML = `<i class="ph ph-warning mr-1"></i>${message}`;
                element.parentNode.appendChild(warningDiv);
            }

            function removeWarning(element) {
                const existingWarning = element.parentNode.querySelector('.warning-message');
                if (existingWarning) {
                    existingWarning.remove();
                }
            }

            // Validate dosen selection
            function validateDosen() {
                if (dosenSelect && dosen2Select) {
                    const dosen1Value = dosenSelect.value;
                    const dosen2Value = dosen2Select.value;

                    if (dosen1Value && dosen2Value && dosen1Value === dosen2Value) {
                        showWarning(dosen2Select, 'Dosen pendamping harus berbeda dengan dosen utama');
                        return false;
                    } else {
                        removeWarning(dosen2Select);
                        return true;
                    }
                }
                return true;
            }

            // Add event listeners
            if (dosenSelect) {
                dosenSelect.addEventListener('change', validateDosen);
            }

            if (dosen2Select) {
                dosen2Select.addEventListener('change', validateDosen);
            }

            // Form validation before submit
            if (form) {
                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Validate dosen selection
                    if (!validateDosen()) {
                        isValid = false;
                    }

                    // Check if required fields are filled
                    const requiredFields = [kelasSelect, matkulSelect, dosenSelect, ruanganSelect, waktuSelect];
                    requiredFields.forEach(field => {
                        if (field && !field.value) {
                            showWarning(field, 'Field ini wajib diisi');
                            isValid = false;
                        } else if (field) {
                            removeWarning(field);
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        // Scroll to first error
                        const firstError = document.querySelector('.warning-message');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } else {
                        // Disable submit button to prevent double submission
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="ph ph-spinner animate-spin mr-2"></i>Menyimpan...';
                        }
                    }
                });
            }

            // Auto-fill helpers (optional enhancement)
            // You can add logic here to help users by showing related information
            // For example, showing available time slots when room is selected
        });
    </script>
@endsection