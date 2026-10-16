<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Support\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    use RespondsToAdminRequests;

    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category.translations', 'translations'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view($this->wantsFragment($request) ? 'admin.products._table' : 'admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create(Request $request): View
    {
        return view($this->wantsPanel($request) ? 'admin.products._form' : 'admin.products.form', [
            'product' => new Product([
                'is_active' => true,
                'sort_order' => (Product::query()->max('sort_order') ?? 0) + 1,
            ]),
            'categories' => Category::query()->ordered()->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse|JsonResponse
    {
        $product = new Product;
        $product->fill($this->baseData($request));
        $product->image = ImageUploader::store($request->file('image_upload'), 'products');
        $product->save();

        $product->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.products.index'), 'Mahsulot qo‘shildi.');
    }

    public function edit(Request $request, Product $product): View
    {
        $product->load('translations');

        return view($this->wantsPanel($request) ? 'admin.products._form' : 'admin.products.form', [
            'product' => $product,
            'categories' => Category::query()->ordered()->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse|JsonResponse
    {
        $product->fill($this->baseData($request));

        if ($file = $request->file('image_upload')) {
            $product->image = ImageUploader::store($file, 'products', $product->image);
        }

        $product->save();
        $product->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.products.index'), 'Mahsulot yangilandi.');
    }

    public function toggle(Product $product): JsonResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        return response()->json([
            'ok' => true,
            'message' => $product->is_active ? 'Saytda ko‘rsatiladi.' : 'Yashirildi.',
            'value' => $product->is_active,
        ]);
    }

    public function destroy(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        ImageUploader::delete($product->image);
        $product->delete();

        return $this->adminResponse($request, redirect()->route('admin.products.index'), 'Mahsulot o‘chirildi.');
    }

    /**
     * @return array<string, mixed>
     */
    private function baseData(ProductRequest $request): array
    {
        $data = $request->safe()->except(['image_upload', 'translations']);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
