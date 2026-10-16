@props(['items' => []])

<nav class="breadcrumb" aria-label="{{ t('common.breadcrumb') }}">
    <ol class="breadcrumb__list">
        @foreach ($items as $item)
            <li class="breadcrumb__item">
                @if (! empty($item['url']) && ! $loop->last)
                    <a class="breadcrumb__link" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                @else
                    <span class="breadcrumb__current" @if ($loop->last) aria-current="page" @endif>{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
