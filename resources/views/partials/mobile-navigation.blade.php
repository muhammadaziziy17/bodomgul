@php
    $brand = \App\Support\SiteSettings::brand();
    $navigation = config('bodomgul.navigation');
@endphp

<div class="nav-overlay" data-mobile-nav-overlay></div>

<aside class="mobile-nav" id="mobile-nav" data-mobile-nav aria-label="Mobil navigatsiya">
    <div class="mobile-nav__head">
        <span class="brand" aria-hidden="true">
            <span class="brand__mark">@include('partials.logo-mark')</span>
        </span>
        <button type="button" class="icon-button" data-mobile-nav-close aria-label="{{ t('header.close_menu') }}">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
        </button>
    </div>

    <nav class="mobile-nav__body" aria-label="Mobil navigatsiya havolalari">
        <ul class="mobile-nav__list">
            @foreach ($navigation as $item)
                @php($active = request()->routeIs(...$item['patterns']))
                <li>
                    <a
                        class="mobile-nav__link @if ($active) is-active @endif"
                        href="{{ route($item['route']) }}"
                        @if ($active) aria-current="page" @endif
                    >{{ t($item['label_key']) }}</a>
                </li>
            @endforeach
        </ul>

        <div class="mobile-nav__extra">
            <a class="mobile-nav__link mobile-nav__link--small" href="{{ route('cart.index') }}">{{ t('header.cart') }}</a>
            <a class="mobile-nav__link mobile-nav__link--small" href="{{ route('favorites.index') }}">{{ t('header.favorites') }}</a>
        </div>

        <div class="mobile-nav__contact">
            <p><i class="bi bi-telephone" aria-hidden="true"></i> {{ $brand['phone'] }}</p>
            <p><i class="bi bi-clock" aria-hidden="true"></i> {{ $brand['hours'] }}</p>
            <p class="mobile-nav__demo">Demo ma’lumotlar — haqiqiy emas.</p>
        </div>
    </nav>
</aside>
