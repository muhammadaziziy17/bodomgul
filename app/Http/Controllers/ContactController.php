<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contacts.index', [
            'brand' => DemoContent::brand(),
            'content' => DemoContent::page('contacts')['content'] ?? [],
        ]);
    }

    /**
     * Demo forma: xabar validatsiyadan o'tadi, lekin hech qayerga
     * yuborilmaydi va saqlanmaydi — foydalanuvchiga bu haqda ochiq aytiladi.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'contact' => ['required', 'string', 'min:5', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'name.required' => t('form.name_required'),
            'name.min' => t('form.name_min'),
            'name.max' => t('form.name_max'),
            'contact.required' => t('form.contact_required'),
            'contact.min' => t('form.contact_min'),
            'contact.max' => t('form.contact_max'),
            'message.required' => t('form.message_required'),
            'message.min' => t('form.message_min'),
            'message.max' => t('form.message_max'),
        ]);

        return redirect()
            ->route('contacts')
            ->with('demo_notice', t('form.demo_notice', ['name' => $validated['name']]));
    }
}
