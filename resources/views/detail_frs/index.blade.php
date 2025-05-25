@extends('master')

@section('title', 'Detail FRS')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-putih">
            {{-- Profil User di Header --}}
            @include('components.header')

            {{-- Content --}}
            <div class="flex flex-row px-6 pb-6 space-x-6">
                <div class="flex flex-col grow items-end space-y-4">
                    {{-- Informasi Mahasiswa --}}
                    <section class="flex grow w-full gap-x-8 rounded-lg sm:grid-cols-3">
                        <x-form.card-information label="Nama" :value="$frs->mahasiswa->nama" />
                        <x-form.card-information label="Semester" :value="$frs->semester" />
                        <x-form.card-information label="Tahun Ajaran" :value="$frs->tahun_ajaran" />
                    </section>

                    {{-- Modal FRS Component --}}
                    <x-modal.frs :frs="$frs" :jadwals="$jadwals" :formAction="route('detail-frs.store')" />

                    {{-- Tabel Data Detail FRS --}}
                    <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-hitam">#</th>
                                <th class="w-48 px-4 py-3 text-sm font-semibold text-center text-hitam">Matkul</th>
                                <th class="w-40 px-4 py-3 text-sm font-semibold text-center text-hitam">Dosen</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Ruangan</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Waktu</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Status</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-putih divide-y divide-gray-100">
                            @foreach ($frs->detailFrs as $index => $detail)
                                <tr class="hover:bg-gray-100">
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $detail->jadwal->matkul->nama_matkul }}</x-table.table-td>
                                    <x-table.table-td>{{ $detail->jadwal->dosen->nama_dosen }}</x-table.table-td>
                                    <x-table.table-td
                                        class="text-center">{{ $detail->jadwal->ruangan->kode_ruangan }}</x-table.table-td>
                                    <x-table.table-td class="text-center">
                                        <p>{{ $detail->jadwal->waktu->hari }}</p>
                                        <p>{{ substr($detail->jadwal->waktu->jam_mulai, 0, 5) }} -
                                            {{ substr($detail->jadwal->waktu->jam_selesai, 0, 5) }}</p>
                                    </x-table.table-td>
                                    {{-- Status Dropdown Component --}}
                                    <x-table.table-td class="text-center">
                                        <x-form.status-frs :detailId="$detail->id_detail_frs" :currentStatus="$detail->status" />
                                    </x-table.table-td>
                                    {{-- Button Delete --}}
                                    <x-table.table-td class="text-center">
                                        <x-button.delete :deleteId="$detail->id_detail_frs" :deleteRoute="route('detail-frs.destroy', $detail->id_detail_frs)" :itemName="$detail->jadwal->matkul->nama_matkul"
                                            title="Hapus Jadwal dari FRS?" />
                                    </x-table.table-td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
