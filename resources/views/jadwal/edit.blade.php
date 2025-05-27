@extends('master')

@section('title', 'Edit Jadwal')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        <div class="flex flex-col w-full bg-putih">
            <h2 class="p-6 text-2xl text-hitam">Edit Jadwal</h2>

            {{-- Toast Notification --}}
            <x-notification.toast-notification />

            <div class="px-6 pb-6">               
                {{-- Current Schedule Info --}}
                {{-- <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Jadwal Saat Ini:</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Kelas:</span>
                            <span class="font-medium ml-1">{{ $jadwal->kelas->prodi }}-{{ $jadwal->kelas->paralel }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Mata Kuliah:</span>
                            <span class="font-medium ml-1">{{ $jadwal->matkul->jenis }} - {{ $jadwal->matkul->nama_matkul }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Dosen:</span>
                            <span class="font-medium ml-1">{{ $jadwal->dosen->nama_dosen }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Waktu:</span>
                            <span class="font-medium ml-1">{{ $jadwal->waktu->hari }}, {{ substr($jadwal->waktu->jam_mulai, 0, 5) }}-{{ substr($jadwal->waktu->jam_selesai, 0, 5) }}</span>
                        </div>
                    </div>
                </div> --}}
                
                {{-- Form --}}
                <form action="{{ route('jadwal.update', $jadwal->id_jadwal) }}" method="POST" id="jadwalEditForm"
                    class="px-6 pt-3 pb-6 border rounded-lg shadow space-y-4">
                    @csrf
                    @method('PUT')

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
                            :labelFields="['prodi', 'paralel']" :selected="$jadwal->id_kelas" />
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
                            :labelFields="['jenis', 'nama_matkul']" :selected="$jadwal->id_matkul" />
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
                            labelFields="nama_dosen" :selected="$jadwal->id_dosen" />
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
                            valueField="id_dosen" labelFields="nama_dosen" :required="false" :selected="$jadwal->id_dosen_2" />
                        @error('id_dosen_2')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-sm text-gray-500">
                            <i class="ph ph-info mr-1"></i>
                            Opsional. Pilih dosen pendamping jika diperlukan (harus berbeda dengan dosen utama).
                            @if($jadwal->id_dosen_2)
                                <span class="text-blue-600">Saat ini: {{ $jadwal->dosen2->nama_dosen }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- Ruangan --}}
                    <div class="space-y-2">
                        <x-form.dropdown-field label="Ruangan" name="id_ruangan" :options="$ruangan" valueField="id_ruangan"
                            labelFields="kode_ruangan" :selected="$jadwal->id_ruangan" />
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
                            :labelFields="['hari', 'jam_mulai', 'jam_selesai']" :selected="$jadwal->id_waktu" />
                        @error('id_waktu')
                            <p class="text-sm text-red-600 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Change Detection Info --}}
                    <div id="changesDetected" class="hidden bg-amber-50 border border-amber-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ph ph-warning text-amber-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-amber-800">
                                    Perubahan Terdeteksi
                                </h3>
                                <div class="mt-2 text-sm text-amber-700">
                                    <p>Anda telah mengubah beberapa data. Pastikan perubahan sudah sesuai sebelum menyimpan.</p>
                                </div>
                            </div>
                        </div>
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

                    {{-- Button Actions --}}
                    <div class="flex justify-between items-center pt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="ph ph-clock mr-1"></i>
                            <span>Terakhir diupdate: {{ $jadwal->updated_at ? $jadwal->updated_at->format('d M Y, H:i') : 'Belum pernah' }}</span>
                        </div>
                        <div class="flex gap-x-2">
                            <x-button.cancel icon="ph ph-x" onConfirm="window.location.href='{{ route('jadwal.index') }}';">
                                Batal
                            </x-button.cancel>
                            <button type="button" id="resetBtn" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <i class="ph ph-arrow-counter-clockwise mr-2"></i>
                                Reset
                            </button>
                            <x-button.submit icon="ph ph-floppy-disk" id="updateBtn">
                                Update
                            </x-button.submit>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk validasi dan change detection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('jadwalEditForm');
            const dosenSelect = document.querySelector('select[name="id_dosen"]');
            const dosen2Select = document.querySelector('select[name="id_dosen_2"]');
            const kelasSelect = document.querySelector('select[name="id_kelas"]');
            const matkulSelect = document.querySelector('select[name="id_matkul"]');
            const ruanganSelect = document.querySelector('select[name="id_ruangan"]');
            const waktuSelect = document.querySelector('select[name="id_waktu"]');
            const updateBtn = document.getElementById('updateBtn');
            const resetBtn = document.getElementById('resetBtn');
            const changesDetectedDiv = document.getElementById('changesDetected');

            // Store original values
            const originalValues = {
                id_kelas: '{{ $jadwal->id_kelas }}',
                id_matkul: '{{ $jadwal->id_matkul }}',
                id_dosen: '{{ $jadwal->id_dosen }}',
                id_dosen_2: '{{ $jadwal->id_dosen_2 ?? "" }}',
                id_ruangan: '{{ $jadwal->id_ruangan }}',
                id_waktu: '{{ $jadwal->id_waktu }}'
            };

            // Function to show/hide warning messages
            function showWarning(element, message) {
                const existingWarning = element.parentNode.querySelector('.warning-message');
                if (existingWarning) {
                    existingWarning.remove();
                }

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

            // Check for changes
            function detectChanges() {
                const currentValues = {
                    id_kelas: kelasSelect?.value || '',
                    id_matkul: matkulSelect?.value || '',
                    id_dosen: dosenSelect?.value || '',
                    id_dosen_2: dosen2Select?.value || '',
                    id_ruangan: ruanganSelect?.value || '',
                    id_waktu: waktuSelect?.value || ''
                };

                const hasChanges = Object.keys(originalValues).some(key => 
                    originalValues[key] !== currentValues[key]
                );

                if (hasChanges) {
                    changesDetectedDiv?.classList.remove('hidden');
                } else {
                    changesDetectedDiv?.classList.add('hidden');
                }

                return hasChanges;
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

            // Add event listeners for all selects
            const allSelects = [kelasSelect, matkulSelect, dosenSelect, dosen2Select, ruanganSelect, waktuSelect];
            allSelects.forEach(select => {
                if (select) {
                    select.addEventListener('change', function() {
                        detectChanges();
                        if (select === dosenSelect || select === dosen2Select) {
                            validateDosen();
                        }
                    });
                }
            });

            // Reset button functionality
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    if (confirm('Yakin ingin mereset semua perubahan ke nilai awal?')) {
                        // Reset all selects to original values
                        Object.keys(originalValues).forEach(key => {
                            const select = document.querySelector(`select[name="${key}"]`);
                            if (select) {
                                select.value = originalValues[key];
                            }
                        });
                        
                        // Hide changes detection and warnings
                        changesDetectedDiv?.classList.add('hidden');
                        document.querySelectorAll('.warning-message').forEach(warning => {
                            warning.remove();
                        });
                    }
                });
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
                        const firstError = document.querySelector('.warning-message');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } else {
                        // Show confirmation if there are changes
                        if (detectChanges()) {
                            if (!confirm('Yakin ingin menyimpan perubahan jadwal ini?')) {
                                e.preventDefault();
                                return;
                            }
                        }

                        // Disable submit button to prevent double submission
                        if (updateBtn) {
                            updateBtn.disabled = true;
                            updateBtn.innerHTML = '<i class="ph ph-spinner animate-spin mr-2"></i>Mengupdate...';
                        }
                    }
                });
            }

            // Initial change detection
            detectChanges();
        });
    </script>
@endsection