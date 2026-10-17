<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Til prefiksisiz manzillarni asosiy tilga yo'naltiradi.
 */
class LocaleRedirectController extends Controller
{
    public function root(): RedirectResponse
    {
        return redirect('/'.Locales::default());
    }

    /**
     * Noma'lum GET manzillar: /catalog -> /uz/catalog.
     *
     * Manzil allaqachon til prefiksi bilan boshlansa (masalan /uz/yoq-sahifa),
     * qayta yo'naltirish tsikl hosil qiladi — shuning uchun 404 qaytaramiz.
     */
    public function fallback(Request $request): RedirectResponse
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            abort(404);
        }

        $path = ltrim($request->path(), '/');
        $firstSegment = explode('/', $path, 2)[0];

        if (Locales::exists($firstSegment)) {
            app()->setLocale($firstSegment);

            abort(404);
        }

        return redirect('/'.Locales::default().'/'.$path);
    }
}
