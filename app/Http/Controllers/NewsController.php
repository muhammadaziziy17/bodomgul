<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Support\DemoContent;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        return view('news.index', [
            'news' => DemoContent::news(),
        ]);
    }

    public function show(News $news): View
    {
        abort_unless($news->is_published, 404);

        $item = DemoContent::newsToArray($news);

        return view('news.show', [
            'item' => $item,
            'relatedNews' => DemoContent::relatedNews($item),
        ]);
    }
}
