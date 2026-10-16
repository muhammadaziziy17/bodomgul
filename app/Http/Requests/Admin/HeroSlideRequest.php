<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use App\Support\Locales;
use App\Support\RouteOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroSlideRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'image_upload' => [
                Rule::requiredIf($this->route('hero_slide') === null),
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'translations' => ['required', 'array'],
            'translations.'.Locales::default().'.title' => ['required', 'string', 'max:255'],
            'translations.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.alt' => ['nullable', 'string', 'max:255'],
            'translations.*.eyebrow' => ['nullable', 'string', 'max:255'],
            'translations.*.text' => ['nullable', 'string', 'max:1000'],
            'translations.*.primary_label' => ['nullable', 'string', 'max:255'],
            'translations.*.secondary_label' => ['nullable', 'string', 'max:255'],
            'primary_route' => ['nullable', 'string', Rule::in(array_keys(RouteOptions::all()))],
            'primary_category' => [
                'nullable',
                'string',
                Rule::exists('translations', 'value')->where(
                    fn ($query) => $query->where('field', 'slug')
                        ->where('locale', Locales::default())
                        ->where('translatable_type', Category::class)
                ),
            ],
            'secondary_route' => ['nullable', 'string', Rule::in(array_keys(RouteOptions::all()))],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image_upload.required' => 'Slayd uchun rasm yuklang.',
            'image_upload.mimes' => 'Rasm JPG, PNG yoki WEBP formatida bo‘lishi kerak.',
            'image_upload.max' => 'Rasm hajmi 4 MB dan oshmasligi kerak.',
            'translations.'.Locales::default().'.title.required' => 'Asosiy tildagi sarlavha kiritilishi shart.',
            'primary_category.exists' => 'Tanlangan kategoriya topilmadi.',
        ];
    }
}
