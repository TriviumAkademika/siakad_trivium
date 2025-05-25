@extends('master')

@section('title', 'User')

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

                    {{-- Judul dan Tombol Tambah users --}}
                    <div class="flex justify-between items-center w-full">
                        <h1 class="text-xl  ">Data User</h1>
                        <a href="{{ route('users.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah User
                            </x-button.submit>
                        </a>
                    </div>

                    {{-- Tabel Data User --}}
                    <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Email</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Role</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama User</th>
                            </tr>
                        </thead>

                        <tbody class="bg-putih divide-y divide-gray-200">
                            @foreach ($users as $index => $user)
                                <tr>
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $user->email }}</x-table.table-td>
                                    <x-table.table-td class="text-center">
                                        {{ $user->getRoleNames()->first() ?? '-' }}
                                    </x-table.table-td>
                                    <x-table.table-td class="text-center">
                                        @if ($user->role === 'mahasiswa')
                                            {{ $user->mahasiswa->nama ?? '-' }} ({{ $user->mahasiswa->nrp ?? '-' }})
                                        @elseif ($user->role === 'dosen')
                                            {{ $user->dosen->nama_dosen ?? '-' }}
                                        @elseif ($user->role === 'admin')
                                            {{ $user->name ?? '-' }}
                                        @else
                                            -
                                        @endif
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
