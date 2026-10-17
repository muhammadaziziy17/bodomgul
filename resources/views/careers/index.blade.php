@extends('layouts.app')

@section('title', t('careers.title').' — Bodomgul')
@section('meta_description', t('careers.meta_description'))

@php($hero = $content['hero'] ?? [])

@section('content')
    {{-- Hero Banner --}}
    <section class="safia-slab safia-slab--tint">
        <div class="container">
            <div class="careers-hero">
                <div class="careers-hero__copy">
                    <span class="pill-badge pill-badge--white">
                        {{ $hero['badge'] ?? 'Bodomgul jamoasi' }}
                    </span>
                    <h1 class="careers-hero__title">
                        {{ $hero['title'] ?? 'Bodomgul oilasining bir qismi bo‘ling' }}
                    </h1>
                    <p class="careers-hero__text">{{ $hero['text'] ?? '' }}</p>
                    <div class="careers-hero__actions">
                        <a href="#vacancies" class="btn btn--primary">
                            <i class="bi bi-briefcase" aria-hidden="true"></i>
                            <span>{{ t('careers.vacancies_button') }}</span>
                        </a>
                        @if (! empty($content['cta']['telegram_url']))
                            <a href="{{ $content['cta']['telegram_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn--secondary">
                                <i class="bi bi-telegram" aria-hidden="true"></i>
                                <span>{{ t('careers.hr_bot') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="careers-hero__media">
                    <div class="careers-hero__avatar-wrap">
                        <img
                            class="careers-hero__avatar"
                            src="{{ asset($hero['image'] ?? 'images/brand/team.svg') }}"
                            alt="Bodomgul qandolatchilik jamoasi"
                            width="288"
                            height="288"
                            loading="lazy"
                        >
                        @if (! empty($hero['badge_text']))
                            <span class="careers-hero__badge">
                                <i class="bi bi-people" aria-hidden="true"></i> {{ $hero['badge_text'] }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Bizning afzalliklar --}}
    <section class="safia-slab">
        <div class="container">
            <div class="slab-head">
                <div>
                    <h2 class="slab-head__title">{{ $content['perks']['title'] ?? 'Nega aynan biz?' }}</h2>
                    <p class="slab-head__subtitle">{{ $content['perks']['subtitle'] ?? '' }}</p>
                </div>
            </div>

            <div class="feature-grid feature-grid--4">
                @foreach ($content['perks']['items'] ?? [] as $perk)
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="bi {{ $perk['icon'] ?? 'bi-award' }}" aria-hidden="true"></i>
                        </div>
                        <h3 class="feature-card__title">{{ $perk['title'] ?? '' }}</h3>
                        <p class="feature-card__text">{{ $perk['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Vakansiyalar ro'yxati --}}
    <section class="safia-slab" id="vacancies">
        <div class="container">
            <div class="slab-head">
                <div>
                    <h2 class="slab-head__title">{{ $content['vacancies']['title'] ?? 'Ochiq vakansiyalar' }}</h2>
                    <p class="slab-head__subtitle">{{ $content['vacancies']['subtitle'] ?? '' }}</p>
                </div>
                <span class="pill-badge pill-badge--plain">
                    {{ count($content['vacancies']['items'] ?? []) }} {{ t('careers.vacancies_count_suffix') }}
                </span>
            </div>

            <div class="vacancy-list">
                @foreach ($content['vacancies']['items'] ?? [] as $vacancy)
                    <div class="vacancy-card">
                        <div class="vacancy-card__main">
                            <div class="vacancy-card__head">
                                <h3 class="vacancy-card__title">{{ $vacancy['title'] ?? '' }}</h3>
                                @if (! empty($vacancy['tag']))
                                    <span class="vacancy-card__tag">{{ $vacancy['tag'] }}</span>
                                @endif
                            </div>
                            <p class="vacancy-card__text">{{ $vacancy['text'] ?? '' }}</p>
                            <div class="vacancy-card__meta">
                                @if (! empty($vacancy['location']))
                                    <span><i class="bi bi-geo-alt" aria-hidden="true"></i>{{ $vacancy['location'] }}</span>
                                @endif
                                @if (! empty($vacancy['salary']))
                                    <span><i class="bi bi-cash" aria-hidden="true"></i>{{ $vacancy['salary'] }}</span>
                                @endif
                                @if (! empty($vacancy['schedule']))
                                    <span><i class="bi bi-clock" aria-hidden="true"></i>{{ $vacancy['schedule'] }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="vacancy-card__action">
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $content['cta']['phone_label'] ?? '+998781134040') }}" class="btn btn--secondary btn--sm">
                                <i class="bi bi-telephone" aria-hidden="true"></i>
                                <span>{{ t('careers.apply') }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tezkor murojaat --}}
    <section class="safia-slab">
        <div class="container">
            <div class="cta-band">
                <div>
                    <h3 class="cta-band__title">{{ $content['cta']['title'] ?? 'Rezyumengiz bormi?' }}</h3>
                    <p class="cta-band__text">{{ $content['cta']['text'] ?? '' }}</p>
                </div>
                <div class="cta-band__actions">
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $content['cta']['phone_label'] ?? '+998781134040') }}" class="btn btn--primary">
                        <i class="bi bi-telephone" aria-hidden="true"></i>
                        <span>{{ $content['cta']['phone_label'] ?? '+998 78 113 40 40' }}</span>
                    </a>
                    @if (! empty($content['cta']['telegram_url']))
                        <a href="{{ $content['cta']['telegram_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn--light">
                            <i class="bi bi-send" aria-hidden="true"></i>
                            <span>{{ $content['cta']['telegram_label'] ?? '@bodomgul_hr_bot' }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
