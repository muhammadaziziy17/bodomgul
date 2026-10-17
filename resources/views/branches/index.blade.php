@extends('layouts.app')

@section('title', t('branches.title').' — Bodomgul')
@section('meta_description', t('branches.description'))

@section('content')
    <div class="section-gap">
        <x-page-header
            :title="t('branches.title')"
            :description="t('branches.description')"
            :breadcrumbs="[
                ['label' => t('common.home'), 'url' => route('home')],
                ['label' => t('branches.title')],
            ]"
        />
    </div>

    <section class="safia-slab" data-branches>
        <div class="container">
            <div class="catalog-toolbar">
                <div class="catalog-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <label class="visually-hidden" for="branch-search">{{ t('branches.search_label') }}</label>
                    <input
                        id="branch-search"
                        class="catalog-search__input"
                        type="search"
                        placeholder="{{ t('branches.search_placeholder') }}"
                        autocomplete="off"
                        data-branch-search
                    >
                </div>
            </div>

            <p class="catalog-meta" aria-live="polite">
                <span data-branches-count>{{ count($branches) }}</span> {{ t('branches.found_suffix') }}
            </p>

            <div class="branch-grid">
                @foreach ($branches as $branch)
                    <x-branch-card :branch="$branch" />
                @endforeach
            </div>

            <x-empty-state
                class="catalog-empty"
                data-branches-empty
                icon="bi-geo"
                :title="t('branches.empty_title')"
                :text="t('branches.empty_text')"
                hidden
            />
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/branches.js') }}" defer></script>
@endpush
