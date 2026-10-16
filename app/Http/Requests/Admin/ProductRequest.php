<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesLocalizedSlug;
use App\Models\Product;
use App\Support\Locales;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    use ValidatesLocalizedSlug;

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $default = Locales::default();

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'image_upload' => [
                Rule::requiredIf($this->route('product') === null),
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'translations' => ['required', 'array'],
            'translations.'.$default.'.name' => ['required', 'string', 'max:200'],
            'translations.'.$default.'.slug' => ['required', 'string', 'alpha_dash', 'max:120', $this->uniqueSlugRule(Locales::default(), Product::class, 'product')],
            'translations.*.name' => ['nullable', 'string', 'max:200'],
            'translations.*.slug' => ['nullable', 'string', 'alpha_dash', 'max:120'],
            'translations.*.description' => ['nullable', 'string', 'max:2000'],
            'translations.*.composition' => ['nullable', 'string', 'max:2000'],
            'translations.*.weight' => ['nullable', 'string', 'max:50'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $default = Locales::default();

        return [
            'category_id.required' => 'Kategoriyani tanlang.',
            'category_id.exists' => 'Tanlangan kategoriya topilmadi.',
            'price.required' => 'Narxni kiriting.',
            'price.integer' => 'Narx butun son bo‘lishi kerak (so‘m).',
            'image_upload.required' => 'Mahsulot uchun rasm yuklang.',
            'image_upload.mimes' => 'Rasm JPG, PNG yoki WEBP formatida bo‘lishi kerak.',
            'image_upload.max' => 'Rasm hajmi 4 MB dan oshmasligi kerak.',
            'translations.'.$default.'.name.required' => 'Asosiy tildagi nom kiritilishi shart.',
            'translations.'.$default.'.slug.required' => 'Asosiy tildagi URL manzil (slug) kiritilishi shart.',
            'translations.*.slug.alpha_dash' => 'Slug faqat lotin harflari, raqamlar va chiziqchalardan iborat bo‘lishi kerak.',
        ];
    }
}
