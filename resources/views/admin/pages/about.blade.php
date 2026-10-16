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
                    hint="Sarlavha ostidagi bir qatorli matn."
                />
            </div>
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Tarix bloki</h2>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($data = $localizedContents[$locale->code] ?? [])
        @php($story = $data['story'] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.image
                    name="story_image_upload[{{ $locale->code }}]"
                    label="Rasm"
                    :current="$story['image'] ?? null"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][story][eyebrow]"
                    label="Kichik yozuv"
                    :value="$story['eyebrow'] ?? ''"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][story][title]"
                    label="Sarlavha"
                    :value="$story['title'] ?? ''"
                />
                <x-admin.textarea
                    name="story_paragraphs[{{ $locale->code }}]"
                    label="Matn"
                    :value="implode(\"\n\n\", $story['paragraphs'] ?? [])"
                    :rows="6"
                    hint="Har bir paragrafni bo‘sh qator bilan ajrating."
                />
            </div>
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Missiya</h2>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($mission = ($localizedContents[$locale->code] ?? [])['mission'] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <div class="a-grid">
                <x-admin.input
                    name="content[{{ $locale->code }}][mission][badge]"
                    label="Yorliq"
                    :value="$mission['badge'] ?? ''"
                />
                <x-admin.input
                    name="content[{{ $locale->code }}][mission][attribution]"
                    label="Imzo"
                    :value="$mission['attribution'] ?? ''"
                />
                <x-admin.textarea
                    name="content[{{ $locale->code }}][mission][text]"
                    label="Missiya matni"
                    :value="$mission['text'] ?? ''"
                    :rows="3"
                />
            </div>
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Qadriyatlar</h2>
        <p class="a-card__hint">Kartalar saytda shu tartibda chiqadi.</p>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($values = ($localizedContents[$locale->code] ?? [])['values'] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <x-admin.repeater
                name="content[{{ $locale->code }}][values]"
                :items="old('content.'.$locale->code.'.values', $values)"
                :fields="[
                    ['key' => 'icon', 'label' => 'Ikonka (bi-...)'],
                    ['key' => 'title', 'label' => 'Sarlavha'],
                    ['key' => 'text', 'label' => 'Matn', 'type' => 'textarea'],
                ]"
                add-label="Qadriyat qo‘shish"
            />
        </div>
    @endforeach
</div>

<div class="a-card">
    <div class="a-card__head">
        <h2>Raqamlar</h2>
    </div>

    @foreach (\App\Support\Locales::all() as $locale)
        @php($stats = ($localizedContents[$locale->code] ?? [])['stats'] ?? [])
        <div class="a-locale-pane" data-locale-pane="{{ $locale->code }}" @if (! $loop->first) hidden @endif>
            <x-admin.repeater
                name="content[{{ $locale->code }}][stats]"
                :items="old('content.'.$locale->code.'.stats', $stats)"
                :fields="[
                    ['key' => 'value', 'label' => 'Qiymat (masalan: 1 200+)'],
                    ['key' => 'label', 'label' => 'Izoh'],
                ]"
                add-label="Raqam qo‘shish"
            />
        </div>
    @endforeach
</div>
