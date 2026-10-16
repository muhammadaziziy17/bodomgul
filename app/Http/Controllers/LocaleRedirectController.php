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
     */
    public function fallback(Request $request): RedirectResponse
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            abort(404);
        }

        return redirect('/'.Locales::default().'/'.ltrim($request->path(), '/'));
    }
}
