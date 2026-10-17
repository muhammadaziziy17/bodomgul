<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $page = DemoContent::page('about') ?? [];

        return view('about.index', [
            'title' => $page['title'] ?? t('about.title'),
            'content' => $page['content'] ?? [],
        ]);
    }
}
