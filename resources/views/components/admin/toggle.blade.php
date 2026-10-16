@props([
    'name',
    'label',
    'checked' => false,
    'hint' => null,
])

<label class="a-toggle">
    <input type="hidden" name="{{ $name }}" value="0">
    <input
        type="checkbox"
        name="{{ $name }}"
        value="1"
        @checked((bool) old($name, $checked))
        {{ $attributes }}
    >
    <span class="a-toggle__box" aria-hidden="true"></span>
    <span class="a-toggle__label">
        {{ $label }}
        @if ($hint)
            <small>{{ $hint }}</small>
        @endif
    </span>
</label>

@error($name)
    <p class="a-error">{{ $message }}</p>
@enderror
