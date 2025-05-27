{{-- resources/views/components/stat-card.blade.php --}}

@props([
    'title' => 'Default Title',
    'value' => '0',
    'description' => '',
    'status' => null,
    'statusColor' => 'blue'
])

@php
    // Define status badge colors
    $statusColors = [
        'blue' => 'bg-brand-100 text-brand-900',
        'green' => 'bg-green-100 text-green-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'red' => 'bg-red-100 text-red-800',
        'gray' => 'bg-gray-100 text-gray-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'indigo' => 'bg-indigo-100 text-indigo-800',
        'pink' => 'bg-pink-100 text-pink-800',
    ];
    
    $badgeClass = $statusColors[$statusColor] ?? $statusColors['blue'];
@endphp

{{-- <div class="flex flex-row w-full p-4"></div> --}}
<div class="flex flex-col w-full p-4 bg-brand-50 rounded-3xl border border-brand-200">
    <div class="flex justify-between items-start mb-2">
        <h4 class="text-base text-hitam">{{ $title }}</h4>
        
        @if($status)
            <span class="px-2 py-1 text-xs rounded-full {{ $badgeClass }}">
                {{ $status }}
            </span>
        @endif
    </div>
    
    <h1 class="text-3xl text-hitam font-medium">{{ $value }}</h1>
    
    @if($description)
        <p class="text-xs text-hitam mt-1">{{ $description }}</p>
    @endif
</div>