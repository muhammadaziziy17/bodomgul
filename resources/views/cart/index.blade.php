@extends('layouts.app')

@section('title', t('cart.title').' — Bodomgul')
@section('meta_description', t('cart.description'))

@section('content')
    <div class="section-gap">
        <x-page-header
            title="{{ t('cart.title') }}"
            description="{{ t('cart.description') }}"
            :breadcrumbs="[
                ['label' => t('common.home'), 'url' => route('home')],
                ['label' => t('cart.title')],
            ]"
        />
    </div>

    <section class="safia-slab">
        <div class="container" data-cart-page>
            <noscript>
                <div class="alert alert--demo">
                    <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                    <p>{{ t('cart.noscript') }}</p>
                </div>
            </noscript>

            <div class="cart-layout">
                <div class="cart-layout__main">
                    <div class="cart-items" data-cart-items>
                        @foreach ($products as $product)
                            <article
                                class="cart-row"
                                data-cart-row
                                data-product-id="{{ $product['id'] }}"
                                data-price="{{ $product['price'] }}"
                                hidden
                            >
                                <a class="cart-row__media" href="{{ route('products.show', ['product' => $product['slug']]) }}" tabindex="-1" aria-hidden="true">
                                    <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" width="1200" height="900" loading="lazy">
                                </a>

                                <div class="cart-row__info">
                                    <p class="cart-row__category">{{ $product['category_name'] }}</p>
                                    <h2 class="cart-row__title">
                                        <a href="{{ route('products.show', ['product' => $product['slug']]) }}">{{ $product['name'] }}</a>
                                    </h2>
                                    <p class="cart-row__unit">{{ t('cart.unit_price', ['price' => format_price($product['price']), 'weight' => $product['weight']]) }}</p>
                                </div>

                                <div class="cart-row__qty">
                                    <button type="button" class="qty-button" data-cart-minus aria-label="{{ $product['name'] }} — miqdorni kamaytirish">
                                        <i class="bi bi-dash-lg" aria-hidden="true"></i>
                                    </button>
                                    <label class="visually-hidden" for="cart-qty-{{ $product['id'] }}">Miqdor</label>
                                    <input
                                        class="qty-input"
                                        id="cart-qty-{{ $product['id'] }}"
                                        type="number"
                                        inputmode="numeric"
                                        min="1"
                                        max="99"
                                        value="1"
                                        data-cart-qty
                                        aria-label="{{ $product['name'] }} miqdori"
                                    >
                                    <button type="button" class="qty-button" data-cart-plus aria-label="{{ $product['name'] }} — miqdorni oshirish">
                                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                                    </button>
                                </div>

                                <div class="cart-row__total">
                                    <p class="cart-row__total-label">{{ t('cart.row_total') }}</p>
                                    <p class="cart-row__total-value" data-cart-row-total>{{ format_price($product['price']) }}</p>
                                </div>

                                <button
                                    type="button"
                                    class="cart-row__remove"
                                    data-cart-remove
                                    aria-label="{{ t('cart.remove', ['name' => $product['name']]) }}"
                                >
                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                </button>
                            </article>
                        @endforeach
                    </div>

                    <x-empty-state
                        data-cart-empty
                        icon="bi-bag"
                        title="{{ t('cart.empty_title') }}"
                        text="{{ t('cart.empty_text') }}"
                        hidden
                    >
                        <a class="btn btn--primary" href="{{ route('catalog.index') }}">{{ t('cart.go_catalog') }}</a>
                    </x-empty-state>
                </div>

                <aside class="cart-summary" data-cart-summary>
                    <h2 class="cart-summary__title">{{ t('cart.summary_title') }}</h2>

                    <dl class="cart-summary__list">
                        <div class="cart-summary__row">
                            <dt>{{ t('cart.items_count') }}</dt>
                            <dd data-cart-total-items>0 {{ t('cart.items_suffix') }}</dd>
                        </div>
                        <div class="cart-summary__row">
                            <dt>{{ t('cart.subtotal') }}</dt>
                            <dd data-cart-subtotal>0 so‘m</dd>
                        </div>
                        <div class="cart-summary__row">
                            <dt>{{ t('cart.delivery') }}</dt>
                            <dd>{{ t('cart.delivery_demo') }}</dd>
                        </div>
                    </dl>

                    <p class="cart-summary__total">
                        <span>{{ t('cart.total') }}</span>
                        <strong data-cart-subtotal>0 so‘m</strong>
                    </p>

                    <p class="cart-summary__note">{{ t('cart.note') }}</p>

                    <button type="button" class="btn btn--ghost btn--block" data-cart-clear>{{ t('cart.clear') }}</button>
                    <a class="btn btn--secondary btn--block" href="{{ route('catalog.index') }}">{{ t('cart.continue') }}</a>
                </aside>
            </div>
        </div>
    </section>
@endsection
