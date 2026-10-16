<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('about.index', [
            'content' => DemoContent::page('about')['content'] ?? [],
        ]);
    }
}
