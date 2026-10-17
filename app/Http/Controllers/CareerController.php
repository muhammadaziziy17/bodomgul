<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        $page = DemoContent::page('careers') ?? [];

        return view('careers.index', [
            'title' => $page['title'] ?? t('careers.title'),
            'content' => $page['content'] ?? [],
        ]);
    }
}
