/**
 * Bodomgul — slayderlar (Swiper): hero karusel va katalog lenti.
 * Avtomatik slayd o'tishi (autoplay), progress taymer va silliq animatsiyalar.
 */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var AUTOPLAY_DELAY = 4500; // 4.5 soniya — qulay va o'z vaqtida avtomatik o'tish

    /* --------------------------------------------------------------------------
       Hero karusel
       -------------------------------------------------------------------------- */

    function initHeroSwiper() {
        var heroContainer = document.querySelector('[data-hero-swiper]');
        if (!heroContainer) {
            return;
        }

        // Agar Swiper CDN scripti hali to'liq yuklanmagan bo'lsa, kutamiz
        if (typeof window.Swiper === 'undefined') {
            var checkCount = 0;
            var interval = setInterval(function () {
                checkCount++;
                if (typeof window.Swiper !== 'undefined') {
                    clearInterval(interval);
                    mountHeroSwiper(heroContainer);
                } else if (checkCount > 40) {
                    clearInterval(interval);
                    mountFallbackHeroSlider(heroContainer);
                }
            }, 100);
            return;
        }

        mountHeroSwiper(heroContainer);
    }

    function mountHeroSwiper(heroContainer) {
        var progressBar = heroContainer.querySelector('.hero-swiper__progress-bar');

        function triggerProgress() {
            if (!progressBar || reduceMotion) {
                return;
            }

            progressBar.classList.remove('is-animating');
            progressBar.style.transition = 'none';
            progressBar.style.width = '0%';
            void progressBar.offsetWidth; // browser reflow

            progressBar.style.transition = 'width ' + AUTOPLAY_DELAY + 'ms linear';
            progressBar.classList.add('is-animating');
            progressBar.style.width = '100%';
        }

        var swiper = new window.Swiper(heroContainer, {
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            loop: true,
            speed: 850,
            grabCursor: true,
            keyboard: { enabled: true },
            a11y: { enabled: true },
            autoplay: reduceMotion
                ? false
                : {
                    delay: AUTOPLAY_DELAY,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: false
                },
            navigation: {
                prevEl: heroContainer.querySelector('.hero-swiper__btn--prev'),
                nextEl: heroContainer.querySelector('.hero-swiper__btn--next')
            },
            pagination: {
                el: heroContainer.querySelector('.swiper-pagination'),
                clickable: true
            },
            on: {
                init: function () {
                    triggerProgress();
                },
                slideChangeTransitionStart: function () {
                    triggerProgress();

                    // Matn animatsiyalarini har bir slayd almashganda qayta faollashtirish
                    var activeSlide = heroContainer.querySelector('.swiper-slide-active');
                    if (activeSlide) {
                        var animatedEls = activeSlide.querySelectorAll(
                            '.hero-slide__eyebrow, .hero-slide__title, .hero-slide__text, .hero-slide__actions'
                        );
                        animatedEls.forEach(function (el) {
                            el.style.animation = 'none';
                            void el.offsetWidth;
                            el.style.animation = '';
                        });
                    }
                }
            }
        });

        // Autoplay doimo faol bo'lishini ta'minlash
        if (!reduceMotion && swiper.autoplay) {
            swiper.autoplay.start();
        }
    }

    /* Agar internet yoki CDN uzilsa — zaxira sof JS avtomatik slayder */
    function mountFallbackHeroSlider(heroContainer) {
        var slides = heroContainer.querySelectorAll('.hero-slide');
        if (slides.length <= 1) {
            return;
        }

        var currentIndex = 0;
        slides.forEach(function (slide, idx) {
            slide.style.display = idx === 0 ? 'flex' : 'none';
            slide.style.opacity = idx === 0 ? '1' : '0';
            slide.style.transition = 'opacity 0.8s ease';
        });

        if (!reduceMotion) {
            setInterval(function () {
                slides[currentIndex].style.opacity = '0';
                setTimeout(function () {
                    slides[currentIndex].style.display = 'none';
                    currentIndex = (currentIndex + 1) % slides.length;
                    slides[currentIndex].style.display = 'flex';
                    void slides[currentIndex].offsetWidth;
                    slides[currentIndex].style.opacity = '1';
                }, 300);
            }, AUTOPLAY_DELAY);
        }
    }

    /* --------------------------------------------------------------------------
       Katalog lenti (gorizontal kategoriya karuseli)
       -------------------------------------------------------------------------- */

    function initCatalogSwiper() {
        var catalogContainer = document.querySelector('[data-catalog-swiper]');
        if (!catalogContainer) {
            return;
        }

        if (typeof window.Swiper === 'undefined') {
            var checkCount = 0;
            var interval = setInterval(function () {
                checkCount++;
                if (typeof window.Swiper !== 'undefined') {
                    clearInterval(interval);
                    mountCatalogSwiper(catalogContainer);
                } else if (checkCount > 40) {
                    clearInterval(interval);
                }
            }, 100);
            return;
        }

        mountCatalogSwiper(catalogContainer);
    }

    function mountCatalogSwiper(catalogContainer) {
        new window.Swiper(catalogContainer, {
            slidesPerView: 'auto',
            spaceBetween: 12,
            grabCursor: true,
            watchOverflow: true,
            freeMode: {
                enabled: true,
                sticky: false
            },
            a11y: { enabled: true },
            breakpoints: {
                1024: {
                    spaceBetween: 16
                }
            },
            navigation: {
                prevEl: document.querySelector('[data-catalog-prev]'),
                nextEl: document.querySelector('[data-catalog-next]')
            }
        });
    }

    /* Boshlash */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initHeroSwiper();
            initCatalogSwiper();
        });
    } else {
        initHeroSwiper();
        initCatalogSwiper();
    }
})();
