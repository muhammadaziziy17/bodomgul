@extends('layouts.app')

@section('title', t('about.title').' — Bodomgul')
@section('meta_description', $content['description'] ?? t('about.description'))

@section('content')
    <div class="section-gap">
        <x-page-header
            :title="t('about.title')"
            :description="$content['description'] ?? null"
            :breadcrumbs="[
                ['label' => t('common.home'), 'url' => route('home')],
                ['label' => t('about.title')],
            ]"
        />
    </div>

    {{-- 1. Tariximiz --}}
    <section class="safia-slab">
        <div class="container">
            <div class="story">
                <div class="story__media">
                    <img
                        src="{{ asset($content['story']['image'] ?? 'images/brand/about.svg') }}"
                        alt="Bodomgul qandolatchilik ustaxonasi"
                        width="1200"
                        height="900"
                        loading="lazy"
                    >
                </div>

                <div class="story__content">
                    <span class="pill-badge pill-badge--gold story__eyebrow">
                        {{ $content['story']['eyebrow'] ?? 'Bizning tariximiz' }}
                    </span>
                    <h2 class="story__title">
                        {{ $content['story']['title'] ?? '' }}
                    </h2>

                    @foreach ($content['story']['paragraphs'] ?? [] as $paragraph)
                        <p class="story__text">{{ $paragraph }}</p>
                    @endforeach

                    <div class="story__actions">
                        <x-button :href="route('catalog.index')" icon-after="bi-arrow-right">
                            {{ t('about.view_catalog') }}
                        </x-button>
                        <x-button :href="route('branches.index')" variant="secondary">
                            {{ t('nav.branches') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Missiya va Shior --}}
    <section class="safia-slab">
        <div class="container">
            <div class="mission-band">
                <span class="pill-badge pill-badge--on-dark mission-band__badge">
                    {{ $content['mission']['badge'] ?? 'Bizning missiyamiz' }}
                </span>
                <p class="mission-band__text">
                    {{ $content['mission']['text'] ?? '' }}
                </p>
                <span class="mission-band__attribution">
                    {{ $content['mission']['attribution'] ?? '' }}
                </span>
            </div>
        </div>
    </section>

    {{-- 3. Qadriyatlar --}}
    <section class="safia-slab">
        <div class="container">
            <div class="slab-head">
                <div>
                    <h2 class="slab-head__title">{{ t('about.values_title') }}</h2>
                    <p class="slab-head__subtitle">{{ t('about.values_subtitle') }}</p>
                </div>
            </div>

            <div class="feature-grid feature-grid--4">
                @foreach ($content['values'] ?? [] as $value)
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="bi {{ $value['icon'] ?? 'bi-award' }}" aria-hidden="true"></i>
                        </div>
                        <h3 class="feature-card__title">{{ $value['title'] ?? '' }}</h3>
                        <p class="feature-card__text">{{ $value['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 4. Raqamlarda --}}
    <section class="safia-slab">
        <div class="container">
            <div class="stats-grid">
                @foreach ($content['stats'] ?? [] as $stat)
                    <div class="stats-grid__item">
                        <p class="stats-grid__value">{{ $stat['value'] ?? '' }}</p>
                        <p class="stats-grid__label">{{ $stat['label'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
