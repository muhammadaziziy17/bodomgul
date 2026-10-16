@props([
    'name',
    'items' => [],
    'fields' => [],
    'addLabel' => 'Qator qo‘shish',
])

<div class="a-repeater" data-repeater="{{ $name }}">
    <div class="a-repeater__rows" data-repeater-rows>
        @foreach ($items as $index => $row)
            <div class="a-repeater__row">
                @foreach ($fields as $field)
                    <label class="a-field">
                        <span class="a-label">{{ $field['label'] }}</span>
                        @if (($field['type'] ?? 'text') === 'textarea')
                            <textarea
                                class="a-input a-textarea"
                                rows="2"
                                name="{{ $name }}[{{ $index }}][{{ $field['key'] }}]"
                            >{{ $row[$field['key']] ?? '' }}</textarea>
                        @else
                            <input
                                class="a-input"
                                type="{{ $field['type'] ?? 'text' }}"
                                name="{{ $name }}[{{ $index }}][{{ $field['key'] }}]"
                                value="{{ $row[$field['key']] ?? '' }}"
                            >
                        @endif
                    </label>
                @endforeach
                <button type="button" class="a-repeater__remove" data-repeater-remove aria-label="Qatorni o‘chirish">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
            </div>
        @endforeach
    </div>

    <template data-repeater-template>
        <div class="a-repeater__row">
            @foreach ($fields as $field)
                <label class="a-field">
                    <span class="a-label">{{ $field['label'] }}</span>
                    @if (($field['type'] ?? 'text') === 'textarea')
                        <textarea
                            class="a-input a-textarea"
                            rows="2"
                            name="{{ $name }}[__INDEX__][{{ $field['key'] }}]"
                        ></textarea>
                    @else
                        <input
                            class="a-input"
                            type="{{ $field['type'] ?? 'text' }}"
                            name="{{ $name }}[__INDEX__][{{ $field['key'] }}]"
                            value=""
                        >
                    @endif
                </label>
            @endforeach
            <button type="button" class="a-repeater__remove" data-repeater-remove aria-label="Qatorni o‘chirish">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>
    </template>

    <button type="button" class="a-add-row" data-repeater-add>
        <i class="bi bi-plus-lg" aria-hidden="true"></i> {{ $addLabel }}
    </button>
</div>
