# Bodomgul 🧁

[![Live Demo](https://img.shields.io/badge/demo-bodomgul.page.gd-7E2433)](https://bodomgul.page.gd/uz)
[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Pest](https://img.shields.io/badge/testlar-Pest-56B3B4)](https://pestphp.com)

**Bodomgul** — Laravel va Blade asosida qurilgan ko‘p tilli qandolat va novvoyxona sayti. Sayt **portfolio maqsadida** [Safia Bakery](https://safiabakery.uz/uz) saytining sahifa tuzilishi va foydalanuvchi tajribasidan ilhomlanib (klon sifatida) yasalgan.

🔗 **Jonli demo:** <https://bodomgul.page.gd/uz>

> **Muhim izoh:** Bodomgul — mustaqil portfolio loyihasi. Safia nomi, logotipi, original matnlari, fotosuratlari yoki boshqa himoyalangan materiallari ko‘chirilmagan. Barcha brend elementi, matn, rasm va ma’lumotlar Bodomgul uchun demo sifatida tayyorlangan. Savatcha, sevimlilar va aloqa formasi ham demo rejimida ishlaydi — real to‘lov, buyurtma yoki xabar yuborish yo‘q.

## Texnologiyalar

| Qism | Tanlov |
|---|---|
| Backend | Laravel 13, PHP 8.3+ |
| Templating | Blade (`layouts`, `partials`, `components`) |
| Frontend | Maxsus CSS (`public/css`) + Vanilla JavaScript (`public/js`), build talab qilinmaydi |
| Ma’lumotlar bazasi | SQLite (migratsiya va seederlar bilan) |
| Testlar | Pest |
| Kod uslubi | Laravel Pint |
| Deploy | InfinityFree bepul hosting — <https://bodomgul.page.gd/uz> |

## Asosiy imkoniyatlar

### Ommaviy sayt

- **Bosh sahifa** — hero slider, kategoriya yo‘nalishlari, tavsiya etilgan mahsulotlar, mavsumiy promo banner, yangiliklar, brend hikoyasi va filiallar CTA.
- **Katalog** — kategoriya bo‘yicha filtr, nom bo‘yicha qidiruv va narx bo‘yicha saralash (Vanilla JS).
- **Mahsulot sahifasi** — rasm, tavsif, tarkib, miqdor tanlash, savatcha va sevimlilarga qo‘shish, o‘xshash mahsulotlar.
- **Savatcha va sevimlilar** — `localStorage` asosidagi demo savatcha: miqdorni o‘zgartirish, umumiy summa hisobi va header’dagi counter.
- **Filiallar** — qidiruv, manzil, ish vaqti, telefon va tashqi xarita havolalari.
- **Yangiliklar** — ro‘yxat va tafsilot sahifalari.
- **Biz haqimizda / Karyera / Kontaktlar** — admin panel orqali boshqariladigan sahifalar; validatsiyali demo aloqa formasi.
- **Ko‘p tillilik** — `/{til}/...` URL tuzilishi (masalan, `/uz/catalog`, `/uz/news`); yangi tillar admin panel orqali qo‘shiladi.
- Responsive dizayn, 404/500 sahifalar, SEO-friendly slug’lar va throttle bilan himoyalangan formalar.

### Admin panel — `/admin`

Sayt kontentini to‘liq brauzerdan boshqarish mumkin:

- **Login** — `throttle` bilan himoyalangan avtorizatsiya.
- **Dashboard** — mahsulot, kategoriya, yangilik, filial va hero slaydlar statistikasi.
- **Kontent CRUD** — hero slaydlar, kategoriyalar, mahsulotlar, yangiliklar va filiallar: rasm yuklash, til tablari (ko‘p tilli maydonlar) va faollik toggle.
- **Tillar boshqaruvi** — til qo‘shish, faollashtirish va asosiy tilni tanlash.
- **UI tarjimalar** — interfeys matnlarini tahrirlash.
- **Sahifalar** — «Biz haqimizda», «Kontaktlar» va «Karyera» sahifalari matnini tahrirlash.
- **Sozlamalar** — sayt nomi, kontakt ma’lumotlari, ijtimoiy tarmoq havolalari va h.k.

**Demo kirish ma’lumotlari:**

```text
Email:  admin@bodomgul.test
Parol:  bodomgul123
```

> Ushbu qiymatlar `.env` faylidagi `ADMIN_EMAIL` va `ADMIN_PASSWORD` orqali o‘zgartiriladi. `php artisan migrate --seed` buyrug‘i admin foydalanuvchini avtomatik yaratadi.

## O‘rnatish (lokal muhit)

Talablar: PHP 8.3+, Composer.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Sayt `http://localhost:8000` da ochiladi va asosiy tilga (`/uz`) yo‘naltiradi. Frontend uchun `npm` yoki build qadami kerak emas — CSS/JS fayllari bevosita `public/` katalogidan yuklanadi.

## Testlar

```bash
php artisan test --compact
```

Feature testlar sahifalar, katalog, yangiliklar, aloqa formasi va admin panelni qamrab oladi; unit testlar narx formatlashni tekshiradi.

## Kod uslubi

```bash
vendor/bin/pint --dirty --format agent
```

## Loyiha tuzilmasi

```text
app/
├── Http/Controllers/          # Ommaviy sayt controllerlari
│   └── Admin/                 # Admin panel controllerlari
├── Http/Requests/Admin/       # Admin validatsiya so‘rovlari
├── Models/                    # Product, Category, News, Branch, HeroSlide, Page, Locale, ...
└── Support/                   # Locales, SiteSettings, ImageUploader, DemoContent, ...
config/
└── bodomgul.php               # Demo kontent va admin sozlamalari
public/
├── css/                       # Maxsus CSS (variables, base, layout, components, pages, responsive)
├── js/                        # Vanilla JS (cart, favorites, catalog, branches, hero-slider)
└── images/                    # Brend, mahsulot, kategoriya va yangilik rasmlari
resources/views/
├── layouts/, partials/, components/
├── admin/                     # Admin panel view’lari
└── home/, catalog/, products/, branches/, news/, cart/, favorites/, ...
routes/
├── web.php                    # Ommaviy sayt (/{locale}/...)
└── admin.php                  # Admin panel (/admin/...)
```

## Deploy

Loyiha InfinityFree bepul hostingga deploy qilingan: **<https://bodomgul.page.gd/uz>**

Deploy InfinityFree cheklovlarini hisobga olgan holda amalga oshiriladi: `htdocs/.htaccess` request’larni `public/` katalogiga yo‘naltiradi, `vendor/` lokalda tayyorlanadi, `APP_DEBUG=false` va maxfiy yo‘llar URL orqali ochilmasligi tekshiriladi.

## Litsenziya

Portfolio va o‘quv maqsadida tayyorlangan demo loyiha. Loyiha kodi Laravel ekotizimi qoidalariga (MIT) asoslanadi; Bodomgul brendi va kontenti esa faqat ushbu portfolio demosi uchun xizmat qiladi.
