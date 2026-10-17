<div class="a-card">
    <div class="a-card__head">
        <h2>Sahifa sarlavhasi</h2>
        <p class="a-card__hint">Brauzer yorlig'i va SEO sarlavhasi uchun (H1 — «Yuqori blok»dagi sarlavha).</p>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.input
                    name="title[{{ $locale->code }}]"
                    label="Sahifa sarlavhasi"
                    :value="$titles[$locale->code] ?? ''"
                    :required="$locale->is_default"
                />
            </div>
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Yuqori blok</h2>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($hero = ($localizedContents[$locale->code] ?? [])['hero'] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.image
                    name="hero_image_upload[{{ $locale->code }}]"
                    label="Rasm"
                    :current="$hero['image'] ?? null"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][hero][badge]"
                    label="Yorliq"
                    :value="$hero['badge'] ?? ''"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][hero][title]"
                    label="Sarlavha"
                    :value="$hero['title'] ?? ''"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][hero][badge_text]"
                    label="Rasm ustidagi yozuv"
                    :value="$hero['badge_text'] ?? ''"
                />
                <x-admin.textarea
                    name="content[{{ $locale->code }}][hero][text]"
                    label="Matn"
                    :value="$hero['text'] ?? ''"
                    :rows="3"
                />
            </div>
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Afzalliklar («Nega aynan biz?»)</h2>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($perks = ($localizedContents[$locale->code] ?? [])['perks'] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.input
                    name="content[{{ $locale->code }}][perks][title]"
                    label="Bo‘lim sarlavhasi"
                    :value="$perks['title'] ?? ''"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][perks][subtitle]"
                    label="Bo‘lim izohi"
                    :value="$perks['subtitle'] ?? ''"
                />
            </div>

            <x-admin.repeater
                name="content[{{ $locale->code }}][perks][items]"
                :items="old('content.'.$locale->code.'.perks.items', $perks['items'] ?? [])"
                :fields="[
                    ['key' => 'icon', 'label' => 'Ikonka (bi-...)'],
                    ['key' => 'title', 'label' => 'Sarlavha'],
                    ['key' => 'text', 'label' => 'Matn', 'type' => 'textarea'],
                ]"
                add-label="Afzallik qo‘shish"
            />
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Vakansiyalar</h2>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($vacancies = ($localizedContents[$locale->code] ?? [])['vacancies'] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.input
                    name="content[{{ $locale->code }}][vacancies][title]"
                    label="Bo‘lim sarlavhasi"
                    :value="$vacancies['title'] ?? ''"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][vacancies][subtitle]"
                    label="Bo‘lim izohi"
                    :value="$vacancies['subtitle'] ?? ''"
                />
            </div>

            <x-admin.repeater
                name="content[{{ $locale->code }}][vacancies][items]"
                :items="old('content.'.$locale->code.'.vacancies.items', $vacancies['items'] ?? [])"
                :fields="[
                    ['key' => 'title', 'label' => 'Lavozim'],
                    ['key' => 'tag', 'label' => 'Teg'],
                    ['key' => 'text', 'label' => 'Vazifa matni', 'type' => 'textarea'],
                    ['key' => 'location', 'label' => 'Joylashuv'],
                    ['key' => 'salary', 'label' => 'Maosh'],
                    ['key' => 'schedule', 'label' => 'Grafik'],
                ]"
                add-label="Vakansiya qo‘shish"
            />
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Pastki murojaat bloki</h2>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($cta = ($localizedContents[$locale->code] ?? [])['cta'] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.input
                    name="content[{{ $locale->code }}][cta][title]"
                    label="Sarlavha"
                    :value="$cta['title'] ?? ''"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][cta][phone_label]"
                    label="Telefon (ko‘rinadigan)"
                    :value="$cta['phone_label'] ?? ''"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][cta][telegram_label]"
                    label="Telegram yorlig‘i"
                    :value="$cta['telegram_label'] ?? ''"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][cta][telegram_url]"
                    label="Telegram havolasi"
                    type="url"
                    :value="$cta['telegram_url'] ?? ''"
                />
                <x-admin.textarea
                    name="content[{{ $locale->code }}][cta][text]"
                    label="Matn"
                    :value="$cta['text'] ?? ''"
                    :rows="3"
                />
            </div>
        </div>
    @endforeach
</div>
