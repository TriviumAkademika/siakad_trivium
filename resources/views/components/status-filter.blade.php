{{-- resources/views/components/status-filter.blade.php --}}
@props(['statuses' => []])

<div class="relative">
    <button type="button" 
            id="filterButton"
            class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
        <i class="ph ph-funnel"></i>
        Filter Status
        <span id="filterCount"
              class="hidden ml-1 px-2 py-0.5 text-xs bg-biru-100 text-biru-800 rounded-full">0</span>
    </button>

    {{-- Dropdown Filter --}}
    <div id="filterDropdown"
         class="hidden absolute right-0 z-10 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200">
        <div class="p-3">
            <div class="space-y-2" id="filterOptions">
                @if(empty($statuses))
                    {{-- Default status options --}}
                    <label class="flex items-center">
                        <input type="checkbox"
                               class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500"
                               value="AKTIF"
                               {{ in_array('AKTIF', request('status', [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                AKTIF
                            </span>
                        </span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox"
                               class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500"
                               value="CUTI"
                               {{ in_array('CUTI', request('status', [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                CUTI
                            </span>
                        </span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox"
                               class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500"
                               value="PENSIUN"
                               {{ in_array('PENSIUN', request('status', [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                PENSIUN
                            </span>
                        </span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox"
                               class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500"
                               value="NONAKTIF"
                               {{ in_array('NONAKTIF', request('status', [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                NONAKTIF
                            </span>
                        </span>
                    </label>
                @else
                    {{-- Dynamic status options --}}
                    @foreach($statuses as $status)
                        <label class="flex items-center">
                            <input type="checkbox"
                                   class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500"
                                   value="{{ $status['value'] }}"
                                   {{ in_array($status['value'], request('status', [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $status['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $status['label'] }}
                                </span>
                            </span>
                        </label>
                    @endforeach
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between">
                <button type="button" 
                        id="clearFilter"
                        class="text-xs text-gray-500 hover:text-gray-700">
                    Clear All
                </button>
                <button type="button" 
                        id="applyFilter"
                        class="text-xs bg-biru-600 text-white px-3 py-1 rounded hover:bg-biru-700">
                    Apply
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterDropdown = document.getElementById('filterDropdown');
    const filterCount = document.getElementById('filterCount');
    const statusFilters = document.querySelectorAll('.status-filter');
    const clearFilter = document.getElementById('clearFilter');
    const applyFilter = document.getElementById('applyFilter');

    let activeStatusFilters = [];

    // Toggle dropdown filter
    if (filterButton) {
        filterButton.addEventListener('click', function(e) {
            e.stopPropagation();
            filterDropdown.classList.toggle('hidden');
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (filterButton && filterDropdown && 
            !filterButton.contains(e.target) && 
            !filterDropdown.contains(e.target)) {
            filterDropdown.classList.add('hidden');
        }
    });

    // Status filter functionality
    statusFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            updateFilterCount();
        });
    });

    // Clear all filters
    if (clearFilter) {
        clearFilter.addEventListener('click', function() {
            statusFilters.forEach(filter => {
                filter.checked = false;
            });
            updateFilterCount();
        });
    }

    // Apply filters
    if (applyFilter) {
        applyFilter.addEventListener('click', function() {
            activeStatusFilters = Array.from(statusFilters)
                .filter(filter => filter.checked)
                .map(filter => filter.value);

            filterDropdown.classList.add('hidden');
            
            // Dispatch custom event for real-time filtering
            const filterEvent = new CustomEvent('filterChanged', {
                detail: {
                    activeFilters: activeStatusFilters
                }
            });
            document.dispatchEvent(filterEvent);
        });
    }

    function updateFilterCount() {
        if (filterCount) {
            const checkedFilters = Array.from(statusFilters).filter(filter => filter.checked);
            if (checkedFilters.length > 0) {
                filterCount.textContent = checkedFilters.length;
                filterCount.classList.remove('hidden');
            } else {
                filterCount.classList.add('hidden');
            }
        }
    }

    // Initialize filter count on page load
    updateFilterCount();

    // Set active filters from URL parameters if any
    const urlParams = new URLSearchParams(window.location.search);
    const statusParams = urlParams.getAll('status[]');
    if (statusParams.length > 0) {
        activeStatusFilters = statusParams;
        statusFilters.forEach(filter => {
            if (statusParams.includes(filter.value)) {
                filter.checked = true;
            }
        });
        updateFilterCount();
        
        // Dispatch initial filter event
        const filterEvent = new CustomEvent('filterChanged', {
            detail: {
                activeFilters: activeStatusFilters
            }
        });
        document.dispatchEvent(filterEvent);
    }
});
</script>