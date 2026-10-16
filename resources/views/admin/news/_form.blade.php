<form
    method="POST"
    action="{{ $item->exists ? route('admin.news.update', $item) : route('admin.news.store') }}"
    enctype="multipart/form-data"
    class="a-form"
    data-admin-form
    data-admin-index="{{ route('admin.news.index') }}"
>
    @csrf
    @if ($item->exists)
        @method('PUT')
    @endif

    <div class="a-card">
        <div class="a-card__head">
            <h2>Asosiy ma’lumotlar</h2>
        </div>

        <div class="a-grid">
            <x-admin.image :current="$item->image" :required="! $item->exists" />
            <x-admin.input
                name="published_at"
                label="Sana"
                type="date"
                :value="$item->published_at?->toDateString()"
            />
            <x-admin.toggle name="is_published" label="Saytda chop etish" :checked="$item->is_published" />
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
                        :value="$item->translationFor($locale->code, 'title')"
                        :required="$locale->is_default"
                        data-slug-source
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][slug]"
                        label="URL manzil (slug)"
                        :value="$item->translationFor($locale->code, 'slug')"
                        :required="$locale->is_default"
                        data-slug-target
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][tag]"
                        label="Teg"
                        :value="$item->translationFor($locale->code, 'tag')"
                        placeholder="Masalan: Yangilik yoki Aksiya"
                    />
                    <x-admin.textarea
                        name="translations[{{ $locale->code }}][excerpt]"
                        label="Qisqa matn"
                        :value="$item->translationFor($locale->code, 'excerpt')"
                        :rows="2"
                        hint="Karta va ro‘yxatlarda ko‘rinadigan qisqa tavsif."
                    />
                    <x-admin.textarea
                        name="translations[{{ $locale->code }}][body]"
                        label="Maqola matni"
                        :value="$item->translationFor($locale->code, 'body')"
                        :rows="8"
                        hint="Har bir paragrafni bo‘sh qator bilan ajrating."
                    />
                </div>
            </div>
        @endforeach
    </div>

    <div class="a-form__actions">
        <button type="submit" class="a-btn">
            <i class="bi bi-check-lg" aria-hidden="true"></i> Saqlash
        </button>
        <a class="a-btn a-btn--ghost" href="{{ route('admin.news.index') }}" data-admin-close>Bekor qilish</a>
    </div>
</form>
