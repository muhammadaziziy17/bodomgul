<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Boshqaruv paneli') — Bodomgul Admin</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="64x64">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>
<body class="admin">
    <div class="a-shell">
        <div class="a-overlay" data-admin-overlay></div>

        <aside class="a-sidebar" data-admin-sidebar>
            <a class="a-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/brand/logo-emblem.png') }}" alt="" width="40" height="40">
                <span>
                    <strong>Bodomgul</strong>
                    <small>Admin panel</small>
                </span>
            </a>

            <nav class="a-nav" aria-label="Admin navigatsiyasi">
                <a class="a-nav__link @if (request()->routeIs('admin.dashboard')) is-active @endif" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid" aria-hidden="true"></i> Boshqaruv paneli
                </a>
                <a class="a-nav__link @if (request()->routeIs('admin.hero-slides.*')) is-active @endif" href="{{ route('admin.hero-slides.index') }}">
                    <i class="bi bi-images" aria-hidden="true"></i> Hero slaydlar
                </a>
                <a class="a-nav__link @if (request()->routeIs('admin.categories.*')) is-active @endif" href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-tags" aria-hidden="true"></i> Kategoriyalar
                </a>
                <a class="a-nav__link @if (request()->routeIs('admin.products.*')) is-active @endif" href="{{ route('admin.products.index') }}">
                    <i class="bi bi-bag" aria-hidden="true"></i> Mahsulotlar
                </a>
                <a class="a-nav__link @if (request()->routeIs('admin.news.*')) is-active @endif" href="{{ route('admin.news.index') }}">
                    <i class="bi bi-newspaper" aria-hidden="true"></i> Yangiliklar
                </a>
                <a class="a-nav__link @if (request()->routeIs('admin.branches.*')) is-active @endif" href="{{ route('admin.branches.index') }}">
                    <i class="bi bi-geo-alt" aria-hidden="true"></i> Filiallar
                </a>

                <p class="a-nav__group">Sahifalar</p>

                <a class="a-nav__link @if (request()->routeIs('admin.pages.*') && request()->route('page')?->slug === 'about') is-active @endif" href="{{ route('admin.pages.edit', 'about') }}">
                    <i class="bi bi-file-text" aria-hidden="true"></i> Biz haqimizda
                </a>
                <a class="a-nav__link @if (request()->routeIs('admin.pages.*') && request()->route('page')?->slug === 'careers') is-active @endif" href="{{ route('admin.pages.edit', 'careers') }}">
                    <i class="bi bi-briefcase" aria-hidden="true"></i> Karyera
                </a>
                <a class="a-nav__link @if (request()->routeIs('admin.pages.*') && request()->route('page')?->slug === 'contacts') is-active @endif" href="{{ route('admin.pages.edit', 'contacts') }}">
                    <i class="bi bi-telephone" aria-hidden="true"></i> Kontaktlar
                </a>

                <p class="a-nav__group">Sayt</p>

                <a class="a-nav__link @if (request()->routeIs('admin.locales.*')) is-active @endif" href="{{ route('admin.locales.index') }}">
                    <i class="bi bi-translate" aria-hidden="true"></i> Tillar
                </a>
                <a class="a-nav__link @if (request()->routeIs('admin.ui.*')) is-active @endif" href="{{ route('admin.ui.index') }}">
                    <i class="bi bi-fonts" aria-hidden="true"></i> Matnlar tarjimasi
                </a>
                <a class="a-nav__link @if (request()->routeIs('admin.settings.*')) is-active @endif" href="{{ route('admin.settings.edit') }}">
                    <i class="bi bi-sliders" aria-hidden="true"></i> Sozlamalar
                </a>
                <a class="a-nav__link" href="{{ route('home') }}" target="_blank" rel="noopener">
                    <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> Saytni ko‘rish
                </a>
            </nav>

            <div class="a-sidebar__foot">
                <p class="a-user">{{ auth()->user()?->name }}</p>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="a-btn a-btn--ghost a-btn--block">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Chiqish
                    </button>
                </form>
            </div>
        </aside>

        <div class="a-main">
            <header class="a-topbar">
                <button type="button" class="a-burger" data-admin-menu-toggle aria-label="Menyuni ochish">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>
                <h1 class="a-topbar__title">@yield('heading', 'Boshqaruv paneli')</h1>
            </header>

            <main class="a-content">
                @if (session('status'))
                    <div class="a-flash a-flash--success" data-flash>
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="a-flash a-flash--error" data-flash>
                        <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div class="a-modal" data-admin-modal hidden>
        <div class="a-modal__dialog" data-admin-modal-dialog>
            <div class="a-modal__bar">
                <p class="a-modal__title" data-admin-modal-title>Yuklanmoqda...</p>
                <button type="button" class="a-modal__close" data-admin-modal-close aria-label="Yopish">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
            </div>
            <div data-admin-modal-content>
                <p class="a-modal__loading">Yuklanmoqda...</p>
            </div>
        </div>
    </div>

    <div class="a-toasts" data-admin-toasts aria-live="polite" aria-atomic="true"></div>

    <script src="{{ asset('js/admin.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
