@props([
    'frs',
    'jadwals',
    'formAction',
])

<div x-data="{ open: false }">
    {{-- Button Trigger Modal --}}
    <x-button.submit @click="open = true" icon="ph ph-plus">
        Tambah FRS
    </x-button.submit>
    
    {{-- Modal --}}
    <div x-show="open"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        style="display: none" @click.away="open = false">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 space-y-4 relative">
            {{-- Button Tutup --}}
            <button @click="open = false"
                class="absolute top-4 right-4 text-hitam hover:text-merah-500 text-xl">
                <i class="ph ph-x"></i>
            </button>
            
            {{-- Judul Modal --}}
            <h3 class="text-xl font-semibold">Pilih Jadwal</h3>
            
            {{-- Form --}}
            <form method="POST" id="frsForm" class="space-y-4" action="{{ route('detail-frs.store') }}">
                @csrf
                <input type="hidden" name="id_frs" value="{{ $frs->id_frs }}">
                
                {{-- Tabel Jadwal --}}
                <div class="overflow-y-auto max-h-[60vh] border rounded">
                    <table class="w-full text-sm text-left divide-hitam">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Pilih</th>
                                <th class="w-56 px-4 py-3 text-sm font-semibold text-center text-hitam">Matkul</th>
                                <th class="w-48 px-4 py-3 text-sm font-semibold text-center text-hitam">Dosen</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Hari</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Jam</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Ruangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-putih divide-y divide-gray-200">
                            @foreach ($jadwals as $jadwal)
                                <tr class="hover:bg-gray-100 border-t">
                                    <x-table.table-td class="text-center">
                                        <input type="checkbox" name="jadwal_ids[]" value="{{ $jadwal->id_jadwal }}">
                                    </x-table.table-td>
                                    <x-table.table-td>{{ $jadwal->matkul->nama_matkul }}</x-table.table-td>
                                    <x-table.table-td>{{ $jadwal->dosen->nama_dosen }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $jadwal->waktu->hari }}</x-table.table-td>
                                    <x-table.table-td class="text-center">
                                        <p>{{ substr($jadwal->waktu->jam_mulai, 0, 5) }} - {{ substr($jadwal->waktu->jam_selesai, 0, 5) }}</p>
                                    </x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $jadwal->ruangan->kode_ruangan }}</x-table.table-td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Button Actions --}}
                <div class="flex justify-end gap-x-2">
                    <x-button.cancel 
                        showAlert="false" 
                        @click="open = false"
                        title="Batalkan Penambahan"
                        text="Apakah Anda yakin ingin membatalkan penambahan jadwal?"
                        confirmText="Ya, Batalkan"
                        cancelText="Tidak">
                        Batal
                    </x-button.cancel>

                    <x-button.add formId="frsForm" checkboxName="jadwal_ids[]">
                        Tambahkan FRS
                    </x-button.add>
                </div>
            </form>
        </div>
    </div>
</div>