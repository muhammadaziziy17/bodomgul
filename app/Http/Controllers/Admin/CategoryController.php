<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Support\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use RespondsToAdminRequests;

    public function index(Request $request): View
    {
        $categories = Category::query()
            ->with('translations')
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view($this->wantsFragment($request) ? 'admin.categories._table' : 'admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(Request $request): View
    {
        return view($this->wantsPanel($request) ? 'admin.categories._form' : 'admin.categories.form', [
            'category' => new Category([
                'is_active' => true,
                'sort_order' => (Category::query()->max('sort_order') ?? 0) + 1,
            ]),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse|JsonResponse
    {
        $category = new Category;
        $category->fill($this->baseData($request));
        $category->image = ImageUploader::store($request->file('image_upload'), 'categories');
        $category->save();

        $category->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.categories.index'), 'Kategoriya qo‘shildi.');
    }

    public function edit(Request $request, Category $category): View
    {
        $category->load('translations');

        return view($this->wantsPanel($request) ? 'admin.categories._form' : 'admin.categories.form', [
            'category' => $category,
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse|JsonResponse
    {
        $category->fill($this->baseData($request));

        if ($file = $request->file('image_upload')) {
            $category->image = ImageUploader::store($file, 'categories', $category->image);
        }

        $category->save();
        $category->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.categories.index'), 'Kategoriya yangilandi.');
    }

    public function toggle(Category $category): JsonResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        return response()->json([
            'ok' => true,
            'message' => $category->is_active ? 'Saytda ko‘rsatiladi.' : 'Yashirildi.',
            'value' => $category->is_active,
        ]);
    }

    public function destroy(Request $request, Category $category): RedirectResponse|JsonResponse
    {
        if ($category->products()->exists()) {
            $message = 'Bu kategoriyada mahsulotlar bor. Avval ularni boshqa kategoriyaga o‘tkazing.';

            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        ImageUploader::delete($category->image);
        $category->delete();

        return $this->adminResponse($request, redirect()->route('admin.categories.index'), 'Kategoriya o‘chirildi.');
    }

    /**
     * @return array<string, mixed>
     */
    private function baseData(CategoryRequest $request): array
    {
        $data = $request->safe()->except(['image_upload', 'translations']);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
