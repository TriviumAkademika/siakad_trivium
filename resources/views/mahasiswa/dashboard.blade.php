@extends('master')

@section('title', 'Dashboard')

@section('content')
    <div class="flex w-full h-full">
        {{-- Sidebar --}}
        @include('components.sidebar')
        {{-- Main --}}
        <div class="flex flex-col w-full bg-amber-200">
            {{-- Profil User di Header --}}
            @include('components.header')
            {{-- Content --}}
            <div class="p-6 bg-blue-50">
                <div class="flex flex-row w-2/3 space-x-4 bg-amber-300">
                    <div class="flex flex-col w-1/3 p-4 bg-biru rounded-3xl">
                        <h4 class="text-base text-putih">Status</h4>
                        <h1 class="text-3xl text-putih font-medium">Aktif</h1>
                    </div>
                    <div class="flex flex-col w-1/3 p-4 bg-biru rounded-3xl">
                        <h4 class="text-base text-putih">IP Kumulatif</h4>
                        <h1 class="text-3xl text-putih font-medium">3.76/4.00</h1>
                    </div>
                    <div class="flex flex-col w-1/3 p-4 bg-biru rounded-3xl">
                        <h4 class="text-base text-putih">SKS</h4>
                        <h1 class="text-3xl text-putih font-medium">98/108</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection