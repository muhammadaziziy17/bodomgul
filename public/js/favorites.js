/**
 * Bodomgul — sevimlilar: localStorage'da saqlanadigan mahsulot ID'lari.
 * Buzilgan yoki noto'g'ri ma'lumot bo'lsa, ro'yxat xavfsiz holatga qaytariladi.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'bodomgul:favorites';
    var MAX_ITEMS = 200;

    function i18n(key, replacements) {
        var value = (window.BgI18n && window.BgI18n[key]) || '';
        var map = replacements || {};

        Object.keys(map).forEach(function (token) {
            value = value.split(':' + token).join(String(map[token]));
        });

        return value;
    }

    function read() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);

            if (!raw) {
                return [];
            }

            var parsed = JSON.parse(raw);

            if (!Array.isArray(parsed)) {
                return [];
            }

            return parsed
                .map(function (value) {
                    return parseInt(value, 10);
                })
                .filter(function (value) {
                    return Number.isInteger(value) && value > 0;
                })
                .slice(0, MAX_ITEMS);
        } catch (error) {
            return [];
        }
    }

    function write(ids) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
        } catch (error) {
            /* localStorage to'lgan yoki o'chirilgan bo'lsa jim o'tamiz */
        }

        document.dispatchEvent(new CustomEvent('bodomgul:favorites-updated', {
            detail: { count: ids.length }
        }));
    }

    function toggle(id) {
        var ids = read();
        var index = ids.indexOf(id);
        var added;

        if (index === -1) {
            ids.push(id);
            added = true;
        } else {
            ids.splice(index, 1);
            added = false;
        }

        write(ids);

        return added;
    }

    function syncButtons() {
        var ids = read();

        document.querySelectorAll('[data-favorite-toggle]').forEach(function (button) {
            var id = parseInt(button.dataset.productId, 10);
            var active = ids.indexOf(id) !== -1;

            button.classList.toggle('is-active', active);
            button.setAttribute('aria-pressed', active ? 'true' : 'false');

            var icon = button.querySelector('i');

            if (icon) {
                icon.classList.toggle('bi-heart', !active);
                icon.classList.toggle('bi-heart-fill', active);
            }

            var label = button.querySelector('[data-favorite-label]');

            if (label) {
                label.textContent = active ? 'Sevimlilarda' : 'Sevimlilarga';
            }
        });

        updateHeaderCount(ids.length);
        renderPage(ids);
    }

    function updateHeaderCount(count) {
        document.querySelectorAll('[data-favorites-count]').forEach(function (badge) {
            badge.textContent = String(count);
            badge.hidden = count === 0;
        });

        var link = document.querySelector('[data-favorites-link]');

        if (link) {
            link.setAttribute(
                'aria-label',
                count > 0
                    ? (i18n('favorites_aria', { count: count }) || 'Sevimlilar, ' + count + ' ta mahsulot')
                    : (window.BgI18n && window.BgI18n.favorites) || 'Sevimlilar'
            );
        }
    }

    function renderPage(ids) {
        var page = document.querySelector('[data-favorites-page]');

        if (!page) {
            return;
        }

        var visibleCount = 0;

        page.querySelectorAll('[data-favorite-item]').forEach(function (item) {
            var id = parseInt(item.dataset.productId, 10);
            var visible = ids.indexOf(id) !== -1;

            item.hidden = !visible;

            if (visible) {
                visibleCount++;
            }
        });

        var emptyState = page.querySelector('[data-favorites-empty]');
        var grid = page.querySelector('[data-favorites-grid]');
        var summary = page.querySelector('[data-favorites-summary]');
        var countLabel = page.querySelector('[data-favorites-count-text]');

        if (emptyState) {
            emptyState.hidden = visibleCount > 0;
        }

        if (grid) {
            grid.hidden = visibleCount === 0;
        }

        if (summary) {
            summary.hidden = visibleCount === 0;
        }

        if (countLabel) {
            countLabel.textContent = String(visibleCount);
        }
    }

    document.addEventListener('click', function (event) {
        var button = event.target.closest('[data-favorite-toggle]');

        if (!button) {
            return;
        }

        event.preventDefault();

        var id = parseInt(button.dataset.productId, 10);

        if (!Number.isInteger(id)) {
            return;
        }

        var added = toggle(id);

        if (window.BgToast) {
            window.BgToast.show(
                added
                    ? (i18n('added_to_favorites') || 'Sevimlilarga qo‘shildi')
                    : (i18n('removed_from_favorites') || 'Sevimlilardan olib tashlandi'),
                added ? 'bi-heart-fill' : 'bi-heart'
            );
        }
    });

    document.addEventListener('bodomgul:favorites-updated', syncButtons);

    syncButtons();
})();
