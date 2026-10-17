<?php

use App\Models\Branch;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Locale;
use App\Models\News;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use App\Support\ImageUploader;
use App\Support\SiteSettings;
use Illuminate\Http\UploadedFile;

/*
|--------------------------------------------------------------------------
| Admin panel: kirish va himoya
|--------------------------------------------------------------------------
*/

it('redirects guests to the admin login page', function (string $url) {
    $this->get($url)->assertRedirect(route('admin.login'));
})->with([
    'dashboard' => fn () => route('admin.dashboard'),
    'products' => fn () => route('admin.products.index'),
    'categories' => fn () => route('admin.categories.index'),
    'news' => fn () => route('admin.news.index'),
    'branches' => fn () => route('admin.branches.index'),
    'hero slides' => fn () => route('admin.hero-slides.index'),
    'locales' => fn () => route('admin.locales.index'),
    'ui translations' => fn () => route('admin.ui.index'),
    'settings' => fn () => route('admin.settings.edit'),
    'about page' => fn () => route('admin.pages.edit', 'about'),
]);

it('renders the admin login page', function () {
    $this->get(route('admin.login'))
        ->assertOk()
        ->assertSee('Bodomgul Admin');
});

it('rejects an invalid password and stays on the login page', function () {
    $admin = User::factory()->create();

    $this->post(route('admin.login.store'), [
        'email' => $admin->email,
        'password' => 'wrong-password',
    ])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs the admin in and redirects to the dashboard', function () {
    $admin = User::factory()->create(['password' => 'secret-password']);

    $this->post(route('admin.login.store'), [
        'email' => $admin->email,
        'password' => 'secret-password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($admin);
});

it('logs the admin out', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.logout'))
        ->assertRedirect(route('admin.login'));

    $this->assertGuest();
});

it('redirects authenticated admins away from the login page', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.login'))
        ->assertRedirect(route('admin.dashboard'));
});

/*
|--------------------------------------------------------------------------
| Mahsulotlar
|--------------------------------------------------------------------------
*/

it('creates a product from the admin form and shows it on the site', function () {
    $admin = User::factory()->create();
    $category = Category::query()->first();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'price' => 99000,
            'is_featured' => '1',
            'is_active' => '1',
            'sort_order' => 99,
            'image_upload' => UploadedFile::fake()->image('tort.jpg', 400, 300),
            'translations' => [
                'uz' => [
                    'name' => 'Yangi tort',
                    'slug' => 'yangi-tort',
                    'description' => 'Sinov uchun yangi tort.',
                    'composition' => 'Bodom, krem',
                    'weight' => '1 kg',
                ],
            ],
        ])
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()->whereSlug('yangi-tort')->first();

    expect($product)->not->toBeNull()
        ->and($product->is_featured)->toBeTrue()
        ->and($product->name)->toBe('Yangi tort');

    $this->get(route('products.show', ['product' => 'yangi-tort']))
        ->assertOk()
        ->assertSee('Yangi tort');

    ImageUploader::delete($product->image);
});

it('returns json for ajax product creation', function () {
    $admin = User::factory()->create();
    $category = Category::query()->first();

    $response = $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'price' => 15000,
            'is_active' => '1',
            'image_upload' => UploadedFile::fake()->image('ajx.jpg', 300, 300),
            'translations' => [
                'uz' => ['name' => 'Ajax tort', 'slug' => 'ajax-tort'],
            ],
        ], ['Accept' => 'application/json']);

    $response->assertOk()->assertJson(['ok' => true]);

    $product = Product::query()->whereSlug('ajax-tort')->first();
    ImageUploader::delete($product->image);
});

it('returns json validation errors for ajax requests', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [], ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['category_id', 'image_upload', 'translations.uz.name']);
});

it('rejects a product without a name and an image', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'price' => 1000,
            'translations' => ['uz' => ['slug' => 'rasmsiz-tort']],
        ])
        ->assertSessionHasErrors(['category_id', 'image_upload', 'translations.uz.name']);

    $this->assertDatabaseMissing('products', ['price' => 1000]);
});

it('rejects a product slug that is already taken in the same locale', function () {
    $admin = User::factory()->create();
    $existing = Product::query()->first();
    $category = Category::query()->first();

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'price' => 45000,
            'is_active' => '1',
            'image_upload' => UploadedFile::fake()->image('dup.jpg', 300, 300),
            'translations' => [
                'uz' => ['name' => 'Dublikat tort', 'slug' => $existing->slug],
            ],
        ])
        ->assertSessionHasErrors('translations.uz.slug');
});

it('hides an inactive product from the public site', function () {
    $admin = User::factory()->create();
    $product = Product::query()->first();

    $this->actingAs($admin)
        ->put(route('admin.products.update', $product), [
            'category_id' => $product->category_id,
            'price' => $product->price,
            'is_active' => '0',
            'translations' => [
                'uz' => [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'weight' => $product->weight,
                ],
            ],
        ])
        ->assertRedirect(route('admin.products.index'));

    expect($product->refresh()->is_active)->toBeFalse();

    $this->get(route('products.show', ['product' => $product->slug]))->assertNotFound();
    $this->get(route('catalog.index'))->assertDontSee($product->name);
});

it('toggles a product state through the ajax endpoint', function () {
    $admin = User::factory()->create();
    $product = Product::query()->first();

    $this->actingAs($admin)
        ->patchJson(route('admin.products.toggle', $product))
        ->assertOk()
        ->assertJson(['ok' => true, 'value' => false]);

    expect($product->refresh()->is_active)->toBeFalse();

    $this->actingAs($admin)
        ->patchJson(route('admin.products.toggle', $product))
        ->assertOk()
        ->assertJson(['value' => true]);
});

it('deletes a product with its uploaded image', function () {
    $admin = User::factory()->create();
    $product = Product::query()->first();

    $this->actingAs($admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertModelMissing($product);
});

/*
|--------------------------------------------------------------------------
| Kategoriyalar
|--------------------------------------------------------------------------
*/

it('refuses to delete a category that still has products', function () {
    $admin = User::factory()->create();
    $category = Category::query()->has('products')->first();

    $this->actingAs($admin)
        ->from(route('admin.categories.index'))
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($category);
});

/*
|--------------------------------------------------------------------------
| Yangiliklar
|--------------------------------------------------------------------------
*/

it('publishes a news article that appears on the public site', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.news.store'), [
            'published_at' => '2026-10-10',
            'is_published' => '1',
            'image_upload' => UploadedFile::fake()->image('news.jpg', 600, 400),
            'translations' => [
                'uz' => [
                    'title' => 'Yangi mavsumiy aksiya',
                    'slug' => 'yangi-mavsumiy-aksiya',
                    'tag' => 'Aksiya',
                    'excerpt' => 'Qisqa tavsif.',
                    'body' => "Birinchi paragraf matni.\n\nIkkinchi paragraf matni.",
                ],
            ],
        ])
        ->assertRedirect(route('admin.news.index'));

    $news = News::query()->whereSlug('yangi-mavsumiy-aksiya')->first();

    $this->get(route('news.index'))
        ->assertOk()
        ->assertSee('Yangi mavsumiy aksiya');

    $this->get(route('news.show', ['news' => 'yangi-mavsumiy-aksiya']))
        ->assertOk()
        ->assertSee('Birinchi paragraf matni.')
        ->assertSee('Ikkinchi paragraf matni.');

    ImageUploader::delete($news->image);
});

it('hides an unpublished news article from the public site', function () {
    $admin = User::factory()->create();
    $news = News::query()->first();

    $this->actingAs($admin)
        ->put(route('admin.news.update', $news), [
            'published_at' => $news->published_at?->toDateString(),
            'is_published' => '0',
            'translations' => [
                'uz' => [
                    'title' => $news->title,
                    'slug' => $news->slug,
                    'tag' => $news->tag,
                    'excerpt' => $news->excerpt,
                    'body' => $news->translationFor('uz', 'body'),
                ],
            ],
        ])
        ->assertRedirect(route('admin.news.index'));

    expect($news->refresh()->is_published)->toBeFalse();

    $this->get(route('news.show', ['news' => $news->slug]))->assertNotFound();
});

/*
|--------------------------------------------------------------------------
| Hero slaydlar
|--------------------------------------------------------------------------
*/

it('creates a hero slide that appears on the home page', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.hero-slides.store'), [
            'primary_route' => 'catalog.index',
            'sort_order' => 10,
            'is_active' => '1',
            'image_upload' => UploadedFile::fake()->image('slide.jpg', 800, 450),
            'translations' => [
                'uz' => [
                    'title' => 'Sinov slaydi',
                    'eyebrow' => 'Yangi mavsum',
                    'text' => 'Slayd matni.',
                    'primary_label' => 'Katalog',
                ],
            ],
        ])
        ->assertRedirect(route('admin.hero-slides.index'));

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Sinov slaydi');

    $slide = HeroSlide::query()
        ->whereHas('translations', fn ($query) => $query->where('value', 'Sinov slaydi'))
        ->first();

    ImageUploader::delete($slide->image);
});

it('validates the hero slide category against translated slugs', function () {
    $admin = User::factory()->create();

    // Mavjud kategoriya slug'i tarjimalar jadvalidan tekshiriladi.
    $this->actingAs($admin)
        ->post(route('admin.hero-slides.store'), [
            'primary_route' => 'catalog.category',
            'primary_category' => 'tortlar',
            'sort_order' => 11,
            'is_active' => '1',
            'image_upload' => UploadedFile::fake()->image('slide.jpg', 800, 450),
            'translations' => ['uz' => ['title' => 'Kategoriyali slayd']],
        ])
        ->assertRedirect(route('admin.hero-slides.index'));

    $slide = HeroSlide::query()
        ->whereHas('translations', fn ($query) => $query->where('value', 'Kategoriyali slayd'))
        ->first();

    expect($slide->primary_params)->toBe(['category' => 'tortlar']);

    // Mavjud bo'lmagan kategoriya rad etiladi.
    $this->actingAs($admin)
        ->post(route('admin.hero-slides.store'), [
            'primary_route' => 'catalog.category',
            'primary_category' => 'yoq-kategoriya',
            'image_upload' => UploadedFile::fake()->image('slide-2.jpg', 800, 450),
            'translations' => ['uz' => ['title' => 'Xato slayd']],
        ])
        ->assertSessionHasErrors('primary_category');

    ImageUploader::delete($slide->image);
});

it('rejects a hero slide without an image and a title', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.hero-slides.store'), [])
        ->assertSessionHasErrors(['image_upload', 'translations.uz.title']);
});

it('opens the hero slide edit form in the panel with locale tabs', function () {
    $admin = User::factory()->create();
    $slide = HeroSlide::query()->first();

    $this->actingAs($admin)
        ->get(route('admin.hero-slides.edit', ['hero_slide' => $slide, 'panel' => 1]))
        ->assertOk()
        ->assertSee('Matnlar (tillar bo‘yicha)')
        ->assertSee('data-locale-tab="uz"', false)
        ->assertSee($slide->title);
});

it('toggles a hero slide state through the ajax endpoint', function () {
    $admin = User::factory()->create();
    $slide = HeroSlide::query()->first();

    expect($slide->is_active)->toBeTrue();

    $this->actingAs($admin)
        ->patchJson(route('admin.hero-slides.toggle', $slide))
        ->assertOk()
        ->assertJson(['ok' => true, 'value' => false]);

    expect($slide->refresh()->is_active)->toBeFalse();
});

it('deletes a hero slide through the admin panel', function () {
    $admin = User::factory()->create();
    $slide = HeroSlide::query()->first();

    $this->actingAs($admin)
        ->delete(route('admin.hero-slides.destroy', $slide))
        ->assertRedirect(route('admin.hero-slides.index'));

    $this->assertModelMissing($slide);
});

it('opens the branch edit form in the panel with locale tabs', function () {
    $admin = User::factory()->create();
    $branch = Branch::query()->first();

    $this->actingAs($admin)
        ->get(route('admin.branches.edit', ['branch' => $branch, 'panel' => 1]))
        ->assertOk()
        ->assertSee('Matnlar (tillar bo‘yicha)')
        ->assertSee($branch->name);
});

/*
|--------------------------------------------------------------------------
| Tillar (admin boshqaruvi)
|--------------------------------------------------------------------------
*/

it('adds a new locale from the admin panel and serves the site at its prefix', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.locales.store'), [
            'code' => 'fr',
            'name' => 'Français',
            'is_active' => '1',
            'sort_order' => 4,
        ])
        ->assertRedirect(route('admin.locales.index'));

    $this->assertDatabaseHas('locales', ['code' => 'fr', 'is_active' => true]);

    // Tarjima hali kiritilmagan — asosiy til matni ko'rsatiladi.
    $this->get('/fr')
        ->assertOk()
        ->assertSee('Sevimli mahsulotlar');
});

it('rejects a duplicate locale code', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.locales.store'), [
            'code' => 'uz',
            'name' => 'O‘zbekcha',
        ])
        ->assertSessionHasErrors('code');
});

it('refuses to delete the default locale', function () {
    $admin = User::factory()->create();
    $locale = Locale::query()->where('is_default', true)->first();

    $this->actingAs($admin)
        ->from(route('admin.locales.index'))
        ->delete(route('admin.locales.destroy', $locale))
        ->assertRedirect(route('admin.locales.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($locale);
});

it('serves translated content on the locale url', function () {
    seedLocale('ru');
    $product = Product::query()->first();
    $product->saveTranslations([
        'ru' => [
            'name' => 'Миндальный торт',
            'slug' => 'mindalnyj-tort',
            'weight' => '1 кг',
        ],
    ]);

    $this->get('/ru/products/mindalnyj-tort')
        ->assertOk()
        ->assertSee('Миндальный торт');

    // Asosiy til manzili o'zgarmagan.
    $this->get(route('products.show', ['product' => $product->tr('slug', 'uz'), 'locale' => 'uz']))
        ->assertOk()
        ->assertSee($product->tr('name', 'uz'));
});

it('falls back to the default locale when a translation is missing', function () {
    $product = Product::query()->first();

    // Ruscha tarjimani o'chirib, zaxira (fallback) zanjirini tekshiramiz.
    $product->translations()->where('locale', 'ru')->delete();
    $product->unsetRelation('translations');

    $this->get('/ru/catalog')
        ->assertOk()
        ->assertSee($product->tr('name', 'uz'));
});

it('updates interface strings for a locale', function () {
    $admin = User::factory()->create();
    seedLocale('ru');

    $this->actingAs($admin)
        ->put(route('admin.ui.update'), [
            'strings' => [
                'ru' => [
                    'nav.catalog' => 'Каталог',
                    'common.view_all' => 'Смотреть все',
                ],
            ],
        ])
        ->assertRedirect();

    $this->get('/ru')
        ->assertOk()
        ->assertSee('Каталог');

    $this->get(route('home', ['locale' => 'uz']))
        ->assertOk()
        ->assertSee('Katalog');
});

/*
|--------------------------------------------------------------------------
| Sayt sozlamalari
|--------------------------------------------------------------------------
*/

it('reflects updated brand and promo settings on the public site', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'brand' => [
                'name' => 'Bodomgul Yangi',
                'phone' => '+998 90 000 00 00',
                'phone_display' => '90 000 00 00',
                'motto' => 'Yangi shior matni',
                'about' => 'Yangi qisqa brend tavsifi.',
                'announcement' => 'Yangi e’lon matni.',
            ],
            'promo' => [
                'eyebrow' => 'Yangi tanlov',
                'title' => 'Yangi promo sarlavhasi',
                'text' => 'Promo matni.',
                'cta_label' => 'Ko‘rish',
                'cta_route' => 'catalog.index',
            ],
            'social' => ['facebook' => 'https://facebook.com/bodomgul'],
            'app' => ['google_play' => 'https://play.google.com/store'],
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->get(route('home', ['locale' => 'uz']))
        ->assertOk()
        ->assertSee('Bodomgul Yangi')
        ->assertSee('Yangi promo sarlavhasi')
        ->assertSee('Yangi shior matni')
        ->assertSee('Yangi qisqa brend tavsifi.')
        ->assertSee('Yangi e’lon matni.');
});

it('flashes and returns the save message for ajax saves', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->putJson(route('admin.settings.update'), [
            'brand' => ['name' => 'Bodomgul'],
        ])
        ->assertOk()
        ->assertJson(['ok' => true, 'message' => 'Sozlamalar saqlandi.'])
        ->assertSessionHas('status', 'Sozlamalar saqlandi.');

    // Sahifa yangilanganda yashil bildirishnoma ko'rinadi.
    $this->get(route('admin.settings.edit'))
        ->assertOk()
        ->assertSee('Sozlamalar saqlandi.');
});

it('refuses to update settings without a brand name', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'brand' => ['name' => ''],
        ])
        ->assertSessionHasErrors('brand.name');
});

it('validates the promo category against translated slugs', function () {
    $admin = User::factory()->create();

    // Mavjud kategoriya slug'i tarjimalar jadvalidan tekshiriladi.
    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'brand' => ['name' => 'Bodomgul'],
            'promo' => [
                'cta_route' => 'catalog.category',
                'cta_category' => 'shirinlik-toplamlari',
            ],
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    // Mavjud bo'lmagan kategoriya rad etiladi.
    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'brand' => ['name' => 'Bodomgul'],
            'promo' => [
                'cta_route' => 'catalog.category',
                'cta_category' => 'yoq-kategoriya',
            ],
        ])
        ->assertSessionHasErrors('promo.cta_category');
});

it('hides the mobile app section from the home page when disabled', function () {
    $admin = User::factory()->create();

    // Boshlang'ich holatda bo'lim ko'rinadi.
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('id="mobile-app"', false);

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'brand' => ['name' => 'Bodomgul'],
            'app' => ['enabled' => '0'],
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    SiteSettings::flush();

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('id="mobile-app"', false);

    // Qayta yoqilsa yana ko'rinadi.
    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'brand' => ['name' => 'Bodomgul'],
            'app' => ['enabled' => '1'],
        ])
        ->assertRedirect();

    SiteSettings::flush();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('id="mobile-app"', false);
});

it('stores per-locale settings overlays', function () {
    $admin = User::factory()->create();
    seedLocale('ru');

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'brand' => ['name' => 'Bodomgul'],
            'overlay' => [
                'ru' => [
                    'brand.motto' => 'Русский девиз',
                ],
            ],
        ])
        ->assertRedirect();

    SiteSettings::flush();

    $this->get('/ru')
        ->assertOk()
        ->assertSee('Русский девиз');

    $this->get(route('home', ['locale' => 'uz']))
        ->assertOk()
        ->assertDontSee('Русский девиз');
});

it('keeps serving the site after the default locale changes', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.locales.default', 'en'))
        ->assertOk()
        ->assertJson(['ok' => true]);

    // Yangi asosiy til sahifasi tarjima qilingan kontentni ko'rsatadi.
    $this->get('/en')
        ->assertOk()
        ->assertSee('Favourite products')
        ->assertSee('Almond Cake');

    // Eski (o'zbekcha) sahifalar ham buzilmaydi.
    $this->get('/uz/catalog/tortlar')
        ->assertOk()
        ->assertSee('Bodomli tort');

    // Tarjimasi yo'q yozuv boshlang'ich kontent tiliga qaytadi.
    $news = News::query()->first();
    $news->translations()->where('locale', 'en')->delete();
    $news->unsetRelation('translations');

    $this->get('/en/news')
        ->assertOk()
        ->assertSee($news->tr('title', 'uz'));
});

it('renders pages when a record has no slug in any language', function () {
    $category = new Category([
        'image' => 'images/categories/tortlar.svg',
        'is_active' => true,
        'sort_order' => 99,
    ]);
    $category->save();

    $this->get(route('home', ['locale' => 'uz']))->assertOk();
    $this->get(route('catalog.index', ['locale' => 'uz']))->assertOk();
});

/*
|--------------------------------------------------------------------------
| Sahifalar
|--------------------------------------------------------------------------
*/

it('updates the about page content shown on the site', function () {
    $admin = User::factory()->create();
    $page = Page::query()->firstWhere('slug', 'about');

    $this->actingAs($admin)
        ->put(route('admin.pages.update', 'about'), [
            'title' => ['uz' => 'Biz haqimizda yangi'],
            'content' => [
                'uz' => [
                    'description' => 'Yangi qisqa tavsif matni.',
                    'story' => [
                        'eyebrow' => 'Tarix',
                        'title' => 'Yangi tarix sarlavhasi',
                    ],
                    'mission' => [
                        'badge' => 'Missiya',
                        'text' => 'Missiya matni.',
                        'attribution' => 'Bodomgul',
                    ],
                    'values' => [
                        ['icon' => 'bi-award', 'title' => 'Qadriyat', 'text' => 'Qadriyat matni.'],
                        ['icon' => '', 'title' => '', 'text' => ''],
                    ],
                    'stats' => [
                        ['value' => '10+', 'label' => 'Yangi raqam'],
                    ],
                ],
            ],
            'story_paragraphs' => [
                'uz' => "Birinchi xatboshi.\n\nIkkinchi xatboshi.",
            ],
        ])
        ->assertRedirect();

    $page->refresh();
    $content = $page->contentFor('uz');

    expect($content['story']['title'])->toBe('Yangi tarix sarlavhasi')
        ->and($content['values'])->toHaveCount(1)
        ->and($content['story']['paragraphs'])->toHaveCount(2);

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('<h1 class="page-header__title">Biz haqimizda yangi</h1>', false)
        ->assertSee('Biz haqimizda yangi</span>', false)
        ->assertSee('Yangi qisqa tavsif matni.')
        ->assertSee('Yangi tarix sarlavhasi')
        ->assertSee('Birinchi xatboshi.');
});

it('shows the edited contacts page title in the header and breadcrumb', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.pages.update', 'contacts'), [
            'title' => ['uz' => 'Biz bilan bog‘lanish'],
            'content' => [
                'uz' => [
                    'description' => 'Yangi aloqa tavsifi.',
                ],
            ],
        ])
        ->assertRedirect();

    $this->get(route('contacts'))
        ->assertOk()
        ->assertSee('<h1 class="page-header__title">Biz bilan bog‘lanish</h1>', false)
        ->assertSee('Biz bilan bog‘lanish</span>', false)
        ->assertSee('Yangi aloqa tavsifi.');
});

it('opens the careers page form with the existing vacancies and perks', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.pages.edit', 'careers'))
        ->assertOk()
        ->assertSee('Qandolatchi usta / yordamchi')
        ->assertSee('data-repeater="content[uz][vacancies][items]"', false)
        ->assertSee('data-repeater="content[uz][perks][items]"', false);
});

it('shows the edited careers page vacancies and sections on the site', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.pages.update', 'careers'), [
            'title' => ['uz' => 'Karyera'],
            'content' => [
                'uz' => [
                    'hero' => [
                        'badge' => 'Yangi yorliq',
                        'title' => 'Yangi karyera sarlavhasi',
                        'text' => 'Yangi hero matni.',
                        'badge_text' => '2 000+ xodim',
                    ],
                    'perks' => [
                        'title' => 'Yangi afzalliklar',
                        'subtitle' => 'Afzalliklar izohi.',
                        'items' => [
                            ['icon' => 'bi-award', 'title' => 'Yangi afzallik', 'text' => 'Afzallik matni.'],
                        ],
                    ],
                    'vacancies' => [
                        'title' => 'Yangi vakansiyalar',
                        'subtitle' => 'Vakansiyalar izohi.',
                        'items' => [
                            [
                                'title' => 'Yangi lavozim',
                                'tag' => 'To‘liq bandlik',
                                'text' => 'Yangi vazifa matni.',
                                'location' => 'Toshkent shahri',
                                'salary' => '9 000 000 so‘m',
                                'schedule' => '2/2',
                            ],
                        ],
                    ],
                    'cta' => [
                        'title' => 'Yangi murojaat',
                        'text' => 'Murojaat matni.',
                        'phone_label' => '+998 78 113 40 40',
                        'telegram_label' => '@bodomgul_hr_bot',
                        'telegram_url' => 'https://t.me/bodomgul_hr_bot',
                    ],
                ],
            ],
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->get(route('careers'))
        ->assertOk()
        ->assertSee('Yangi yorliq')
        ->assertSee('Yangi karyera sarlavhasi')
        ->assertSee('2 000+ xodim')
        ->assertSee('Yangi afzallik')
        ->assertSee('Afzallik matni.')
        ->assertSee('Yangi vakansiyalar')
        ->assertSee('Yangi lavozim')
        ->assertSee('Yangi vazifa matni.')
        ->assertSee('9 000 000 so‘m')
        ->assertSee('@bodomgul_hr_bot')
        ->assertSee('Yangi murojaat');
});
