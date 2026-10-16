<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Category;
use App\Models\Setting;
use App\Support\ImageUploader;
use App\Support\Locales;
use App\Support\SiteSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    use RespondsToAdminRequests;

    public function edit(): View
    {
        $setting = SiteSettings::model();

        $overlays = [];

        if ($setting !== null) {
            $setting->load('translations');

            foreach ($setting->translations as $translation) {
                $overlays[$translation->locale][$translation->field] = $translation->value;
            }
        }

        return view('admin.settings.edit', [
            'settings' => SiteSettings::all(),
            'categories' => Category::query()->ordered()->get(),
            'overlays' => $overlays,
            'translatableFields' => SiteSettings::TRANSLATABLE_FIELDS,
        ]);
    }

    public function update(SettingRequest $request): RedirectResponse|JsonResponse
    {
        $data = $request->validated();

        $setting = Setting::query()->firstOrCreate(['key' => 'site'], ['value' => []]);
        $base = $setting->value ?? [];

        // Asosiy til qiymatlari (brand, promo, social, app).
        $base['brand'] = array_merge($base['brand'] ?? [], $data['brand'] ?? []);

        $promo = array_merge($base['promo'] ?? [], $data['promo'] ?? []);
        $currentImage = $base['promo']['image'] ?? config('bodomgul.promo.image');
        $promo['image'] = $request->file('promo_image_upload')
            ? ImageUploader::store($request->file('promo_image_upload'), 'promo', $currentImage)
            : $currentImage;
        $promo['cta_params'] = ($promo['cta_route'] ?? null) === 'catalog.category' && ! empty($promo['cta_category'])
            ? ['category' => $promo['cta_category']]
            : null;
        unset($promo['cta_category']);

        $base['promo'] = $promo;
        $base['social'] = array_merge($base['social'] ?? [], $data['social'] ?? []);
        $base['app'] = array_merge($base['app'] ?? [], $data['app'] ?? []);
        $base['app']['enabled'] = filter_var($base['app']['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN);

        $setting->update(['value' => $base]);

        // Boshqa tillar uchun tarjimalar (overlay).
        $overlays = $request->input('overlay', []);

        foreach (Locales::all() as $locale) {
            if ($locale->code === Locales::default()) {
                continue;
            }

            $values = [];

            foreach (SiteSettings::TRANSLATABLE_FIELDS as $field) {
                $values[$field] = $overlays[$locale->code][$field] ?? null;
            }

            $setting->saveTranslations([$locale->code => $values]);
        }

        SiteSettings::flush();

        return $this->adminResponse($request, back(), 'Sozlamalar saqlandi.');
    }
}
