@extends('admin.layouts.app')

@section('heading', 'Matnlar tarjimasi')

@section('content')
    <div class="a-page-actions">
        <p class="a-page-actions__text">
            Menyu, tugmalar va xabarlar matnlari. Har bir til ustuniga tarjimani yozing —
            bo‘sh qoldirilgan joyda asosiy tildagi matn ko‘rsatiladi.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.ui.update') }}"
        class="a-form"
        data-admin-form
        data-admin-index="{{ route('admin.ui.index') }}"
    >
        @csrf
        @method('PUT')

        @foreach ($grouped as $group => $strings)
            <div class="a-card">
                <div class="a-card__head">
                    <h2>{{ $groupLabels[$group] ?? $group }}</h2>
                    <p class="a-card__hint">{{ count($strings) }} ta matn</p>
                </div>

                <div class="a-strings">
                    @foreach ($strings as $key => $default)
                        <div class="a-string">
                            <div class="a-string__info">
                                <code class="a-code">{{ $key }}</code>
                                <p class="a-string__default">{{ $default }}</p>
                            </div>

                            <div class="a-string__inputs">
                                @foreach ($locales as $locale)
                                    <label class="a-field">
                                        <span class="a-label">
                                            {{ strtoupper($locale->code) }}
                                            @if ($locale->is_default)
                                                <span class="a-hint">(asosiy)</span>
                                            @endif
                                        </span>
                                        <input
                                            class="a-input"
                                            type="text"
                                            name="strings[{{ $locale->code }}][{{ $key }}]"
                                            value="{{ $overrides[$locale->code][$key] ?? ($locale->is_default ? $default : '') }}"
                                            @if ($locale->is_default) placeholder="{{ $default }}" @endif
                                        >
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="a-form__actions">
            <button type="submit" class="a-btn">
                <i class="bi bi-check-lg" aria-hidden="true"></i> Saqlash
            </button>
        </div>
    </form>
@endsection
