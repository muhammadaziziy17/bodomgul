<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsToAdminRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use App\Support\ImageUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    use RespondsToAdminRequests;

    public function index(Request $request): View
    {
        $news = News::query()
            ->with('translations')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        return view($this->wantsFragment($request) ? 'admin.news._table' : 'admin.news.index', [
            'news' => $news,
        ]);
    }

    public function create(Request $request): View
    {
        return view($this->wantsPanel($request) ? 'admin.news._form' : 'admin.news.form', [
            'item' => new News([
                'is_published' => true,
                'published_at' => now()->toDateString(),
            ]),
        ]);
    }

    public function store(NewsRequest $request): RedirectResponse|JsonResponse
    {
        $news = new News;
        $news->fill($this->baseData($request));
        $news->image = ImageUploader::store($request->file('image_upload'), 'news');
        $news->save();

        $news->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.news.index'), 'Yangilik qo‘shildi.');
    }

    public function edit(Request $request, News $news): View
    {
        $news->load('translations');

        return view($this->wantsPanel($request) ? 'admin.news._form' : 'admin.news.form', [
            'item' => $news,
        ]);
    }

    public function update(NewsRequest $request, News $news): RedirectResponse|JsonResponse
    {
        $news->fill($this->baseData($request));

        if ($file = $request->file('image_upload')) {
            $news->image = ImageUploader::store($file, 'news', $news->image);
        }

        $news->save();
        $news->saveTranslations($request->validated()['translations'] ?? []);

        return $this->adminResponse($request, redirect()->route('admin.news.index'), 'Yangilik yangilandi.');
    }

    public function toggle(News $news): JsonResponse
    {
        $news->update(['is_published' => ! $news->is_published]);

        return response()->json([
            'ok' => true,
            'message' => $news->is_published ? 'Chop etildi.' : 'Qoralamaga o‘tkazildi.',
            'value' => $news->is_published,
        ]);
    }

    public function destroy(Request $request, News $news): RedirectResponse|JsonResponse
    {
        ImageUploader::delete($news->image);
        $news->delete();

        return $this->adminResponse($request, redirect()->route('admin.news.index'), 'Yangilik o‘chirildi.');
    }

    /**
     * @return array<string, mixed>
     */
    private function baseData(NewsRequest $request): array
    {
        $data = $request->safe()->except(['image_upload', 'translations']);

        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
