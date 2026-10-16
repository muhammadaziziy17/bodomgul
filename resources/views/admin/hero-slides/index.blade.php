@extends('admin.layouts.app')

@section('heading', 'Hero slaydlar')

@section('content')
    <div class="a-page-actions">
        <p class="a-page-actions__text">
            Bosh sahifadagi katta slayder. Har bir slaydning matnlari tillar bo‘yicha kiritiladi.
        </p>
        <a
            class="a-btn"
            href="{{ route('admin.hero-slides.create') }}"
            data-admin-panel="{{ route('admin.hero-slides.create', ['panel' => 1]) }}"
            data-admin-title="Yangi slayd"
        >
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Yangi slayd
        </a>
    </div>

    <div class="a-card" data-admin-table data-admin-table-url="{{ route('admin.hero-slides.index') }}">
        @include('admin.hero-slides._table')
    </div>
@endsection
