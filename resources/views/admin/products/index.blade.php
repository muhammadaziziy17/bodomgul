@extends('admin.layouts.app')

@section('heading', 'Mahsulotlar')

@section('content')
    <div class="a-page-actions">
        <p class="a-page-actions__text">
            {{ $products->count() }} ta mahsulot. Narx so‘mda, butun son bilan kiritiladi.
        </p>
        <a
            class="a-btn"
            href="{{ route('admin.products.create') }}"
            data-admin-panel="{{ route('admin.products.create', ['panel' => 1]) }}"
            data-admin-title="Yangi mahsulot"
        >
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Yangi mahsulot
        </a>
    </div>

    <div class="a-card" data-admin-table data-admin-table-url="{{ route('admin.products.index') }}">
        @include('admin.products._table')
    </div>
@endsection
