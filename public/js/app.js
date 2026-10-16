/**
 * Bodomgul — umumiy UI: mobil menyu, qidiruv paneli, xabarnoma,
 * toast xabarlar, miqdor stepper va rasm fallback.
 */
(function () {
    'use strict';

    var html = document.documentElement;

    /* Toast xabarlar */

    var toastRegion = document.querySelector('[data-toast-region]');

    function showToast(message, iconClass) {
        if (!toastRegion) {
            return;
        }

        var toast = document.createElement('div');
        toast.className = 'toast';
        toast.setAttribute('role', 'status');

        var icon = document.createElement('i');
        icon.className = 'bi ' + (iconClass || 'bi-check-circle');
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
        }, 2800);
    }

    window.BgToast = { show: showToast };

    /* Announcement bar yopish */

    var announcementClose = document.querySelector('[data-announcement-close]');

    if (announcementClose) {
        announcementClose.addEventListener('click', function () {
            html.classList.add('announcement-dismissed');

            try {
                sessionStorage.setItem('bodomgul:announcement-dismissed', '1');
            } catch (error) {
                /* sessionStorage mavjud bo'lmasa ham davom etamiz */
            }
        });
    }

    /* Header qidiruv paneli */

    var searchToggle = document.querySelector('[data-search-toggle]');
    var searchPanel = document.querySelector('[data-search-panel]');
    var searchInput = document.querySelector('[data-search-input]');

    if (searchToggle && searchPanel) {
        searchToggle.addEventListener('click', function () {
            var willOpen = searchPanel.hidden;
            searchPanel.hidden = !willOpen;
            searchToggle.setAttribute('aria-expanded', String(willOpen));

            if (willOpen && searchInput) {
                searchInput.focus();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !searchPanel.hidden) {
                searchPanel.hidden = true;
                searchToggle.setAttribute('aria-expanded', 'false');
                searchToggle.focus();
            }
        });
    }

    /* Mobil menyu (drawer) */

    var drawer = document.querySelector('[data-mobile-nav]');
    var overlay = document.querySelector('[data-mobile-nav-overlay]');
    var openButton = document.querySelector('[data-mobile-nav-toggle]');
    var closeButton = document.querySelector('[data-mobile-nav-close]');
    var lastFocused = null;

    function openDrawer() {
        if (!drawer) {
            return;
        }

        lastFocused = document.activeElement;
        drawer.classList.add('is-open');

        if (overlay) {
            overlay.classList.add('is-active');
        }

        document.body.classList.add('nav-open');
        openButton.setAttribute('aria-expanded', 'true');

        if (window.BgLenis) {
            window.BgLenis.stop();
        }

        if (closeButton) {
            closeButton.focus();
        }
    }

    function closeDrawer() {
        if (!drawer) {
            return;
        }

        drawer.classList.remove('is-open');

        if (overlay) {
            overlay.classList.remove('is-active');
        }

        document.body.classList.remove('nav-open');
        openButton.setAttribute('aria-expanded', 'false');

        if (window.BgLenis) {
            window.BgLenis.start();
        }

        if (lastFocused && typeof lastFocused.focus === 'function') {
            lastFocused.focus();
        }
    }

    if (openButton && drawer) {
        openButton.addEventListener('click', openDrawer);
    }

    if (closeButton) {
        closeButton.addEventListener('click', closeDrawer);
    }

    if (overlay) {
        overlay.addEventListener('click', closeDrawer);
    }

    if (drawer) {
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && drawer.classList.contains('is-open')) {
                closeDrawer();
            }
        });

        /* Klaviatura fokusi drawer ichida aylanadi */
        drawer.addEventListener('keydown', function (event) {
            if (event.key !== 'Tab') {
                return;
            }

            var focusables = drawer.querySelectorAll(
                'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );

            if (!focusables.length) {
                return;
            }

            var first = focusables[0];
            var last = focusables[focusables.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });
    }

    /* Miqdor stepper (mahsulot sahifasi) */

    document.addEventListener('click', function (event) {
        var stepper = event.target.closest('[data-qty-stepper]');

        if (!stepper) {
            return;
        }

        var input = stepper.querySelector('input');

        if (!input) {
            return;
        }

        var min = parseInt(input.min, 10) || 1;
        var max = parseInt(input.max, 10) || 99;
        var value = parseInt(input.value, 10) || min;

        if (event.target.closest('[data-qty-plus]')) {
            value = Math.min(value + 1, max);
        } else if (event.target.closest('[data-qty-minus]')) {
            value = Math.max(value - 1, min);
        } else {
            return;
        }

        input.value = String(value);
    });

    document.addEventListener('change', function (event) {
        var stepper = event.target.closest('[data-qty-stepper]');

        if (!stepper || event.target.tagName !== 'INPUT') {
            return;
        }

        var input = event.target;
        var min = parseInt(input.min, 10) || 1;
        var max = parseInt(input.max, 10) || 99;
        var value = parseInt(input.value, 10);

        if (!Number.isInteger(value)) {
            value = min;
        }

        input.value = String(Math.min(Math.max(value, min), max));
    });

    /* Rasm yuklanmasa — placeholder ko'rsatiladi */

    document.addEventListener('error', function (event) {
        var target = event.target;

        if (!target || target.tagName !== 'IMG' || target.dataset.fallbackApplied) {
            return;
        }

        target.dataset.fallbackApplied = '1';
        target.src = target.dataset.fallback || html.dataset.placeholder || '/images/placeholders/placeholder.svg';
    }, true);

    /* ----------------------------------------------------------------------
       Scroll-driven Reveal & Mikro-animatsiyalar
       ---------------------------------------------------------------------- */

    /* ----------------------------------------------------------------------
       Scroll-driven Reveal & Mikro-animatsiyalar
       ---------------------------------------------------------------------- */

    function initScrollReveal() {
        var prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var targets = document.querySelectorAll(
            '.reveal, .reveal--left, .reveal--right, .reveal--zoom, ' +
            '.slab-head, .section-title, .product-card, .safia-cat-card, .news-card, .branch-card, ' +
            '.feature-card, .join-band__head, .join-band__cta, .app-promo, .app-promo__copy, ' +
            '.app-promo__media, .promo-banner, .value-card, .story, .promo-banner__inner, ' +
            '.cta-band, .brand-quote, [data-reveal]'
        );

        if (!targets.length) {
            return;
        }

        if (prefersReduced || !('IntersectionObserver' in window)) {
            targets.forEach(function (el) {
                el.classList.add('is-revealed');
            });
            return;
        }

        // Grid ichidagi elementlarga ketma-ket kechikish (stagger) berish
        document.querySelectorAll(
            '.category-grid, .product-grid, .product-grid-safia, .feature-grid, .news-grid, ' +
            '.branch-grid, .value-grid, .catalog-swiper .swiper-wrapper'
        ).forEach(function (grid) {
            Array.from(grid.children).forEach(function (child, index) {
                var delay = (index % 6) + 1;
                child.classList.add('reveal--delay-' + delay);
            });
        });

        var observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    obs.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '0px 0px -20px 0px',
            threshold: 0.05
        });

        var windowHeight = window.innerHeight || document.documentElement.clientHeight || 800;

        targets.forEach(function (el) {
            el.classList.add('reveal');
            var rect = el.getBoundingClientRect();

            // Agar element allaqachon ekranda ko'rinib turgan bo'lsa, uni darhol ko'rsatamiz
            if (rect.top <= windowHeight + 60 && rect.bottom >= -20) {
                el.classList.add('is-revealed');
            } else {
                observer.observe(el);
            }
        });

        // Xavfsizlik kafolati: har qanday holatda ham elementlar ko'rinmay qolishining oldini olamiz
        window.setTimeout(function () {
            targets.forEach(function (el) {
                el.classList.add('is-revealed');
            });
        }, 1200);
    }

    /* Sticky header scroll effekti */
    function initStickyHeader() {
        var header = document.querySelector('[data-site-header]');
        if (!header) {
            return;
        }

        var checkHeaderScroll = function () {
            if (window.scrollY > 8) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        };

        window.addEventListener('scroll', checkHeaderScroll, { passive: true });
        checkHeaderScroll();
    }

    /* ----------------------------------------------------------------------
       Lenis — Inertial Smooth Scroll ("og'irroq", vazmin silliq skroll)
       ---------------------------------------------------------------------- */

    function initSmoothScroll() {
        var prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReduced) {
            return;
        }

        if (typeof window.Lenis !== 'undefined') {
            var lenis = new window.Lenis({
                duration: 1.25, // silliq va vazminroq o'tish
                easing: function (t) {
                    return Math.min(1, 1.001 - Math.pow(2, -10 * t));
                },
                orientation: 'vertical',
                gestureOrientation: 'vertical',
                smoothWheel: true,
                wheelMultiplier: 0.85, // og'irroq, elastik va vazmin harakat
                touchMultiplier: 1.5,
                infinite: false
            });

            window.BgLenis = lenis;

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }

            requestAnimationFrame(raf);

            // Header scroll sinxronizatsiyasi
            lenis.on('scroll', function (e) {
                var header = document.querySelector('[data-site-header]');
                if (header) {
                    if (e.scroll > 8) {
                        header.classList.add('is-scrolled');
                    } else {
                        header.classList.remove('is-scrolled');
                    }
                }
            });

            // Barcha ichki linklar (#...) bosilganda Lenis orqali silliq o'tish
            document.addEventListener('click', function (event) {
                var link = event.target.closest('a[href^="#"]');
                if (!link) {
                    return;
                }

                var hash = link.getAttribute('href');
                if (!hash || hash === '#' || hash === '#!') {
                    return;
                }

                var target = document.querySelector(hash);
                if (target) {
                    event.preventDefault();
                    var header = document.querySelector('[data-site-header]');
                    var headerOffset = header ? -(header.offsetHeight + 14) : -80;

                    lenis.scrollTo(target, {
                        offset: headerOffset,
                        duration: 1.25
                    });

                    if (window.history && window.history.pushState) {
                        window.history.pushState(null, null, hash);
                    }
                }
            });
        } else {
            // Zaxira: oddiy brauzer smooth scroll
            document.addEventListener('click', function (event) {
                var link = event.target.closest('a[href^="#"]');
                if (!link) {
                    return;
                }

                var hash = link.getAttribute('href');
                if (!hash || hash === '#' || hash === '#!') {
                    return;
                }

                var target = document.querySelector(hash);
                if (target) {
                    event.preventDefault();
                    var header = document.querySelector('[data-site-header]');
                    var headerOffset = header ? header.offsetHeight + 14 : 80;
                    var elementPosition = target.getBoundingClientRect().top;
                    var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: Math.max(0, offsetPosition),
                        behavior: 'smooth'
                    });

                    if (window.history && window.history.pushState) {
                        window.history.pushState(null, null, hash);
                    }
                }
            });
        }
    }

    function triggerBadgeBump(selector) {
        var badges = document.querySelectorAll(selector);
        badges.forEach(function (badge) {
            badge.classList.remove('is-bumping');
            // Re-trigger CSS animatsiyasi uchun reflow
            void badge.offsetWidth;
            badge.classList.add('is-bumping');
            window.setTimeout(function () {
                badge.classList.remove('is-bumping');
            }, 450);
        });
    }

    document.addEventListener('bodomgul:cart-updated', function () {
        triggerBadgeBump('[data-cart-count]');
    });

    document.addEventListener('bodomgul:favorites-updated', function () {
        triggerBadgeBump('[data-favorites-count]');
    });

    document.addEventListener('click', function (event) {
        var favBtn = event.target.closest('[data-favorite-toggle]');
        if (favBtn) {
            favBtn.classList.remove('is-popping');
            void favBtn.offsetWidth;
            favBtn.classList.add('is-popping');
            window.setTimeout(function () {
                favBtn.classList.remove('is-popping');
            }, 450);
        }

        var pressBtn = event.target.closest('.btn, .round-btn, .product-card__btn-pill, .header-pill-btn');
        if (pressBtn) {
            pressBtn.classList.add('is-pressed');
            window.setTimeout(function () {
                pressBtn.classList.remove('is-pressed');
            }, 200);
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initScrollReveal();
            initStickyHeader();
            initSmoothScroll();
        });
    } else {
        initScrollReveal();
        initStickyHeader();
        initSmoothScroll();
    }
})();
