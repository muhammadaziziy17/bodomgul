<form
    method="POST"
    action="{{ $slide->exists ? route('admin.hero-slides.update', $slide) : route('admin.hero-slides.store') }}"
    enctype="multipart/form-data"
    class="a-form"
    data-admin-form
    data-admin-index="{{ route('admin.hero-slides.index') }}"
>
    @csrf
    @if ($slide->exists)
        @method('PUT')
    @endif

    <div class="a-card">
        <div class="a-card__head">
            <h2>Asosiy ma’lumotlar</h2>
        </div>

        <div class="a-grid">
            <x-admin.image :current="$slide->image" :required="! $slide->exists" />

            <x-admin.input name="sort_order" label="Tartib raqami" type="number" :value="$slide->sort_order" />
            <x-admin.toggle name="is_active" label="Saytda ko‘rsatish" :checked="$slide->is_active" />

            <x-admin.select
                name="primary_route"
                label="Asosiy tugma yo‘nalishi"
                :options="\App\Support\RouteOptions::all()"
                :value="$slide->primary_route"
            />
            <x-admin.select
                name="primary_category"
                label="Kategoriya (agar yo‘nalish — kategoriya sahifasi)"
                :options="$categories->pluck('name', 'slug')->all()"
                :value="$slide->primary_params['category'] ?? null"
            />
            <x-admin.select
                name="secondary_route"
                label="Ikkinchi tugma yo‘nalishi"
                :options="\App\Support\RouteOptions::all()"
                :value="$slide->secondary_route"
            />
        </div>
    </div>

    <div class="a-card" data-locale-scope>
        <div class="a-card__head">
            <h2>Matnlar (tillar bo‘yicha)</h2>
        </div>

        <x-admin.locale-tabs />

        @foreach (\App\Support\Locales::all() as $locale)
            <div
                class="a-locale-pane"
                data-locale-pane="{{ $locale->code }}"
                @if (! $loop->first) hidden @endif
            >
                <div class="a-grid">
                    <x-admin.input
                        name="translations[{{ $locale->code }}][title]"
                        label="Sarlavha"
                        :value="$slide->translationFor($locale->code, 'title')"
                        :required="$locale->is_default"
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][eyebrow]"
                        label="Kichik yozuv (eyebrow)"
                        :value="$slide->translationFor($locale->code, 'eyebrow')"
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][primary_label]"
                        label="Asosiy tugma matni"
                        :value="$slide->translationFor($locale->code, 'primary_label')"
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][secondary_label]"
                        label="Ikkinchi tugma matni"
                        :value="$slide->translationFor($locale->code, 'secondary_label')"
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][alt]"
                        label="Rasm tavsifi (alt)"
                        :value="$slide->translationFor($locale->code, 'alt')"
                    />
                    <x-admin.textarea
                        name="translations[{{ $locale->code }}][text]"
                        label="Qisqa matn"
                        :value="$slide->translationFor($locale->code, 'text')"
                        :rows="3"
                    />
                </div>
            </div>
        @endforeach
    </div>

    <div class="a-form__actions">
        <button type="submit" class="a-btn">
            <i class="bi bi-check-lg" aria-hidden="true"></i> Saqlash
        </button>
        <a class="a-btn a-btn--ghost" href="{{ route('admin.hero-slides.index') }}" data-admin-close>Bekor qilish</a>
    </div>
</form>
