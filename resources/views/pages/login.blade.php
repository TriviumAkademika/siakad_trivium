@extends('master')

@section('title', 'Login')

@section('content')
    <div class="flex flex-col md:flex-row w-full min-h-screen">
        {{-- Cover Login --}}
        <div class="hidden md:block md:w-3/5">
            <img src="{{ asset('assets/images/login-cover.png') }}" alt="Login"
                class="w-full h-full object-cover object-center">
        </div>
        {{-- Logo Trivium Akademika --}}
        <div class="w-full md:w-2/5 bg-putih">
            <div class="w-full px-8 pt-16 md:px-16 md:pt-32">
                <div class="flex items-end gap-2">
                    <img src="{{ asset('assets/icons/logo.svg') }}" class="w-10 md:w-12" alt="Trivium Akademika">
                    <h1 class="text-2xl md:text-3xl text-hitam font-medium">Trivium Akademika</h1>
                </div>
            </div>
            {{-- Form Login --}}
            <div class="flex flex-col w-full px-8 md:px-16 py-8 space-y-4">
                <h2 class="text-xl text-hitam font-medium">Masuk</h2>
                <div>
                    <label for="email" class="text-base text-hitam font-normal">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email"
                        class="w-full px-4 py-2 border-default focus:outline-none rounded-xl text-base text-hitam font-normal"
                        required>
                </div>
                <div>
                    <label for="password" class="text-base text-hitam font-normal">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password"
                        class="w-full px-4 py-2 border-default focus:outline-none rounded-xl text-base text-hitam font-normal"
                        required>
                </div>
                <div class="flex justify-end">
                    <button class="button-default w-1/3">Masuk</button>
                </div>
                <div class="flex flex-row p-4 md:p-6 rounded-xl items-start gap-2 bg-info">
                    <span class="material-symbols-outlined text-info">
                        info
                    </span>
                    <p class="text-sm text-info text-justify">Saat Anda melanjutkan, informasi pribadi Anda akan dibagikan ke layanan ini. Dengan masuk, Anda menyetujui pembagian informasi pribadi setiap kali mengakses layanan.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
