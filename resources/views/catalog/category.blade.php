@extends('layouts.app')

@section('title', $title.' — Bodomgul')
@section('meta_description', $description)

@section('content')
    <div class="section-gap">
        <x-page-header
            :title="$title"
            :description="$description"
            :breadcrumbs="[
                ['label' => t('common.home'), 'url' => route('home')],
                ['label' => t('catalog.title'), 'url' => route('catalog.index')],
                ['label' => $category['name']],
            ]"
        />
    </div>

    <section class="safia-slab" data-catalog>
        <div class="container">
            <div class="catalog-toolbar">
                <div class="catalog-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <label class="visually-hidden" for="catalog-search">{{ t('catalog.search_label') }}</label>
                    <input
                        id="catalog-search"
                        class="catalog-search__input"
                        type="search"
                        placeholder="{{ t('catalog.search_category_placeholder') }}"
                        value="{{ $search }}"
                        autocomplete="off"
                        data-catalog-search
                    >
                </div>

                <div class="catalog-sort">
                    <label class="catalog-sort__label" for="catalog-sort">{{ t('catalog.sort_label') }}</label>
                    <select id="catalog-sort" class="catalog-sort__select" data-catalog-sort>
                        <option value="default">{{ t('catalog.sort_default') }}</option>
                        <option value="price-asc">{{ t('catalog.sort_price_asc') }}</option>
                        <option value="price-desc">{{ t('catalog.sort_price_desc') }}</option>
                        <option value="name-asc">{{ t('catalog.sort_name_asc') }}</option>
                    </select>
                </div>
            </div>

            <nav class="catalog-chips" aria-label="Kategoriyalar">
                <a class="chip" href="{{ route('catalog.index') }}">{{ t('catalog.all') }}</a>
                @foreach ($categories as $categoryItem)
                    <a
                        class="chip @if ($categoryItem['slug'] === $category['slug']) is-active @endif"
                        href="{{ route('catalog.category', ['category' => $categoryItem['slug']]) }}"
                        @if ($categoryItem['slug'] === $category['slug']) aria-current="page" @endif
                    >
                        {{ $categoryItem['name'] }}
                        <span class="chip__count">{{ $categoryItem['products_count'] }}</span>
                    </a>
                @endforeach
            </nav>

            <p class="catalog-meta" aria-live="polite">
                <span data-catalog-count>{{ count($products) }}</span> {{ t('catalog.found_suffix') }}
            </p>

            <div class="product-grid-safia" data-catalog-grid>
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <x-empty-state
                class="catalog-empty"
                data-catalog-empty
                icon="bi-search"
                :title="t('catalog.empty_title')"
                :text="t('catalog.empty_category_text')"
                hidden
            >
                <button type="button" class="btn btn--secondary" data-catalog-clear>{{ t('catalog.clear_search') }}</button>
            </x-empty-state>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/catalog.js') }}" defer></script>
@endpush
