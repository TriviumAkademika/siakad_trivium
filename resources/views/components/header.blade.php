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
                'users.index', 'users.index' => 'Data Pengguna',
                'dosen.index', 'dosen.index' => 'Data Dosen',
                'dosen.index', 'dosen.show' => 'Detail Dosen',
                'mahasiswa.index', 'mahasiswa.show' => 'Data Mahasiswa',
                'jadwal.index', 'jadwal.show' => 'Data Jadwal',
                'matkul.index', 'matkul.index' => 'Data Mata Kuliah',
                'ruangan.index', 'ruangan.index' => 'Data Ruangan',
                'waktu.index', 'waktu.index' => 'Data Waktu',
                'kelas.index', 'kelas.index' => 'Data Kelas',
                'kelas.index', 'kelas.show' => 'Detail Kelas',
                'frs.index', 'frs.index' => 'Data FRS',
                'detail-frs.index', 'detail-frs.show' => 'Detail FRS',
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
            {{ $pageTitle }}@if (Request::is('dashboard'))
                , {{ Auth::user()->display_name }}!
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
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <i class="ph ph-caret-down text-lg text-hitam"></i>
                </div>
                <ul tabindex="0" class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow pt-3">
                    {{-- <li>
                        <form method="GET" action="{{ route('profile.edit') }}" id="profile-form">
                            @csrf
                            <button type="submit" class="w-full text-left text-base text-hitam flex items-center gap-2 px-4 py-2">
                                <i class="ph ph-user text-lg"></i>
                                Lihat Profil
                            </button>
                        </form>
                    </li> --}}
                    <li class="cursor-pointer hover:bg-red-50 transition-colors duration-200"
                        onclick="document.getElementById('logout-form').submit();">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                            @csrf
                        </form>
                        <div class="w-full text-left text-base text-red-600 flex items-center gap-2 p-3 rounded-lg">
                            <i class="ph ph-sign-out text-lg"></i>
                            Logout
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Script untuk memastikan dropdown bekerja -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForm = document.getElementById('logout-form');
            const profileForm = document.getElementById('profile-form');

            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    console.log('Logout form submitted');
                });
            }

            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    console.log('Profile form submitted');
                });
            }
        });
    </script>
</header>
