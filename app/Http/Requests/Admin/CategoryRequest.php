<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesLocalizedSlug;
use App\Models\Category;
use App\Support\Locales;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    use ValidatesLocalizedSlug;

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $default = Locales::default();

        return [
            'image_upload' => [
                Rule::requiredIf($this->route('category') === null),
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'translations' => ['required', 'array'],
            'translations.'.$default.'.name' => ['required', 'string', 'max:150'],
            'translations.'.$default.'.slug' => ['required', 'string', 'alpha_dash', 'max:100', $this->uniqueSlugRule($default, Category::class, 'category')],
            'translations.*.name' => ['nullable', 'string', 'max:150'],
            'translations.*.slug' => ['nullable', 'string', 'alpha_dash', 'max:100'],
            'translations.*.description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $default = Locales::default();

        return [
            'image_upload.required' => 'Kategoriya uchun rasm yuklang.',
            'image_upload.mimes' => 'Rasm JPG, PNG yoki WEBP formatida bo‘lishi kerak.',
            'image_upload.max' => 'Rasm hajmi 4 MB dan oshmasligi kerak.',
            'translations.'.$default.'.name.required' => 'Asosiy tildagi nom kiritilishi shart.',
            'translations.'.$default.'.slug.required' => 'Asosiy tildagi URL manzil (slug) kiritilishi shart.',
            'translations.*.slug.alpha_dash' => 'Slug faqat lotin harflari, raqamlar va chiziqchalardan iborat bo‘lishi kerak.',
        ];
    }
}
