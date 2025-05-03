<aside class="w-[272px] h-screen">
    <div class="flex flex-col flex-wrap h-full bg-sidebar  shadow-abu">
        <div class="p-4 flex flex-col space-y-6">
            {{-- Logo Trivium Akademika --}}
            <div class="flex items-end gap-2">
                <img src="{{ asset('assets/icons/logo.svg') }}" class="w-10" alt="Trivium Akademika">
                <h3 class="text-xl text-hitam font-medium">Trivium Akademika</h3>
            </div>
            {{-- Search Box --}}
            <div class="flex items-center gap-2 p-2 border-default rounded-lg">
                <span class="material-symbols-outlined text-hitam">search</span>
                <input type="text" placeholder="Cari" class="text-base text-hitam focus:outline-none focus:ring-0" />
            </div>
            {{-- Dashboard --}}
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-hitam">
                    show_chart
                </span>
                <span class="text-base text-hitam">Dashboard</span>
            </div>
            {{-- FRS --}}
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-hitam">
                    assignment
                </span>
                <div class="flex w-full justify-between items-center">
                    <span class="text-base text-hitam">FRS</span>
                    <span class="material-symbols-outlined text-hitam">
                        keyboard_arrow_down
                    </span>
                </div>
            </div>
            {{-- Nilai --}}
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-hitam">
                    leaderboard
                </span>
                <span class="text-base text-hitam">Nilai</span>
            </div>
        </div>
    </div>
</aside>
