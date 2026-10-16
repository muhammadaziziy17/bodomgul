<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-placeholder="{{ asset('images/placeholders/placeholder.svg') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', t('meta.home_title'))</title>
    <meta name="description" content="@yield('meta_description', t('meta.home_description'))">
    <meta name="theme-color" content="#F9F8F6">
    <meta property="og:image" content="{{ asset('images/brand/logo-og.png') }}">

    @foreach (\App\Support\LocalizedUrl::alternates(request()) as $alternate)
        <link rel="alternate" hreflang="{{ $alternate['code'] }}" href="{{ $alternate['url'] }}">
    @endforeach

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="64x64">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">

    @stack('styles')
</head>
<body class="@yield('body_class')">
    <div class="site-shell">
        <a class="skip-link" href="#main">Asosiy kontentga o‘tish</a>

        @include('partials.header')

        <main id="main" class="site-main">
            @yield('content')
        </main>

        @include('partials.footer')
        @include('partials.mobile-navigation')

        <div class="toast-region" data-toast-region aria-live="polite" aria-atomic="true"></div>
    </div>

    <script>
        window.BgI18n = {!! \Illuminate\Support\Js::from([
            'currency' => t('common.currency'),
            'added_to_cart' => t('js.added_to_cart'),
            'removed_from_cart' => t('js.removed_from_cart'),
            'cart_cleared' => t('js.cart_cleared'),
            'added_to_favorites' => t('js.added_to_favorites'),
            'removed_from_favorites' => t('js.removed_from_favorites'),
            'cart_aria' => t('js.cart_aria'),
            'favorites_aria' => t('js.favorites_aria'),
            'items_suffix' => t('js.items_suffix'),
        ]) !!};
    </script>

    <script src="{{ asset('js/lenis.min.js') }}" defer></script>
    <script src="{{ asset('js/favorites.js') }}" defer></script>
    <script src="{{ asset('js/cart.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('scripts')
</body>
</html>
