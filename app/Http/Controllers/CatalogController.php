<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Support\DemoContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        return view('catalog.index', [
            'categories' => DemoContent::categories(),
            'products' => DemoContent::products(),
            'category' => null,
            'search' => $request->string('q')->trim()->toString(),
            'title' => t('catalog.title'),
            'description' => t('catalog.description'),
        ]);
    }

    public function category(Category $category): View
    {
        abort_unless($category->is_active, 404);

        return view('catalog.category', [
            'categories' => DemoContent::categories(),
            'products' => DemoContent::productsByCategory($category->slug),
            'category' => DemoContent::category($category->slug),
            'search' => '',
            'title' => $category->name,
            'description' => (string) $category->description,
        ]);
    }
}
