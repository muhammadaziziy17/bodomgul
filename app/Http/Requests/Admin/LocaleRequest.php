<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocaleRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'alpha',
                'min:2',
                'max:12',
                'lowercase',
                Rule::unique('locales', 'code')->ignore($this->route('locale')),
            ],
            'name' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Til kodini kiriting (masalan: ru, en).',
            'code.alpha' => 'Til kodi faqat lotin harflaridan iborat bo‘lishi kerak.',
            'code.lowercase' => 'Til kodi kichik harflarda yoziladi (masalan: ru).',
            'code.unique' => 'Bu til kodi allaqachon mavjud.',
            'code.min' => 'Til kodi kamida 2 ta harfdan iborat bo‘lishi kerak.',
            'name.required' => 'Til nomini kiriting (masalan: Ruscha).',
        ];
    }
}
