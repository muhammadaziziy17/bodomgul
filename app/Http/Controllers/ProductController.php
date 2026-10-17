<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\DemoContent;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        abort_unless($product->is_active && $product->category?->is_active, 404);

        $productData = DemoContent::productToArray($product);

        return view('products.show', [
            'product' => $productData,
            'relatedProducts' => DemoContent::relatedProducts($productData),
            'category' => DemoContent::category($productData['category']),
        ]);
    }
}
