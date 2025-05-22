{{-- resources/views/auth/login.blade.php --}}

@extends('master')

@section('title', 'Login')

@section('content')
    <div class="flex flex-col md:flex-row w-full min-h-screen">
        {{-- Cover Login sebelah kiri untuk desktop --}}
        <div class="hidden md:block md:w-3/5">
            <img src="{{ asset('assets/images/login-cover.png') }}" alt="Login"
                class="w-full h-full object-cover object-center">
        </div>

        {{-- Form Login sebelah kanan --}}
        <div class="w-full md:w-2/5 bg-putih">
            <div class="w-full px-8 pt-16 md:px-16 md:pt-32">
                <div class="flex items-end gap-2">
                    <img src="{{ asset('assets/icons/logo.svg') }}" class="w-10" alt="Trivium Akademika">
                    <h1 class="text-2xl text-hitam font-medium">Trivium Akademika</h1>
                </div>
            </div>

            {{-- Toast Notification (Logout Success) --}}
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                    class="fixed top-8 right-8 flex items-center bg-hijau-100 text-success px-6 py-4 rounded shadow z-50">
                    <i class="ph ph-check-circle text-lg mr-2"></i>
                    <span class="text-sm">Berhasil keluar dari Trivium Akademika!</span>
                </div>
            @endif

            {{-- Error Message (Validation errors) --}}
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                    class="fixed top-8 right-8 flex items-center gap-2 bg-merah-100 text-error px-6 py-4 rounded-lg shadow z-50">
                    <i class="ph ph-warning-circle text-lg"></i>
                    <span class="text-sm">Email atau password salah</span>
                </div>
            @endif


            <div class="flex flex-col w-full px-8 md:px-16 py-8 space-y-4">
                <h2 class="text-xl text-hitam font-medium">Masuk</h2>
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email Input --}}
                    <label for="email" class="block text-base text-hitam font-normal">
                        Email
                        <input type="email" id="email" name="email" placeholder="Masukkan email"
                            value="{{ old('email') }}"
                            class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required autofocus>
                        {{-- @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror --}}
                    </label>

                    {{-- Password Input --}}
                    <label for="password" class="block text-base text-hitam font-normal">
                        Password
                        <input type="password" id="password" name="password" placeholder="Masukkan password"
                            class="w-full p-2 border-default focus:outline-none focus:ring-1 focus:ring-biru-700 rounded-lg font-normal"
                            required>
                        {{-- @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror --}}
                    </label>

                    {{-- Submit Button --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="btn bg-brand-900 hover:bg-brand-950 text-sm font-normal text-putih rounded-lg focus:outline-none focus:ring-0 transition w-1/3">
                            Masuk
                        </button>
                    </div>
                </form>

                {{-- Info box --}}
                <div class="flex flex-row p-4 md:p-6 rounded-lg items-start gap-2 bg-biru-100">
                    <i class="ph ph-info text-lg text-info"></i>
                    <p class="text-sm text-info text-justify">
                        Saat Anda melanjutkan, informasi pribadi Anda akan dibagikan ke layanan ini. Dengan masuk, Anda
                        menyetujui pembagian informasi pribadi setiap kali mengakses layanan.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
