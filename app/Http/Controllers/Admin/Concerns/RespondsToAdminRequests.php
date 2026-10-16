<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Admin paneldagi AJAX so'rovlar uchun umumiy javoblar.
 *
 * JS yuborgan so'rov (`Accept: application/json`) uchun JSON,
 * oddiy brauzer so'rovi uchun esa redirect qaytaradi.
 */
trait RespondsToAdminRequests
{
    /**
     * Jadval fragmenti so'ralganmi (`?fragment=1`).
     */
    protected function wantsFragment(Request $request): bool
    {
        return $request->boolean('fragment');
    }

    /**
     * Forma paneli (modal) so'ralganmi (`?panel=1`).
     */
    protected function wantsPanel(Request $request): bool
    {
        return $request->boolean('panel');
    }

    /**
     * Saqlash javobi: AJAX uchun JSON, aks holda redirect.
     *
     * @param  array<string, mixed>  $extra
     */
    protected function adminResponse(
        Request $request,
        RedirectResponse $fallback,
        string $message = 'Saqlandi.',
        array $extra = [],
    ): RedirectResponse|JsonResponse {
        if ($request->expectsJson()) {
            return response()->json(array_merge([
                'ok' => true,
                'message' => $message,
            ], $extra));
        }

        return $fallback;
    }
}
