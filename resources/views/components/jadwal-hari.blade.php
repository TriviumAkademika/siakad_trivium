{{-- resources/views/components/jadwal-hari.blade.php --}}
@props(['hari', 'mataKuliah'])

<div class="flex flex-col p-4 rounded-2xl space-y-2 bg-brand-50">
    <h4 class="text-base text-hitam font-semibold">{{ $hari }}</h4>
    @foreach ($mataKuliah as $mk)
        <div class="flex flex-row justify-between">
            <div class="flex flex-col pl-4 border-biru">
                <h4 class="text-base text-hitam">{{ $mk['nama'] }}</h4>
                @if (!empty($mk['dosen']))
                    @foreach ($mk['dosen'] as $dosen)
                        <p class="text-xs text-hitam">{{ $dosen }}</p>
                    @endforeach
                @endif
            </div>
            <div class="flex flex-col w-24 p-2 rounded-lg items-center justify-center bg-brand-200">
                <p class="text-xs text-hitam">{{ $mk['ruangan'] }}</p>
                <p class="text-xs text-hitam">{{ $mk['waktu'] }}</p>
            </div>
        </div>
    @endforeach
</div>