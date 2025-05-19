<header>
    <div class="flex flex-row w-full p-6 justify-between items-end">
        @php
            $user = Auth::user();
            $nama = null;
            if ($user->mahasiswa) {
                $nama = $user->mahasiswa->nama;
            } elseif ($user->dosen) {
                $nama = $user->dosen->nama;
            } else {
                $nama = $user->name ?? 'User'; // fallback
            }
        @endphp

        <h2 class="text-2xl text-hitam">Selamat Datang, {{ Auth::user()->display_name }} !</h2>

        <div class="flex flex-row items-center space-x-2">
            <div class="avatar">
                <div class="w-8 rounded-full">
                    <img src="https://img.daisyui.com/images/profile/demo/yellingcat@192.webp" />
                </div>
            </div>
            <h3 class="text-base">{{ Auth::user()->display_name }}</h3>
            <i class="ph ph-caret-down text-xl text-hitam"></i>
        </div>
    </div>
</header>
