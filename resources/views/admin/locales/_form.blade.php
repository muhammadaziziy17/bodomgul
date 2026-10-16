<form
    method="POST"
    action="{{ $locale->exists ? route('admin.locales.update', $locale) : route('admin.locales.store') }}"
    class="a-form"
    data-admin-form
    data-admin-index="{{ route('admin.locales.index') }}"
>
    @csrf
    @if ($locale->exists)
        @method('PUT')
    @endif

    <div class="a-card">
        <div class="a-card__head">
            <h2>Til ma’lumotlari</h2>
        </div>

        <div class="a-grid">
            <x-admin.input
                name="code"
                label="Til kodi"
                :value="$locale->code"
                required
                placeholder="ru"
                hint="Standart kodlar: ru, en, kk, tr... (kichik harflar bilan)."
            />
            <x-admin.input
                name="name"
                label="Til nomi"
                :value="$locale->name"
                required
                placeholder="Ruscha"
                hint="Til almashtirgichda ko‘rinadigan nom."
            />
            <x-admin.input name="sort_order" label="Tartib raqami" type="number" :value="$locale->sort_order" />
            <x-admin.toggle name="is_active" label="Tilni yoqish" :checked="$locale->is_active" />
        </div>
    </div>

    <div class="a-form__actions">
        <button type="submit" class="a-btn">
            <i class="bi bi-check-lg" aria-hidden="true"></i> Saqlash
        </button>
        <a class="a-btn a-btn--ghost" href="{{ route('admin.locales.index') }}" data-admin-close>Bekor qilish</a>
    </div>
</form>
