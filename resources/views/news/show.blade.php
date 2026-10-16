@extends('layouts.app')

@section('title', $item['title'].' — Bodomgul')
@section('meta_description', $item['excerpt'])

@section('content')
    <section class="safia-slab">
        <div class="container article">
            <x-breadcrumb
                :items="[
                    ['label' => t('common.home'), 'url' => route('home')],
                    ['label' => t('nav.news'), 'url' => route('news.index')],
                    ['label' => $item['title']],
                ]"
            />

            <article class="article__body">
                <header class="article__header">
                    <p class="article__meta">
                        <span class="badge badge--soft">{{ $item['tag'] }}</span>
                        <time datetime="{{ $item['date'] }}">{{ $item['date_label'] }}</time>
                    </p>
                    <h1 class="article__title">{{ $item['title'] }}</h1>
                </header>

                <img
                    class="article__cover"
                    src="{{ asset($item['image']) }}"
                    alt="{{ $item['title'] }}"
                    width="1200"
                    height="800"
                    fetchpriority="high"
                >

                <div class="article__content">
                    @foreach ($item['body'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>

                <p class="article__back">
                    <a class="btn btn--ghost" href="{{ route('news.index') }}">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                        <span>{{ t('news.back') }}</span>
                    </a>
                </p>
            </article>
        </div>
    </section>

    @if (count($relatedNews))
        <section class="safia-slab">
            <div class="container">
                <x-section-title :eyebrow="t('news.related_eyebrow')" :title="t('news.related_title')" :link="route('news.index')" :link-label="t('catalog.all')" />

                <div class="news-grid">
                    @foreach ($relatedNews as $newsItem)
                        <x-news-card :item="$newsItem" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
