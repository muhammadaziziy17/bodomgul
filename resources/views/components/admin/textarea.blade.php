@props([
    'name',
    'label' => null,
    'value' => null,
    'rows' => 5,
    'hint' => null,
    'required' => false,
    'placeholder' => null,
])

@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $fieldId = 'field-'.\Illuminate\Support\Str::slug(str_replace(['[', ']'], '-', $name), '-');
@endphp

<div class="a-field a-field--full">
    @if ($label)
        <label class="a-label" for="{{ $fieldId }}">
            {{ $label }}@if ($required)<span class="a-req">*</span>@endif
        </label>
    @endif

    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->class(['a-input', 'a-textarea', 'is-invalid' => $errors->has($key)]) }}
    >{{ old($key, $value) }}</textarea>

    @if ($hint)
        <p class="a-hint">{{ $hint }}</p>
    @endif

    @error($key)
        <p class="a-error">{{ $message }}</p>
    @enderror
</div>
