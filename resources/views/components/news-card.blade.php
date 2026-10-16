@props(['item'])

<article class="news-card">
    <a class="news-card__media" href="{{ route('news.show', ['news' => $item['slug']]) }}" tabindex="-1" aria-hidden="true">
        <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}" width="1200" height="800" loading="lazy">
    </a>

    <div class="news-card__body">
        <p class="news-card__meta">
            <span class="badge badge--soft">{{ $item['tag'] }}</span>
            <time datetime="{{ $item['date'] }}">{{ $item['date_label'] }}</time>
        </p>
        <h3 class="news-card__title">
            <a href="{{ route('news.show', ['news' => $item['slug']]) }}">{{ $item['title'] }}</a>
        </h3>
        <p class="news-card__excerpt">{{ $item['excerpt'] }}</p>
        <a class="news-card__more" href="{{ route('news.show', ['news' => $item['slug']]) }}">
            <span>{{ t('common.more') }}</span>
            <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </a>
    </div>
</article>
