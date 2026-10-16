@php($navigation = config('bodomgul.navigation'))

<nav class="site-nav" aria-label="Asosiy navigatsiya">
    <ul class="site-nav__list">
        @foreach ($navigation as $item)
            @php($active = request()->routeIs(...$item['patterns']))
            <li>
                <a
                    class="site-nav__link @if ($active) is-active @endif"
                    href="{{ route($item['route']) }}"
                    @if ($active) aria-current="page" @endif
                >{{ t($item['label_key']) }}</a>
            </li>
        @endforeach
    </ul>
</nav>
