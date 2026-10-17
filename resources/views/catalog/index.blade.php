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
                ['label' => t('catalog.title')],
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
                        placeholder="{{ t('catalog.search_placeholder') }}"
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

            <div class="catalog-chips" role="group" aria-label="{{ t('catalog.filter_label') }}">
                <button type="button" class="chip is-active" data-catalog-category="all" aria-pressed="true">
                    {{ t('catalog.all') }}
                </button>
                @foreach ($categories as $categoryItem)
                    <button type="button" class="chip" data-catalog-category="{{ $categoryItem['slug'] }}" aria-pressed="false">
                        {{ $categoryItem['name'] }}
                        <span class="chip__count">{{ $categoryItem['products_count'] }}</span>
                    </button>
                @endforeach
            </div>

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
                :text="t('catalog.empty_text')"
                hidden
            >
                <button type="button" class="btn btn--secondary" data-catalog-clear>{{ t('catalog.clear_filters') }}</button>
            </x-empty-state>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/catalog.js') }}" defer></script>
@endpush
