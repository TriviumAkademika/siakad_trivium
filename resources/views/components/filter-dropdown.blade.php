{{-- resources/views/components/filter-dropdown.blade.php --}}

@props([
    'buttonId' => 'filterButton',
    'dropdownId' => 'filterDropdown', 
    'countId' => 'filterCount',
    'clearId' => 'clearFilter',
    'applyId' => 'applyFilter',
    'filters' => [],
    'selectedFilters' => [],
    'buttonText' => 'Filter Status',
    'buttonIcon' => 'ph ph-funnel'
])

@if(count($filters) > 0)
<div class="relative">
    <button type="button" 
            id="{{ $buttonId }}"
            class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
        <i class="{{ $buttonIcon }}"></i>
        {{ $buttonText }}
        <span id="{{ $countId }}" class="hidden ml-1 px-2 py-0.5 text-xs bg-biru-100 text-biru-800 rounded-full">0</span>
    </button>
    
    {{-- Dropdown Filter --}}
    <div id="{{ $dropdownId }}" 
         class="hidden absolute right-0 z-10 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200">
        <div class="p-3">
            <div class="space-y-2">
                @foreach($filters as $filter)
                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded">
                    <input type="checkbox" 
                           class="filter-checkbox rounded border-gray-300 text-biru-600 focus:ring-biru-500" 
                           value="{{ $filter['value'] }}" 
                           {{ in_array($filter['value'], $selectedFilters) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm">
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $filter['class'] }}">
                            {{ $filter['label'] }}
                        </span>
                    </span>
                </label>
                @endforeach
            </div>
            <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between">
                <button type="button" 
                        id="{{ $clearId }}"
                        class="text-xs text-gray-500 hover:text-gray-700 hover:underline">
                    Clear All
                </button>
                <button type="button" 
                        id="{{ $applyId }}"
                        class="text-xs bg-biru-600 text-white px-3 py-1 rounded hover:bg-biru-700 transition-colors">
                    Apply
                </button>
            </div>
        </div>
    </div>
</div>
@endif