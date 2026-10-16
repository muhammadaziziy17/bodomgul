@if ($news->isEmpty())
    <p class="a-empty">Hozircha yangiliklar yo‘q.</p>
@else
    <table class="a-table">
        <thead>
            <tr>
                <th>Rasm</th>
                <th>Sarlavha</th>
                <th>Tag</th>
                <th>Sana</th>
                <th>Holat</th>
                <th class="a-table__actions">Amallar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($news as $item)
                <tr>
                    <td><img class="a-thumb" src="{{ asset($item->image) }}" alt="" loading="lazy"></td>
                    <td><strong>{{ $item->title }}</strong></td>
                    <td>
                        @if ($item->tag)
                            <span class="a-badge a-badge--accent">{{ $item->tag }}</span>
                        @endif
                    </td>
                    <td>{{ $item->published_at?->format('d.m.Y') }}</td>
                    <td>
                        <button
                            type="button"
                            class="a-switch @if ($item->is_published) is-on @endif"
                            data-admin-toggle="{{ route('admin.news.toggle', $item) }}"
                            aria-pressed="{{ $item->is_published ? 'true' : 'false' }}"
                            aria-label="Holatni almashtirish"
                        ></button>
                    </td>
                    <td class="a-table__actions">
                        <div class="a-row-actions">
                            <a
                                class="a-icon-btn"
                                href="{{ route('admin.news.edit', $item) }}"
                                data-admin-panel="{{ route('admin.news.edit', ['news' => $item, 'panel' => 1]) }}"
                                data-admin-title="Yangilikni tahrirlash"
                                aria-label="Tahrirlash"
                            >
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                            </a>
                            <form
                                method="POST"
                                action="{{ route('admin.news.destroy', $item) }}"
                                data-admin-delete
                                data-confirm="«{{ $item->title }}» yangiligini o‘chirishni tasdiqlaysizmi?"
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
