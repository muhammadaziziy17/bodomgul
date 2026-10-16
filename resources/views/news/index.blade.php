@extends('layouts.app')

@section('title', t('news.title').' — Bodomgul')
@section('meta_description', t('news.description'))

@section('content')
    <div class="section-gap">
        <x-page-header
            title="{{ t('news.title') }}"
            description="{{ t('news.description') }}"
            :breadcrumbs="[
                ['label' => t('common.home'), 'url' => route('home')],
                ['label' => t('news.title')],
            ]"
        />
    </div>

    <section class="safia-slab">
        <div class="container">
            <div class="news-grid news-grid--page">
                @foreach ($news as $item)
                    <x-news-card :item="$item" />
                @endforeach
            </div>
        </div>
    </section>
@endsection
