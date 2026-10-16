@if ($categories->isEmpty())
    <p class="a-empty">Hozircha kategoriyalar yo‘q.</p>
@else
    <table class="a-table">
        <thead>
            <tr>
                <th>Rasm</th>
                <th>Nomi</th>
                <th>Slug</th>
                <th>Mahsulotlar</th>
                <th>Tartib</th>
                <th>Holat</th>
                <th class="a-table__actions">Amallar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td><img class="a-thumb" src="{{ asset($category->image) }}" alt="" loading="lazy"></td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td><code class="a-code">{{ $category->slug }}</code></td>
                    <td>{{ $category->products_count }} ta</td>
                    <td>{{ $category->sort_order }}</td>
                    <td>
                        <button
                            type="button"
                            class="a-switch @if ($category->is_active) is-on @endif"
                            data-admin-toggle="{{ route('admin.categories.toggle', $category) }}"
                            aria-pressed="{{ $category->is_active ? 'true' : 'false' }}"
                            aria-label="Holatni almashtirish"
                        ></button>
                    </td>
                    <td class="a-table__actions">
                        <div class="a-row-actions">
                            <a
                                class="a-icon-btn"
                                href="{{ route('admin.categories.edit', $category) }}"
                                data-admin-panel="{{ route('admin.categories.edit', ['category' => $category, 'panel' => 1]) }}"
                                data-admin-title="Kategoriyani tahrirlash"
                                aria-label="Tahrirlash"
                            >
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                            </a>
                            <form
                                method="POST"
                                action="{{ route('admin.categories.destroy', $category) }}"
                                data-admin-delete
                                data-confirm="«{{ $category->name }}» kategoriyasini o‘chirishni tasdiqlaysizmi?"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="a-icon-btn a-icon-btn--danger" aria-label="O‘chirish">
                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
