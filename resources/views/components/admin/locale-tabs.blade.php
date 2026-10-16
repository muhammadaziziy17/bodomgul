@props([])

<div class="a-tabs" role="tablist">
    @foreach (\App\Support\Locales::all() as $locale)
        <button
            type="button"
            class="a-tab @if ($loop->first) is-active @endif"
            data-locale-tab="{{ $locale->code }}"
            role="tab"
        >
            {{ $locale->name }}
            <span class="a-tab__code">{{ mb_strtoupper($locale->code) }}</span>
        </button>
    @endforeach
</div>
