@props([
    'name' => 'image_upload',
    'label' => 'Rasm',
    'current' => null,
    'hint' => 'JPG, PNG yoki WEBP. Maks. 4 MB.',
    'required' => false,
])

<div class="a-field a-field--full">
    <span class="a-label">
        {{ $label }}@if ($required)<span class="a-req">*</span>@endif
    </span>

    <div class="a-image">
        <div class="a-image__preview" data-image-preview>
            @if ($current)
                <img src="{{ asset($current) }}" alt="">
            @else
                <span class="a-image__empty">Rasm tanlanmagan</span>
            @endif
        </div>

        <div class="a-image__control">
            <input
                type="file"
                name="{{ $name }}"
                accept="image/jpeg,image/png,image/webp"
                data-image-input
                {{ $attributes->class(['a-file', 'is-invalid' => $errors->has($name)]) }}
            >
            <p class="a-hint">{{ $hint }}</p>
            @error($name)
                <p class="a-error">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
