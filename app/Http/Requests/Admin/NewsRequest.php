<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesLocalizedSlug;
use App\Models\News;
use App\Support\Locales;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsRequest extends FormRequest
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
                Rule::requiredIf($this->route('news') === null),
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'translations' => ['required', 'array'],
            'translations.'.$default.'.title' => ['required', 'string', 'max:255'],
            'translations.'.$default.'.slug' => ['required', 'string', 'alpha_dash', 'max:120', $this->uniqueSlugRule(Locales::default(), News::class, 'news')],
            'translations.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'alpha_dash', 'max:120'],
            'translations.*.tag' => ['nullable', 'string', 'max:50'],
            'translations.*.excerpt' => ['nullable', 'string', 'max:500'],
            'translations.*.body' => ['nullable', 'string', 'max:20000'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $default = Locales::default();

        return [
            'image_upload.required' => 'Yangilik uchun rasm yuklang.',
            'image_upload.mimes' => 'Rasm JPG, PNG yoki WEBP formatida bo‘lishi kerak.',
            'image_upload.max' => 'Rasm hajmi 4 MB dan oshmasligi kerak.',
            'translations.'.$default.'.title.required' => 'Asosiy tildagi sarlavha kiritilishi shart.',
            'translations.'.$default.'.slug.required' => 'Asosiy tildagi URL manzil (slug) kiritilishi shart.',
        ];
    }
}
