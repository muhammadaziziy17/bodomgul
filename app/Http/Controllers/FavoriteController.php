<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Sevimlilar brauzerdagi localStorage asosida ishlaydi; sahifa barcha
     * demo mahsulotlarni beradi va JavaScript tanlanganlarini ko'rsatadi.
     */
    public function index(): View
    {
        return view('favorites.index', [
            'products' => DemoContent::products(),
        ]);
    }
}
