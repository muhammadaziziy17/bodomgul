@extends('layouts.app')

@section('title', t('meta.home_title'))
@section('meta_description', t('meta.home_description'))

@section('content')
    {{-- Hero slayder --}}
    <div class="section-gap">
        <div class="hero-card-frame">
            <div class="swiper hero-swiper" data-hero-swiper>
                <div class="swiper-wrapper">
                    @foreach ($heroSlides as $slide)
                        <div class="swiper-slide hero-slide">
                            <img
                                class="hero-slide__media"
                                src="{{ asset($slide['image']) }}"
                                alt="{{ $slide['alt'] }}"
                                width="1920"
                                height="1080"
                                @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
                            >
                            <div class="hero-slide__overlay" aria-hidden="true"></div>

                            <div class="hero-slide__content">
                                <p class="hero-slide__eyebrow">{{ $slide['eyebrow'] }}</p>
                                <h2 class="hero-slide__title">{{ $slide['title'] }}</h2>
                                <p class="hero-slide__text">{{ $slide['text'] }}</p>

                                <div class="hero-slide__actions">
                                    @if ($slide['primary_label'])
                                        <x-button :href="\App\Support\RouteOptions::url($slide['primary_route'], $slide['primary_params'] ?? [])" variant="light" size="lg">
                                            {{ $slide['primary_label'] }}
                                        </x-button>
                                    @endif
                                    @if ($slide['secondary_label'])
                                        <x-button :href="\App\Support\RouteOptions::url($slide['secondary_route'])" variant="outline-light" size="lg">
                                            {{ $slide['secondary_label'] }}
                                        </x-button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="hero-swiper__btn hero-swiper__btn--prev" aria-label="{{ t('home.hero.prev') }}">
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                </button>
                <button type="button" class="hero-swiper__btn hero-swiper__btn--next" aria-label="{{ t('home.hero.next') }}">
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </button>

                <div class="swiper-pagination hero-swiper__pagination"></div>
                <div class="hero-swiper__progress" aria-hidden="true">
                    <div class="hero-swiper__progress-bar"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Katalog --}}
    <section class="safia-slab">
        <div class="container">
            <div class="slab-head">
                <h2 class="slab-head__title">Katalog</h2>

                <div class="section-head__actions">
                    <button type="button" class="round-btn" data-catalog-prev aria-label="{{ t('home.catalog.prev') }}">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="round-btn" data-catalog-next aria-label="{{ t('home.catalog.next') }}">
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="catalog-rail">
                <div class="swiper catalog-swiper" data-catalog-swiper>
                    <div class="swiper-wrapper">
                        @foreach ($categories as $category)
                            <div class="swiper-slide">
                                <x-category-card :category="$category" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php($appPromo = \App\Support\SiteSettings::app())

    @if ($appPromo['enabled'] ?? true)
        {{-- Mobil ilova --}}
        <div id="mobile-app" class="safia-slab safia-slab--overflow">
            <div class="marquee-giant" aria-hidden="true">
                <div class="marquee-giant__track">
                    @for ($i = 0; $i < 8; $i++)
                        <span class="marquee-giant__item">
                            <span class="marquee-giant__accent">mobil</span> ilova •
                        </span>
                    @endfor
                </div>
            </div>

            <div class="container">
                <div class="app-promo">
                    <div class="app-promo__copy reveal reveal--left">
                        <h3 class="app-promo__title">
                            {{ t('home.app.title') }}
                        </h3>
                        <p class="app-promo__text">
                            {{ t('home.app.text') }}
                        </p>

                        <div class="app-promo__actions">
                            <div class="app-promo__qr">
                                <i class="bi bi-qr-code" aria-hidden="true"></i>
                                <div>
                                    <strong>{{ t('home.app.qr_title') }}</strong>
                                    <span>{{ t('home.app.qr_hint') }}</span>
                                </div>
                            </div>

                            @if (! empty($appPromo['google_play']))
                                <a href="{{ $appPromo['google_play'] }}" target="_blank" rel="noopener noreferrer" class="app-promo__store">
                                    <i class="bi bi-google-play" aria-hidden="true"></i> Google Play
                                </a>
                            @endif

                            @if (! empty($appPromo['app_store']))
                                <a href="{{ $appPromo['app_store'] }}" target="_blank" rel="noopener noreferrer" class="app-promo__store">
                                    <i class="bi bi-apple" aria-hidden="true"></i> App Store
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="app-promo__media reveal reveal--right">
                        <img
                            src="{{ asset('images/brand/app.svg') }}"
                            alt="Bodomgul mobil ilovasi"
                            width="360"
                            height="420"
                            loading="lazy"
                        >
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- {{ t('home.products.title') }} --}}
    <section class="safia-slab">
        <div class="container">
            <div class="slab-head">
                <div>
                    <h2 class="slab-head__title">{{ t('home.products.title') }}</h2>
                    <p class="slab-head__subtitle">{{ t('home.products.subtitle') }}</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="slab-head__link">
                    {{ t('common.view_all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <div class="product-grid-safia">
                @foreach ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Mavsumiy promo --}}
    <section class="safia-slab safia-slab--compact" aria-label="{{ t('home.promo_label') }}">
        <div class="container">
            <div class="promo-banner reveal reveal--zoom">
                <img class="promo-banner__bg" src="{{ asset($promo['image']) }}" alt="" aria-hidden="true" loading="lazy">
                <div class="promo-banner__inner">
                    <p class="promo-banner__eyebrow">{{ $promo['eyebrow'] }}</p>
                    <h2 class="promo-banner__title">{{ $promo['title'] }}</h2>
                    <p class="promo-banner__text">{{ $promo['text'] }}</p>
                    @if (! empty($promo['cta_label']))
                        <x-button :href="\App\Support\RouteOptions::url($promo['cta_route'] ?? null, $promo['cta_params'] ?? [])" variant="light" icon-after="bi-arrow-right">
                            {{ $promo['cta_label'] }}
                        </x-button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Oilamizda bo'ling --}}
    <section class="safia-slab" aria-label="Karyera va jamoa">
        <div class="container">
            <div class="join-band__head">
                <h2 class="join-band__title">
                    {{ t('home.join.title') }} <span class="join-band__accent">{{ t('home.join.title_accent') }}</span>
                </h2>
                <div class="join-band__badge">
                    <img
                        src="{{ asset('images/brand/logo-emblem.png') }}"
                        alt="Bodomgul jamoasi"
                        class="join-band__avatar"
                        loading="lazy"
                    >
                    <div>
                        <strong>{{ t('home.join.badge_title') }}</strong>
                        <span>{{ t('home.join.badge_text') }}</span>
                    </div>
                </div>
            </div>

            <div class="feature-grid feature-grid--3 join-band__perks">
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="bi bi-graph-up-arrow" aria-hidden="true"></i>
                    </div>
                    <h3 class="feature-card__title">{{ t('home.join.perk1_title') }}</h3>
                    <p class="feature-card__text">
                        {{ t('home.join.perk1_text') }}
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="bi bi-cup-hot" aria-hidden="true"></i>
                    </div>
                    <h3 class="feature-card__title">{{ t('home.join.perk2_title') }}</h3>
                    <p class="feature-card__text">
                        {{ t('home.join.perk2_text') }}
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                    </div>
                    <h3 class="feature-card__title">{{ t('home.join.perk3_title') }}</h3>
                    <p class="feature-card__text">
                        {{ t('home.join.perk3_text') }}
                    </p>
                </div>
            </div>

            <div class="join-band__cta">
                <div class="join-band__cta-quote">
                    <i class="bi bi-quote" aria-hidden="true"></i>
                    <p>
                        {{ t('home.join.quote') }}
                    </p>
                </div>
                <a href="{{ route('careers') }}" class="btn btn--light btn--lg">
                    <span>{{ t('home.join.cta') }}</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Yangiliklar --}}
    <section class="safia-slab">
        <div class="container">
            <div class="slab-head">
                <div>
                    <h2 class="slab-head__title">{{ t('home.news.title') }}</h2>
                    <p class="slab-head__subtitle">{{ t('home.news.subtitle') }}</p>
                </div>
                <a href="{{ route('news.index') }}" class="slab-head__link">
                    {{ t('home.news.view_all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <div class="news-grid">
                @foreach ($news as $item)
                    <x-news-card :item="$item" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Filiallar --}}
    <section class="safia-slab">
        <div class="container">
            <div class="slab-head">
                <div>
                    <h2 class="slab-head__title">{{ t('home.branches.title') }}</h2>
                    <p class="slab-head__subtitle">{{ t('home.branches.subtitle') }}</p>
                </div>
                <a href="{{ route('branches.index') }}" class="slab-head__link">
                    {{ t('home.branches.view_all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <div class="branch-grid">
                @foreach (array_slice($branches, 0, 3) as $branch)
                    <x-branch-card :branch="$branch" />
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js" defer></script>
    <script src="{{ asset('js/hero-slider.js') }}" defer></script>
@endpush
