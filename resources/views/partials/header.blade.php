@php($brand = \App\Support\SiteSettings::brand())

<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <!-- 1. Brend & Shahar tanlash -->
        <div class="site-header__brand-group">
            <a class="brand" href="{{ route('home') }}" aria-label="Bodomgul">
                <span class="brand__mark" aria-hidden="true">@include('partials.logo-mark')</span>
            </a>

            {{-- <button type="button" class="header-pill-btn header-pill-btn--city" aria-label="Shahar tanlash">
                <span class="header-pill-btn__text">{{ $brand['city'] ?? 'Toshkent' }}</span>
                <i class="bi bi-geo-alt" aria-hidden="true"></i>
            </button> --}}
        </div>

        <!-- 2. Asosiy menyu havolalari -->
        @include('partials.desktop-navigation')

        <!-- 3. O'ng tarafdagi amallar: Telefon, Qidiruv, Til, Sevimlilar, Savatcha, Mobil menyu -->
        <div class="site-header__actions">
            <a href="tel:{{ preg_replace('/[^\d+]/', '', $brand['phone'] ?? '+998781134040') }}" class="header-pill-btn header-pill-btn--phone" aria-label="Telefon: {{ $brand['phone_display'] ?? '78 113 40 40' }}">
                <span class="header-pill-btn__text">{{ $brand['phone_display'] ?? '78 113 40 40' }}</span>
                <i class="bi bi-telephone" aria-hidden="true"></i>
            </a>

            <button type="button" class="header-pill-btn header-pill-btn--icon" data-search-toggle aria-expanded="false" aria-controls="site-search" aria-label="{{ t('header.search') }}">
                <i class="bi bi-search" aria-hidden="true"></i>
            </button>

            @include('partials.language-switcher')

            <a class="header-pill-btn header-pill-btn--icon" href="{{ route('favorites.index') }}" data-favorites-link aria-label="{{ t('header.favorites') }}">
                <i class="bi bi-heart" aria-hidden="true"></i>
                <span class="header-pill-btn__count" data-favorites-count hidden>0</span>
            </a>

            <a class="header-pill-btn header-pill-btn--icon" href="{{ route('cart.index') }}" data-cart-link aria-label="{{ t('header.cart') }}">
                <i class="bi bi-bag" aria-hidden="true"></i>
                <span class="header-pill-btn__count" data-cart-count hidden>0</span>
            </a>

            <button type="button" class="header-pill-btn header-pill-btn--icon header-pill-btn--menu" data-mobile-nav-toggle aria-expanded="false" aria-controls="mobile-nav" aria-label="{{ t('header.menu') }}">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <!-- Qidiruv paneli -->
    <div class="site-search" id="site-search" data-search-panel hidden>
        <div class="container">
            <form class="site-search__form" action="{{ route('catalog.index') }}" method="get" role="search">
                <i class="bi bi-search" aria-hidden="true"></i>
                <label class="visually-hidden" for="site-search-input">{{ t('header.search_label') }}</label>
                <input
                    id="site-search-input"
                    class="site-search__input"
                    type="search"
                    name="q"
                    placeholder="{{ t('header.search_placeholder') }}"
                    value="{{ request()->routeIs('catalog.*') ? request('q') : '' }}"
                    autocomplete="off"
                    data-search-input
                >
                <button class="btn btn--primary btn--sm" type="submit">{{ t('header.search_button') }}</button>
            </form>
        </div>
    </div>
</header>
