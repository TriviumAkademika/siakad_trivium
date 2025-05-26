<aside class="w-[272px] h-full shrink-0">
    <div class="flex flex-col flex-wrap h-full bg-brand-100">
        <div class="p-4 flex flex-col space-y-4">
            {{-- Logo Trivium Akademika --}}
            <div class="flex items-end gap-2">
                <img src="{{ asset('assets/icons/logo.svg') }}" class="w-10" alt="Trivium Akademika">
                <h3 class="text-xl text-hitam font-medium">Trivium Akademika</h3>
            </div>
            <hr class="border-abu w-full">

            {{-- Search Box --}}
            {{-- <label class="flex items-center gap-2 px-2 border-default rounded-lg">
                <i class="ph ph-magnifying-glass text-xl text-hitam"></i>
                <input type="search" class="w-full bg-transparent text-sm text-hitam outline-none focus:outline-none focus:ring-0 focus:border-none border-none shadow-none appearance-none" placeholder="Cari di sini" />
            </label> --}}

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                <i class="ph ph-house text-xl text-hitam"></i>
                <span class="text-base text-hitam">Beranda</span>
            </a>

            {{-- Dropdown Data Pengguna --}}
            <div class="flex flex-col">
                <button class="flex items-center justify-between gap-2 p-2 rounded-lg hover:bg-brand-200"
                    onclick="toggleDropdown('dropdown-pengguna')">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-users text-xl text-hitam"></i>
                        <span class="text-base text-hitam flex-1">Data Pengguna</span>
                    </div>
                    <i class="ph ph-caret-down text-hitam transition-transform duration-200" id="icon-pengguna"></i>
                </button>

                <div id="dropdown-pengguna" class="hidden ml-6 mt-2 space-y-2">
                    {{-- Pengguna cuma diakses Admin --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('users.index') }}"
                            class="flex items-center gap-2 {{ request()->routeIs('users.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                            <i class="ph ph-user text-xl text-hitam"></i>
                            <span class="text-base text-hitam">Pengguna</span>
                        </a>
                    @endif
                    {{-- Dosen --}}
                    <a href="{{ route('dosen.index') }}"
                        class="flex items-center gap-2 {{ request()->routeIs('dosen.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                        <i class="ph ph-chalkboard-teacher text-xl text-hitam"></i>
                        <span class="text-base text-hitam">Dosen</span>
                    </a>
                    {{-- Mahasiswa --}}
                    <a href="{{ route('mahasiswa.index') }}"
                        class="flex items-center gap-2 {{ request()->routeIs('mahasiswa.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                        <i class="ph ph-student text-xl text-hitam"></i>
                        <span class="text-base text-hitam">Mahasiswa</span>
                    </a>
                </div>
            </div>

            {{-- Dropdown Data Jadwal --}}
            <div class="flex flex-col">
                <button class="flex items-center justify-between gap-2 p-2 rounded-lg hover:bg-brand-200"
                    onclick="toggleDropdown('dropdown-jadwal')">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-calendar-dots text-xl text-hitam"></i>
                        <span class="text-base text-hitam flex-1">Data Jadwal</span>
                    </div>
                    <i class="ph ph-caret-down text-hitam transition-transform duration-200" id="icon-jadwal"></i>
                </button>

                <div id="dropdown-jadwal" class="hidden ml-6 mt-2 space-y-2">
                    {{-- Jadwal --}}
                    <a href="{{ route('jadwal.index') }}"
                        class="flex items-center gap-2 {{ request()->routeIs('jadwal.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                        <i class="ph ph-calendar-dots text-xl text-hitam"></i>
                        <span class="text-base text-hitam">Jadwal</span>
                    </a>
                    {{-- Matkul --}}
                    <a href="{{ route('matkul.index') }}"
                        class="flex items-center gap-2 {{ request()->routeIs('matkul.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                        <i class="ph ph-book-open text-xl text-hitam"></i>
                        <span class="text-base text-hitam">Matkul</span>
                    </a>
                    {{-- Ruangan cuma diakses Admin --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('ruangan.index') }}"
                            class="flex items-center gap-2 {{ request()->routeIs('ruangan.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                            <i class="ph ph-house-line text-xl text-hitam"></i>
                            <span class="text-base text-hitam">Ruangan</span>
                        </a>
                    @endif
                    {{-- Waktu cuma diakses Admin --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('waktu.index') }}"
                            class="flex items-center gap-2 {{ request()->routeIs('waktu.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                            <i class="ph ph-clock text-xl text-hitam"></i>
                            <span class="text-base text-hitam">Waktu</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Kelas --}}
            <a href="{{ route('kelas.index') }}"
                class="flex items-center gap-2 {{ request()->routeIs('kelas.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                <i class="ph ph-chalkboard text-xl text-hitam"></i>
                <span class="text-base text-hitam">Kelas</span>
            </a>

            {{-- FRS --}}
            <a href="{{ route('frs.index') }}"
                class="flex items-center gap-2 {{ request()->routeIs('frs.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                <i class="ph ph-clipboard-text text-xl text-hitam"></i>
                <span class="text-base text-hitam">FRS</span>
            </a>

            {{-- Nilai tampilan dosen --}}
            @if (auth()->user()->role === 'dosen')
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'dosen')
                <a href="{{ route('nilai-dosen') }}" class="flex items-center gap-2 {{ request()->routeIs('nilai-dosen') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                    <i class="ph ph-ranking text-xl text-hitam"></i>
                    <span class="text-base text-hitam">Nilai</span>
                </a>
            @elseif(auth()->user()->role === 'mahasiswa')
                <a href="{{ route('nilai-mahasiswa') }}" class="flex items-center gap-2 {{ request()->routeIs('nilai-mahasiswa.*') ? 'bg-brand-200' : '' }} p-2 rounded-lg">
                    <i class="ph ph-ranking text-xl text-hitam"></i>
                    <span class="text-base text-hitam">Nilai</span>
                </a>
            @endif
            @endif
        </div>
    </div>

    {{-- JavaScript untuk Toggle Dropdown --}}
    <script>
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const icon = document.getElementById('icon-' + dropdownId.split('-')[1]);

            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                dropdown.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        // Auto expand dropdown jika user sedang di halaman tersebut
        document.addEventListener('DOMContentLoaded', function() {
            // Cek untuk dropdown pengguna
            const userRoutes = ['users', 'dosen', 'mahasiswa'];
            const currentRoute = '{{ request()->route()->getName() }}';

            userRoutes.forEach(route => {
                if (currentRoute.includes(route)) {
                    toggleDropdown('dropdown-pengguna');
                }
            });

            // Cek untuk dropdown jadwal
            const jadwalRoutes = ['jadwal', 'matkul', 'ruangan', 'waktu'];
            jadwalRoutes.forEach(route => {
                if (currentRoute.includes(route)) {
                    toggleDropdown('dropdown-jadwal');
                }
            });
        });
    </script>
</aside>
