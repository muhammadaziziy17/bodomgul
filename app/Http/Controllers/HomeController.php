<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home.index', [
            'heroSlides' => DemoContent::heroSlides(),
            'categories' => DemoContent::categories(),
            'featuredProducts' => DemoContent::featuredProducts(8),
            'promo' => DemoContent::promo(),
            'news' => DemoContent::latestNews(3),
            'branches' => DemoContent::branches(),
            'brand' => DemoContent::brand(),
        ]);
    }
}
