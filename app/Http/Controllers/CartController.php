<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Savatcha brauzerdagi localStorage asosida ishlaydi; sahifa barcha
     * demo mahsulotlarni beradi va JavaScript kerakli qatorlarni ko'rsatadi.
     */
    public function index(): View
    {
        return view('cart.index', [
            'products' => DemoContent::products(),
        ]);
    }
}
