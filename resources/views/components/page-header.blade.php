@props(['title', 'description' => null, 'breadcrumbs' => []])

<section class="page-header">
    <div class="container">
        @if ($breadcrumbs)
            <x-breadcrumb :items="$breadcrumbs" />
        @endif

        <h1 class="page-header__title">{{ $title }}</h1>

        @if ($description)
            <p class="page-header__text">{{ $description }}</p>
        @endif

        @if (trim($slot ?? '') !== '')
            <div class="page-header__actions">{{ $slot }}</div>
        @endif
    </div>
</section>
