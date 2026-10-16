@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => '— Tanlanmagan —',
    'hint' => null,
    'required' => false,
])

@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $fieldId = 'field-'.\Illuminate\Support\Str::slug(str_replace(['[', ']'], '-', $name), '-');
    $selectedValue = old($key, $value);
@endphp

<div class="a-field">
    @if ($label)
        <label class="a-label" for="{{ $fieldId }}">
            {{ $label }}@if ($required)<span class="a-req">*</span>@endif
        </label>
    @endif

    <select
        id="{{ $fieldId }}"
        name="{{ $name }}"
        {{ $attributes->class(['a-input', 'a-select', 'is-invalid' => $errors->has($key)]) }}
    >
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $selectedValue === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if ($hint)
        <p class="a-hint">{{ $hint }}</p>
    @endif

    @error($key)
        <p class="a-error">{{ $message }}</p>
    @enderror
</div>
