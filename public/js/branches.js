/**
 * Bodomgul — filiallar sahifasi: nom, tuman va manzil bo'yicha qidiruv.
 */
(function () {
    'use strict';

    var root = document.querySelector('[data-branches]');

    if (!root) {
        return;
    }

    var searchInput = root.querySelector('[data-branch-search]');
    var cards = Array.prototype.slice.call(root.querySelectorAll('[data-branch-card]'));
    var emptyState = root.querySelector('[data-branches-empty]');
    var countLabel = root.querySelector('[data-branches-count]');

    function normalize(value) {
        return (value || '')
            .toString()
            .toLowerCase()
            .replace(/[’‘ʻ`']/g, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function apply() {
        var query = normalize(searchInput ? searchInput.value : '');
        var visible = 0;

        cards.forEach(function (card) {
            var show = query === '' || normalize(card.dataset.search).indexOf(query) !== -1;

            card.hidden = !show;

            if (show) {
                visible++;
            }
        });

        if (emptyState) {
            emptyState.hidden = visible !== 0;
        }

        if (countLabel) {
            countLabel.textContent = String(visible);
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', apply);
    }

    apply();
})();
