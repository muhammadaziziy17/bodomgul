<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroSlideRequest;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Support\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    use RespondsToAdminRequests;

    public function index(Request $request): View
    {
        $slides = HeroSlide::query()
            ->with('translations')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view($this->wantsFragment($request) ? 'admin.hero-slides._table' : 'admin.hero-slides.index', [
            'slides' => $slides,
        ]);
    }

    public function create(Request $request): View
    {
        $slide = new HeroSlide([
            'is_active' => true,
            'sort_order' => (HeroSlide::query()->max('sort_order') ?? 0) + 1,
        ]);

        return view($this->wantsPanel($request) ? 'admin.hero-slides._form' : 'admin.hero-slides.form', [
            'slide' => $slide,
            'categories' => Category::query()->ordered()->get(),
        ]);
    }

    public function store(HeroSlideRequest $request): RedirectResponse|JsonResponse
    {
        $slide = new HeroSlide;
        $slide->fill($this->baseData($request));
        $slide->image = ImageUploader::store($request->file('image_upload'), 'hero');
        $slide->save();

        $slide->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.hero-slides.index'), 'Slayd qo‘shildi.');
    }

    public function edit(Request $request, HeroSlide $heroSlide): View
    {
        $heroSlide->load('translations');

        return view($this->wantsPanel($request) ? 'admin.hero-slides._form' : 'admin.hero-slides.form', [
            'slide' => $heroSlide,
            'categories' => Category::query()->ordered()->get(),
        ]);
    }

    public function update(HeroSlideRequest $request, HeroSlide $heroSlide): RedirectResponse|JsonResponse
    {
        $heroSlide->fill($this->baseData($request));

        if ($file = $request->file('image_upload')) {
            $heroSlide->image = ImageUploader::store($file, 'hero', $heroSlide->image);
        }

        $heroSlide->save();
        $heroSlide->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.hero-slides.index'), 'Slayd yangilandi.');
    }

    public function toggle(HeroSlide $heroSlide): JsonResponse
    {
        $heroSlide->update(['is_active' => ! $heroSlide->is_active]);

        return response()->json([
            'ok' => true,
            'message' => $heroSlide->is_active ? 'Saytda ko‘rsatiladi.' : 'Yashirildi.',
            'value' => $heroSlide->is_active,
        ]);
    }

    public function destroy(Request $request, HeroSlide $heroSlide): RedirectResponse|JsonResponse
    {
        ImageUploader::delete($heroSlide->image);
        $heroSlide->delete();

        return $this->adminResponse($request, redirect()->route('admin.hero-slides.index'), 'Slayd o‘chirildi.');
    }

    /**
     * @return array<string, mixed>
     */
    private function baseData(HeroSlideRequest $request): array
    {
        $data = $request->safe()->except(['image_upload', 'translations', 'primary_category']);

        $data['primary_params'] = $request->input('primary_route') === 'catalog.category' && $request->filled('primary_category')
            ? ['category' => $request->string('primary_category')->toString()]
            : null;

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
