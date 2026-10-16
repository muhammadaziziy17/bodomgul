@props([
    'name',
    'label' => null,
    'value' => null,
    'type' => 'text',
    'hint' => null,
    'required' => false,
    'placeholder' => null,
])

@php
    // `story[eyebrow]` → old/error uchun `story.eyebrow`
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $fieldId = 'field-'.\Illuminate\Support\Str::slug(str_replace(['[', ']'], '-', $name), '-');
@endphp

<div class="a-field">
    @if ($label)
        <label class="a-label" for="{{ $fieldId }}">
            {{ $label }}@if ($required)<span class="a-req">*</span>@endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $fieldId }}"
        name="{{ $name }}"
        value="{{ old($key, $value) }}"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->class(['a-input', 'is-invalid' => $errors->has($key)]) }}
    >

    @if ($hint)
        <p class="a-hint">{{ $hint }}</p>
    @endif

    @error($key)
        <p class="a-error">{{ $message }}</p>
    @enderror
</div>
