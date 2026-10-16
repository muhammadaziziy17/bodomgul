<form
    method="POST"
    action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
    enctype="multipart/form-data"
    class="a-form"
    data-admin-form
    data-admin-index="{{ route('admin.products.index') }}"
>
    @csrf
    @if ($product->exists)
        @method('PUT')
    @endif

    <div class="a-card">
        <div class="a-card__head">
            <h2>Asosiy ma’lumotlar</h2>
        </div>

        <div class="a-grid">
            <x-admin.image :current="$product->image" :required="! $product->exists" />

            <x-admin.select
                name="category_id"
                label="Kategoriya"
                :options="$categories->pluck('name', 'id')->all()"
                :value="$product->category_id"
                :placeholder="null"
                required
            />
            <x-admin.input name="price" label="Narx (so‘m)" type="number" :value="$product->price" required />
            <x-admin.input name="sort_order" label="Tartib raqami" type="number" :value="$product->sort_order" />

            <div class="a-toggles">
                <x-admin.toggle
                    name="is_featured"
                    label="«Sevimli mahsulotlar» bo‘limida ko‘rsatish"
                    :checked="$product->is_featured"
                />
                <x-admin.toggle name="is_active" label="Saytda ko‘rsatish" :checked="$product->is_active" />
            </div>
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
                        :value="$product->translationFor($locale->code, 'name')"
                        :required="$locale->is_default"
                        data-slug-source
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][slug]"
                        label="URL manzil (slug)"
                        :value="$product->translationFor($locale->code, 'slug')"
                        :required="$locale->is_default"
                        data-slug-target
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][weight]"
                        label="Vazn / hajm"
                        :value="$product->translationFor($locale->code, 'weight')"
                        placeholder="Masalan: 1 kg yoki 300 ml"
                    />
                    <x-admin.textarea
                        name="translations[{{ $locale->code }}][description]"
                        label="Tavsif"
                        :value="$product->translationFor($locale->code, 'description')"
                        :rows="3"
                    />
                    <x-admin.textarea
                        name="translations[{{ $locale->code }}][composition]"
                        label="Tarkib"
                        :value="$product->translationFor($locale->code, 'composition')"
                        :rows="2"
                    />
                </div>
            </div>
        @endforeach
    </div>

    <div class="a-form__actions">
        <button type="submit" class="a-btn">
            <i class="bi bi-check-lg" aria-hidden="true"></i> Saqlash
        </button>
        <a class="a-btn a-btn--ghost" href="{{ route('admin.products.index') }}" data-admin-close>Bekor qilish</a>
    </div>
</form>
