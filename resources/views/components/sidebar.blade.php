<aside class="w-[272px] h-full shrink-0">
    <div class="flex flex-col flex-wrap h-full shadow-abu bg-sidebar">
        <div class="p-4 flex flex-col space-y-6">
            {{-- Logo Trivium Akademika --}}
            <div class="flex items-end gap-2">
                <img src="{{ asset('assets/icons/logo.svg') }}" class="w-10" alt="Trivium Akademika">
                <h3 class="text-xl text-hitam font-medium">Trivium Akademika</h3>
            </div>
            {{-- Search Box --}}
            <div class="flex items-center gap-2 p-2 border-default rounded-lg">
                <i class="ph ph-magnifying-glass text-xl text-hitam"></i>
                <input type="text" placeholder="Cari" class="text-base text-hitam focus:outline-none focus:ring-0" />
            </div>
            {{-- Dashboard --}}
            <div class="flex items-center gap-2">
                <i class="ph ph-house text-xl text-hitam"></i>
                <span class="text-base text-hitam">Beranda</span>
            </div>
            {{-- Nilai --}}
            <div class="flex items-center gap-2">
                <i class="ph ph-ranking text-xl text-hitam"></i>
                <span class="text-base text-hitam">Nilai</span>
            </div>
            {{-- Jadwal --}}
            <div class="flex items-center gap-2">
                <i class="ph ph-list-dashes text-xl text-hitam"></i>
                <span class="text-base text-hitam">Jadwal</span>
            </div>
            {{-- FRS --}}
            <div class="flex items-center gap-2">
                <i class="ph ph-clipboard-text text-xl text-hitam"></i>
                <span class="text-base text-hitam">FRS</span>
            </div>
            {{-- Dosen --}}
            <div class="flex items-center gap-2">
                <i class="ph ph-users text-xl text-hitam"></i>
                <span class="text-base text-hitam">Dosen</span>
            </div>
        </div>
    </div>
</aside>
