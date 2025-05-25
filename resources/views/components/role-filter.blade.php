{{-- resources/views/components/role-filter.blade.php --}}
@props(['roles' => []])

<div class="relative">
    <button type="button" 
            id="roleFilterButton"
            class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
        <i class="ph ph-funnel"></i>
        Filter Role
        <span id="roleFilterCount"
              class="hidden ml-1 px-2 py-0.5 text-xs bg-brand-100 text-brand-800 rounded-full">0</span>
    </button>

    {{-- Dropdown Filter --}}
    <div id="roleFilterDropdown"
         class="hidden absolute right-0 z-10 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200">
        <div class="p-3">
            <div class="space-y-2" id="roleFilterOptions">
                @if(empty($roles))
                    {{-- Default role options --}}
                    <label class="flex items-center">
                        <input type="checkbox"
                               class="role-filter rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                               value="admin"
                               {{ in_array('admin', request('roles', [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                Admin
                            </span>
                        </span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox"
                               class="role-filter rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                               value="dosen"
                               {{ in_array('dosen', request('roles', [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                Dosen
                            </span>
                        </span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox"
                               class="role-filter rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                               value="mahasiswa"
                               {{ in_array('mahasiswa', request('roles', [])) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                Mahasiswa
                            </span>
                        </span>
                    </label>
                @else
                    {{-- Dynamic role options --}}
                    @foreach($roles as $role)
                        <label class="flex items-center">
                            <input type="checkbox"
                                   class="role-filter rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                                   value="{{ $role['value'] }}"
                                   {{ in_array($role['value'], request('roles', [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $role['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $role['label'] }}
                                </span>
                            </span>
                        </label>
                    @endforeach
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between">
                <button type="button" 
                        id="clearRoleFilter"
                        class="text-xs text-gray-500 hover:text-gray-700">
                    Clear All
                </button>
                <button type="button" 
                        id="applyRoleFilter"
                        class="text-xs bg-brand-600 text-white px-3 py-1 rounded hover:bg-brand-700">
                    Apply
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('roleFilterButton');
    const filterDropdown = document.getElementById('roleFilterDropdown');
    const filterCount = document.getElementById('roleFilterCount');
    const roleFilters = document.querySelectorAll('.role-filter');
    const clearFilter = document.getElementById('clearRoleFilter');
    const applyFilter = document.getElementById('applyRoleFilter');

    let activeRoleFilters = [];

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

    // Role filter functionality
    roleFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            updateFilterCount();
        });
    });

    // Clear all filters
    if (clearFilter) {
        clearFilter.addEventListener('click', function() {
            roleFilters.forEach(filter => {
                filter.checked = false;
            });
            updateFilterCount();
        });
    }

    // Apply filters
    if (applyFilter) {
        applyFilter.addEventListener('click', function() {
            activeRoleFilters = Array.from(roleFilters)
                .filter(filter => filter.checked)
                .map(filter => filter.value);

            filterDropdown.classList.add('hidden');
            
            // Dispatch custom event for real-time filtering
            const filterEvent = new CustomEvent('roleFilterChanged', {
                detail: {
                    activeFilters: activeRoleFilters
                }
            });
            document.dispatchEvent(filterEvent);
        });
    }

    function updateFilterCount() {
        if (filterCount) {
            const checkedFilters = Array.from(roleFilters).filter(filter => filter.checked);
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
    const roleParams = urlParams.getAll('roles[]');
    if (roleParams.length > 0) {
        activeRoleFilters = roleParams;
        roleFilters.forEach(filter => {
            if (roleParams.includes(filter.value)) {
                filter.checked = true;
            }
        });
        updateFilterCount();
        
        // Dispatch initial filter event
        const filterEvent = new CustomEvent('roleFilterChanged', {
            detail: {
                activeFilters: activeRoleFilters
            }
        });
        document.dispatchEvent(filterEvent);
    }
});
</script>