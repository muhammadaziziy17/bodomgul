@extends('admin.layouts.app')

@section('heading', 'Boshqaruv paneli')

@section('content')
    <div class="a-stats">
        <a class="a-stat" href="{{ route('admin.products.index') }}">
            <span class="a-stat__icon"><i class="bi bi-bag" aria-hidden="true"></i></span>
            <span class="a-stat__value">{{ $stats['products'] }}</span>
            <span class="a-stat__label">Mahsulotlar</span>
        </a>
        <a class="a-stat" href="{{ route('admin.categories.index') }}">
            <span class="a-stat__icon"><i class="bi bi-tags" aria-hidden="true"></i></span>
            <span class="a-stat__value">{{ $stats['categories'] }}</span>
            <span class="a-stat__label">Kategoriyalar</span>
        </a>
        <a class="a-stat" href="{{ route('admin.news.index') }}">
            <span class="a-stat__icon"><i class="bi bi-newspaper" aria-hidden="true"></i></span>
            <span class="a-stat__value">{{ $stats['news'] }}</span>
            <span class="a-stat__label">Yangiliklar</span>
        </a>
        <a class="a-stat" href="{{ route('admin.branches.index') }}">
            <span class="a-stat__icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
            <span class="a-stat__value">{{ $stats['branches'] }}</span>
            <span class="a-stat__label">Filiallar</span>
        </a>
        <a class="a-stat" href="{{ route('admin.hero-slides.index') }}">
            <span class="a-stat__icon"><i class="bi bi-images" aria-hidden="true"></i></span>
            <span class="a-stat__value">{{ $stats['heroSlides'] }}</span>
            <span class="a-stat__label">Hero slaydlar</span>
        </a>
    </div>

    @if ($hiddenProducts > 0)
        <div class="a-flash a-flash--note">
            <i class="bi bi-eye-slash" aria-hidden="true"></i>
            <span>{{ $hiddenProducts }} ta mahsulot saytda yashirilgan holatda turibdi.</span>
        </div>
    @endif

    <div class="a-card">
        <div class="a-card__head">
            <h2>Oxirgi yangiliklar</h2>
            <a class="a-link" href="{{ route('admin.news.index') }}">Barchasini ko‘rish</a>
        </div>

        @if ($latestNews->isEmpty())
            <p class="a-empty">Hozircha yangiliklar yo‘q.</p>
        @else
            <table class="a-table">
                <thead>
                    <tr>
                        <th>Sarlavha</th>
                        <th>Tag</th>
                        <th>Sana</th>
                        <th>Holat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestNews as $item)
                        <tr>
                            <td>
                                <a class="a-link" href="{{ route('admin.news.edit', $item) }}">{{ $item->title }}</a>
                            </td>
                            <td>{{ $item->tag }}</td>
                            <td>{{ $item->published_at?->format('d.m.Y') }}</td>
                            <td>
                                @if ($item->is_published)
                                    <span class="a-badge a-badge--success">Chop etilgan</span>
                                @else
                                    <span class="a-badge a-badge--muted">Qoralama</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="a-card">
        <div class="a-card__head">
            <h2>Tezkor amallar</h2>
        </div>
        <div class="a-quick">
            <a class="a-quick__item" href="{{ route('admin.products.create') }}">
                <i class="bi bi-plus-circle" aria-hidden="true"></i> Yangi mahsulot
            </a>
            <a class="a-quick__item" href="{{ route('admin.news.create') }}">
                <i class="bi bi-plus-circle" aria-hidden="true"></i> Yangi yangilik
            </a>
            <a class="a-quick__item" href="{{ route('admin.hero-slides.create') }}">
                <i class="bi bi-plus-circle" aria-hidden="true"></i> Hero slayd qo‘shish
            </a>
            <a class="a-quick__item" href="{{ route('admin.settings.edit') }}">
                <i class="bi bi-sliders" aria-hidden="true"></i> Sayt sozlamalari
            </a>
        </div>
    </div>
@endsection
