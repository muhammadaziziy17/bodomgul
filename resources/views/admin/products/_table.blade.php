@if ($products->isEmpty())
    <p class="a-empty">Hozircha mahsulotlar yo‘q.</p>
@else
    <table class="a-table">
        <thead>
            <tr>
                <th>Rasm</th>
                <th>Nomi</th>
                <th>Kategoriya</th>
                <th>Narx</th>
                <th>Holat</th>
                <th class="a-table__actions">Amallar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td><img class="a-thumb" src="{{ asset($product->image) }}" alt="" loading="lazy"></td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        <small class="a-muted">{{ $product->weight }}</small>
                    </td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ format_price($product->price) }}</td>
                    <td>
                        <div class="a-row-actions" style="justify-content: flex-start;">
                            @if ($product->is_featured)
                                <span class="a-badge a-badge--accent">Tanlangan</span>
                            @endif
                            <button
                                type="button"
                                class="a-switch @if ($product->is_active) is-on @endif"
                                data-admin-toggle="{{ route('admin.products.toggle', $product) }}"
                                aria-pressed="{{ $product->is_active ? 'true' : 'false' }}"
                                aria-label="Holatni almashtirish"
                            ></button>
                        </div>
                    </td>
                    <td class="a-table__actions">
                        <div class="a-row-actions">
                            <a
                                class="a-icon-btn"
                                href="{{ route('admin.products.edit', $product) }}"
                                data-admin-panel="{{ route('admin.products.edit', ['product' => $product, 'panel' => 1]) }}"
                                data-admin-title="Mahsulotni tahrirlash"
                                aria-label="Tahrirlash"
                            >
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                            </a>
                            <form
                                method="POST"
                                action="{{ route('admin.products.destroy', $product) }}"
                                data-admin-delete
                                data-confirm="«{{ $product->name }}» mahsulotini o‘chirishni tasdiqlaysizmi?"
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
