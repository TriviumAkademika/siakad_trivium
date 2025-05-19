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

            <div class="flex flex-col w-full px-8 md:px-16 py-8 space-y-4">
                <h2 class="text-xl text-hitam font-medium">Masuk</h2>

                {{-- Flash Message (success) --}}
                @if (session('status'))
                    <div class="text-green-600 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Error Message (validation errors) --}}
                @if ($errors->any())
                    <div class="text-red-600 text-sm">
                        Email atau password salah.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email Input --}}
                    <div>
                        <label for="email" class="text-base text-hitam font-normal">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl text-base text-hitam font-normal focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required autofocus>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="mt-4">
                        <label for="password" class="text-base text-hitam font-normal">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl text-base text-hitam font-normal focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl hover:bg-indigo-700 transition w-1/3">
                            Masuk
                        </button>
                    </div>
                </form>

                {{-- Info box --}}
                <div class="flex flex-row p-4 md:p-6 rounded-xl items-start gap-2 bg-blue-100 mt-6">
                    <span class="material-symbols-outlined text-blue-600">
                        info
                    </span>
                    <p class="text-sm text-blue-600 text-justify">
                        Saat Anda melanjutkan, informasi pribadi Anda akan dibagikan ke layanan ini. Dengan masuk, Anda menyetujui pembagian informasi pribadi setiap kali mengakses layanan.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
