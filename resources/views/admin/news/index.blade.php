@extends('admin.layouts.app')

@section('heading', 'Yangiliklar')

@section('content')
    <div class="a-page-actions">
        <p class="a-page-actions__text">
            Yangiliklar va aksiyalar. Chop etilganlari saytda eng yangisi birinchi ko‘rinadi.
        </p>
        <a
            class="a-btn"
            href="{{ route('admin.news.create') }}"
            data-admin-panel="{{ route('admin.news.create', ['panel' => 1]) }}"
            data-admin-title="Yangi yangilik"
        >
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Yangi yangilik
        </a>
    </div>

    <div class="a-card" data-admin-table data-admin-table-url="{{ route('admin.news.index') }}">
        @include('admin.news._table')
    </div>
@endsection
