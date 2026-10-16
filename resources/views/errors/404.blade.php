@extends('layouts.app')

@section('title', t('errors.not_found_title').' — Bodomgul')
@section('meta_description', t('errors.not_found_text'))

@section('content')
    <section class="safia-slab error-page">
        <div class="container error-page__inner">
            <p class="error-page__code" aria-hidden="true">404</p>
            <h1 class="error-page__title">{{ t('errors.not_found_title') }}</h1>
            <p class="error-page__text">{{ t('errors.not_found_text') }}</p>
            <div class="error-page__actions">
                <x-button :href="route('home')" icon="bi-house">{{ t('common.home') }}</x-button>
                <x-button :href="route('catalog.index')" variant="secondary" icon="bi-grid">{{ t('catalog.title') }}</x-button>
            </div>
        </div>
    </section>
@endsection
