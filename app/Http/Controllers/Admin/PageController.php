<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Support\ImageUploader;
use App\Support\Locales;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    use RespondsToAdminRequests;

    public function edit(Page $page): View
    {
        $page->load('translations');

        return view('admin.pages.edit', [
            'page' => $page,
            'content' => $page->contentFor(Locales::default()),
            'localizedContents' => $this->localizedContents($page),
            'titles' => $this->localizedTitles($page),
        ]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse|JsonResponse
    {
        $data = $request->validated();
        $titles = $data['title'] ?? [];
        $contents = $data['content'] ?? [];
        $storyParagraphs = $request->input('story_paragraphs', []);
        $servicesText = $request->input('services_text', []);

        foreach (Locales::all() as $locale) {
            $code = $locale->code;

            $content = $this->buildContent(
                $page,
                $code,
                $request,
                $contents[$code] ?? [],
                (string) ($storyParagraphs[$code] ?? ''),
                (string) ($servicesText[$code] ?? ''),
            );

            $page->saveTranslations([
                $code => [
                    'title' => $titles[$code] ?? null,
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
                ],
            ]);
        }

        return $this->adminResponse($request, back(), 'Sahifa saqlandi.');
    }

    /**
     * Har bir til uchun kontent: [locale => content array].
     *
     * @return array<string, array<string, mixed>>
     */
    private function localizedContents(Page $page): array
    {
        $result = [];

        foreach (Locales::all() as $locale) {
            $result[$locale->code] = $page->contentFor($locale->code);
        }

        return $result;
    }

    /**
     * @return array<string, string>
     */
    private function localizedTitles(Page $page): array
    {
        $result = [];

        foreach (Locales::all() as $locale) {
            $result[$locale->code] = (string) ($page->translationFor($locale->code, 'title') ?? '');
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function buildContent(
        Page $page,
        string $locale,
        PageRequest $request,
        array $input,
        string $paragraphs,
        string $services,
    ): array {
        $existing = $page->contentFor($locale);

        return match ($page->slug) {
            Page::ABOUT => [
                'description' => $input['description'] ?? '',
                'story' => [
                    'eyebrow' => $input['story']['eyebrow'] ?? '',
                    'title' => $input['story']['title'] ?? '',
                    'image' => $this->resolveImage(
                        $request,
                        'story_image_upload',
                        $locale,
                        $existing['story']['image'] ?? 'images/brand/about.svg',
                    ),
                    'paragraphs' => $this->paragraphs($paragraphs),
                ],
                'mission' => $input['mission'] ?? [],
                'values' => $this->cleanRows($input['values'] ?? []),
                'stats' => $this->cleanRows($input['stats'] ?? []),
            ],
            Page::CAREERS => [
                'hero' => array_merge($input['hero'] ?? [], [
                    'image' => $this->resolveImage(
                        $request,
                        'hero_image_upload',
                        $locale,
                        $existing['hero']['image'] ?? 'images/brand/team.svg',
                    ),
                ]),
                'perks' => [
                    'title' => $input['perks']['title'] ?? '',
                    'subtitle' => $input['perks']['subtitle'] ?? '',
                    'items' => $this->cleanRows($input['perks']['items'] ?? []),
                ],
                'vacancies' => [
                    'title' => $input['vacancies']['title'] ?? '',
                    'subtitle' => $input['vacancies']['subtitle'] ?? '',
                    'items' => $this->cleanRows($input['vacancies']['items'] ?? []),
                ],
                'cta' => $input['cta'] ?? [],
            ],
            Page::CONTACTS => [
                'description' => $input['description'] ?? '',
                'services_title' => $input['services_title'] ?? '',
                'services' => $this->lines($services),
                'form_title' => $input['form_title'] ?? '',
                'form_note' => $input['form_note'] ?? '',
            ],
            default => $existing,
        };
    }

    private function resolveImage(
        PageRequest $request,
        string $field,
        string $locale,
        ?string $current,
        string $folder = 'pages',
    ): string {
        $file = $request->file($field.'.'.$locale);

        if ($file !== null) {
            return ImageUploader::store($file, $folder, $current);
        }

        return $current ?? 'images/brand/about.svg';
    }

    /**
     * Bo'sh qator bilan ajratilgan matnni paragraflarga bo'ladi.
     *
     * @return array<int, string>
     */
    private function paragraphs(string $text): array
    {
        return collect(preg_split('/\R{2,}/u', $text))
            ->map(fn (string $paragraph): string => trim($paragraph))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Har qatori alohida element bo'lgan matn.
     *
     * @return array<int, string>
     */
    private function lines(string $text): array
    {
        return collect(preg_split('/\R/u', $text))
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Bo'sh qatorlarni olib tashlaydi (dinamik qo'shilgan bo'sh bloklar).
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array<string, string>>
     */
    private function cleanRows(array $rows): array
    {
        $cleaned = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $row = array_map(fn ($value): string => trim((string) $value), $row);

            if (implode('', $row) !== '') {
                $cleaned[] = $row;
            }
        }

        return $cleaned;
    }
}
