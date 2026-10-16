@extends('layouts.app')

@section('title', $product['name'].' — Bodomgul')
@section('meta_description', $product['description'])

@section('content')
    <section class="safia-slab">
        <div class="container">
            <x-breadcrumb
                :items="[
                    ['label' => t('common.home'), 'url' => route('home')],
                    ['label' => t('nav.catalog'), 'url' => route('catalog.index')],
                    ['label' => $category['name'], 'url' => route('catalog.category', ['category' => $category['slug']])],
                    ['label' => $product['name']],
                ]"
            />

            <div class="product-detail">
                <div class="product-detail__media">
                    <img
                        src="{{ asset($product['image']) }}"
                        alt="{{ $product['name'] }}"
                        width="1200"
                        height="900"
                        fetchpriority="high"
                    >
                </div>

                <div class="product-detail__info">
                    <p class="product-detail__category">
                        <a href="{{ route('catalog.category', ['category' => $category['slug']]) }}">{{ $product['category_name'] }}</a>
                    </p>

                    <h1 class="product-detail__title">{{ $product['name'] }}</h1>

                    <p class="product-detail__price">
                        {{ format_price($product['price']) }}
                        <span class="product-detail__weight">{{ t('product.weight_unit', ['weight' => $product['weight']]) }}</span>
                    </p>

                    <p class="product-detail__description">{{ $product['description'] }}</p>

                    <div class="product-detail__actions">
                        <div class="qty-stepper" data-qty-stepper>
                            <button type="button" class="qty-button" data-qty-minus aria-label="{{ t('product.qty_decrease') }}">
                                <i class="bi bi-dash-lg" aria-hidden="true"></i>
                            </button>
                            <label class="visually-hidden" for="product-qty">{{ t('product.qty_label') }}</label>
                            <input
                                id="product-qty"
                                class="qty-input"
                                type="number"
                                inputmode="numeric"
                                min="1"
                                max="99"
                                value="1"
                            >
                            <button type="button" class="qty-button" data-qty-plus aria-label="{{ t('product.qty_increase') }}">
                                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            </button>
                        </div>

                        <button
                            type="button"
                            class="btn btn--primary btn--lg"
                            data-add-to-cart
                            data-product-id="{{ $product['id'] }}"
                            data-qty-source="#product-qty"
                        >
                            <i class="bi bi-bag-plus" aria-hidden="true"></i>
                            <span>{{ t('product.add_to_cart_long') }}</span>
                        </button>

                        <button
                            type="button"
                            class="btn btn--secondary btn--lg"
                            data-favorite-toggle
                            data-product-id="{{ $product['id'] }}"
                            aria-pressed="false"
                        >
                            <i class="bi bi-heart" aria-hidden="true"></i>
                            <span data-favorite-label>{{ t('product.favorite_long') }}</span>
                        </button>
                    </div>

                    <dl class="product-detail__meta">
                        <div class="product-detail__meta-row">
                            <dt>{{ t('product.category_label') }}</dt>
                            <dd><a href="{{ route('catalog.category', ['category' => $category['slug']]) }}">{{ $product['category_name'] }}</a></dd>
                        </div>
                        <div class="product-detail__meta-row">
                            <dt>{{ t('product.weight_label') }}</dt>
                            <dd>{{ $product['weight'] }}</dd>
                        </div>
                        <div class="product-detail__meta-row">
                            <dt>{{ t('product.composition_label') }}</dt>
                            <dd>{{ $product['composition'] }}</dd>
                        </div>
                        <div class="product-detail__meta-row">
                            <dt>{{ t('product.status_label') }}</dt>
                            <dd>{{ t('common.demo_data') }}</dd>
                        </div>
                    </dl>

                    <div class="alert alert--demo">
                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                        <p>{{ t('product.demo_alert') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (count($relatedProducts))
        <section class="safia-slab">
            <div class="container">
                <div class="slab-head">
                    <div>
                        <h2 class="slab-head__title">{{ t('product.related_title') }}</h2>
                        <p class="slab-head__subtitle">{{ t('product.related_subtitle', ['category' => $category['name']]) }}</p>
                    </div>
                    <a href="{{ route('catalog.category', ['category' => $category['slug']]) }}" class="slab-head__link">
                        {{ t('catalog.all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="product-grid-safia">
                    @foreach ($relatedProducts as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
