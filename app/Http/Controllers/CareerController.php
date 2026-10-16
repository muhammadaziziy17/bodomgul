<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        return view('careers.index', [
            'content' => DemoContent::page('careers')['content'] ?? [],
        ]);
    }
}
