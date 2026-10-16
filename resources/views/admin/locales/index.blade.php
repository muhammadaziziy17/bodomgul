@extends('admin.layouts.app')

@section('heading', 'Tillar')

@section('content')
    <div class="a-page-actions">
        <p class="a-page-actions__text">
            Sayt tillarini shu yerda qo‘shasiz. Yangi til qo‘shilgach, sayt manzillarida (/ru, /en va h.k.)
            darhol ishlaydi; matnlar kiritilmagan joyda asosiy til ko‘rsatiladi.
        </p>
        <a
            class="a-btn"
            href="{{ route('admin.locales.create') }}"
            data-admin-panel="{{ route('admin.locales.create', ['panel' => 1]) }}"
            data-admin-title="Yangi til"
        >
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Yangi til
        </a>
    </div>

    <div class="a-card" data-admin-table data-admin-table-url="{{ route('admin.locales.index') }}">
        @include('admin.locales._table')
    </div>
@endsection
