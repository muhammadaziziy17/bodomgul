<?php

use App\Models\Category;
use App\Support\DemoContent;

it('renders a category page with only that category’s products', function () {
    $category = DemoContent::category('tortlar');
    $ownProducts = DemoContent::productsByCategory('tortlar');
    $foreignProduct = DemoContent::productsByCategory('ichimliklar')[0];

    $response = $this->get(route('catalog.category', ['category' => $category['slug']]));

    $response->assertOk()->assertSee($category['name']);

    foreach ($ownProducts as $product) {
        $response->assertSee($product['name']);
    }

    $response->assertDontSee($foreignProduct['name']);
});

it('renders a product page with price, composition and related products', function () {
    $product = DemoContent::product('bodomli-tort');
    $related = DemoContent::relatedProducts($product)[0];

    $this->get(route('products.show', ['product' => $product['slug']]))
        ->assertOk()
        ->assertSee($product['name'])
        ->assertSee($product['composition'])
        ->assertSee(format_price($product['price']))
        ->assertSee($related['name']);
});

it('returns 404 for an unknown category slug', function () {
    $this->get(route('catalog.category', ['category' => 'mavjud-emas']))->assertNotFound();
});

it('returns 404 for an unknown product slug', function () {
    $this->get(route('products.show', ['product' => 'mavjud-emas']))->assertNotFound();
});

it('never includes the product itself in related products', function () {
    $product = DemoContent::product('bodomli-tort');

    $relatedSlugs = array_column(DemoContent::relatedProducts($product), 'slug');

    expect($relatedSlugs)->not->toContain($product['slug'])
        ->and($relatedSlugs)->toHaveCount(4)
        ->and($relatedSlugs)->toEqual(array_unique($relatedSlugs));
});

it('hides products of an inactive category from the whole public site', function () {
    $category = Category::query()->whereSlug('tortlar', 'uz')->first();
    $product = DemoContent::product('bodomli-tort');

    $category->update(['is_active' => false]);

    $this->get(route('catalog.index'))
        ->assertOk()
        ->assertDontSee($product['slug'], false);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee($product['slug'], false);

    $this->get(route('products.show', ['product' => $product['slug']]))
        ->assertNotFound();

    // Kategoriya qayta faollashtirilsa, mahsulot yana ko'rinadi.
    $category->update(['is_active' => true]);

    $this->get(route('catalog.index'))
        ->assertOk()
        ->assertSee($product['slug'], false);

    $this->get(route('products.show', ['product' => $product['slug']]))->assertOk();
});
