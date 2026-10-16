@extends('layouts.app')

@section('title', t('contacts.title').' — Bodomgul')
@section('meta_description', t('contacts.description'))

@section('content')
    <div class="section-gap">
        <x-page-header
            :title="t('contacts.title')"
            :description="$content['description'] ?? t('contacts.description')"
            :breadcrumbs="[
                ['label' => t('common.home'), 'url' => route('home')],
                ['label' => t('contacts.title')],
            ]"
        />
    </div>

    <section class="safia-slab">
        <div class="container">
            <div class="contact-info-grid">
                <article class="contact-info-card">
                    <span class="contact-info-card__icon" aria-hidden="true"><i class="bi bi-telephone"></i></span>
                    <h2 class="contact-info-card__title">{{ t('contacts.phone_title') }}</h2>
                    <p class="contact-info-card__text">{{ $brand['phone'] }}</p>
                </article>

                <article class="contact-info-card">
                    <span class="contact-info-card__icon" aria-hidden="true"><i class="bi bi-envelope"></i></span>
                    <h2 class="contact-info-card__title">{{ t('contacts.email_title') }}</h2>
                    <p class="contact-info-card__text">{{ $brand['email'] }}</p>
                </article>

                <article class="contact-info-card">
                    <span class="contact-info-card__icon" aria-hidden="true"><i class="bi bi-geo-alt"></i></span>
                    <h2 class="contact-info-card__title">{{ t('contacts.address_title') }}</h2>
                    <p class="contact-info-card__text">{{ $brand['address'] }}</p>
                </article>

                <article class="contact-info-card">
                    <span class="contact-info-card__icon" aria-hidden="true"><i class="bi bi-clock"></i></span>
                    <h2 class="contact-info-card__title">{{ t('contacts.hours_title') }}</h2>
                    <p class="contact-info-card__text">{{ $brand['hours'] }}</p>
                </article>
            </div>
        </div>
    </section>

    <section class="safia-slab">
        <div class="container">
            <div class="contact-layout">
                <div class="contact-form-wrap">
                    <h2 class="contact-form__title">{{ $content['form_title'] ?? 'Bizga xabar qoldiring' }}</h2>

                    <p class="contact-form__note">
                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                        {{ $content['form_note'] ?? 'Demo forma: xabar serverda validatsiyadan o‘tadi va qabul qilinadi.' }}
                    </p>

                    @if (session('demo_notice'))
                        <div class="alert alert--success" role="status">{{ session('demo_notice') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert--error" role="alert">
                            <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                            <div>
                                <p><strong>{{ t('form.check_fields') }}</strong></p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form class="contact-form" action="{{ route('contacts.store') }}" method="post">
                        @csrf

                        <div class="form-field">
                            <label class="form-label" for="contact-name">{{ t('contacts.name_label') }}</label>
                            <input
                                class="form-input"
                                id="contact-name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                required
                                minlength="2"
                                maxlength="80"
                                autocomplete="name"
                            >
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="contact-contact">{{ t('contacts.contact_label') }}</label>
                            <input
                                class="form-input"
                                id="contact-contact"
                                name="contact"
                                type="text"
                                value="{{ old('contact') }}"
                                required
                                minlength="5"
                                maxlength="120"
                                autocomplete="email"
                            >
                            @error('contact')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="contact-message">{{ t('contacts.message_label') }}</label>
                            <textarea
                                class="form-input form-textarea"
                                id="contact-message"
                                name="message"
                                rows="5"
                                required
                                minlength="10"
                                maxlength="2000"
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <button class="btn btn--primary btn--lg" type="submit">
                            <i class="bi bi-send" aria-hidden="true"></i>
                            <span>{{ t('contacts.form_submit') }}</span>
                        </button>
                    </form>
                </div>

                <aside class="contact-aside">
                    <h2 class="contact-aside__title">{{ $content['services_title'] ?? 'Safia & Bodomgul servislari' }}</h2>
                    <ul class="contact-aside__list">
                        @foreach ($content['services'] ?? [] as $service)
                            <li><i class="bi bi-check2" aria-hidden="true"></i> {{ $service }}</li>
                        @endforeach
                    </ul>

                    <div class="contact-aside__links">
                        <a class="btn btn--secondary btn--block" href="{{ route('branches.index') }}">{{ t('contacts.view_branches') }}</a>
                        <a class="btn btn--ghost btn--block" href="{{ route('catalog.index') }}">{{ t('contacts.go_catalog') }}</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
