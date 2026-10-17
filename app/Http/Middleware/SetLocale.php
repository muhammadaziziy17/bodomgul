<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * URL'dagi til parametrini aniqlaydi va butun so'rov uchun o'rnatadi.
 *
 * Middleware `web` guruhining boshida turadi, shu sabab model binding
 * (SubstituteBindings) lokalizatsiya qilingan sluglarni to'g'ri topadi.
 * Til parametri bo'lmagan so'rovlar (admin, health, redirect) uchun
 * hech narsa qilmaydi.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = (string) $request->route()?->getName();

        // Admin panel va boshqa til prefiksisiz yo'nalishlarga tegmaymiz.
        if (str_starts_with($routeName, 'admin.')) {
            return $next($request);
        }

        $locale = $request->route('locale');

        if ($locale === null) {
            return $next($request);
        }

        if (! is_string($locale) || ! Locales::exists($locale)) {
            // Til prefiksi noto'g'ri bo'lsa (masalan /catalog yoki /de/...),
            // GET so'rovlarni asosiy til prefiksiga yo'naltiramiz.
            // Aks holda qayta-qayta prefiks qo'shilib ketadigan tsikl paydo bo'ladi.
            if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
                return redirect('/'.Locales::default().'/'.ltrim($request->path(), '/'));
            }

            abort(404);
        }

        app()->setLocale($locale);
        Carbon::setLocale(carbon_locale($locale));
        URL::defaults(['locale' => $locale]);

        // Controller metodlariga faqat haqiqiy parametrlar uzatiladi.
        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
