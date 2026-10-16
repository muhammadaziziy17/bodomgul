<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\News;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::query()->count(),
                'categories' => Category::query()->count(),
                'news' => News::query()->count(),
                'branches' => Branch::query()->count(),
                'heroSlides' => HeroSlide::query()->count(),
            ],
            'latestNews' => News::query()->orderByDesc('id')->limit(5)->get(),
            'hiddenProducts' => Product::query()->where('is_active', false)->count(),
        ]);
    }
}
