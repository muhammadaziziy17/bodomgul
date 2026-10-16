@props(['icon' => 'bi-inboxes', 'title', 'text' => null])

<div {{ $attributes->class(['empty-state']) }}>
    <span class="empty-state__icon" aria-hidden="true">
        <i class="bi {{ $icon }}"></i>
    </span>
    <h2 class="empty-state__title">{{ $title }}</h2>
    @if ($text)
        <p class="empty-state__text">{{ $text }}</p>
    @endif
    @if (trim($slot ?? '') !== '')
        <div class="empty-state__actions">{{ $slot }}</div>
    @endif
</div>
