<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\LocaleController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UiTranslationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('login.store');
});

Route::post('logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('hero-slides', HeroSlideController::class)->except('show');
    Route::patch('hero-slides/{hero_slide}/toggle', [HeroSlideController::class, 'toggle'])->name('hero-slides.toggle');

    Route::resource('categories', CategoryController::class)->except('show');
    Route::patch('categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');

    Route::resource('products', ProductController::class)->except('show');
    Route::patch('products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');

    Route::resource('news', NewsController::class)->except('show');
    Route::patch('news/{news}/toggle', [NewsController::class, 'toggle'])->name('news.toggle');

    Route::resource('branches', BranchController::class)->except('show');
    Route::patch('branches/{branch}/toggle', [BranchController::class, 'toggle'])->name('branches.toggle');

    Route::resource('locales', LocaleController::class)->except('show');
    Route::patch('locales/{locale}/toggle', [LocaleController::class, 'toggle'])->name('locales.toggle');
    Route::put('locales/{locale}/default', [LocaleController::class, 'makeDefault'])->name('locales.default');

    Route::get('ui-translations', [UiTranslationController::class, 'index'])->name('ui.index');
    Route::put('ui-translations', [UiTranslationController::class, 'update'])->name('ui.update');

    Route::get('pages/{page}', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('pages/{page}', [PageController::class, 'update'])->name('pages.update');

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
