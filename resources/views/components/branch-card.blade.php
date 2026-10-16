@props(['branch'])

<article class="branch-card" data-branch-card data-search="{{ $branch['search'] }}">
    <div class="branch-card__head">
        <h3 class="branch-card__title">{{ $branch['name'] }}</h3>
        <span class="badge badge--soft">{{ t('branches.demo_badge') }}</span>
    </div>

    <ul class="branch-card__list">
        <li>
            <i class="bi bi-geo-alt" aria-hidden="true"></i>
            <span>{{ $branch['district'] }}, {{ $branch['address'] }}</span>
        </li>
        <li>
            <i class="bi bi-clock" aria-hidden="true"></i>
            <span>{{ $branch['hours'] }}</span>
        </li>
        @if ($branch['phone'])
            <li>
                <i class="bi bi-telephone" aria-hidden="true"></i>
                <span>{{ $branch['phone'] }}</span>
            </li>
        @endif
    </ul>

    <a class="btn btn--ghost btn--sm" href="{{ $branch['map_url'] }}" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-map" aria-hidden="true"></i>
        <span>{{ t('branches.map_link') }}</span>
    </a>
</article>
