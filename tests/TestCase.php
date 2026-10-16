<?php

namespace Tests;

use App\Support\Locales;
use App\Support\SiteSettings;
use App\Support\Ui;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Har bir migratsiyadan keyin demo kontentni yuklash
     * (feature testlari bazadagi kontent bilan ishlaydi).
     */
    protected $seed = true;

    /**
     * So'rovlar orasida saqlanadigan statik keshlarni tozalash
     * (haqiqiy hayotda har bir so'rov — yangi jarayon).
     */
    protected function setUp(): void
    {
        parent::setUp();

        Locales::flush();
        Ui::flush();
        SiteSettings::flush();
    }
}
