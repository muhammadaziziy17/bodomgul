@extends('admin.layouts.app')

@section('heading', 'Filiallar')

@section('content')
    <div class="a-page-actions">
        <p class="a-page-actions__text">
            Filiallar ro‘yxati. Xarita havolasi bo‘sh qoldirilsa, manzil bo‘yicha avtomatik qidiriladi.
        </p>
        <a
            class="a-btn"
            href="{{ route('admin.branches.create') }}"
            data-admin-panel="{{ route('admin.branches.create', ['panel' => 1]) }}"
            data-admin-title="Yangi filial"
        >
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Yangi filial
        </a>
    </div>

    <div class="a-card" data-admin-table data-admin-table-url="{{ route('admin.branches.index') }}">
        @include('admin.branches._table')
    </div>
@endsection
