<div class="a-card">
    <div class="a-card__head">
        <h2>Sahifa sarlavhasi</h2>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($data = $localizedContents[$locale->code] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.input
                    name="title[{{ $locale->code }}]"
                    label="Sahifa sarlavhasi"
                    :value="$titles[$locale->code] ?? ''"
                    :required="$locale->is_default"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][description]"
                    label="Qisqa tavsif"
                    :value="$data['description'] ?? ''"
                />
            </div>
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Xizmatlar bloki</h2>
        <p class="a-card__hint">Telefon, email, manzil va ish vaqti «Sozlamalar» bo‘limidan boshqariladi.</p>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($data = $localizedContents[$locale->code] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.input
                    name="content[{{ $locale->code }}][services_title]"
                    label="Sarlavha"
                    :value="$data['services_title'] ?? ''"
                />
                <x-admin.textarea
                    name="services_text[{{ $locale->code }}]"
                    label="Xizmatlar ro‘yxati"
                    :value="implode(\"\n\", $data['services'] ?? [])"
                    :rows="4"
                    hint="Har bir xizmatni alohida qatorda yozing."
                />
            </div>
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Aloqa formasi</h2>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($data = $localizedContents[$locale->code] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.input
                    name="content[{{ $locale->code }}][form_title]"
                    label="Forma sarlavhasi"
                    :value="$data['form_title'] ?? ''"
                />
                <x-admin.textarea
                    name="content[{{ $locale->code }}][form_note]"
                    label="Forma izohi"
                    :value="$data['form_note'] ?? ''"
                    :rows="2"
                />
            </div>
        </div>
    @endforeach
</div>
