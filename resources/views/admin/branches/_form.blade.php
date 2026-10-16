<form
    method="POST"
    action="{{ $branch->exists ? route('admin.branches.update', $branch) : route('admin.branches.store') }}"
    class="a-form"
    data-admin-form
    data-admin-index="{{ route('admin.branches.index') }}"
>
    @csrf
    @if ($branch->exists)
        @method('PUT')
    @endif

    <div class="a-card">
        <div class="a-card__head">
            <h2>Umumiy ma’lumotlar</h2>
        </div>

        <div class="a-grid">
            <x-admin.input name="phone" label="Telefon" :value="$branch->phone" />
            <x-admin.input
                name="map_url"
                label="Xarita havolasi"
                :value="$branch->getRawOriginal('map_url')"
                placeholder="https://maps.google.com/..."
                hint="Bo‘sh qoldirilsa, manzil bo‘yicha avtomatik havola tayyorlanadi."
            />
            <x-admin.input name="sort_order" label="Tartib raqami" type="number" :value="$branch->sort_order" />
            <x-admin.toggle name="is_active" label="Saytda ko‘rsatish" :checked="$branch->is_active" />
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
                        :value="$branch->translationFor($locale->code, 'name')"
                        :required="$locale->is_default"
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][district]"
                        label="Tuman"
                        :value="$branch->translationFor($locale->code, 'district')"
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][address]"
                        label="Manzil"
                        :value="$branch->translationFor($locale->code, 'address')"
                        :required="$locale->is_default"
                    />
                    <x-admin.input
                        name="translations[{{ $locale->code }}][hours]"
                        label="Ish vaqti"
                        :value="$branch->translationFor($locale->code, 'hours')"
                        placeholder="Masalan: Har kuni, 08:00 — 22:00"
                    />
                </div>
            </div>
        @endforeach
    </div>

    <div class="a-form__actions">
        <button type="submit" class="a-btn">
            <i class="bi bi-check-lg" aria-hidden="true"></i> Saqlash
        </button>
        <a class="a-btn a-btn--ghost" href="{{ route('admin.branches.index') }}" data-admin-close>Bekor qilish</a>
    </div>
</form>
