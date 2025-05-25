// public/js/search-filter-components.js

// Global state untuk komunikasi antar komponen
window.searchFilterState = {
    searchTerm: '',
    activeFilters: [],
    subscribers: []
};

// Subscribe ke perubahan state
window.subscribeToSearchFilter = function(callback) {
    window.searchFilterState.subscribers.push(callback);
};

// Trigger update ke semua subscriber
function triggerUpdate() {
    window.searchFilterState.subscribers.forEach(callback => {
        callback(window.searchFilterState);
    });
}

// Initialize Search Component
window.initializeSearch = function(config) {
    const searchInput = document.getElementById(config.inputId);
    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        window.searchFilterState.searchTerm = this.value.toLowerCase();
        triggerUpdate();
    });

    // Set initial value
    window.searchFilterState.searchTerm = searchInput.value.toLowerCase();
};

// Initialize Filter Component  
window.initializeFilter = function(config) {
    const filterButton = document.getElementById(config.buttonId);
    const filterDropdown = document.getElementById(config.dropdownId);
    const filterCount = document.getElementById(config.countId);
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
    const clearFilter = document.getElementById(config.clearId);
    const applyFilter = document.getElementById(config.applyId);

    if (!filterButton || !filterDropdown) return;

    // Toggle dropdown
    filterButton.addEventListener('click', function(e) {
        e.stopPropagation();
        filterDropdown.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!filterButton.contains(e.target) && !filterDropdown.contains(e.target)) {
            filterDropdown.classList.add('hidden');
        }
    });

    // Update filter count
    function updateFilterCount() {
        const checkedFilters = Array.from(filterCheckboxes).filter(cb => cb.checked);
        if (checkedFilters.length > 0) {
            filterCount.textContent = checkedFilters.length;
            filterCount.classList.remove('hidden');
        } else {
            filterCount.classList.add('hidden');
        }
    }

    // Filter checkbox change
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateFilterCount);
    });

    // Clear filters
    if (clearFilter) {
        clearFilter.addEventListener('click', function() {
            filterCheckboxes.forEach(cb => cb.checked = false);
            updateFilterCount();
        });
    }

    // Apply filters
    if (applyFilter) {
        applyFilter.addEventListener('click', function() {
            window.searchFilterState.activeFilters = Array.from(filterCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            filterDropdown.classList.add('hidden');
            triggerUpdate();
        });
    }

    // Initialize
    updateFilterCount();
    
    // Set active filters from URL or initial state
    const urlParams = new URLSearchParams(window.location.search);
    const statusParams = urlParams.getAll('status[]');
    if (statusParams.length > 0) {
        window.searchFilterState.activeFilters = statusParams;
        filterCheckboxes.forEach(cb => {
            if (statusParams.includes(cb.value)) {
                cb.checked = true;
            }
        });
        updateFilterCount();
    }
};

// Initialize Table Filter (untuk menangani filtering actual pada tabel)
window.initializeTableFilter = function(config) {
    const dataRows = document.querySelectorAll(config.rowSelector);
    const noResults = document.getElementById(config.noResultsId);
    const tableBody = document.getElementById(config.targetTableId);

    if (!dataRows.length) return;

    // Subscribe ke perubahan search/filter
    window.subscribeToSearchFilter(function(state) {
        filterTable(state.searchTerm, state.activeFilters);
    });

    function filterTable(searchTerm, activeFilters) {
        let visibleCount = 0;

        dataRows.forEach(row => {
            const searchData = row.getAttribute(config.searchAttribute) || '';
            const statusData = row.getAttribute(config.statusAttribute) || '';
            
            // Check search criteria
            const matchesSearch = searchTerm === '' || searchData.toLowerCase().includes(searchTerm);
            
            // Check status filter
            const matchesStatus = activeFilters.length === 0 || activeFilters.includes(statusData);
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                // Update row number if first cell contains number
                const numberCell = row.querySelector('td:first-child');
                if (numberCell && /^\d+$/.test(numberCell.textContent.trim())) {
                    numberCell.textContent = visibleCount + 1;
                }
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (noResults && tableBody) {
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
                tableBody.parentElement.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                tableBody.parentElement.classList.remove('hidden');
            }
        }
    }

    // Initial filter
    filterTable(window.searchFilterState.searchTerm, window.searchFilterState.activeFilters);

    return {
        filterTable: filterTable,
        clearAll: function() {
            window.searchFilterState.searchTerm = '';
            window.searchFilterState.activeFilters = [];
            triggerUpdate();
        }
    };
};