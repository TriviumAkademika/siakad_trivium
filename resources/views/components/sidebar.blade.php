<aside class="w-[272px] h-full shrink-0">
    <div class="flex flex-col flex-wrap h-full bg-brand-100">
        <div class="p-4 flex flex-col space-y-6">
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
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <i class="ph ph-house text-xl text-hitam"></i>
                <span class="text-base text-hitam">Beranda</span>
            </a>

            {{-- Nilai --}}
            <a href="#" class="flex items-center gap-2">
                <i class="ph ph-ranking text-xl text-hitam"></i>
                <span class="text-base text-hitam">Nilai</span>
            </a>

            {{-- Matkul --}}
            <a href="{{ route('matkul.index') }}" class="flex items-center gap-2">
                <i class="ph ph-users text-xl text-hitam"></i>
                <span class="text-base text-hitam">Matkul</span>
            </a>

            {{-- Jadwal --}}
            <a href="{{ route('jadwal.index') }}" class="flex items-center gap-2">
                <i class="ph ph-list-dashes text-xl text-hitam"></i>
                <span class="text-base text-hitam">Jadwal</span>
            </a>

            {{-- FRS --}}
            <a href="{{ route('frs.index') }}" class="flex items-center gap-2">
                <i class="ph ph-clipboard-text text-xl text-hitam"></i>
                <span class="text-base text-hitam">FRS</span>
            </a>

            {{-- Dosen --}}
            <a href="{{ route('dosen.index') }}" class="flex items-center gap-2">
                <i class="ph ph-users text-xl text-hitam"></i>
                <span class="text-base text-hitam">Dosen</span>
            </a>
        </div>
    </div>
</aside>
