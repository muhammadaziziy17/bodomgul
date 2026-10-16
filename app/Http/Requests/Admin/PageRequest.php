<?php

namespace App\Http\Requests\Admin;

use App\Support\Locales;
use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    /**
     * Sahifa kontenti har bir til uchun alohida kiritiladi:
     * `title[uz]`, `content[uz][story][title]`, va hokazo.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $default = Locales::default();

        return [
            'title' => ['required', 'array'],
            'title.'.$default => ['required', 'string', 'max:255'],
            'title.*' => ['nullable', 'string', 'max:255'],

            'content' => ['required', 'array'],

            // Umumiy maydonlar (about, contacts)
            'content.*.description' => ['nullable', 'string', 'max:500'],
            'content.*.services_title' => ['nullable', 'string', 'max:255'],
            'content.*.form_title' => ['nullable', 'string', 'max:255'],
            'content.*.form_note' => ['nullable', 'string', 'max:1000'],

            // About: hikoya bloki
            'content.*.story.eyebrow' => ['nullable', 'string', 'max:255'],
            'content.*.story.title' => ['nullable', 'string', 'max:255'],
            'story_paragraphs.*' => ['nullable', 'string', 'max:10000'],
            'story_image_upload.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // About: missiya
            'content.*.mission.badge' => ['nullable', 'string', 'max:255'],
            'content.*.mission.text' => ['nullable', 'string', 'max:2000'],
            'content.*.mission.attribution' => ['nullable', 'string', 'max:255'],

            // About: qadriyatlar va raqamlar
            'content.*.values' => ['nullable', 'array', 'max:12'],
            'content.*.values.*.icon' => ['nullable', 'string', 'max:100'],
            'content.*.values.*.title' => ['nullable', 'string', 'max:255'],
            'content.*.values.*.text' => ['nullable', 'string', 'max:1000'],
            'content.*.stats' => ['nullable', 'array', 'max:8'],
            'content.*.stats.*.value' => ['nullable', 'string', 'max:100'],
            'content.*.stats.*.label' => ['nullable', 'string', 'max:255'],

            // Careers: yuqori blok
            'content.*.hero.badge' => ['nullable', 'string', 'max:255'],
            'content.*.hero.title' => ['nullable', 'string', 'max:255'],
            'content.*.hero.text' => ['nullable', 'string', 'max:2000'],
            'content.*.hero.badge_text' => ['nullable', 'string', 'max:100'],
            'hero_image_upload.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            // Careers: afzalliklar
            'content.*.perks.title' => ['nullable', 'string', 'max:255'],
            'content.*.perks.subtitle' => ['nullable', 'string', 'max:500'],
            'content.*.perks.items' => ['nullable', 'array', 'max:8'],
            'content.*.perks.items.*.icon' => ['nullable', 'string', 'max:100'],
            'content.*.perks.items.*.title' => ['nullable', 'string', 'max:255'],
            'content.*.perks.items.*.text' => ['nullable', 'string', 'max:1000'],

            // Careers: vakansiyalar
            'content.*.vacancies.title' => ['nullable', 'string', 'max:255'],
            'content.*.vacancies.subtitle' => ['nullable', 'string', 'max:500'],
            'content.*.vacancies.items' => ['nullable', 'array', 'max:30'],
            'content.*.vacancies.items.*.title' => ['nullable', 'string', 'max:255'],
            'content.*.vacancies.items.*.tag' => ['nullable', 'string', 'max:100'],
            'content.*.vacancies.items.*.text' => ['nullable', 'string', 'max:1000'],
            'content.*.vacancies.items.*.location' => ['nullable', 'string', 'max:255'],
            'content.*.vacancies.items.*.salary' => ['nullable', 'string', 'max:255'],
            'content.*.vacancies.items.*.schedule' => ['nullable', 'string', 'max:255'],

            // Careers: pastki murojaat bloki
            'content.*.cta.title' => ['nullable', 'string', 'max:255'],
            'content.*.cta.text' => ['nullable', 'string', 'max:1000'],
            'content.*.cta.phone_label' => ['nullable', 'string', 'max:50'],
            'content.*.cta.telegram_label' => ['nullable', 'string', 'max:100'],
            'content.*.cta.telegram_url' => ['nullable', 'url', 'max:500'],

            // Contacts: xizmatlar ro'yxati
            'services_text.*' => ['nullable', 'string', 'max:3000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.'.Locales::default().'.required' => 'Asosiy tildagi sahifa sarlavhasi kiritilishi shart.',
            'story_image_upload.*.mimes' => 'Rasm JPG, PNG yoki WEBP formatida bo‘lishi kerak.',
            'hero_image_upload.*.mimes' => 'Rasm JPG, PNG yoki WEBP formatida bo‘lishi kerak.',
        ];
    }
}
