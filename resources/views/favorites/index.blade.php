@extends('layouts.app')

@section('title', t('favorites.title').' — Bodomgul')
@section('meta_description', t('favorites.description'))

@section('content')
    <div class="section-gap">
        <x-page-header
            :title="t('favorites.title')"
            :description="t('favorites.description')"
            :breadcrumbs="[
                ['label' => t('common.home'), 'url' => route('home')],
                ['label' => t('favorites.title')],
            ]"
        />
    </div>

    <section class="safia-slab">
        <div class="container" data-favorites-page>
            <noscript>
                <div class="alert alert--demo">
                    <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                    <p>{{ t('favorites.noscript') }}</p>
                </div>
            </noscript>

            <p class="catalog-meta" aria-live="polite" data-favorites-summary hidden>
                <span data-favorites-count-text>0</span> {{ t('favorites.count_suffix') }}
            </p>

            <div class="product-grid-safia" data-favorites-grid>
                @foreach ($products as $product)
                    <div class="product-grid__cell" data-favorite-item data-product-id="{{ $product['id'] }}" hidden>
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            <x-empty-state
                data-favorites-empty
                icon="bi-heart"
                :title="t('favorites.empty_title')"
                :text="t('favorites.empty_text_alt')"
                hidden
            >
                <a class="btn btn--primary" href="{{ route('catalog.index') }}">{{ t('cart.go_catalog') }}</a>
            </x-empty-state>
        </div>
    </section>
@endsection
