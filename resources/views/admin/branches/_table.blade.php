@if ($branches->isEmpty())
    <p class="a-empty">Hozircha filiallar yo‘q.</p>
@else
    <table class="a-table">
        <thead>
            <tr>
                <th>Nomi</th>
                <th>Manzil</th>
                <th>Ish vaqti</th>
                <th>Telefon</th>
                <th>Holat</th>
                <th class="a-table__actions">Amallar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($branches as $branch)
                <tr>
                    <td>
                        <strong>{{ $branch->name }}</strong>
                        <small class="a-muted">{{ $branch->district }}</small>
                    </td>
                    <td>{{ $branch->address }}</td>
                    <td>{{ $branch->hours }}</td>
                    <td>{{ $branch->phone ?? '—' }}</td>
                    <td>
                        <button
                            type="button"
                            class="a-switch @if ($branch->is_active) is-on @endif"
                            data-admin-toggle="{{ route('admin.branches.toggle', $branch) }}"
                            aria-pressed="{{ $branch->is_active ? 'true' : 'false' }}"
                            aria-label="Holatni almashtirish"
                        ></button>
                    </td>
                    <td class="a-table__actions">
                        <div class="a-row-actions">
                            <a
                                class="a-icon-btn"
                                href="{{ route('admin.branches.edit', $branch) }}"
                                data-admin-panel="{{ route('admin.branches.edit', ['branch' => $branch, 'panel' => 1]) }}"
                                data-admin-title="Filialni tahrirlash"
                                aria-label="Tahrirlash"
                            >
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                            </a>
                            <form
                                method="POST"
                                action="{{ route('admin.branches.destroy', $branch) }}"
                                data-admin-delete
                                data-confirm="«{{ $branch->name }}» filialini o‘chirishni tasdiqlaysizmi?"
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
