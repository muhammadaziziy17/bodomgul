@if ($locales->isEmpty())
    <p class="a-empty">Hozircha tillar yo‘q.</p>
@else
    <table class="a-table">
        <thead>
            <tr>
                <th>Kodi</th>
                <th>Nomi</th>
                <th>Tartib</th>
                <th>Asosiy</th>
                <th>Holat</th>
                <th class="a-table__actions">Amallar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($locales as $locale)
                <tr>
                    <td><code class="a-code">{{ strtoupper($locale->code) }}</code></td>
                    <td><strong>{{ $locale->name }}</strong></td>
                    <td>{{ $locale->sort_order }}</td>
                    <td>
                        @if ($locale->is_default)
                            <span class="a-badge a-badge--success">Asosiy til</span>
                        @else
                            <button
                                type="button"
                                class="a-badge a-badge--muted"
                                data-admin-action="{{ route('admin.locales.default', $locale) }}"
                                data-admin-method="PUT"
                                style="border: 0; cursor: pointer;"
                            >Asosiy qilish</button>
                        @endif
                    </td>
                    <td>
                        <button
                            type="button"
                            class="a-switch @if ($locale->is_active) is-on @endif"
                            data-admin-toggle="{{ route('admin.locales.toggle', $locale) }}"
                            aria-pressed="{{ $locale->is_active ? 'true' : 'false' }}"
                            aria-label="Holatni almashtirish"
                            @disabled($locale->is_default)
                        ></button>
                    </td>
                    <td class="a-table__actions">
                        <div class="a-row-actions">
                            <a
                                class="a-icon-btn"
                                href="{{ route('admin.locales.edit', $locale) }}"
                                data-admin-panel="{{ route('admin.locales.edit', ['locale' => $locale, 'panel' => 1]) }}"
                                data-admin-title="Tilni tahrirlash"
                                aria-label="Tahrirlash"
                            >
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                            </a>
                            @if (! $locale->is_default)
                                <form
                                    method="POST"
                                    action="{{ route('admin.locales.destroy', $locale) }}"
                                    data-admin-delete
                                    data-confirm="«{{ $locale->name }}» tilini o‘chirishni tasdiqlaysizmi? Tarjimalar ham o‘chadi."
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="a-icon-btn a-icon-btn--danger" aria-label="O‘chirish">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
