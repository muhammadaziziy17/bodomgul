@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconAfter' => null,
    'type' => 'button',
    'block' => false,
])

@php
    $classes = ['btn', 'btn--'.$variant, 'btn--'.$size];

    if ($block) {
        $classes[] = 'btn--block';
    }
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon)
            <i class="bi {{ $icon }}" aria-hidden="true"></i>
        @endif
        <span>{{ $slot }}</span>
        @if ($iconAfter)
            <i class="bi {{ $iconAfter }}" aria-hidden="true"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        @if ($icon)
            <i class="bi {{ $icon }}" aria-hidden="true"></i>
        @endif
        <span>{{ $slot }}</span>
        @if ($iconAfter)
            <i class="bi {{ $iconAfter }}" aria-hidden="true"></i>
        @endif
    </button>
@endif
