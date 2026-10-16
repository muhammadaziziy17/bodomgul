@extends('admin.layouts.app')

@section('heading', 'Sayt sozlamalari')

@php
    $brand = $settings['brand'] ?? [];
    $promo = $settings['promo'] ?? [];
    $social = $settings['social'] ?? [];
    $app = $settings['app'] ?? [];
    $default = \App\Support\Locales::default();
@endphp

@section('content')
    <div class="a-page-actions">
        <p class="a-page-actions__text">
            Brend, promo banner, ijtimoiy tarmoq va ilova havolalari butun sayt bo‘ylab ishlatiladi.
            Matnli maydonlar tillar bo‘yicha kiritiladi.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
        enctype="multipart/form-data"
        class="a-form"
        data-admin-form
        data-admin-index="{{ route('admin.settings.edit') }}"
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

            <div class="a-card">
                <div class="a-card__head">
                    <h2>Brend</h2>
                </div>

                <div class="a-grid">
                    <x-admin.input name="brand[name]" label="Brend nomi" :value="$brand['name'] ?? ''" required />
                    <x-admin.input name="brand[city]" label="Shahar" :value="$brand['city'] ?? ''" />
                    <x-admin.input name="brand[phone]" label="Telefon" :value="$brand['phone'] ?? ''" placeholder="+998 78 113 40 40" />
                    <x-admin.input name="brand[phone_display]" label="Telefon (ko‘rinadigan)" :value="$brand['phone_display'] ?? ''" placeholder="78 113 40 40" />
                    <x-admin.input name="brand[email]" label="Email" type="email" :value="$brand['email'] ?? ''" />
                </div>

                @foreach (\App\Support\Locales::all() as $locale)
                    <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
                        <div class="a-grid">
                            @php($isDefault = $locale->code === $default)
                            <x-admin.input
                                name="{{ $isDefault ? 'brand[tagline]' : 'overlay['.$locale->code.'][brand.tagline]' }}"
                                label="Shior (tagline)"
                                :value="$isDefault ? ($brand['tagline'] ?? '') : ($overlays[$locale->code]['brand.tagline'] ?? '')"
                            />
                            <x-admin.input
                                name="{{ $isDefault ? 'brand[hours]' : 'overlay['.$locale->code.'][brand.hours]' }}"
                                label="Ish vaqti"
                                :value="$isDefault ? ($brand['hours'] ?? '') : ($overlays[$locale->code]['brand.hours'] ?? '')"
                            />
                            <x-admin.input
                                name="{{ $isDefault ? 'brand[address]' : 'overlay['.$locale->code.'][brand.address]' }}"
                                label="Manzil"
                                :value="$isDefault ? ($brand['address'] ?? '') : ($overlays[$locale->code]['brand.address'] ?? '')"
                            />
                            <x-admin.input
                                name="{{ $isDefault ? 'brand[copyright_note]' : 'overlay['.$locale->code.'][brand.copyright_note]' }}"
                                label="Mualliflik izohi (footer)"
                                :value="$isDefault ? ($brand['copyright_note'] ?? '') : ($overlays[$locale->code]['brand.copyright_note'] ?? '')"
                            />
                            <x-admin.textarea
                                name="{{ $isDefault ? 'brand[motto]' : 'overlay['.$locale->code.'][brand.motto]' }}"
                                label="Katta shior (footer)"
                                :value="$isDefault ? ($brand['motto'] ?? '') : ($overlays[$locale->code]['brand.motto'] ?? '')"
                                :rows="2"
                            />
                            <x-admin.textarea
                                name="{{ $isDefault ? 'brand[about]' : 'overlay['.$locale->code.'][brand.about]' }}"
                                label="Qisqa tavsif"
                                :value="$isDefault ? ($brand['about'] ?? '') : ($overlays[$locale->code]['brand.about'] ?? '')"
                                :rows="2"
                            />
                            <x-admin.textarea
                                name="{{ $isDefault ? 'brand[announcement]' : 'overlay['.$locale->code.'][brand.announcement]' }}"
                                label="E’lon matni"
                                :value="$isDefault ? ($brand['announcement'] ?? '') : ($overlays[$locale->code]['brand.announcement'] ?? '')"
                                :rows="2"
                            />
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="a-card">
                <div class="a-card__head">
                    <h2>Promo banner</h2>
                    <p class="a-card__hint">Bosh sahifadagi quyuq fonli banner.</p>
                </div>

                <div class="a-grid">
                    <x-admin.image
                        name="promo_image_upload"
                        label="Fon rasmi"
                        :current="$promo['image'] ?? null"
                    />
                    <x-admin.select
                        name="promo[cta_route]"
                        label="Tugma yo‘nalishi"
                        :options="\App\Support\RouteOptions::all()"
                        :value="$promo['cta_route'] ?? null"
                    />
                    <x-admin.select
                        name="promo[cta_category]"
                        label="Kategoriya (agar yo‘nalish — kategoriya sahifasi)"
                        :options="$categories->pluck('name', 'slug')->all()"
                        :value="$promo['cta_params']['category'] ?? null"
                    />
                </div>

                @foreach (\App\Support\Locales::all() as $locale)
                    <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
                        @php($isDefault = $locale->code === $default)
                        <div class="a-grid">
                            <x-admin.input
                                name="{{ $isDefault ? 'promo[eyebrow]' : 'overlay['.$locale->code.'][promo.eyebrow]' }}"
                                label="Kichik yozuv"
                                :value="$isDefault ? ($promo['eyebrow'] ?? '') : ($overlays[$locale->code]['promo.eyebrow'] ?? '')"
                            />
                            <x-admin.input
                                name="{{ $isDefault ? 'promo[title]' : 'overlay['.$locale->code.'][promo.title]' }}"
                                label="Sarlavha"
                                :value="$isDefault ? ($promo['title'] ?? '') : ($overlays[$locale->code]['promo.title'] ?? '')"
                            />
                            <x-admin.input
                                name="{{ $isDefault ? 'promo[cta_label]' : 'overlay['.$locale->code.'][promo.cta_label]' }}"
                                label="Tugma matni"
                                :value="$isDefault ? ($promo['cta_label'] ?? '') : ($overlays[$locale->code]['promo.cta_label'] ?? '')"
                            />
                            <x-admin.textarea
                                name="{{ $isDefault ? 'promo[text]' : 'overlay['.$locale->code.'][promo.text]' }}"
                                label="Matn"
                                :value="$isDefault ? ($promo['text'] ?? '') : ($overlays[$locale->code]['promo.text'] ?? '')"
                                :rows="3"
                            />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="a-card">
            <div class="a-card__head">
                <h2>Ijtimoiy tarmoqlar</h2>
            </div>

            <div class="a-grid">
                <x-admin.input name="social[facebook]" label="Facebook" type="url" :value="$social['facebook'] ?? ''" />
                <x-admin.input name="social[instagram]" label="Instagram" type="url" :value="$social['instagram'] ?? ''" />
                <x-admin.input name="social[telegram]" label="Telegram" type="url" :value="$social['telegram'] ?? ''" />
            </div>
        </div>

        <div class="a-card">
            <div class="a-card__head">
                <h2>Mobil ilova havolalari</h2>
                <p class="a-card__hint">Bosh sahifadagi «Mobil ilova» bo‘limi va footer havolalari.</p>
            </div>

            <div class="a-grid">
                <x-admin.toggle
                    name="app[enabled]"
                    label="«Mobil ilova» bo‘limini bosh sahifada ko‘rsatish"
                    :checked="$app['enabled'] ?? true"
                />
                <x-admin.input name="app[google_play]" label="Google Play" type="url" :value="$app['google_play'] ?? ''" />
                <x-admin.input name="app[app_store]" label="App Store" type="url" :value="$app['app_store'] ?? ''" />
            </div>
        </div>

        <div class="a-form__actions">
            <button type="submit" class="a-btn">
                <i class="bi bi-check-lg" aria-hidden="true"></i> Saqlash
            </button>
        </div>
    </form>
@endsection
