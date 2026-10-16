@extends('admin.layouts.app')

@section('heading', 'Kategoriyalar')

@section('content')
    <div class="a-page-actions">
        <p class="a-page-actions__text">Katalog bo‘limlari. Nom va slug tillar bo‘yicha kiritiladi.</p>
        <a
            class="a-btn"
            href="{{ route('admin.categories.create') }}"
            data-admin-panel="{{ route('admin.categories.create', ['panel' => 1]) }}"
            data-admin-title="Yangi kategoriya"
        >
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Yangi kategoriya
        </a>
    </div>

    <div class="a-card" data-admin-table data-admin-table-url="{{ route('admin.categories.index') }}">
        @include('admin.categories._table')
    </div>
@endsection
