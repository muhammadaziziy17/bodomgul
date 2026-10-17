/**
 * Bodomgul — savatcha: localStorage'da saqlanadigan {id, qty} ro'yxati.
 * Savatcha sahifasi serverda chiqarilgan qatorlarni ko'rsatadi/yashiradi,
 * summalar data-price asosida hisoblanadi (server narxi bilan bir xil).
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'bodomgul:cart';
    var MAX_QTY = 99;
    var MAX_ITEMS = 100;

    function i18n(key, replacements) {
        var value = (window.BgI18n && window.BgI18n[key]) || '';
        var map = replacements || {};

        Object.keys(map).forEach(function (token) {
            value = value.split(':' + token).join(String(map[token]));
        });

        return value;
    }

    function formatSom(amount) {
        var currency = i18n('currency') || 'so\u2018m';

        return String(amount).replace(/\B(?=(\d{3})+(?!\d))/g, '\u00A0') + '\u00A0' + currency;
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

            var items = [];

            parsed.forEach(function (entry) {
                if (!entry || typeof entry !== 'object') {
                    return;
                }

                var id = parseInt(entry.id, 10);
                var qty = parseInt(entry.qty, 10);

                if (!Number.isInteger(id) || id <= 0) {
                    return;
                }

                if (!Number.isInteger(qty) || qty <= 0) {
                    qty = 1;
                }

                qty = Math.min(qty, MAX_QTY);

                var existing = items.find(function (item) {
                    return item.id === id;
                });

                if (existing) {
                    existing.qty = Math.min(existing.qty + qty, MAX_QTY);

                    return;
                }

                if (items.length < MAX_ITEMS) {
                    items.push({ id: id, qty: qty });
                }
            });

            return items;
        } catch (error) {
            /* Buzilgan JSON — savatchani xavfsiz tozalaymiz */
            try {
                localStorage.removeItem(STORAGE_KEY);
            } catch (innerError) {
                /* e'tiborsiz */
            }

            return [];
        }
    }

    function write(items) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        } catch (error) {
            /* localStorage to'lgan yoki o'chirilgan bo'lsa jim o'tamiz */
        }

        var count = items.reduce(function (sum, item) {
            return sum + item.qty;
        }, 0);

        document.dispatchEvent(new CustomEvent('bodomgul:cart-updated', {
            detail: { count: count }
        }));
    }

    function count(items) {
        return items.reduce(function (sum, item) {
            return sum + item.qty;
        }, 0);
    }

    function add(id, qty) {
        var items = read();
        var existing = items.find(function (item) {
            return item.id === id;
        });

        if (existing) {
            existing.qty = Math.min(existing.qty + qty, MAX_QTY);
        } else if (items.length < MAX_ITEMS) {
            items.push({ id: id, qty: Math.min(qty, MAX_QTY) });
        }

        write(items);
    }

    function setQty(id, qty) {
        var items = read();
        var item = items.find(function (entry) {
            return entry.id === id;
        });

        if (!item) {
            return;
        }

        item.qty = Math.min(Math.max(qty, 1), MAX_QTY);

        write(items);
    }

    function remove(id) {
        write(read().filter(function (item) {
            return item.id !== id;
        }));
    }

    function clear() {
        write([]);
    }

    function updateHeader(total) {
        document.querySelectorAll('[data-cart-count]').forEach(function (badge) {
            badge.textContent = String(total);
            badge.hidden = total === 0;
        });

        var link = document.querySelector('[data-cart-link]');

        if (link) {
            link.setAttribute(
                'aria-label',
                total > 0
                    ? (i18n('cart_aria', { count: total }) || 'Savatcha, ' + total + ' ta mahsulot')
                    : (window.BgI18n && window.BgI18n.cart) || 'Savatcha'
            );
        }
    }

    function renderPage() {
        var page = document.querySelector('[data-cart-page]');

        if (!page) {
            return;
        }

        var items = read();
        var subtotal = 0;
        var totalQty = 0;

        page.querySelectorAll('[data-cart-row]').forEach(function (row) {
            var id = parseInt(row.dataset.productId, 10);
            var item = items.find(function (entry) {
                return entry.id === id;
            });

            if (!item) {
                row.hidden = true;

                return;
            }

            row.hidden = false;

            var price = parseInt(row.dataset.price, 10) || 0;
            var qtyInput = row.querySelector('[data-cart-qty]');
            var totalCell = row.querySelector('[data-cart-row-total]');

            if (qtyInput && parseInt(qtyInput.value, 10) !== item.qty) {
                qtyInput.value = String(item.qty);
            }

            if (totalCell) {
                totalCell.textContent = formatSom(price * item.qty);
            }

            subtotal += price * item.qty;
            totalQty += item.qty;
        });

        var isEmpty = items.length === 0;
        var emptyState = page.querySelector('[data-cart-empty]');
        var itemsWrap = page.querySelector('[data-cart-items]');
        var summary = page.querySelector('[data-cart-summary]');
        var countLabel = page.querySelector('[data-cart-count-text]');
        var subtotalLabels = page.querySelectorAll('[data-cart-subtotal]');

        if (emptyState) {
            emptyState.hidden = !isEmpty;
        }

        if (itemsWrap) {
            itemsWrap.hidden = isEmpty;
        }

        if (summary) {
            summary.hidden = isEmpty;
        }

        if (countLabel) {
            countLabel.textContent = String(totalQty);
        }

        subtotalLabels.forEach(function (label) {
            label.textContent = formatSom(subtotal);
        });
    }

    document.addEventListener('click', function (event) {
        var addButton = event.target.closest('[data-add-to-cart]');

        if (addButton) {
            event.preventDefault();

            var id = parseInt(addButton.dataset.productId, 10);

            if (!Number.isInteger(id)) {
                return;
            }

            var qty = 1;
            var source = addButton.dataset.qtySource;

            if (source) {
                var input = document.querySelector(source);
                var parsed = input ? parseInt(input.value, 10) : 1;

                if (Number.isInteger(parsed) && parsed > 0) {
                    qty = Math.min(parsed, MAX_QTY);
                }
            }

            add(id, qty);

            if (window.BgToast) {
                window.BgToast.show(i18n('added_to_cart') || 'Savatga qo‘shildi', 'bi-bag-check');
            }

            return;
        }

        var page = document.querySelector('[data-cart-page]');

        if (!page || !page.contains(event.target)) {
            return;
        }

        var row = event.target.closest('[data-cart-row]');

        if (!row) {
            if (event.target.closest('[data-cart-clear]')) {
                clear();

                if (window.BgToast) {
                    window.BgToast.show(i18n('cart_cleared') || 'Savatcha bo‘shatildi', 'bi-bag-x');
                }
            }

            return;
        }

        var rowId = parseInt(row.dataset.productId, 10);

        if (!Number.isInteger(rowId)) {
            return;
        }

        var qtyInput = row.querySelector('[data-cart-qty]');
        var current = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;

        if (event.target.closest('[data-cart-plus]')) {
            setQty(rowId, current + 1);
        } else if (event.target.closest('[data-cart-minus]')) {
            setQty(rowId, current - 1);
        } else if (event.target.closest('[data-cart-remove]')) {
            remove(rowId);

            if (window.BgToast) {
                window.BgToast.show(i18n('removed_from_cart') || 'Savatdan olib tashlandi', 'bi-trash3');
            }
        }
    });

    document.addEventListener('change', function (event) {
        var input = event.target.closest('[data-cart-qty]');

        if (!input) {
            return;
        }

        var row = input.closest('[data-cart-row]');

        if (!row) {
            return;
        }

        var id = parseInt(row.dataset.productId, 10);
        var qty = parseInt(input.value, 10);

        if (!Number.isInteger(id)) {
            return;
        }

        setQty(id, Number.isInteger(qty) && qty > 0 ? qty : 1);
    });

    document.addEventListener('bodomgul:cart-updated', function (event) {
        updateHeader(event.detail.count);
        renderPage();
    });

    updateHeader(count(read()));
    renderPage();
})();
