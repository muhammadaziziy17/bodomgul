<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use App\Support\Locales;
use App\Support\RouteOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            // Brend (asosiy til)
            'brand.name' => ['required', 'string', 'max:100'],
            'brand.city' => ['nullable', 'string', 'max:100'],
            'brand.tagline' => ['nullable', 'string', 'max:255'],
            'brand.motto' => ['nullable', 'string', 'max:500'],
            'brand.about' => ['nullable', 'string', 'max:1000'],
            'brand.phone' => ['nullable', 'string', 'max:50'],
            'brand.phone_display' => ['nullable', 'string', 'max:50'],
            'brand.email' => ['nullable', 'email', 'max:150'],
            'brand.address' => ['nullable', 'string', 'max:255'],
            'brand.hours' => ['nullable', 'string', 'max:150'],
            'brand.announcement' => ['nullable', 'string', 'max:500'],
            'brand.copyright_note' => ['nullable', 'string', 'max:500'],

            // Promo banner (asosiy til)
            'promo.eyebrow' => ['nullable', 'string', 'max:255'],
            'promo.title' => ['nullable', 'string', 'max:255'],
            'promo.text' => ['nullable', 'string', 'max:1000'],
            'promo.cta_label' => ['nullable', 'string', 'max:255'],
            'promo_image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'promo.cta_route' => ['nullable', 'string', Rule::in(array_keys(RouteOptions::all()))],
            'promo.cta_category' => [
                'nullable',
                'string',
                Rule::exists('translations', 'value')->where(
                    fn ($query) => $query->where('field', 'slug')
                        ->where('locale', Locales::default())
                        ->where('translatable_type', Category::class)
                ),
            ],

            // Ijtimoiy tarmoqlar
            'social.facebook' => ['nullable', 'url', 'max:500'],
            'social.instagram' => ['nullable', 'url', 'max:500'],
            'social.telegram' => ['nullable', 'url', 'max:500'],

            // Ilova havolalari
            'app.enabled' => ['nullable', 'boolean'],
            'app.google_play' => ['nullable', 'url', 'max:500'],
            'app.app_store' => ['nullable', 'url', 'max:500'],

            // Asosiy tildan boshqa tillar uchun matnlar
            // (nuqtali maydon nomi = SiteSettings::TRANSLATABLE_FIELDS)
            'overlay' => ['nullable', 'array'],
            'overlay.*' => ['nullable', 'array'],
            'overlay.*.*' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'brand.name.required' => 'Brend nomini kiriting.',
            'brand.email.email' => 'Elektron pochta manzili to‘g‘ri bo‘lishi kerak.',
            'promo_image_upload.mimes' => 'Rasm JPG, PNG yoki WEBP formatida bo‘lishi kerak.',
            'promo_image_upload.max' => 'Rasm hajmi 4 MB dan oshmasligi kerak.',
            'social.*.url' => 'Havola to‘g‘ri URL bo‘lishi kerak (https:// bilan boshlanadi).',
            'app.*.url' => 'Havola to‘g‘ri URL bo‘lishi kerak (https:// bilan boshlanadi).',
            'promo.cta_category.exists' => 'Tanlangan kategoriya topilmadi.',
        ];
    }
}
