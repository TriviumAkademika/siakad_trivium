{{-- resources/views/components/header.blade.php --}}
<header>
    <div class="flex flex-row w-full p-6 justify-between items-center">
        @php
            $user = Auth::user();
            $currentPath = Request::path();
            $currentRouteName = Route::currentRouteName(); // Nama Route di Header

            // Tentukan page title berdasarkan path
            // Extended to include profile.show
            $pageTitle = match ($currentRouteName) {
                'dashboard' => 'Selamat Datang',
                'users.index' => 'Data Pengguna', // Simplified duplicate keys
                'dosen.index' => 'Data Dosen',
                'dosen.show' => 'Detail Dosen',
                'mahasiswa.index' => 'Data Mahasiswa',
                'mahasiswa.show' => 'Detail Mahasiswa', // Corrected from 'Data Mahasiswa'
                'jadwal.index', 'jadwal.show' => 'Data Jadwal',
                'matkul.index' => 'Data Mata Kuliah',
                'ruangan.index' => 'Data Ruangan',
                'waktu.index' => 'Data Waktu',
                'kelas.index' => 'Data Kelas',
                'kelas.show' => 'Detail Kelas',
                'frs.index' => 'Data FRS',
                'detail-frs.index', 'detail-frs.show' => 'Detail FRS',
                'profile.show' => 'Profil Saya', // Added title for profile page
                default => 'Dashboard',
            };

            // Cek apakah user adalah admin dan bukan di dashboard
            $isAdminNotDashboard = $user->hasRole('admin') && $currentPath !== 'dashboard';

            // $nama = null; // This $nama variable doesn't seem to be used in the rest of this specific blade file.
            // if ($user->mahasiswa) {
            //     $nama = $user->mahasiswa->nama;
            // } elseif ($user->dosen) {
            //     $nama = $user->dosen->nama_dosen; // Corrected from ->nama to ->nama_dosen for consistency
            // } else {
            //     $nama = $user->name ?? 'User'; // Corrected from nama_user to name, which is standard
            // }
        @endphp

        <h2 class="text-2xl text-hitam dark:text-putih">
            {{ $pageTitle }}@if (Request::is('dashboard'))
                , {{ Auth::user()->display_name }}!
            @endif
        </h2>

        <div class="flex flex-row items-center space-x-2">
            <div class="avatar hidden sm:block"> {{-- Hide on small screens if name is already there --}}
                <div class="w-8 h-8 rounded-full">
                    {{-- Consider using a dynamic avatar or a default one from your assets --}}
                    <img src="{{ $user->avatar_url ?? 'https://img.daisyui.com/images/profile/demo/yellingcat@192.webp' }}" alt="Avatar" />
                </div>
            </div>
            <h3 class="text-base text-hitam dark:text-putih">{{ Auth::user()->display_name }}</h3>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <i class="ph ph-caret-down text-lg text-hitam dark:text-putih"></i>
                </div>
                <ul tabindex="0" class="menu dropdown-content bg-base-100 dark:bg-gray-700 rounded-box z-[10] w-56 p-2 shadow mt-2">
                    {{-- Link Lihat Profil --}}
                    <li class="hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 rounded-lg">
                        <a href="{{ route('profile.show') }}" class="w-full text-left text-base text-hitam dark:text-putih flex items-center gap-2 p-3">
                            <i class="ph ph-user text-lg"></i>
                            Lihat Profil
                        </a>
                    </li>
                    {{-- Garis Pemisah --}}
                    {{-- <li><hr class="my-1 border-gray-200 dark:border-gray-600"></li> --}}

                    {{-- Tombol Logout --}}
                    <li class="cursor-pointer hover:bg-red-50 dark:hover:bg-red-700/30 transition-colors duration-200 rounded-lg"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                            @csrf
                        </form>
                        <div class="w-full text-left text-base text-red-600 dark:text-red-400 flex items-center gap-2 p-3">
                            <i class="ph ph-sign-out text-lg"></i>
                            Logout
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Minimal script, assuming DaisyUI handles dropdown toggle. Only logout form submission logging remains --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForm = document.getElementById('logout-form');

            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    console.log('Logout form submitted');
                });
            }

            // The profile-form related JS is removed as we are using a direct <a> link
        });
    </script>
</header>