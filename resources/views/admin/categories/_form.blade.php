<form
    method="POST"
    action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
    enctype="multipart/form-data"
    class="a-form"
    data-admin-form
    data-admin-index="{{ route('admin.categories.index') }}"
>
    @csrf
    @if ($category->exists)
        @method('PUT')
    @endif

    <div class="a-card">
        <div class="a-card__head">
            <h2>Asosiy ma’lumotlar</h2>
        </div>

        <div class="a-grid">
            <x-admin.image :current="$category->image" :required="! $category->exists" />
            <x-admin.input name="sort_order" label="Tartib raqami" type="number" :value="$category->sort_order" />
            <x-admin.toggle name="is_active" label="Saytda ko‘rsatish" :checked="$category->is_active" />
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
                        name="translations[{{ $locale->code }}][name]"
                        label="Nomi"
                        :value="$category->translationFor($locale->code, 'name')"
                        :required="$locale->is_default"
                        data-slug-source
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][slug]"
                        label="URL manzil (slug)"
                        :value="$category->translationFor($locale->code, 'slug')"
                        :required="$locale->is_default"
                        data-slug-target
                        hint="Bo‘sh qoldirilsa, o‘sha tilda asosiy til manzili ishlatiladi."
                    />
                    <x-admin.textarea
                        name="translations[{{ $locale->code }}][description]"
                        label="Tavsif"
                        :value="$category->translationFor($locale->code, 'description')"
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
        <a class="a-btn a-btn--ghost" href="{{ route('admin.categories.index') }}" data-admin-close>Bekor qilish</a>
    </div>
</form>
