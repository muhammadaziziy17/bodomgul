@extends('admin.layouts.app')

@section('heading', $page->title.' — sahifa')

@section('content')
    <form
        method="POST"
        action="{{ route('admin.pages.update', $page) }}"
        enctype="multipart/form-data"
        class="a-form"
        data-admin-form
        data-admin-index="{{ route('admin.pages.edit', $page) }}"
    >
        @csrf
        @method('PUT')

        <div data-locale-scope>
            <div class="a-card">
                <div class="a-card__head">
                    <h2>Tillar</h2>
                    <p class="a-card__hint">Tilni tanlab, shu tildagi matnlarni to‘ldiring.</p>
                </div>

                <x-admin.locale-tabs />
            </div>

            @include('admin.pages.'.$page->slug)
        </div>

        <div class="a-form__actions">
            <button type="submit" class="a-btn">
                <i class="bi bi-check-lg" aria-hidden="true"></i> Saqlash
            </button>
        </div>
    </form>
@endsection
