<?php

namespace App\Http\Requests\Admin;

use App\Support\Locales;
use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $default = Locales::default();

        return [
            'translations' => ['required', 'array'],
            'translations.'.$default.'.name' => ['required', 'string', 'max:200'],
            'translations.'.$default.'.address' => ['required', 'string', 'max:255'],
            'translations.*.name' => ['nullable', 'string', 'max:200'],
            'translations.*.district' => ['nullable', 'string', 'max:150'],
            'translations.*.address' => ['nullable', 'string', 'max:255'],
            'translations.*.hours' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'map_url' => ['nullable', 'url', 'max:500'],
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
            'translations.'.$default.'.name.required' => 'Asosiy tildagi filial nomini kiriting.',
            'translations.'.$default.'.address.required' => 'Asosiy tildagi manzilni kiriting.',
            'map_url.url' => 'Xarita havolasi to‘g‘ri URL bo‘lishi kerak.',
        ];
    }
}
