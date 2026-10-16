@if ($slides->isEmpty())
    <p class="a-empty">Hozircha slaydlar yo‘q. «Yangi slayd» tugmasi bilan qo‘shing.</p>
@else
    <table class="a-table">
        <thead>
            <tr>
                <th>Rasm</th>
                <th>Sarlavha</th>
                <th>Yo‘nalish</th>
                <th>Tartib</th>
                <th>Holat</th>
                <th class="a-table__actions">Amallar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($slides as $slide)
                <tr>
                    <td><img class="a-thumb" src="{{ asset($slide->image) }}" alt="" loading="lazy"></td>
                    <td>
                        <strong>{{ $slide->title }}</strong>
                        @if ($slide->eyebrow)
                            <small class="a-muted">{{ $slide->eyebrow }}</small>
                        @endif
                    </td>
                    <td>
                        {{ \App\Support\RouteOptions::label($slide->primary_route) }}
                        @if (! empty($slide->primary_params['category']))
                            <small class="a-muted">({{ $slide->primary_params['category'] }})</small>
                        @endif
                    </td>
                    <td>{{ $slide->sort_order }}</td>
                    <td>
                        <button
                            type="button"
                            class="a-switch @if ($slide->is_active) is-on @endif"
                            data-admin-toggle="{{ route('admin.hero-slides.toggle', $slide) }}"
                            aria-pressed="{{ $slide->is_active ? 'true' : 'false' }}"
                            aria-label="Holatni almashtirish"
                        ></button>
                    </td>
                    <td class="a-table__actions">
                        <div class="a-row-actions">
                            <a
                                class="a-icon-btn"
                                href="{{ route('admin.hero-slides.edit', $slide) }}"
                                data-admin-panel="{{ route('admin.hero-slides.edit', ['hero_slide' => $slide, 'panel' => 1]) }}"
                                data-admin-title="Slaydni tahrirlash"
                                aria-label="Tahrirlash"
                            >
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                            </a>
                            <form
                                method="POST"
                                action="{{ route('admin.hero-slides.destroy', $slide) }}"
                                data-admin-delete
                                data-confirm="«{{ $slide->title }}» slaydini o‘chirishni tasdiqlaysizmi?"
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
