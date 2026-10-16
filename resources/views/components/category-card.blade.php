@props(['category'])

<a class="safia-cat-card" href="{{ route('catalog.category', ['category' => $category['slug']]) }}">
    <span class="safia-cat-card__title">{{ $category['name'] }}</span>
    <span class="safia-cat-card__media">
        <img src="{{ asset($category['image']) }}" alt="{{ $category['name'] }}" loading="lazy">
    </span>
</a>
