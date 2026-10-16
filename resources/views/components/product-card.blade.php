@props(['product', 'showFavorite' => true])

<article
    class="product-card"
    data-product-item
    data-product-id="{{ $product['id'] }}"
    data-name="{{ Str::lower($product['name']) }}"
    data-price="{{ $product['price'] }}"
    data-category="{{ $product['category'] }}"
>
    <div class="product-card__media">
        <a class="product-card__image-link" href="{{ route('products.show', ['product' => $product['slug']]) }}" tabindex="-1" aria-hidden="true">
            <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" loading="lazy">
        </a>

        @if ($product['featured'] ?? true)
            <span class="product-card__badge">{{ t('product.new_badge') }}</span>
        @endif

        @if ($showFavorite)
            <button
                type="button"
                class="favorite-toggle product-card__favorite"
                data-favorite-toggle
                data-product-id="{{ $product['id'] }}"
                aria-pressed="false"
                aria-label="{{ t('product.favorite_add', ['name' => $product['name']]) }}"
            >
                <i class="bi bi-heart" aria-hidden="true"></i>
            </button>
        @endif
    </div>

    <div class="product-card__body">
        <h3 class="product-card__title">
            <a href="{{ route('products.show', ['product' => $product['slug']]) }}">{{ $product['name'] }}</a>
        </h3>
        <p class="product-card__desc">{{ $product['description'] ?? ($product['category_name'] . ' • ' . $product['weight']) }}</p>

        <div class="product-card__footer">
            <p class="product-card__price">{{ format_price($product['price']) }}</p>
            <button
                type="button"
                class="product-card__btn-pill"
                data-add-to-cart
                data-product-id="{{ $product['id'] }}"
                aria-label="{{ t('product.cart_add', ['name' => $product['name']]) }}"
            >
                <span>{{ t('product.add_to_cart') }}</span>
                <i class="bi bi-bag-plus" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</article>
