{{-- resources/views/components/header.blade.php --}}
<header>
    <div class="flex flex-row w-full p-6 justify-between items-center">
        @php
            $user = Auth::user();
            $currentPath = Request::path();
            $currentRouteName = Route::currentRouteName(); // Nama Route di Header

            // Tentukan page title berdasarkan path
            $pageTitle = match ($currentRouteName) {
                'dashboard' => 'Selamat Datang',
                'frs.index', 'frs.index' => 'Data FRS',
                'frs.edit', 'frs.show' => 'Data Detail FRS',
                'detail-frs.index', 'detail-frs.show' => 'Data Detail FRS',
                'jadwal.index', 'jadwal.show' => 'Data Jadwal',
                'mahasiswa.index', 'mahasiswa.show' => 'Data Mahasiswa',
                'dosen.index', 'dosen.show' => 'Data Dosen',
                default => 'Dashboard',
            };

            // Cek apakah user adalah admin dan bukan di dashboard
            $isAdminNotDashboard = $user->hasRole('admin') && $currentPath !== 'dashboard';

            $nama = null;
            if ($user->mahasiswa) {
                $nama = $user->mahasiswa->nama;
            } elseif ($user->dosen) {
                $nama = $user->dosen->nama;
            } else {
                $nama = $user->nama_user ?? 'User'; // Fallback
            }
        @endphp

        <h2 class="text-2xl text-hitam">
            {{ $pageTitle }}@if (Request::is('dashboard')), {{ Auth::user()->display_name }}!
            @endif
        </h2>

        <div class="flex flex-row items-center space-x-2">
            <div class="avatar">
                <div class="w-8 h-8 rounded-full">
                    <img src="https://img.daisyui.com/images/profile/demo/yellingcat@192.webp" />
                </div>
            </div>
            <h3 class="text-base">{{ Auth::user()->display_name }}</h3>
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                    <i class="ph ph-caret-down text-lg text-hitam"></i>
                </label>
                <ul tabindex="0" class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a class="text-base">Lihat Profil</a></li>
                    <li>
                        {{-- <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-base w-full text-left">Logout</button>
                        </form> --}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
