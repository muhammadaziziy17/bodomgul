/**
 * Bodomgul — Admin panel skriptlari (AJAX).
 *
 * - Sahifani yangilamasdan CRUD (modal + fetch)
 * - Toaster xabarlari
 * - Til tablari, takrorlanuvchi bloklar, rasm ko'rinishi, slug avtomatik to'ldirish
 */
(function () {
    'use strict';

    /* ------------------------------------------------------------------
     * Toaster
     * ------------------------------------------------------------------ */

    var toastRegion = document.querySelector('[data-admin-toasts]');

    function showToast(message, type) {
        if (!toastRegion || !message) {
            return;
        }

        var toast = document.createElement('div');
        toast.className = 'a-toast a-toast--' + (type === 'error' ? 'error' : 'success');
        toast.setAttribute('role', 'status');

        var icon = document.createElement('i');
        icon.className = type === 'error' ? 'bi bi-exclamation-circle' : 'bi bi-check-circle';
        icon.setAttribute('aria-hidden', 'true');

        var text = document.createElement('span');
        text.textContent = message;

        toast.appendChild(icon);
        toast.appendChild(text);
        toastRegion.appendChild(toast);

        window.setTimeout(function () {
            toast.classList.add('is-leaving');
            window.setTimeout(function () {
                toast.remove();
            }, 220);
        }, 3600);
    }

    window.AdminToast = { show: showToast };

    /* ------------------------------------------------------------------
     * Mobil yon panel
     * ------------------------------------------------------------------ */

    var sidebar = document.querySelector('[data-admin-sidebar]');
    var overlay = document.querySelector('[data-admin-overlay]');
    var menuToggle = document.querySelector('[data-admin-menu-toggle]');

    function closeSidebar() {
        if (!sidebar) {
            return;
        }

        sidebar.classList.remove('is-open');

        if (overlay) {
            overlay.classList.remove('is-active');
        }
    }

    if (sidebar && menuToggle) {
        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('is-open');

            if (overlay) {
                overlay.classList.toggle('is-active', sidebar.classList.contains('is-open'));
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    /* ------------------------------------------------------------------
     * Flash xabarlarni avtomatik yashirish
     * ------------------------------------------------------------------ */

    document.querySelectorAll('[data-flash]').forEach(function (flash) {
        window.setTimeout(function () {
            flash.classList.add('is-hiding');
        }, 4200);
    });

    /* ------------------------------------------------------------------
     * Rasm tanlaganda ko'rinishni ko'rsatish
     * ------------------------------------------------------------------ */

    function bindImagePreviews(scope) {
        (scope || document).querySelectorAll('[data-image-input]').forEach(function (input) {
            if (input.dataset.imageBound) {
                return;
            }

            input.dataset.imageBound = '1';

            input.addEventListener('change', function () {
                var preview = input.closest('.a-image');

                if (!preview || !input.files || !input.files[0]) {
                    return;
                }

                var box = preview.querySelector('[data-image-preview]');

                if (!box) {
                    return;
                }

                var url = URL.createObjectURL(input.files[0]);
                box.innerHTML = '';

                var img = document.createElement('img');
                img.src = url;
                img.alt = '';
                box.appendChild(img);
            });
        });
    }

    /* ------------------------------------------------------------------
     * Slug avtomatik to'ldirish (har bir til paneli ichida)
     * ------------------------------------------------------------------ */

    function slugify(value) {
        return value
            .toLowerCase()
            .replace(/[‘’'ʻ`]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function bindSlugAutofill(scope) {
        (scope || document).querySelectorAll('[data-slug-source]').forEach(function (source) {
            if (source.dataset.slugBound) {
                return;
            }

            source.dataset.slugBound = '1';

            var container = source.closest('[data-locale-pane]') || source.closest('form');
            var target = container ? container.querySelector('[data-slug-target]') : null;

            if (!target) {
                return;
            }

            var touched = target.value.trim() !== '';

            target.addEventListener('input', function () {
                touched = true;
            });

            source.addEventListener('input', function () {
                if (!touched) {
                    target.value = slugify(source.value);
                }
            });
        });
    }

    /* ------------------------------------------------------------------
     * Til tablari
     * ------------------------------------------------------------------ */

    document.addEventListener('click', function (event) {
        var tab = event.target.closest('[data-locale-tab]');

        if (!tab) {
            return;
        }

        event.preventDefault();

        var scope = tab.closest('[data-locale-scope]') || document;
        var code = tab.getAttribute('data-locale-tab');

        scope.querySelectorAll('[data-locale-tab]').forEach(function (button) {
            button.classList.toggle('is-active', button === tab);
        });

        scope.querySelectorAll('[data-locale-pane]').forEach(function (pane) {
            pane.hidden = pane.getAttribute('data-locale-pane') !== code;
        });
    });

    /* ------------------------------------------------------------------
     * Takrorlanuvchi bloklar
     * ------------------------------------------------------------------ */

    function bindRepeaters(scope) {
        (scope || document).querySelectorAll('[data-repeater]').forEach(function (repeater) {
            if (repeater.dataset.repeaterBound) {
                return;
            }

            repeater.dataset.repeaterBound = '1';

            var rows = repeater.querySelector('[data-repeater-rows]');
            var template = repeater.querySelector('template[data-repeater-template]');
            var addButton = repeater.querySelector('[data-repeater-add]');
            var counter = rows ? rows.children.length : 0;

            if (!rows || !template || !addButton) {
                return;
            }

            addButton.addEventListener('click', function () {
                var html = template.innerHTML.replace(/__INDEX__/g, String(counter));
                counter += 1;

                var holder = document.createElement('div');
                holder.innerHTML = html.trim();

                if (holder.firstElementChild) {
                    rows.appendChild(holder.firstElementChild);
                }
            });

            repeater.addEventListener('click', function (event) {
                var removeButton = event.target.closest('[data-repeater-remove]');

                if (removeButton && removeButton.parentElement) {
                    removeButton.parentElement.remove();
                }
            });
        });
    }

    /* ------------------------------------------------------------------
     * Modal
     * ------------------------------------------------------------------ */

    var modal = document.querySelector('[data-admin-modal]');
    var modalContent = document.querySelector('[data-admin-modal-content]');
    var modalTitle = document.querySelector('[data-admin-modal-title]');

    function openModal() {
        if (modal) {
            modal.hidden = false;
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        if (modal) {
            modal.hidden = true;
            document.body.style.overflow = '';
        }

        if (modalContent) {
            modalContent.innerHTML = '<p class="a-modal__loading">Yuklanmoqda...</p>';
        }
    }

    window.AdminModal = { open: openModal, close: closeModal };

    if (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal || event.target.closest('[data-admin-modal-close]')) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.hidden) {
                closeModal();
            }
        });
    }

    function loadPanel(url, title) {
        if (!modalContent) {
            window.location.href = url;
            return;
        }

        if (modalTitle) {
            modalTitle.textContent = title || 'Yuklanmoqda...';
        }

        modalContent.innerHTML = '<p class="a-modal__loading">Yuklanmoqda...</p>';
        openModal();

        window.fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'text/html',
            },
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('panel');
                }

                return response.text();
            })
            .then(function (html) {
                modalContent.innerHTML = html;
                bindAll(modalContent);
            })
            .catch(function () {
                closeModal();
                showToast('Formani yuklab bo‘lmadi. Qayta urinib ko‘ring.', 'error');
            });
    }

    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-admin-panel]');

        if (trigger) {
            event.preventDefault();
            loadPanel(trigger.getAttribute('data-admin-panel'), trigger.getAttribute('data-admin-title'));
            return;
        }

        var close = event.target.closest('[data-admin-close]');

        if (close && modal && !modal.hidden) {
            event.preventDefault();
            closeModal();
        }
    });

    /* ------------------------------------------------------------------
     * Jadvalni yangilash
     * ------------------------------------------------------------------ */

    var tableHost = document.querySelector('[data-admin-table]');

    function refreshTable() {
        if (!tableHost) {
            return;
        }

        var url = tableHost.getAttribute('data-admin-table-url');

        if (!url) {
            return;
        }

        window.fetch(url + (url.indexOf('?') === -1 ? '?' : '&') + 'fragment=1', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'text/html' },
        })
            .then(function (response) { return response.text(); })
            .then(function (html) {
                tableHost.innerHTML = html;
                bindAll(tableHost);
            });
    }

    /* ------------------------------------------------------------------
     * Forma yuborish (modal va to'liq sahifa)
     * ------------------------------------------------------------------ */

    document.addEventListener('submit', function (event) {
        var form = event.target;

        // O'chirish formasi
        if (form.matches('[data-admin-delete]')) {
            event.preventDefault();
            submitDelete(form);
            return;
        }

        // CRUD formasi
        if (form.matches('[data-admin-form]')) {
            event.preventDefault();
            submitForm(form);
        }
    });

    function submitForm(form) {
        var button = form.querySelector('[type="submit"]');
        var origin = modal && modal.contains(form) ? 'modal' : 'page';

        clearErrors(form);

        if (button) {
            button.classList.add('is-loading');
        }

        window.fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return { status: response.status, payload: payload };
                });
            })
            .then(function (result) {
                if (result.status === 422) {
                    showErrors(form, result.payload.errors || {});
                    showToast(firstError(result.payload.errors) || 'Ma’lumotlarni tekshiring.', 'error');
                    return;
                }

                if (result.status >= 400 || result.payload.ok === false) {
                    showToast(result.payload.message || 'Xatolik yuz berdi.', 'error');
                    return;
                }

                showToast(result.payload.message || 'Saqlandi.', 'success');

                if (origin === 'modal') {
                    closeModal();
                    refreshTable();
                } else {
                    var index = form.getAttribute('data-admin-index');

                    window.location.href = index || window.location.href;
                }
            })
            .catch(function () {
                showToast('Tarmoqda xatolik. Qayta urinib ko‘ring.', 'error');
            })
            .finally(function () {
                if (button) {
                    button.classList.remove('is-loading');
                }
            });
    }

    function submitDelete(form) {
        var message = form.getAttribute('data-confirm');

        if (message && !window.confirm(message)) {
            return;
        }

        window.fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return { status: response.status, payload: payload };
                });
            })
            .then(function (result) {
                if (result.status >= 400 || result.payload.ok === false) {
                    showToast(result.payload.message || 'O‘chirib bo‘lmadi.', 'error');
                    return;
                }

                showToast(result.payload.message || 'O‘chirildi.', 'success');
                refreshTable();
            })
            .catch(function () {
                showToast('Tarmoqda xatolik. Qayta urinib ko‘ring.', 'error');
            });
    }

    /* ------------------------------------------------------------------
     * Holat kaliti (toggle)
     * ------------------------------------------------------------------ */

    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-admin-toggle]');

        if (!toggle) {
            return;
        }

        event.preventDefault();

        window.fetch(toggle.getAttribute('data-admin-toggle'), {
            method: 'PATCH',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return { status: response.status, payload: payload };
                });
            })
            .then(function (result) {
                if (result.status >= 400 || result.payload.ok === false) {
                    showToast(result.payload.message || 'O‘zgartirib bo‘lmadi.', 'error');
                    return;
                }

                toggle.classList.toggle('is-on', !!result.payload.value);
                showToast(result.payload.message || 'Yangilandi.', 'success');
            })
            .catch(function () {
                showToast('Tarmoqda xatolik. Qayta urinib ko‘ring.', 'error');
            });
    });

    /* ------------------------------------------------------------------
     * Umumiy amallar (masalan, «Asosiy qilish»)
     * ------------------------------------------------------------------ */

    document.addEventListener('click', function (event) {
        var action = event.target.closest('[data-admin-action]');

        if (!action) {
            return;
        }

        event.preventDefault();

        window.fetch(action.getAttribute('data-admin-action'), {
            method: action.getAttribute('data-admin-method') || 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return { status: response.status, payload: payload };
                });
            })
            .then(function (result) {
                if (result.status >= 400 || result.payload.ok === false) {
                    showToast(result.payload.message || 'Amalni bajarib bo‘lmadi.', 'error');
                    return;
                }

                showToast(result.payload.message || 'Bajarildi.', 'success');
                refreshTable();
            })
            .catch(function () {
                showToast('Tarmoqda xatolik. Qayta urinib ko‘ring.', 'error');
            });
    });

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');

        if (meta) {
            return meta.getAttribute('content');
        }

        var input = document.querySelector('input[name="_token"]');

        return input ? input.value : '';
    }

    /* ------------------------------------------------------------------
     * Xatoliklarni ko'rsatish
     * ------------------------------------------------------------------ */

    function clearErrors(form) {
        form.querySelectorAll('.a-error.is-field').forEach(function (element) {
            element.remove();
        });

        form.querySelectorAll('.is-invalid').forEach(function (element) {
            element.classList.remove('is-invalid');
        });
    }

    function showErrors(form, errors) {
        Object.keys(errors).forEach(function (key) {
            var field = form.querySelector('[name="' + key.replace(/\./g, '][').replace(/\]\[/g, '][') + '"]')
                || form.querySelector('[name="' + key.replace(/\./g, '][').replace(/([^\[\]]+)/g, '$1') + '"]');

            // Nuqtali kalitni input nomiga aylantiramiz: translations.uz.title -> translations[uz][title]
            var name = key.split('.').map(function (part, index) {
                return index === 0 ? part : '[' + part + ']';
            }).join('');

            field = form.querySelector('[name="' + name + '"]');

            if (!field) {
                return;
            }

            field.classList.add('is-invalid');

            var holder = field.closest('.a-field') || field.parentElement;
            var error = document.createElement('p');
            error.className = 'a-error is-field';
            error.textContent = errors[key][0];
            holder.appendChild(error);
        });
    }

    function firstError(errors) {
        var keys = Object.keys(errors || {});

        return keys.length ? errors[keys[0]][0] : null;
    }

    /* ------------------------------------------------------------------
     * Umumiy bog'lash
     * ------------------------------------------------------------------ */

    function bindAll(scope) {
        bindImagePreviews(scope);
        bindSlugAutofill(scope);
        bindRepeaters(scope);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { bindAll(document); });
    } else {
        bindAll(document);
    }
})();
