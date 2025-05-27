{{-- resources/views/components/stat-card.blade.php --}}

@props([
    'title' => 'Default Title',
    'value' => '0',
    'description' => '',
    'status' => null,
    'statusColor' => 'blue',
    'variant' => 'default', // 'default', 'gradient', 'minimal'
])

@php
    // Define status badge colors
    $statusColors = [
        'blue' => 'bg-blue-50 text-blue-700 border-blue-200',
        'green' => 'bg-green-50 text-green-700 border-green-200',
        'yellow' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
        'red' => 'bg-red-50 text-red-700 border-red-200',
        'gray' => 'bg-gray-50 text-gray-700 border-gray-200',
        'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
        'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'pink' => 'bg-pink-50 text-pink-700 border-pink-200',
    ];

    // Define card variants
    $cardVariants = [
        'default' => 'bg-white border-gray-200 hover:border-brand-300',
        'gradient' =>
            'bg-gradient-to-br from-brand-50 to-brand-100 border-brand-200 hover:from-brand-100 hover:to-brand-150',
        'minimal' => 'bg-gray-50 border-gray-100 hover:bg-gray-100',
    ];

    $badgeClass = $statusColors[$statusColor] ?? $statusColors['blue'];
    $cardClass = $cardVariants[$variant] ?? $cardVariants['default'];
@endphp

<div
    class="group relative overflow-hidden rounded-2xl border-2 {{ $cardClass }} p-6 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] cursor-pointer">

    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-5">
        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-brand-500"></div>
        <div class="absolute -bottom-6 -left-6 h-32 w-32 rounded-full bg-brand-300"></div>
    </div>

    {{-- Header with Status --}}
    <div class="relative flex items-start justify-between mb-4">
        <div>
            <h4 class="text-lg font-medium text-gray-600 group-hover:text-gray-700 transition-colors">{{ $title }}
            </h4>
            @if ($description)
                <p class="text-sm text-gray-500 mt-1">{{ $description }}</p>
            @endif
        </div>

        @if ($status)
            <span
                class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full border {{ $badgeClass }}">
                {{ $status }}
            </span>
        @endif
    </div>

    {{-- Main Value --}}
    <div class="relative">
        <h1 class="text-4xl font-bold text-gray-900 group-hover:text-brand-600 transition-colors duration-300">
            {{ $value }}
        </h1>
    </div>

    {{-- Hover Effect Overlay --}}
    <div
        class="absolute inset-0 bg-gradient-to-r from-brand-500/0 to-brand-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl">
    </div>
</div>