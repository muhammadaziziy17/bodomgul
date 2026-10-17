<?php

use App\Models\UiTranslation;
use App\Support\Ui;

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

/*
|--------------------------------------------------------------------------
| Til prefiksisiz va noma'lum manzillar
|--------------------------------------------------------------------------
*/

it('redirects unknown paths without a locale prefix to the default locale', function () {
    $this->get('/not-found')->assertRedirect('/uz/not-found');
});

it('redirects locale-less paths of existing pages to the default locale', function () {
    $this->get('/catalog')->assertRedirect('/uz/catalog');
});

it('returns a 404 page instead of redirecting unknown paths inside a locale prefix', function () {
    $this->get('/uz/not-found')->assertNotFound();
    $this->get('/uz/uz/not-found')->assertNotFound();
});

it('renders the 404 page in the requested locale', function () {
    $this->get('/en/not-found')
        ->assertNotFound()
        ->assertSee('Sorry, this page was not found');
});

/*
|--------------------------------------------------------------------------
| Interfeys matnlari va demo kontent
|--------------------------------------------------------------------------
*/

it('renders the cart summary hooks expected by the cart script', function () {
    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee('data-cart-count-text', false)
        ->assertSee('data-cart-subtotal', false);
});

it('escapes ampersands in interface text exactly once', function () {
    UiTranslation::query()->updateOrCreate(
        ['locale' => 'uz', 'key' => 'branches.description'],
        ['value' => 'Bodomgul & Bodomgul filiallari'],
    );

    Ui::flush();

    $this->get('/uz/branches')
        ->assertOk()
        ->assertSee('Bodomgul &amp; Bodomgul filiallari', false)
        ->assertDontSee('&amp;amp;', false);
});

it('does not leak the reference brand into the demo content', function (string $path) {
    $response = $this->get($path)->assertOk();

    foreach (['Safia & Bodomgul', 'Safia va Bodomgul', 'Safia oilasining', 'safia_hr_bot'] as $needle) {
        $response->assertDontSee($needle);
    }
})->with([
    '/uz',
    '/uz/about',
    '/uz/careers',
    '/uz/contacts',
    '/uz/branches',
    '/en/about',
    '/en/careers',
    '/en/contacts',
    '/en/branches',
    '/ru/about',
    '/ru/careers',
    '/ru/contacts',
]);

it('shows real branch phones instead of placeholder numbers', function () {
    $this->get('/uz/branches')
        ->assertOk()
        ->assertSee('+998 78 113 40 40')
        ->assertDontSee('+998 00 000 00');
});

it('renders the footer app text in the current locale', function () {
    $this->get('/en')->assertOk()->assertSee('Easy to connect');
    $this->get('/ru')->assertOk()->assertSee('Подключиться просто');
});
