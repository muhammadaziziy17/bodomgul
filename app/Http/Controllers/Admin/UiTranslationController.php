<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminRequests;
use App\Http\Controllers\Controller;
use App\Models\UiTranslation;
use App\Support\Locales;
use App\Support\Ui;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UiTranslationController extends Controller
{
    use RespondsToAdminRequests;

    public function index(): View
    {
        $dictionary = Ui::dictionary();
        $groups = Ui::groups();

        $overrides = UiTranslation::query()
            ->get(['locale', 'key', 'value'])
            ->groupBy('locale')
            ->map(fn ($rows) => $rows->pluck('value', 'key')->all())
            ->all();

        $grouped = [];

        foreach ($dictionary as $key => $default) {
            $group = Ui::groupOf($key);
            $grouped[$group][$key] = $default;
        }

        return view('admin.ui.index', [
            'grouped' => $grouped,
            'groupLabels' => $groups,
            'overrides' => $overrides,
            'locales' => Locales::all(),
        ]);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'strings' => ['nullable', 'array'],
            'strings.*' => ['nullable', 'array'],
            'strings.*.*' => ['nullable', 'string', 'max:2000'],
        ]);

        $dictionary = Ui::dictionary();
        $strings = $validated['strings'] ?? [];

        foreach (Locales::all() as $locale) {
            foreach ($dictionary as $key => $default) {
                if (! array_key_exists($locale->code, $strings) || ! array_key_exists($key, $strings[$locale->code])) {
                    continue;
                }

                $value = trim((string) $strings[$locale->code][$key]);

                if ($value === '' || $value === $default) {
                    UiTranslation::query()
                        ->where('locale', $locale->code)
                        ->where('key', $key)
                        ->delete();

                    continue;
                }

                UiTranslation::query()->updateOrCreate(
                    ['locale' => $locale->code, 'key' => $key],
                    ['value' => $value]
                );
            }
        }

        Ui::flush();

        return $this->adminResponse($request, back(), 'Tarjimalar saqlandi.');
    }
}
