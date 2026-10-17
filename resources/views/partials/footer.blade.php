@php
    $brand = \App\Support\SiteSettings::brand();
    $socialLinks = \App\Support\SiteSettings::social();
    $appLinks = \App\Support\SiteSettings::app();
@endphp

<footer class="site-footer">
    <div class="container">
        <!-- Brendning katta shiori -->
        <blockquote class="site-footer__motto">
            {{ $brand['motto'] ?? '«Qandolatchilik san’atini rivojlantirish orqali, har bir xonadonga bayram shukuhini olib kiramiz»' }}
        </blockquote>

        @if (! empty($brand['about']))
            <p class="site-footer__about">{{ $brand['about'] }}</p>
        @endif

        <!-- Ijtimoiy tarmoqlar pill tugmalari -->
        <div class="site-footer__social">
            @if (! empty($socialLinks['facebook']))
                <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener noreferrer" class="site-footer__social-pill">
                    <i class="bi bi-facebook" aria-hidden="true"></i> facebook
                </a>
            @endif
            @if (! empty($socialLinks['instagram']))
                <a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener noreferrer" class="site-footer__social-pill">
                    <i class="bi bi-instagram" aria-hidden="true"></i> instagram
                </a>
            @endif
            @if (! empty($socialLinks['telegram']))
                <a href="{{ $socialLinks['telegram'] }}" target="_blank" rel="noopener noreferrer" class="site-footer__social-pill">
                    <i class="bi bi-telegram" aria-hidden="true"></i> telegram
                </a>
            @endif
        </div>

        <!-- 4 ta ustun -->
        <div class="site-footer__grid">
            <!-- 1. {{ t('footer.about_title') }} -->
            <nav class="site-footer__col" aria-labelledby="footer-about-title">
                <h3 class="site-footer__col-title" id="footer-about-title">{{ t('footer.about_title') }}</h3>
                <ul class="site-footer__nav-list">
                    <li><a href="{{ route('about') }}">{{ t('nav.about') }}</a></li>
                    <li><a href="{{ route('branches.index') }}">{{ t('nav.branches') }}</a></li>
                    <li><a href="{{ route('contacts') }}">{{ t('footer.contacts_title') }}</a></li>
                    <li><a href="{{ route('contacts') }}">{{ t('nav.catering') }}</a></li>
                    <li><a href="{{ route('catalog.index') }}">{{ t('nav.catalog') }}</a></li>
                </ul>
            </nav>

            <!-- 2. {{ t('footer.links_title') }} -->
            <nav class="site-footer__col" aria-labelledby="footer-links-title">
                <h3 class="site-footer__col-title" id="footer-links-title">{{ t('footer.links_title') }}</h3>
                <ul class="site-footer__nav-list">
                    <li><a href="{{ route('news.index') }}">{{ t('nav.news') }}</a></li>
                    <li><a href="{{ route('careers') }}">{{ t('nav.careers') }}</a></li>
                    <li><a href="{{ route('about') }}">{{ t('footer.loyalty') }}</a></li>
                    <li><a href="{{ route('contacts') }}">{{ t('footer.faq') }}</a></li>
                    <li><a href="{{ route('about') }}">{{ t('footer.privacy') }}</a></li>
                </ul>
            </nav>

            <!-- 3. Kontaktlar -->
            <div class="site-footer__col">
                <h3 class="site-footer__col-title">{{ t('footer.contacts_title') }}</h3>
                <ul class="site-footer__contact-list">
                    <li>
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $brand['phone'] ?? '+998781134040') }}" class="site-footer__contact-phone">
                            {{ $brand['phone'] ?? '+998 78 113 40 40' }}
                        </a>
                    </li>
                    <li class="site-footer__hours">
                        {{ $brand['hours'] ?? 'Dush-Yak: 08:00 dan 23:00 gacha' }}
                    </li>
                    <li>
                        <i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $brand['address'] ?? 'Toshkent shahri' }}
                    </li>
                </ul>
            </div>

            <!-- 4. {{ t('footer.app_title') }} & QR -->
            <div class="site-footer__col site-footer__app-box">
                <h3 class="site-footer__col-title">{{ t('footer.app_title') }}</h3>
                <p class="site-footer__app-text">
                    {{ t('footer.app_text') }}
                </p>
                <div class="site-footer__app-badges">
                    @if (! empty($appLinks['google_play']))
                        <a href="{{ $appLinks['google_play'] }}" target="_blank" rel="noopener noreferrer" class="app-badge-btn" aria-label="Google Play">
                            <i class="bi bi-google-play" aria-hidden="true"></i> Google Play
                        </a>
                    @endif
                    @if (! empty($appLinks['app_store']))
                        <a href="{{ $appLinks['app_store'] }}" target="_blank" rel="noopener noreferrer" class="app-badge-btn" aria-label="App Store">
                            <i class="bi bi-apple" aria-hidden="true"></i> App Store
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pastki qism -->
        <div class="site-footer__bottom">
            <p>© {{ now()->year }} {{ $brand['name'] ?? 'Bodomgul' }}. {{ $brand['copyright_note'] }}</p>
            <p>{{ t('footer.demo_note') }}</p>
        </div>
    </div>
</footer>
