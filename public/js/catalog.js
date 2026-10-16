/**
 * Bodomgul — katalog: qidiruv, kategoriya filtri va saralash.
 * Mahsulotlar serverdan chiqariladi; JS faqat ko'rinishni boshqaradi.
 */
(function () {
    'use strict';

    var root = document.querySelector('[data-catalog]');

    if (!root) {
        return;
    }

    var searchInput = root.querySelector('[data-catalog-search]');
    var sortSelect = root.querySelector('[data-catalog-sort]');
    var categoryButtons = Array.prototype.slice.call(root.querySelectorAll('[data-catalog-category]'));
    var grid = root.querySelector('[data-catalog-grid]');
    var items = grid ? Array.prototype.slice.call(grid.querySelectorAll('[data-product-item]')) : [];
    var emptyState = root.querySelector('[data-catalog-empty]');
    var countLabel = root.querySelector('[data-catalog-count]');
    var clearButton = root.querySelector('[data-catalog-clear]');

    var state = {
        query: searchInput ? searchInput.value : '',
        category: 'all',
        sort: 'default'
    };

    function normalize(value) {
        return (value || '')
            .toString()
            .toLowerCase()
            .replace(/[’‘ʻ`']/g, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function sortItems() {
        if (!grid) {
            return;
        }

        if (state.sort === 'default') {
            items.forEach(function (item) {
                grid.appendChild(item);
            });

            return;
        }

        var sorted = items.slice().sort(function (a, b) {
            if (state.sort === 'price-asc' || state.sort === 'price-desc') {
                var priceA = parseInt(a.dataset.price, 10) || 0;
                var priceB = parseInt(b.dataset.price, 10) || 0;

                return state.sort === 'price-asc' ? priceA - priceB : priceB - priceA;
            }

            if (state.sort === 'name-asc') {
                return (a.dataset.name || '').localeCompare(b.dataset.name || '', 'uz');
            }

            return 0;
        });

        sorted.forEach(function (item) {
            grid.appendChild(item);
        });
    }

    function apply() {
        var query = normalize(state.query);
        var visible = 0;

        items.forEach(function (item) {
            var matchesQuery = query === '' || normalize(item.dataset.name).indexOf(query) !== -1;
            var matchesCategory = state.category === 'all' || item.dataset.category === state.category;
            var show = matchesQuery && matchesCategory;

            item.hidden = !show;

            if (show) {
                visible++;
            }
        });

        if (emptyState) {
            emptyState.hidden = visible !== 0;
        }

        if (grid) {
            grid.hidden = visible === 0;
        }

        if (countLabel) {
            countLabel.textContent = String(visible);
        }

        sortItems();
    }

    function setCategory(category) {
        state.category = category;

        categoryButtons.forEach(function (button) {
            var active = button.dataset.catalogCategory === category;

            button.classList.toggle('is-active', active);
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
        });

        apply();
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            state.query = searchInput.value;
            apply();
        });
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            state.sort = sortSelect.value;
            sortItems();
        });
    }

    categoryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            setCategory(button.dataset.catalogCategory);
        });
    });

    if (clearButton) {
        clearButton.addEventListener('click', function () {
            state.query = '';
            state.sort = 'default';

            if (searchInput) {
                searchInput.value = '';
            }

            if (sortSelect) {
                sortSelect.value = 'default';
            }

            setCategory('all');

            if (searchInput) {
                searchInput.focus();
            }
        });
    }

    apply();
})();
