@php($locales = \App\Support\Locales::all())

@if ($locales->count() > 1)
    <details class="lang-switch">
        <summary
            class="header-pill-btn header-pill-btn--lang"
            aria-label="{{ t('header.language') }}"
        >
            <span>{{ strtoupper(app()->getLocale()) }}</span>
        </summary>

        <ul class="lang-switch__menu">
            @foreach ($locales as $locale)
                <li>
                    <a
                        class="lang-switch__link @if ($locale->code === app()->getLocale()) is-active @endif"
                        href="{{ \App\Support\LocalizedUrl::for(request(), $locale->code) }}"
                        @if ($locale->code === app()->getLocale()) aria-current="true" @endif
                    >
                        {{ $locale->name }}
                        <span class="lang-switch__code">{{ strtoupper($locale->code) }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </details>
@else
    <div class="header-pill-btn header-pill-btn--lang" aria-label="{{ t('header.language') }}">
        <span>{{ strtoupper($locales->first()->code ?? 'uz') }}</span>
    </div>
@endif
