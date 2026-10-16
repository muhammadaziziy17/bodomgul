<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleRedirectController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin panel (til prefiksisiz)
|--------------------------------------------------------------------------
|
| Locale guruhidan oldin ro'yxatga olinadi, aks holda `/{locale}`
| admin manzillarini ushlab qolishi mumkin.
|
*/

Route::prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));

/*
|--------------------------------------------------------------------------
| Ommaviy sayt: /{locale}/...
|--------------------------------------------------------------------------
*/

Route::prefix('{locale}')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/catalog/{category}', [CatalogController::class, 'category'])->name('catalog.category');

        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

        Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
        Route::get('/about', [AboutController::class, 'index'])->name('about');

        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
        Route::post('/contacts', [ContactController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('contacts.store');

        Route::get('/news', [NewsController::class, 'index'])->name('news.index');
        Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::get('/careers', [CareerController::class, 'index'])->name('careers');
    });

/*
|--------------------------------------------------------------------------
| Til prefiksisiz manzillar: asosiy tilga yo'naltiriladi
|--------------------------------------------------------------------------
*/

Route::get('/', [LocaleRedirectController::class, 'root']);

Route::fallback([LocaleRedirectController::class, 'fallback']);
