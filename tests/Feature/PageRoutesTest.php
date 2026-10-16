<?php

dataset('static pages', [
    'home' => ['home', 'Sevimli mahsulotlar'],
    'catalog' => ['catalog.index', 'Barchasi'],
    'branches' => ['branches.index', 'Filiallar'],
    'about' => ['about', 'Nimaga ishonamiz?'],
    'contacts' => ['contacts', 'Xabar yuborish (demo)'],
    'news' => ['news.index', 'Yangiliklar va aksiyalar'],
    'cart' => ['cart.index', 'Savatcha'],
    'favorites' => ['favorites.index', 'Sevimlilar'],
]);

it('renders each static page', function (string $route, string $marker) {
    $this->get(route($route))
        ->assertOk()
        ->assertSee($marker);
})->with('static pages');

dataset('localized pages', [
    'en home' => ['/en', 'Favourite products'],
    'ru home' => ['/ru', 'Любимые продукты'],
    'uz home' => ['/uz', 'Sevimli mahsulotlar'],
    'en category' => ['/en/catalog/cakes', 'Almond Cake'],
    'ru category' => ['/ru/catalog/torty', 'Миндальный торт'],
    'en product' => ['/en/products/almond-cake', 'Almond Cake'],
    'ru product' => ['/ru/products/mindalnyj-tort', 'Миндальный торт'],
    'en news article' => ['/en/news/autumn-flavour-collection', 'Autumn Flavour'],
    'ru about' => ['/ru/about', 'Наша история'],
    'ru careers' => ['/ru/careers', 'Профессиональный рост'],
    'en contacts' => ['/en/contacts', 'Catering'],
    'ru branches' => ['/ru/branches', 'Чиланзар'],
]);

it('serves localized content in every active locale', function (string $path, string $marker) {
    $this->get($path)
        ->assertOk()
        ->assertSee($marker);
})->with('localized pages');

it('renders the home page with hero content and featured products', function () {
    $slide = config('bodomgul.hero_slides.0');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($slide['title'])
        ->assertSee($slide['primary_label'])
        ->assertSee('Bodomgul');
});

it('links every navigation entry to an existing named route', function () {
    foreach (config('bodomgul.navigation') as $item) {
        $this->get(route($item['route']))->assertOk();
    }
});

it('renders the brand logo and animation stylesheet in the layout', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('images/brand/logo-navbar.png')
        ->assertSee('favicon.png')
        ->assertSee('css/animations.css');
});
