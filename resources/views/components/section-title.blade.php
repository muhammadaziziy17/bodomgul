@props([
    'eyebrow' => null,
    'title' => null,
    'link' => null,
    'linkLabel' => 'Barchasi',
])

<div {{ $attributes->class(['section-title']) }}>
    <div class="section-title__text">
        @if ($eyebrow)
            <p class="section-title__eyebrow">{{ $eyebrow }}</p>
        @endif
        @if ($title)
            <h2 class="section-title__heading">{{ $title }}</h2>
        @endif
    </div>

    @if ($link)
        <a class="section-title__link" href="{{ $link }}">
            <span>{{ $linkLabel }}</span>
            <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </a>
    @endif
</div>
