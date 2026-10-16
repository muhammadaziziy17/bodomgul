<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LocaleRequest;
use App\Models\Locale;
use App\Support\Locales;
use App\Support\Ui;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocaleController extends Controller
{
    use RespondsToAdminRequests;

    public function index(Request $request): View
    {
        $locales = Locale::query()->ordered()->get();

        return view($this->wantsFragment($request) ? 'admin.locales._table' : 'admin.locales.index', [
            'locales' => $locales,
        ]);
    }

    public function create(Request $request): View
    {
        return view($this->wantsPanel($request) ? 'admin.locales._form' : 'admin.locales.form', [
            'locale' => new Locale([
                'is_active' => true,
                'sort_order' => (Locale::query()->max('sort_order') ?? 0) + 1,
            ]),
        ]);
    }

    public function store(LocaleRequest $request): RedirectResponse|JsonResponse
    {
        $locale = new Locale;
        $locale->fill($request->validated());
        $locale->is_default = false;
        $locale->save();

        Locales::flush();

        return $this->adminResponse($request, redirect()->route('admin.locales.index'), 'Til qo‘shildi.');
    }

    public function edit(Request $request, Locale $locale): View
    {
        return view($this->wantsPanel($request) ? 'admin.locales._form' : 'admin.locales.form', [
            'locale' => $locale,
        ]);
    }

    public function update(LocaleRequest $request, Locale $locale): RedirectResponse|JsonResponse
    {
        $locale->fill($request->validated())->save();

        Locales::flush();

        return $this->adminResponse($request, redirect()->route('admin.locales.index'), 'Til yangilandi.');
    }

    /**
     * Tilni asosiy til qilib belgilash.
     */
    public function makeDefault(Locale $locale): JsonResponse
    {
        if (! $locale->is_active) {
            return response()->json(['ok' => false, 'message' => 'Avval tilni faollashtiring.'], 422);
        }

        Locale::query()->where('is_default', true)->update(['is_default' => false]);
        $locale->update(['is_default' => true]);

        Locales::flush();
        Ui::flush();

        return response()->json([
            'ok' => true,
            'message' => '«'.$locale->name.'» asosiy til qilib belgilandi.',
        ]);
    }

    public function toggle(Locale $locale): JsonResponse
    {
        if ($locale->is_default) {
            return response()->json(['ok' => false, 'message' => 'Asosiy tilni yashirib bo‘lmaydi.'], 422);
        }

        $locale->update(['is_active' => ! $locale->is_active]);

        Locales::flush();

        return response()->json([
            'ok' => true,
            'message' => $locale->is_active ? 'Til yoqildi.' : 'Til o‘chirildi.',
            'value' => $locale->is_active,
        ]);
    }

    public function destroy(Request $request, Locale $locale): RedirectResponse|JsonResponse
    {
        if ($locale->is_default) {
            $message = 'Asosiy tilni o‘chirib bo‘lmaydi.';

            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $locale->delete();

        Locales::flush();
        Ui::flush();

        return $this->adminResponse($request, redirect()->route('admin.locales.index'), 'Til o‘chirildi.');
    }
}
