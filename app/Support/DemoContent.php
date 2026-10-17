<?php

namespace App\Support;

use App\Models\Branch;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\News;
use App\Models\Page;
use App\Models\Product;

/**
 * Ommaviy sahifalar uchun kontent repozitoriysi.
 *
 * Ma'lumotlar bazadan o'qiladi (joriy tilda) va sahifalarda qulay
 * bo'lgan massiv ko'rinishiga keltiriladi.
 */
class DemoContent
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function categories(): array
    {
        return Category::query()
            ->ordered()
            ->with('translations')
            ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
            ->get()
            ->map(fn (Category $category): array => self::categoryToArray($category))
            ->filter(fn (array $category): bool => $category['slug'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function category(string $slug): ?array
    {
        return collect(self::categories())->firstWhere('slug', $slug);
    }

    public static function categoryName(string $slug): string
    {
        return collect(self::categories())->firstWhere('slug', $slug)['name'] ?? $slug;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function products(): array
    {
        return Product::query()
            ->active()
            ->inActiveCategory()
            ->listing()
            ->with(['category.translations', 'translations'])
            ->get()
            ->map(fn (Product $product): array => self::productToArray($product))
            ->filter(fn (array $product): bool => $product['slug'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function product(string $slug): ?array
    {
        $product = Product::query()
            ->active()
            ->inActiveCategory()
            ->with(['category.translations', 'translations'])
            ->get()
            ->first(fn (Product $product): bool => $product->tr('slug') === $slug
                || $product->tr('slug', Locales::default()) === $slug);

        return $product ? self::productToArray($product) : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function featuredProducts(int $limit = 8): array
    {
        return Product::query()
            ->active()
            ->inActiveCategory()
            ->listing()
            ->where('is_featured', true)
            ->with(['category.translations', 'translations'])
            ->limit($limit)
            ->get()
            ->map(fn (Product $product): array => self::productToArray($product))
            ->filter(fn (array $product): bool => $product['slug'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function productsByCategory(string $slug): array
    {
        return array_values(array_filter(
            self::products(),
            fn (array $product): bool => $product['category'] === $slug
        ));
    }

    /**
     * O'xshash mahsulotlar: avval shu kategoriyadan, yetarli bo'lmasa boshqalardan.
     *
     * @param  array<string, mixed>  $product
     * @return array<int, array<string, mixed>>
     */
    public static function relatedProducts(array $product, int $limit = 4): array
    {
        $all = collect(self::products())->keyBy('slug');
        $others = $all->except($product['slug']);

        return $others
            ->where('category', $product['category'])
            ->concat($others->whereNotIn('category', [$product['category']]))
            ->take($limit)
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function branches(): array
    {
        return Branch::query()
            ->ordered()
            ->with('translations')
            ->get()
            ->map(fn (Branch $branch): array => [
                'id' => $branch->id,
                'name' => $branch->name,
                'district' => $branch->district,
                'address' => $branch->address,
                'hours' => $branch->hours,
                'phone' => $branch->phone,
                'map_url' => $branch->map_url,
                'search' => $branch->search,
            ])
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function news(): array
    {
        return News::query()
            ->published()
            ->with('translations')
            ->get()
            ->map(fn (News $item): array => self::newsToArray($item))
            ->filter(fn (array $item): bool => $item['slug'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function newsItem(string $slug): ?array
    {
        $item = News::query()
            ->published()
            ->with('translations')
            ->get()
            ->first(fn (News $item): bool => $item->tr('slug') === $slug
                || $item->tr('slug', Locales::default()) === $slug);

        return $item ? self::newsToArray($item) : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function latestNews(int $limit = 3): array
    {
        return array_slice(self::news(), 0, $limit);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function relatedNews(array $item, int $limit = 3): array
    {
        return collect(self::news())
            ->where('slug', '!==', $item['slug'])
            ->take($limit)
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function heroSlides(): array
    {
        return HeroSlide::query()
            ->ordered()
            ->with('translations')
            ->get()
            ->map(fn (HeroSlide $slide): array => [
                'image' => $slide->image,
                'alt' => $slide->alt,
                'eyebrow' => $slide->eyebrow,
                'title' => $slide->title,
                'text' => $slide->text,
                'primary_label' => $slide->primary_label,
                'primary_route' => $slide->primary_route,
                'primary_params' => $slide->primary_params ?? [],
                'secondary_label' => $slide->secondary_label,
                'secondary_route' => $slide->secondary_route,
            ])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public static function promo(): array
    {
        return SiteSettings::promo();
    }

    /**
     * @return array<string, mixed>
     */
    public static function brand(): array
    {
        return SiteSettings::brand();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function page(string $slug): ?array
    {
        $page = Page::query()
            ->with('translations')
            ->where('slug', $slug)
            ->first();

        return $page ? [
            'slug' => $page->slug,
            'title' => $page->title,
            'content' => $page->contentFor(),
        ] : null;
    }

    /**
     * @return array<string, mixed>
     */
    public static function productToArray(Product $product): array
    {
        $product->loadMissing(['category.translations', 'translations']);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'category' => $product->category->slug,
            'category_name' => $product->category->name,
            'price' => $product->price,
            'weight' => $product->weight,
            'image' => $product->image,
            'featured' => $product->is_featured,
            'description' => $product->description,
            'composition' => $product->composition,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function newsToArray(News $item): array
    {
        $item->loadMissing('translations');

        return [
            'slug' => $item->slug,
            'title' => $item->title,
            'tag' => $item->tag,
            'image' => $item->image,
            'excerpt' => $item->excerpt,
            'date' => $item->published_at?->toDateString(),
            'date_label' => $item->date_label,
            'body' => $item->paragraphs(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function categoryToArray(Category $category): array
    {
        return [
            'id' => $category->id,
            'slug' => $category->slug,
            'name' => $category->name,
            'image' => $category->image,
            'description' => $category->description,
            'products_count' => $category->products_count ?? 0,
        ];
    }
}
