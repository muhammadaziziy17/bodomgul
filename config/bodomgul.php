<?php

/*
|--------------------------------------------------------------------------
| Bodomgul — demo kontent
|--------------------------------------------------------------------------
|
| MVP bosqichida ma'lumotlar bazasi ishlatilmaydi: kategoriya, mahsulot,
| filial va yangilik ma'lumotlari shu faylda saqlanadi. Barcha ma'lumotlar
| namunaviy (demo) bo'lib, haqiqiy biznes ko'rsatkichlari emas.
|
*/

return [
    'brand' => [
        'name' => 'Bodomgul',
        'city' => 'Toshkent',
        'tagline' => 'Bodom guli ilhomidagi qandolatlar',
        'motto' => '«Qandolatchilik san’atini rivojlantirish orqali, har bir xonadonga bayram shukuhini olib kiramiz»',
        'about' => 'Bodomgul — bodom gulining nafisligidan ilhomlangan qandolat va novvoyxona brendi. Tortlar, kruassanlar va shirinliklar har kuni yangi holatda tayyorlanadi.',
        'phone' => '+998 78 113 40 40',
        'phone_display' => '78 113 40 40',
        'email' => 'salom@bodomgul.example',
        'address' => 'Toshkent shahri (demo manzil)',
        'hours' => 'Dush-Yak: 08:00 dan 23:00 gacha',
        'announcement' => 'Mobil ilovaga ro\'yxatdan o\'ting va birinchi xaridda kofe sovg\'a oling!',
        'demo_note' => 'Bu sayt Safia Bakery (safiabakery.uz) asosidagi portfolio demosi.',
        'copyright_note' => 'Barcha huquqlar himoyalangan.',
    ],

    'navigation' => [
        ['label_key' => 'nav.about', 'route' => 'about', 'patterns' => ['about']],
        ['label_key' => 'nav.catalog', 'route' => 'catalog.index', 'patterns' => ['catalog.*', 'products.*']],
        ['label_key' => 'nav.news', 'route' => 'news.index', 'patterns' => ['news.*']],
        ['label_key' => 'nav.branches', 'route' => 'branches.index', 'patterns' => ['branches.*']],
        ['label_key' => 'nav.careers', 'route' => 'careers', 'patterns' => ['careers*']],
        ['label_key' => 'nav.catering', 'route' => 'contacts', 'patterns' => ['contacts*']],
    ],

    'social' => [
        'facebook' => 'https://facebook.com',
        'instagram' => 'https://instagram.com',
        'telegram' => 'https://t.me',
    ],

    'app' => [
        'enabled' => true,
        'google_play' => 'https://play.google.com',
        'app_store' => 'https://apple.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin panel kirish ma'lumotlari
    |--------------------------------------------------------------------------
    |
    | `.env` faylida ADMIN_EMAIL va ADMIN_PASSWORD ko'rsatilmasa, quyidagi
    | demo qiymatlar ishlatiladi (faqat lokal/portfolio muhiti uchun).
    |
    */

    'admin' => [
        'name' => env('ADMIN_NAME', 'Bodomgul Admin'),
        'email' => env('ADMIN_EMAIL', 'admin@bodomgul.test'),
        'password' => env('ADMIN_PASSWORD', 'bodomgul123'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Kontent tili
    |--------------------------------------------------------------------------
    |
    | Seederlar kontentni shu tilda yozadi. Admin asosiy tilni almashtirsa
    | ham, tarjimasi kiritilmagan maydonlar shu tilga qaytadi.
    |
    */

    'content_locale' => env('CONTENT_LOCALE', 'uz'),

    'hero_slides' => [
        [
            'image' => 'images/hero/hero-1.svg',
            'alt' => 'Bodomli tort va kruassanlardan iborat kompozitsiya',
            'eyebrow' => 'Mavsumiy kolleksiya',
            'title' => 'Bodom guli ilhomida yaratilgan ta’mlar',
            'text' => 'Kuz kolleksiyasida bodom, dolchin va malina uyg‘unligi — har bir luqmada iliqlik.',
            'primary_label' => 'Katalogni ko‘rish',
            'primary_route' => 'catalog.index',
            'secondary_label' => 'Filiallar',
            'secondary_route' => 'branches.index',
        ],
        [
            'image' => 'images/hero/hero-2.svg',
            'alt' => 'Issiq kruassanlar va bir piyola kofe',
            'eyebrow' => 'Har kuni yangi',
            'title' => 'Nonvoyxona peshtaxtasida issiq kruassanlar',
            'text' => 'Qatlamli xamir va sariyog‘ hidi — ertalabki eng yaxshi boshlanish.',
            'primary_label' => 'Kruassanlarni ko‘rish',
            'primary_route' => 'catalog.category',
            'primary_params' => ['category' => 'kruassanlar'],
            'secondary_label' => 'Biz haqimizda',
            'secondary_route' => 'about',
        ],
        [
            'image' => 'images/hero/hero-3.svg',
            'alt' => 'Sovg‘a qutisi va shirinliklar',
            'eyebrow' => 'Sovg‘a g‘oyalari',
            'title' => 'Yaqinlaringiz uchun shirinlik to‘plamlari',
            'text' => 'Bayram yoki kutilmagan quvonch uchun nafis bezatilgan qutilar.',
            'primary_label' => 'To‘plamlarni ko‘rish',
            'primary_route' => 'catalog.category',
            'primary_params' => ['category' => 'shirinlik-toplamlari'],
            'secondary_label' => 'Kontaktlar',
            'secondary_route' => 'contacts',
        ],
    ],

    'categories' => [
        [
            'slug' => 'tortlar',
            'name' => 'Tortlar',
            'image' => 'images/categories/tortlar.svg',
            'description' => 'Bayram va kundalik dasturxon uchun bodomli, shokoladli va mevali tortlar.',
        ],
        [
            'slug' => 'pishiriqlar',
            'name' => 'Pishiriqlar',
            'image' => 'images/categories/pishiriqlar.svg',
            'description' => 'Kanel, ekler va tartlar — choy dasturxoni uchun yumshoq ta’mlar.',
        ],
        [
            'slug' => 'kruassanlar',
            'name' => 'Kruassanlar',
            'image' => 'images/categories/kruassanlar.svg',
            'description' => 'Qarsildoq qatlamli xamir va sariyog‘li ta’m: nonushta sevimlilari.',
        ],
        [
            'slug' => 'pechenye',
            'name' => 'Pechenye',
            'image' => 'images/categories/pechenye.svg',
            'description' => 'Bodomli va shokoladli pechenyelar — choy va suhbat uchun.',
        ],
        [
            'slug' => 'shirinlik-toplamlari',
            'name' => 'Shirinlik to‘plamlari',
            'image' => 'images/categories/shirinlik-toplamlari.svg',
            'description' => 'Sovg‘a uchun bezatilgan qutilar va assorti to‘plamlar.',
        ],
        [
            'slug' => 'ichimliklar',
            'name' => 'Ichimliklar',
            'image' => 'images/categories/ichimliklar.svg',
            'description' => 'Bodomli latte, issiq shokolad va boshqa issiq ichimliklar.',
        ],
    ],

    'products' => [
        [
            'id' => 1,
            'name' => 'Bodomli tort',
            'slug' => 'bodomli-tort',
            'category' => 'tortlar',
            'price' => 185000,
            'weight' => '1 kg',
            'image' => 'images/products/bodomli-tort.svg',
            'featured' => true,
            'description' => 'Yumshoq bodomli biskvit, nafis qaymoqli krem va qovurilgan bodom qatlami. Har bir bo‘lagida bodom gulining iliq ta’mi.',
            'composition' => 'Bodom, bug‘doy uni, sariyog‘, tuxum, qaymoqli krem',
        ],
        [
            'id' => 2,
            'name' => 'Shokoladli tort',
            'slug' => 'shokoladli-tort',
            'category' => 'tortlar',
            'price' => 165000,
            'weight' => '1 kg',
            'image' => 'images/products/shokoladli-tort.svg',
            'featured' => true,
            'description' => 'To‘q shokoladli biskvit va ipakdek ganash qatlami. Shokolad ishqibozlari uchun to‘yimli va muvozanatli ta’m.',
            'composition' => 'Kakao, to‘q shokolad, sariyog‘, tuxum, qaymoq',
        ],
        [
            'id' => 3,
            'name' => 'Malinali chizkeyk',
            'slug' => 'malinali-chizkeyk',
            'category' => 'tortlar',
            'price' => 42000,
            'weight' => '1 bo‘lak',
            'image' => 'images/products/malinali-chizkeyk.svg',
            'featured' => false,
            'description' => 'Yengil kremli pishloq asosidagi chizkeyk va yangi malina sousi. Sovuq holda tortilsa, ta’mi yanada ochiladi.',
            'composition' => 'Krem pishloq, malina, pechenye asosi, qaymoq',
        ],
        [
            'id' => 4,
            'name' => 'Qulupnayli kapkeyk',
            'slug' => 'qulupnayli-kapkeyk',
            'category' => 'tortlar',
            'price' => 22000,
            'weight' => '1 dona',
            'image' => 'images/products/qulupnayli-kapkeyk.svg',
            'featured' => false,
            'description' => 'Havo kabi yengil biskvit, qaymoqli krem va yangi qulupnay. Har qanday kunni kichik bayramga aylantiradi.',
            'composition' => 'Qulupnay, sariyog‘li krem, bug‘doy uni, tuxum',
        ],
        [
            'id' => 5,
            'name' => 'Bodomli kruassan',
            'slug' => 'bodomli-kruassan',
            'category' => 'kruassanlar',
            'price' => 18000,
            'weight' => '1 dona',
            'image' => 'images/products/bodomli-kruassan.svg',
            'featured' => true,
            'description' => 'Ko‘p qatlamli sariyog‘li xamir va bodom kremi uyg‘unligi. Ustiga qovurilgan bodom bo‘laklari sepilgan.',
            'composition' => 'Sariyog‘li xamir, bodom kremi, qovurilgan bodom',
        ],
        [
            'id' => 6,
            'name' => 'Shokoladli kruassan',
            'slug' => 'shokoladli-kruassan',
            'category' => 'kruassanlar',
            'price' => 20000,
            'weight' => '1 dona',
            'image' => 'images/products/shokoladli-kruassan.svg',
            'featured' => false,
            'description' => 'Qarsildoq kruassan ustidan to‘q shokolad surtilgan. Ertalabki nonushta uchun ajoyib tanlov.',
            'composition' => 'Sariyog‘li xamir, to‘q shokolad',
        ],
        [
            'id' => 7,
            'name' => 'Sariyog‘li kruassan',
            'slug' => 'sariyogli-kruassan',
            'category' => 'kruassanlar',
            'price' => 16000,
            'weight' => '1 dona',
            'image' => 'images/products/sariyogli-kruassan.svg',
            'featured' => false,
            'description' => 'Klassik fransuz kruassani: tashqi qatlami qarsildoq, ichi yumshoq va sariyog‘li.',
            'composition' => 'Sariyog‘li qatlamli xamir',
        ],
        [
            'id' => 8,
            'name' => 'Bodomli pechenye',
            'slug' => 'bodomli-pechenye',
            'category' => 'pechenye',
            'price' => 45000,
            'weight' => '500 g',
            'image' => 'images/products/bodomli-pechenye.svg',
            'featured' => true,
            'description' => 'Mayda tuyilgan bodom qo‘shilgan, chetlari qarsildoq pechenye. Choy dasturxoni bilan mukammal uyg‘unlashadi.',
            'composition' => 'Bodom uni, sariyog‘, shakar, bug‘doy uni',
        ],
        [
            'id' => 9,
            'name' => 'Shokolad chipli pechenye',
            'slug' => 'shokolad-chipli-pechenye',
            'category' => 'pechenye',
            'price' => 40000,
            'weight' => '500 g',
            'image' => 'images/products/shokolad-chipli-pechenye.svg',
            'featured' => false,
            'description' => 'Yumshoq markaz va yirik shokolad bo‘laklari. Issiq sut bilan birga eng yaxshi ta’m beradi.',
            'composition' => 'Shokolad bo‘laklari, sariyog‘, shakar, tuxum',
        ],
        [
            'id' => 10,
            'name' => 'Makaron to‘plami',
            'slug' => 'makaron-toplama',
            'category' => 'shirinlik-toplamlari',
            'price' => 95000,
            'weight' => '12 dona',
            'image' => 'images/products/makaron-toplama.svg',
            'featured' => true,
            'description' => 'Bodom, malina va pista kremlari bilan tayyorlangan 12 dona nafis makaron to‘plami.',
            'composition' => 'Bodom, malina va pista kremlari, bodom uni',
        ],
        [
            'id' => 11,
            'name' => 'Bodom gulli sovg‘a qutisi',
            'slug' => 'bodom-gulli-shirinlik-qutisi',
            'category' => 'shirinlik-toplamlari',
            'price' => 150000,
            'weight' => 'To‘plam',
            'image' => 'images/products/bodom-gulli-shirinlik-qutisi.svg',
            'featured' => false,
            'description' => 'Bayram dasturxoni uchun bezatilgan quti: makaronlar, pechenye va shokoladli assorti birga.',
            'composition' => 'Assorti shirinliklar to‘plami',
        ],
        [
            'id' => 12,
            'name' => 'Kanel',
            'slug' => 'kanel',
            'category' => 'pishiriqlar',
            'price' => 24000,
            'weight' => '1 dona',
            'image' => 'images/products/kanel.svg',
            'featured' => true,
            'description' => 'Dolchin va sariyog‘ bilan o‘ralgan, ustiga oq glazur quyilgan issiq kanel bulochkasi.',
            'composition' => 'Dolchin, sariyog‘, shakar glazuri',
        ],
        [
            'id' => 13,
            'name' => 'Ekler to‘plami',
            'slug' => 'ekler-toplama',
            'category' => 'pishiriqlar',
            'price' => 60000,
            'weight' => '6 dona',
            'image' => 'images/products/ekler-toplami.svg',
            'featured' => false,
            'description' => 'Krem bilan to‘ldirilgan va ustidan shokolad qoplangan 6 dona ekler to‘plami.',
            'composition' => 'Krem, shokolad qoplamasi, choux xamiri',
        ],
        [
            'id' => 14,
            'name' => 'Mevali tart',
            'slug' => 'mevali-tart',
            'category' => 'pishiriqlar',
            'price' => 55000,
            'weight' => '1 dona (18 sm)',
            'image' => 'images/products/mevali-tart.svg',
            'featured' => true,
            'description' => 'Qarsildoq qumoqli asos, yengil krem va mavsumiy mevalar uyg‘unligi.',
            'composition' => 'Mavsumiy mevalar, krem, qumoqli asos',
        ],
        [
            'id' => 15,
            'name' => 'Bodomli latte',
            'slug' => 'bodomli-latte',
            'category' => 'ichimliklar',
            'price' => 28000,
            'weight' => '300 ml',
            'image' => 'images/products/bodomli-latte.svg',
            'featured' => true,
            'description' => 'Espresso, issiq sut va bodom siropi uyg‘unligi. Ustiga mayda bodom bo‘laklari sepiladi.',
            'composition' => 'Espresso, sut, bodom siropi',
        ],
        [
            'id' => 16,
            'name' => 'Issiq shokolad',
            'slug' => 'issiq-shokolad',
            'category' => 'ichimliklar',
            'price' => 25000,
            'weight' => '300 ml',
            'image' => 'images/products/issiq-shokolad.svg',
            'featured' => false,
            'description' => 'Quyuq issiq shokolad, qaymoq va marshmallow bilan. Sovuq kunlar uchun eng yaxshi tanlov.',
            'composition' => 'Kakao, sut, qaymoq, marshmallow',
        ],
    ],

    'promo' => [
        'eyebrow' => 'Mavsumiy tanlov',
        'title' => 'Bodom va dolchin — kuzning eng iliq ta’mlari',
        'text' => 'Mavsumiy kolleksiyada bodomli kruassan, kanel va bodomli latte. Kuzgi dasturxonni shirin qiling.',
        'image' => 'images/brand/promo.svg',
        'cta_label' => 'Kolleksiyani ko‘rish',
        'cta_route' => 'catalog.category',
        'cta_params' => ['category' => 'shirinlik-toplamlari'],
    ],

    'branches' => [
        [
            'id' => 1,
            'name' => 'Bodomgul Yunusobod',
            'district' => 'Yunusobod tumani',
            'address' => 'Amir Temur shoh ko‘chasi 108',
            'hours' => 'Har kuni, 08:00 — 22:00',
            'phone' => '+998 78 113 40 40',
        ],
        [
            'id' => 2,
            'name' => 'Bodomgul Chilonzor',
            'district' => 'Chilonzor tumani',
            'address' => 'Bunyodkor shoh ko‘chasi 42',
            'hours' => 'Har kuni, 08:00 — 23:00',
            'phone' => '+998 78 113 40 40',
        ],
        [
            'id' => 3,
            'name' => 'Bodomgul Mirzo Ulug‘bek',
            'district' => 'Mirzo Ulug‘bek tumani',
            'address' => 'Universitet ko‘chasi 15',
            'hours' => 'Har kuni, 08:00 — 22:00',
            'phone' => '+998 78 113 40 40',
        ],
        [
            'id' => 4,
            'name' => 'Bodomgul Shayxontohur',
            'district' => 'Shayxontohur tumani',
            'address' => 'Zarqaynar ko‘chasi 7',
            'hours' => 'Har kuni, 09:00 — 22:00',
            'phone' => '+998 78 113 40 40',
        ],
        [
            'id' => 5,
            'name' => 'Bodomgul Yakkasaroy',
            'district' => 'Yakkasaroy tumani',
            'address' => 'Bobur ko‘chasi 26',
            'hours' => 'Har kuni, 08:00 — 21:00',
            'phone' => '+998 78 113 40 40',
        ],
    ],

    'news' => [
        [
            'slug' => 'kuzgi-tamlar-kolleksiyasi',
            'title' => 'Kuzgi ta’mlar kolleksiyasi taqdim etildi',
            'date' => '2026-10-02',
            'tag' => 'Yangilik',
            'image' => 'images/news/news-1.svg',
            'excerpt' => 'Bodom, dolchin va malina — kuzgi kolleksiyaning uch asosiy ta’mi.',
            'body' => [
                'Kuz kolleksiyamizda issiq va uy ta’mini beruvchi uch yo‘nalish birlashtirildi: qovurilgan bodom, dolchin va yangi malina. Har bir mahsulot shu ta’mlar atrofida qayta ishlab chiqildi.',
                'Kolleksiyaga bodomli tort, bodomli kruassan, kanel va bodomli latte kabi sevimlilar kirdi. Shirinlik to‘plamlari ham kuzgi bezaklar bilan yangilandi.',
                'Bu sahifadagi barcha ma’lumotlar portfolio demosi uchun tayyorlangan namunaviy yangilik hisoblanadi.',
            ],
        ],
        [
            'slug' => 'bodomli-kruassan-har-kuni',
            'title' => 'Bodomli kruassan endi har kuni',
            'date' => '2026-09-18',
            'tag' => 'Yangilik',
            'image' => 'images/news/news-4.svg',
            'excerpt' => 'Sevimli bodomli kruassan kundalik menyudan doimiy joy oldi.',
            'body' => [
                'Mijozlarimizning eng ko‘p so‘ragan mahsulotlaridan biri — bodomli kruassan endi har kuni peshtaxtada. Xamir har kuni ertalab yopiladi va mahalliy pishiriladi.',
                'Kruassan bodom kremi bilan to‘ldirilgan, ustiga qovurilgan bodom bo‘laklari sepilgan. Issiq holatda ta’mi eng yaxshi ochiladi.',
                'Demo sayt bo‘lgani uchun bu yangilik ham namunaviy matn asosida tayyorlangan.',
            ],
        ],
        [
            'slug' => 'sovga-toplamlari-yangilandi',
            'title' => 'Sovg‘a to‘plamlari yangilandi',
            'date' => '2026-09-05',
            'tag' => 'Aksiya',
            'image' => 'images/news/news-3.svg',
            'excerpt' => 'Bayram va minnatdorchilik uchun yangi assorti qutilar tayyor.',
            'body' => [
                'Sovg‘a to‘plamlari yangi dizayndagi qutilarga o‘tkazildi. Endi to‘plamlar tarkibini makaron, pechenye va shokoladli assortidan tanlash mumkin.',
                'Har bir quti lenta bilan bezatiladi va sovg‘a kartochkasi qo‘shiladi. Katta buyurtmalar uchun alohida variantlar ham mavjud.',
                'Eslatma: bu sahifadagi aksiya va narxlar portfolio demo maqsadida ko‘rsatilgan.',
            ],
        ],
        [
            'slug' => 'ichimliklar-menyusi-yangilandi',
            'title' => 'Ichimliklar menyusiga bodomli latte qo‘shildi',
            'date' => '2026-08-22',
            'tag' => 'Yangilik',
            'image' => 'images/news/news-2.svg',
            'excerpt' => 'Espresso, issiq sut va bodom siropi — yangi sevimli ichimlik.',
            'body' => [
                'Menyuga bodom siropi bilan tayyorlanadigan latte qo‘shildi. U espresso, issiq sut va yengil bodom ta’mini birlashtiradi.',
                'Ichimlik issiq va muzli variantda tayyorlanadi. Ustiga mayda bodom bo‘laklari sepiladi.',
                'Menyu va narxlar demo ma’lumot sifatida keltirilgan.',
            ],
        ],
    ],
];
