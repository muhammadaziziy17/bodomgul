# Bodomgul Bakery — Project Plan

> **Loyiha turi:** portfolio uchun ko‘p sahifali bakery / qandolat mahsulotlari sayti  
> **Brend nomi:** Bodomgul  
> **Texnologiyalar:** Laravel + Blade + HTML5 + CSS3 + Vanilla JavaScript  
> **Hosting:** InfinityFree — bepul tarif  
> **Asosiy vizual reference:** https://safiabakery.uz/uz  
> **Muhim:** Bodomgul — mustaqil portfolio brendi. Reference sayt dizayn yo‘nalishini o‘rganish uchun ishlatiladi; Safia nomi, logotipi, original matnlari, fotosuratlari yoki boshqa himoyalangan materiallari ruxsatsiz ko‘chirilmaydi.

---

## 1. Loyiha maqsadi

Bodomgul nomi ostida zamonaviy, chiroyli va mobil qurilmalarga mos bakery saytini yaratish. Sayt Safia Bakery reference’ida kuzatilgan umumiy sahifa tuzilishi va foydalanuvchi tajribasidan ilhomlanadi, lekin o‘ziga xos brend ranglari, matnlari, rasmlari va UI elementlariga ega bo‘ladi.

Portfolio versiyasida foydalanuvchi bosh sahifani ko‘rishi, katalogni ochishi, mahsulotlarni qidirishi va saralashi, mahsulot tafsilotlarini ko‘rishi, mahsulotlarni savatcha yoki sevimlilarga qo‘shishi, filiallar va aloqa sahifalariga o‘tishi mumkin.

**Asosiy texnik qaror:** sahifalar Laravel Blade orqali serverda render qilinadi. Frontend uchun alohida React/Vue ilovasi yoki Vite/Tailwind build pipeline ishlatilmaydi. Layout HTML + maxsus CSS bilan yoziladi; zarur bo‘lgan kutubxonalar CDN orqali ulanadi.

## 2. Texnologiyalar va cheklovlar

| Qism | Tanlov | Izoh |
|---|---|---|
| Backend | Laravel | Route, controller, Blade va kerak bo‘lsa server-side validatsiya |
| Template engine | Blade | `layouts`, `partials`, `components` va sahifa view’lari aniq ajratiladi |
| Markup | HTML5 | Semantik va accessibility talablariga mos |
| Styling | Custom CSS3 | Asosiy dizayn qo‘lda yoziladi; CSS fayllari `public/css/` ichida |
| Interaktivlik | Vanilla JavaScript | Menyu, savatcha, sevimlilar, katalog filtrlari va boshqa sodda funksiyalar |
| Carousel | Swiper.js CDN | Faqat hero yoki kerakli slider uchun |
| Ikonlar | Bootstrap Icons CDN | Bir xil ikon tizimi; ikonlar ishlamasa matnli fallback bo‘lishi kerak |
| Xarita | Leaflet CDN — ixtiyoriy | Filiallar xaritasi haqiqatan kerak bo‘lsa; aks holda tashqi xarita linklari |
| Shrift | Google Fonts yoki system font stack | Tashqi shrift yuklanmasa ham layout buzilmasin |
| Ma’lumotlar | Dastlab PHP demo-data fayli | MVP uchun database majburiy emas; keyin MySQL qo‘shish mumkin |
| Hosting | InfinityFree bepul tarif | PHP versiyasi, fayl soni va server imkoniyatlari hisobga olinadi |

### Qat’iy texnik qoidalar

- React, Next.js, Vue, TypeScript yoki SPA arxitekturasi ishlatilmaydi.
- Tailwind CSS, Sass build yoki Vite talab qilinmaydi. Oddiy CSS bevosita brauzerda ishlashi kerak.
- `npm run build` talab qiladigan frontend pipeline loyihaning majburiy qismi bo‘lmaydi.
- `node_modules/` serverga yuklanmaydi.
- jQuery kabi katta kutubxonalar zaruratsiz ulanmaydi. Kichik funksiyalar Vanilla JavaScript bilan qilinadi.
- CDN kutubxonalari uchun aniq versiya pin qilinadi; butun kutubxonani faqat kerakli funksiyasi uchun ishlatishdan qochiladi.
- Kod lokal kompyuterda tayyorlanadi va tekshiriladi. InfinityFree’da SSH/terminal orqali `php artisan` ishga tushirish mumkin deb hisoblanmaydi.
- Laravel versiyasi loyiha boshlanishida InfinityFree boshqaruv panelida mavjud PHP versiyasiga mos tanlanadi.

## 3. InfinityFree bepul hosting uchun muhim eslatmalar

InfinityFree’da Laravel ishlatishdan oldin hosting cheklovlarini hisobga olish kerak. Rasmiy forum yo‘riqnomasiga ko‘ra, web-root odatda `htdocs` bo‘ladi; uni Laravel kutadigan `public/` katalogiga o‘zgartirib bo‘lmaydi. Shuning uchun routing va maxfiy fayllar himoyasi alohida sinovdan o‘tkazilishi kerak. Shuningdek, serverda Composer, Node.js va Artisan buyruqlariga tayanib bo‘lmaydi. Manba: [InfinityFree — How to install a Laravel site](https://forum.infinityfree.com/t/how-to-install-a-laravel-site-on-infinityfree/118578).

- Laravel dependency’lari lokal kompyuterda Composer orqali o‘rnatiladi.
- Hostingga yuklashdan oldin production uchun kerakli `vendor/` katalogi tayyorlanadi.
- Fayllar FTP yoki InfinityFree File Manager orqali yuklanadi.
- `htdocs/.htaccess` yordamida request’lar `public/` katalogi orqali ishlashi sozlanadi; Laravel yaratgan `public/.htaccess` fayli saqlanadi.
- `.env`, `vendor`, `app`, `storage`, `routes` va boshqa ichki kataloglar URL orqali ochilmasligi sinovdan o‘tkaziladi.
- `APP_DEBUG=false` va to‘g‘ri `APP_URL` ishlatiladi. Debug xatolari ommaga ko‘rinadigan holatda qoldirilmaydi.
- Sessiya va cache uchun tanlangan Laravel versiyasiga mos file-based driver ishlatiladi. Redis, queue worker, cron yoki WebSocket talab qiladigan funksiya MVP’ga kiritilmaydi.
- Mahsulot rasmlari `public/images/` ichida saqlanadi. MVP’da foydalanuvchi fayl yuklashi bo‘lmaydi, shuning uchun `storage:link` talab qilinmaydi.
- Laravel uchun juda ko‘p alohida fayl yuklash kerak bo‘lishi mumkin. Keraksiz dependency’lar, test paketlari, `.git/`, `node_modules/` va lokal cache fayllari serverga yuborilmaydi.
- Agar keyinchalik database kerak bo‘lsa, migratsiya va seed lokal muhitda bajariladi; SQL dump phpMyAdmin orqali import qilinadi. Serverda `php artisan migrate` bajarilishiga tayanilmaydi.

**Muhim xavfsizlik tekshiruvi:** deploy’dan keyin brauzerda `/.env`, `/vendor/`, `/storage/logs/` va `/app/` kabi yo‘llarni tekshiring. Hech biri maxfiy kod yoki konfiguratsiyani ko‘rsatmasligi kerak. Xavfsizlikni faqat `.htaccess` borligi bilan tasdiqlab qo‘ymang.

## 4. Bodomgul brendining dizayn yo‘nalishi

Bodomgul uchun iliq, zamonaviy va mustaqil vizual uslub yaratiladi. Qandolat mahsulotlariga mos yumshoq ranglar, mahsulot fotografiyasi, toza grid va yengil bezaklar ishlatiladi. Reference saytning umumiy tartibi o‘rganilishi mumkin, ammo ranglar, logotip, yozuvlar va kompozitsiyalar birma-bir ko‘chirilmaydi.

### Boshlang‘ich ranglar

```css
:root {
  --color-bg: #FFFBF6;
  --color-surface: #FFFFFF;
  --color-text: #34241F;
  --color-muted: #786F69;
  --color-brand: #7E2433;
  --color-brand-dark: #591824;
  --color-rose: #DDA1A3;
  --color-almond: #E8D8C6;
  --color-border: #E9DED5;
  --color-success: #356B4B;
  --container-width: 1200px;
  --radius-card: 18px;
  --radius-button: 999px;
  --shadow-card: 0 8px 28px rgba(52, 36, 31, 0.08);
}
```

Bu qiymatlar Bodomgul uchun taklif etilgan start palitrasi, rasmiy boshqa brend ranglari emas. Dastlabki sahifalar tayyor bo‘lgach, kontrast va vizual muvozanat tekshiriladi.

### Tipografiya va shakllar

- Sarlavha: o‘qilishi oson serif yoki yumshoq ko‘rinishdagi display shrift.
- Asosiy matn: sodda sans-serif.
- Kartalar: bir xil rasm nisbati, yetarli ichki bo‘shliq va yengil border/shadow.
- Tugmalar: aniq CTA, yaxshi kontrast va bir xil hover/focus holatlari.
- Dekorativ bodom guli yoki bodom shakli ixtiyoriy brend elementi sifatida ishlatilishi mumkin.
- Animatsiyalar qisqa va nozik bo‘lsin; sahifaning tezligi yoki foydalanish qulayligiga xalaqit bermasin.

## 5. Sahifalar va Laravel marshrutlari

MVP sahifalari quyidagicha bo‘ladi. Route nomlari Blade fayllarida `route()` yordamida chaqiriladi; URL’lar view fayl yo‘llariga bevosita bog‘lanmaydi.

| URL | Route nomi | Sahifa | Asosiy tarkib |
|---|---|---|---|
| `/` | `home` | Bosh sahifa | Header, hero, kategoriyalar, tavsiya etilgan mahsulotlar, promo, yangiliklar, brend hikoyasi, filial CTA va footer |
| `/catalog` | `catalog.index` | Katalog | Kategoriyalar, qidiruv, filtrlar, saralash va mahsulot grid’i |
| `/catalog/{category}` | `catalog.category` | Kategoriya | Breadcrumb, kategoriya tavsifi va shu kategoriyadagi mahsulotlar |
| `/products/{slug}` | `products.show` | Mahsulot tafsiloti | Rasm, nom, narx, tavsif, miqdor, savatcha amali va o‘xshash mahsulotlar |
| `/branches` | `branches.index` | Filiallar | Qidiruv, filial kartalari, ish vaqti, tashqi xarita havolasi yoki ixtiyoriy xarita |
| `/about` | `about` | Biz haqimizda | Bodomgul brendi hikoyasi, qadriyatlari va rasmlar |
| `/contacts` | `contacts` | Kontaktlar | Demo kontakt ma’lumoti, ish vaqti va aloqa formasi |
| `/news` | `news.index` | Yangiliklar | Yangilik/aksiya kartalari |
| `/news/{slug}` | `news.show` | Yangilik tafsiloti | Sarlavha, sana, cover va matn |
| `/cart` | `cart.index` | Savatcha | LocalStorage’dan savatcha elementlari, miqdor va umumiy summa |
| `/favorites` | `favorites.index` | Sevimlilar | Foydalanuvchi brauzerida saqlangan sevimli mahsulotlar |

### Qo‘shimcha sahifalar — vaqt qolsa

- FAQ / savol-javob.
- Portfolio loyiha haqida texnik sahifa.
- Demo sodiqlik dasturi sahifasi.

MVP’da real to‘lov, mijoz akkaunti, SMS, yetkazib berish integratsiyasi yoki haqiqiy buyurtma yuborish bo‘lmaydi. Savatcha va sevimlilar portfolio demo sifatida ishlaydi. Tayyor bo‘lmagan sahifalarga olib boruvchi linklar qoldirilmaydi.

## 6. Blade view fayllari tuzilmasi

Blade fayllari tartibli va qayta ishlatiladigan bo‘lishi shart. Har bir sahifa o‘zining view fayliga, umumiy elementlar esa `layouts`, `partials` va `components` kataloglariga ajratiladi.

```text
resources/
└── views/
    ├── layouts/
    │   └── app.blade.php
    ├── partials/
    │   ├── announcement-bar.blade.php
    │   ├── header.blade.php
    │   ├── desktop-navigation.blade.php
    │   ├── mobile-navigation.blade.php
    │   ├── footer.blade.php
    │   └── cookie-note.blade.php          # kerak bo‘lsa
    ├── components/
    │   ├── product-card.blade.php
    │   ├── category-card.blade.php
    │   ├── news-card.blade.php
    │   ├── branch-card.blade.php
    │   ├── breadcrumb.blade.php
    │   ├── section-title.blade.php
    │   ├── button.blade.php
    │   └── empty-state.blade.php
    ├── home/
    │   └── index.blade.php
    ├── catalog/
    │   ├── index.blade.php
    │   └── category.blade.php
    ├── products/
    │   └── show.blade.php
    ├── branches/
    │   └── index.blade.php
    ├── about/
    │   └── index.blade.php
    ├── contacts/
    │   └── index.blade.php
    ├── news/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── cart/
    │   └── index.blade.php
    ├── favorites/
    │   └── index.blade.php
    └── errors/
        ├── 404.blade.php
        └── 500.blade.php
```

### Blade qoidalari

- `layouts/app.blade.php` umumiy `<html>`, `<head>`, CDN linklari, CSS fayllari, header, `@yield('content')` va footer’ni boshqaradi.
- Sahifalar `@extends('layouts.app')` va `@section('content')` orqali layout’ni ishlatadi.
- Takrorlanadigan elementlar `@include(...)` yoki Blade component (`<x-product-card />`) orqali chaqiriladi.
- URL’lar `href="{{ route('catalog.index') }}"` kabi named route bilan tuziladi.
- Kontent chiqarishda kerak bo‘lsa `{{ }}` ishlatiladi; foydalanuvchi kiritgan ma’lumotni ` {!! !!}` bilan escaping’siz chiqarmaslik kerak.
- Formlarda `@csrf` qo‘llanadi. Haqiqiy POST route bo‘lsa, backend validatsiyasi qilinadi.
- Blade fayllariga katta CSS/JS bloklarini joylash o‘rniga alohida `public/css/` va `public/js/` fayllari ishlatiladi.
- Sahifaga xos kichik resurslar kerak bo‘lsa, layout’da `@stack('styles')` va `@stack('scripts')` ishlatilishi mumkin.

## 7. Laravel backend tuzilmasi

```text
app/
├── Http/
│   └── Controllers/
│       ├── HomeController.php
│       ├── CatalogController.php
│       ├── ProductController.php
│       ├── BranchController.php
│       ├── AboutController.php
│       ├── ContactController.php
│       └── NewsController.php
└── Support/
    └── DemoContent.php                 # ixtiyoriy helper/repository

config/
└── bodomgul.php                        # MVP demo kontenti

routes/
└── web.php

public/
├── css/
│   ├── variables.css
│   ├── base.css
│   ├── layout.css
│   ├── components.css
│   ├── pages.css
│   └── responsive.css
├── js/
│   ├── app.js
│   ├── hero-slider.js
│   ├── catalog.js
│   ├── cart.js
│   ├── favorites.js
│   └── branches.js
├── images/
│   ├── hero/
│   ├── products/
│   ├── categories/
│   ├── news/
│   ├── brand/
│   └── placeholders/
└── favicon.ico
```

Bu MVP uchun tavsiya etilgan tuzilma. Keraksiz klass, controller yoki alohida fayllar faqat papkani to‘ldirish uchun yaratilmaydi; yangi funksiya paydo bo‘lsa, tegishli fayl qo‘shiladi.

## 8. Demo ma’lumotlarini tashkil qilish

### MVP varianti: database’siz

InfinityFree’da deploy qilishni yengillashtirish uchun mahsulot, kategoriya, filial va yangilik ma’lumotlari `config/bodomgul.php` yoki aniq ajratilgan PHP demo-data klassida saqlanishi mumkin. Controller ma’lumotni olib, Blade view’ga uzatadi. View ichida bir necha sahifaga takrorlangan mahsulot ma’lumotlarini alohida hardcode qilib yuborilmaydi.

Namuna sifatidagi data modeli:

```php
return [
    'categories' => [
        // ['name' => 'Tortlar', 'slug' => 'tortlar', 'image' => 'images/categories/cakes.jpg'],
    ],
    'products' => [
        // [
        //   'id' => 1,
        //   'name' => 'Bodomli tort',
        //   'slug' => 'bodomli-tort',
        //   'category' => 'tortlar',
        //   'price' => 85000,
        //   'description' => 'Demo tavsif...',
        //   'image' => 'images/products/almond-cake.jpg',
        //   'featured' => true,
        // ],
    ],
    'branches' => [],
    'news' => [],
];
```

Narxlar bitta formatda (butun son, so‘m) saqlansin va barcha sahifalarda yagona helper yoki bir xil formatlash usuli bilan chiqarilsin. Rasmlar `asset('images/...')` orqali chaqiriladi.

### Keyinchalik MySQL qo‘shilsa

Agar portfolio’da to‘liq CRUD yoki admin panel kerak bo‘lsa, keyingi bosqichda `Product`, `Category`, `Branch`, `News` modellari, migration va seed’lar qo‘shish mumkin. Bunda migration va seed lokalda bajariladi, ma’lumotlar bazasi SQL dump holatida eksport qilinib, InfinityFree phpMyAdmin orqali import qilinadi. Bu MVP uchun majburiy emas.

## 9. Global layout

### Header

- Bodomgul wordmark yoki original logotip.
- Desktop navigatsiya: Bosh sahifa, Katalog, Filiallar, Biz haqimizda, Yangiliklar, Kontaktlar.
- Qidiruv, sevimlilar va savatcha ikonlari.
- Mobil uchun ochiladigan menu/drawer.
- Savatchadagi mahsulotlar soni ko‘rsatkichi.
- Aktiv sahifa, hover va klaviatura focus holatlari farqlanadi.

### Footer

- Bodomgul haqida qisqa matn.
- Katalog va foydali havolalar.
- Portfolio uchun demo kontakt va ish vaqti; tekshirilmagan ma’lumot rasmiy faktdek berilmaydi.
- Ijtimoiy tarmoq havolalari faqat mavjud bo‘lsa.
- Copyright va “Mustaqil portfolio demo” izohi.

### Umumiy UI elementlar

- Breadcrumb.
- Page container va section title.
- Product card va category card.
- News card va branch card.
- Empty state, form error, success toast.
- Loading holatidan ko‘ra oddiy statik kontent afzal; kerak bo‘lmasa keraksiz skeleton qo‘shilmaydi.

## 10. Bosh sahifa tarkibi

Tavsiya etilgan bo‘limlar ketma-ketligi:

1. **Announcement bar** — mavsumiy taklif yoki qisqa xabar.
2. **Hero slider** — 2–3 ta Bodomgul banneri, sarlavha, subtitle va CTA.
3. **Kategoriya shortcuts** — tortlar, pishiriqlar, kruassanlar, pechenye, shirinlik to‘plamlari va ichimliklar kabi demo kategoriyalar.
4. **Tavsiya etilgan mahsulotlar** — 4–8 ta product card.
5. **Promo/seasonal banner** — mavsumiy kolleksiya uchun alohida vizual blok.
6. **Yangiliklar va aksiyalar** — 3 ta news card.
7. **Bodomgul hikoyasi** — brendning mustaqil hikoyasi va rasm.
8. **Filial topish CTA** — filiallar sahifasiga o‘tish.
9. **Footer**.

Mobil ko‘rinishda bo‘limlar bir ustunga tushadi yoki kichik grid’dan foydalanadi. Hero matni rasm ustida o‘qiladigan bo‘lsin; asosiy CTA birinchi ekranda topilishi oson bo‘lsin.

## 11. Katalog va mahsulotlar

### Katalog sahifasi

- Breadcrumb va sahifa sarlavhasi.
- Kategoriya nav yoki chiplar.
- Mahsulot nomi bo‘yicha qidiruv.
- Narx bo‘yicha saralash: arzonidan qimmatiga va qimmatidan arzoniga.
- Kategoriya bo‘yicha filter.
- Mahsulot grid’i.
- Natija bo‘lmaganda EmptyState va filtrlarni tozalash amali.

MVP’da filterlash va qidiruv Vanilla JavaScript bilan brauzerda ishlaydi. Mahsulotlar serverdan Blade orqali chiqariladi va JS uchun kerakli `data-*` atributlari beriladi. Filtr UI’si JavaScript o‘chiq bo‘lsa ham sahifa kontentini butunlay yashirib qo‘ymasligi kerak.

### Product card

- Bodomgul’ga mos, huquqi bor mahsulot rasmi.
- Mahsulot nomi va narxi.
- Kategoriya yoki vazn kabi ixtiyoriy qisqa ma’lumot.
- Mahsulot tafsilotiga o‘tish.
- Savatchaga qo‘shish tugmasi.
- Sevimliga belgilash tugmasi.
- Rasmlar uchun bir xil aspect ratio va `alt` matn.

### Mahsulot tafsiloti

- Breadcrumb.
- Asosiy rasm va zarur bo‘lsa gallery.
- Nomi, narxi, tavsifi va mavjudligi.
- Miqdor tanlash.
- Savatchaga qo‘shish va sevimliga belgilash.
- O‘xshash mahsulotlar.

## 12. Savatcha va sevimlilar

- Savatcha va sevimlilar dastlab `localStorage` orqali foydalanuvchining o‘z brauzerida saqlanadi.
- Header counter sahifani yangilagandan keyin ham to‘g‘ri ko‘rinadi.
- Savatchada mahsulot miqdorini oshirish/kamaytirish va olib tashlash mumkin.
- Umumiy summa narx × miqdor asosida hisoblanadi.
- LocalStorage ichidagi ma’lumot buzilgan yoki noto‘g‘ri formatda bo‘lsa, sahifa crash qilmasdan savatchani xavfsiz default holatga qaytaradi.
- Bu funksiya haqiqiy server buyurtmasi yoki to‘lov emas. Checkout/payment forma MVP’da yo‘q.
- Mahsulot narxi JavaScript’da hisoblanganda ko‘rsatiladigan summa uchun data manbasi serverdagi demo ma’lumot bilan mos bo‘lishi kerak.

## 13. Filiallar sahifasi

- Filial nomi yoki manzili bo‘yicha qidiruv.
- Har filial uchun nom, manzil, ish vaqti va ixtiyoriy telefon.
- “Xaritada ko‘rish” tashqi linki.
- Interaktiv xarita kerak bo‘lsa Leaflet CDN va mos tile xizmatidan foydalanish mumkin; xarita yuklanmasa ham manzil va havolalar mavjud bo‘lsin.
- Filial ma’lumotlari demo bo‘lsa, ularni haqiqiy Bodomgul filiali deb noto‘g‘ri ko‘rsatmaslik kerak.
- Geolocation ruxsati avtomatik so‘ralmaydi.

## 14. Kontakt formasi

- Ism, aloqa usuli va xabar maydonlari.
- HTML `required`, input type va kerakli Vanilla JS validatsiyasi.
- Agar forma Laravel controller’ga yuborilsa, `@csrf` va server-side validation ishlatiladi.
- SMTP yoki tashqi mail service o‘rnatilmagan bo‘lsa, “Xabaringiz yuborildi” deb yolg‘on success ko‘rsatmaslik kerak. Portfolio rejimida “Demo forma — xabar haqiqiy yuborilmaydi” holati aniq ko‘rsatiladi.
- MVP’da foydalanuvchi kiritgan shaxsiy ma’lumotlar bazaga saqlanmaydi.

## 15. CSS fayllarini tashkil qilish

`public/css/` ichida CSS maqsad bo‘yicha ajratiladi:

- `variables.css` — ranglar, spacing, radius, shadow, container tokenlari.
- `base.css` — reset, body, typography, link va umumiy elementlar.
- `layout.css` — header, footer, container va grid/flex layout.
- `components.css` — button, card, input, breadcrumb, badge va toast.
- `pages.css` — hero, home section, catalog va product sahifalariga xos uslublar.
- `responsive.css` — breakpoint’lar va mobil moslashuv.

`layouts/app.blade.php` faylida bu CSS’lar `asset('css/variables.css')` kabi Laravel helper orqali ulanadi. Kichik loyiha uchun fayllarni keyinchalik birlashtirish mumkin, ammo buning uchun build tool talab qilinmaydi.

### Responsive breakpoint’lar

```css
/* Mobile-first yondashuv */
/* 576px, 768px, 992px, 1200px atrofida moslashuvlar */
```

- Mobile: 360–767px, mobil menu, ixcham hero, kartalar 1–2 ustun.
- Tablet: 768–991px, mos grid va navigatsiya.
- Desktop: 992px+, to‘liq header va 3–4 ustunli mahsulot grid’i.
- 320–360px ekranlarda ham asosiy kontent gorizontal scroll chiqarmasligi kerak.

## 16. JavaScript fayllari va CDN qoidalari

- `app.js` — mobil menu, umumiy UI va kichik yordamchi funksiyalar.
- `hero-slider.js` — Swiper init; faqat slider bor sahifada yuklanadi.
- `catalog.js` — qidiruv, kategoriya filtri va saralash.
- `cart.js` — savatcha state’i, localStorage va hisob-kitob.
- `favorites.js` — sevimlilar state’i.
- `branches.js` — filiallar qidiruvi va kerak bo‘lsa xarita init.

CDN kutubxonalarini `layouts/app.blade.php` da yoki tegishli sahifaning script stack’ida ulang. Kutubxona ulanmagan holatda ham asosiy kontent o‘qiladigan bo‘lsin. Har bir sahifaga barcha kutubxonalarni avtomatik yuklamang — faqat kerakli sahifada qo‘llang.

## 17. Rasmlar va boshqa assetlar

```text
public/images/
├── hero/
├── products/
├── categories/
├── news/
├── brand/
└── placeholders/
```

- O‘zingiz tayyorlagan, ruxsat bilan olingan yoki litsenziyasi mos rasmlardan foydalaning.
- Safia original fotosuratlarini ruxsatsiz yuklab olish, hotlink qilish yoki Bodomgul kontenti sifatida ko‘rsatish rejalashtirilmaydi.
- Rasm fayllari web uchun optimallashtiriladi; kerak bo‘lsa WebP formatiga o‘tkaziladi.
- Har bir mazmunli rasmda aniq `alt` matn bo‘ladi; dekorativ rasm uchun bo‘sh `alt` mumkin.
- Rasm yuklanmasa, placeholder ko‘rinishi kerak.
- O‘zingiz yaratgan Bodomgul wordmark, favicon va bannerlar ishlatiladi.

## 18. Ish bosqichlari

### 0-bosqich — hosting va loyiha bazasi

- [ ] InfinityFree hisobida tanlangan PHP versiyasini tekshirish.
- [ ] Shu PHP versiyasiga mos Laravel release tanlash.
- [ ] Laravel’ni lokal kompyuterda Composer orqali yaratish/o‘rnatish.
- [ ] `php artisan` buyruqlarini faqat lokal development muhitida ishlatish.
- [ ] Bodomgul nomi, rang palitrasi, font va asset yo‘nalishini belgilash.
- [ ] Reference’dagi sahifa strukturasini ko‘rib, mustaqil layout bo‘yicha qaror qilish.

**Natija:** Laravel lokalda ochiladi, loyiha arxitekturasi va hosting yo‘li ma’lum.

### 1-bosqich — routing va Blade layout

- [ ] `routes/web.php` da MVP route’larini yaratish.
- [ ] `layouts/app.blade.php` yozish.
- [ ] Header, desktop/mobile navigation va footer’ni partial qilib ajratish.
- [ ] CSS fayllari va kerakli CDN’larni ulash.
- [ ] Har bir sahifa uchun oddiy view skeleton yaratish.
- [ ] Named route va barcha navigatsiya linklarini tekshirish.

**Natija:** barcha asosiy URL’lar ochiladi va bir xil umumiy layout’ni ishlatadi.

### 2-bosqich — Bodomgul bosh sahifasi

- [ ] Hero slider.
- [ ] Kategoriya card’lari.
- [ ] Tavsiya etilgan mahsulotlar.
- [ ] Promo va yangiliklar sectionlari.
- [ ] Brend hikoyasi va filial CTA.
- [ ] Desktop va mobile CSS.

**Natija:** portfolio ko‘rinishining asosiy qismi tayyor.

### 3-bosqich — katalog va mahsulot tafsiloti

- [ ] PHP demo data faylini to‘ldirish.
- [ ] ProductCard va CategoryCard Blade componentlarini yaratish.
- [ ] Katalog grid’i va kategoriya sahifasi.
- [ ] Qidiruv, filter va saralash JavaScript’ini yozish.
- [ ] Mahsulot tafsilot sahifasini yaratish.

**Natija:** foydalanuvchi katalogdan mahsulot sahifasigacha o‘ta oladi.

### 4-bosqich — savatcha va sevimlilar

- [ ] LocalStorage helper’lari.
- [ ] Savatchaga qo‘shish/o‘chirish va miqdor hisoblash.
- [ ] Header counter.
- [ ] Sevimlilarga qo‘shish/o‘chirish.
- [ ] Bo‘sh savatcha va bo‘sh sevimlilar holati.

**Natija:** asosiy e-commerce demo interaktivligi ishlaydi.

### 5-bosqich — qolgan kontent sahifalari

- [ ] Filiallar qidiruvi va kartalari.
- [ ] About sahifasi.
- [ ] Contact sahifasi va demo forma holati.
- [ ] Yangiliklar ro‘yxati va tafsiloti.
- [ ] 404 sahifasi va noto‘g‘ri slug holati.

**Natija:** MVP sahifalari tugallangan.

### 6-bosqich — lokal test va InfinityFree deploy

- [ ] Linklar, forma, qidiruv, savatcha va sevimlilarni lokal test qilish.
- [ ] PHP versiyasini va Laravel compatibility’ni qayta tekshirish.
- [ ] Production uchun Composer dependency’larini tayyorlash.
- [ ] Lokal cache va development-only fayllarini tekshirish.
- [ ] FTP/File Manager orqali `htdocs` ga fayllarni yuklash.
- [ ] Root `.htaccess` va `public/.htaccess` sozlamalarini tekshirish.
- [ ] Maxfiy yo‘llar tashqaridan ochilmasligini tekshirish.
- [ ] Hosting URL’da barcha asosiy sahifalarni sinab ko‘rish.

**Natija:** bepul hostingda ishlaydigan, portfolio uchun tayyor demo.

## 19. InfinityFree deploy checklist

Bu bosqichni loyiha kodlashdan oldin ham ko‘zda tutish kerak.

- [ ] Hostingdagi PHP versiyasi va tanlangan Laravel versiyasi mos.
- [ ] `composer install --no-dev` lokalda bajarilgan; `vendor/` mavjud.
- [ ] Frontend uchun Node/Vite build yo‘q; oddiy CSS va JS fayllar tayyor.
- [ ] `node_modules/`, `.git/`, test fayllari va lokal maxfiy fayllar yuklash paketidan chiqarilgan.
- [ ] `htdocs/` da Laravel loyihasining kerakli fayllari joylashtirilgan.
- [ ] `htdocs/.htaccess` request’larni `public/` orqali yuboradi.
- [ ] Laravel’ning `public/.htaccess` fayli saqlangan.
- [ ] `.env` production sozlamalari to‘g‘ri; `APP_DEBUG=false`.
- [ ] `APP_URL` deploy qilingan domen bilan mos.
- [ ] File-based session/cache sozlamalari Laravel versiyasiga mos.
- [ ] `/.env`, `/vendor/`, `/app/` kabi manzillar maxfiy fayllarni ko‘rsatmaydi.
- [ ] Barcha CSS, JS, rasm va CDN havolalari deploy’dan keyin ishlaydi.
- [ ] 404 va server xato sahifalari maxfiy stack trace ko‘rsatmaydi.
- [ ] Agar MySQL qo‘shilgan bo‘lsa, database host va login ma’lumotlari InfinityFree panelidan olingan, SQL import muvaffaqiyatli.

### Tavsiya etiladigan upload tarkibi

```text
htdocs/
├── app/
├── bootstrap/
├── config/
├── database/                 # database ishlatilsa
├── public/
│   ├── index.php
│   ├── .htaccess             # Laravel default rewrite
│   ├── css/
│   ├── js/
│   └── images/
├── resources/
│   └── views/
├── routes/
├── storage/
├── vendor/
├── .env                      # maxfiy; URL orqali ochilmasligi shart
└── .htaccess                 # request’larni public/ ga rewrite qiladi
```

`htdocs/.htaccess` uchun InfinityFree Laravel yo‘riqnomasidagi usul asos qilib olinadi va real hostingda tekshiriladi:

```apache
RewriteEngine On
RewriteRule (.*) /public/$1 [L]
```

Bu qoida barcha routing holatlari uchun avtomatik kafolat emas. `public/.htaccess`, asset URL’lari, 404 va maxfiy yo‘llar deploy qilingandan keyin alohida test qilinadi. Foydalanuvchi fayl yuklash funksiyasi qo‘shilsa, `storage:link` InfinityFree’da ishlamasligi mumkin; bu funksiyani alohida qayta loyihalash kerak.

## 20. Responsive va accessibility talablar

- Mobile-first CSS yoziladi.
- 360px, 390px, 768px, 1024px va 1440px viewport’da tekshiriladi.
- Gorizontal scroll, kesilib qolgan matn va ustma-ust tushgan elementlar bo‘lmasin.
- Semantik `header`, `nav`, `main`, `section`, `footer` elementlari ishlatilsin.
- Har bir icon-only tugmada `aria-label` bo‘lsin.
- Focus holati klaviatura bilan ko‘rinishi shart.
- Forma input’larida label va tushunarli xato xabari bo‘lsin.
- Rang kontrasti o‘qilishi uchun yetarli bo‘lsin.
- Animatsiyalarda `prefers-reduced-motion` imkon qadar hisobga olinsin.
- Hover effektlari mobil qurilmada funksiyaning yagona usuli bo‘lmasin.

## 21. Qabul qilish mezonlari (Definition of Done)

- [ ] Bodomgul nomi va mustaqil vizual uslub barcha sahifalarda bir xil.
- [ ] MVP route’lari Laravel named route’lar bilan ishlaydi.
- [ ] Blade layout, partial va componentlar tartibli ajratilgan.
- [ ] Bosh sahifa, katalog, kategoriya, mahsulot tafsiloti, filiallar, about, contacts va news sahifalari mavjud.
- [ ] Qidiruv, filter va saralash haqiqiy ko‘rinadigan natijani o‘zgartiradi.
- [ ] Savatcha va sevimlilar localStorage’da saqlanadi.
- [ ] Miqdor va summa hisob-kitobi to‘g‘ri.
- [ ] Mobil menu ishlaydi.
- [ ] Rasm yoki CDN yuklanmasa asosiy sahifa ishlatib bo‘lmaydigan holatga kelib qolmaydi.
- [ ] Faqat ruxsatli yoki o‘zi tayyorlagan rasmlar va matnlar ishlatilgan.
- [ ] InfinityFree’ga deploy qilinganda assetlar, URL routing va `APP_DEBUG=false` tekshirilgan.
- [ ] Maxfiy fayllar ommaga ochiq emasligi sinovdan o‘tgan.
- [ ] Loyiha portfolio demo ekani aniq; real buyurtma/to‘lov yuborilayotgandek ko‘rsatilmaydi.

## 22. Yakuniy ish tartibi

Eng avval `layouts/app.blade.php`, header/footer va custom CSS bazasini tayyorlash kerak. Keyin bosh sahifadagi hero va product card’larni sifatli qilish, shu komponentlar orqali katalogni yig‘ish, mahsulot sahifasi, savatcha va sevimlilarni ulash tavsiya etiladi. Oxirida kontent sahifalari, responsive tekshiruv va InfinityFree deploy bajariladi.

**Asosiy qoida:** avval lokal muhitda ishlaydigan to‘liq Laravel loyiha tayyorlanadi; hosting esa deploy va test bosqichi sifatida ishlatiladi. InfinityFree’da server terminali bor deb hisoblab, migratsiya yoki asset build’ni hostingda bajarishga reja tuzilmaydi.

## 23. Reference va texnik manbalar

- Vizual reference: [Safia Bakery](https://safiabakery.uz/uz)
- InfinityFree’da Laravel deploy bo‘yicha yo‘riqnoma: [How to install a Laravel site on InfinityFree](https://forum.infinityfree.com/t/how-to-install-a-laravel-site-on-infinityfree/118578)

---

**Kutiladigan yakuniy natija:** Bodomgul brendi ostida Laravel Blade bilan qurilgan, HTML/CSS/Vanilla JS’dan foydalanadigan, CDN kutubxonalari bilan kengaytiriladigan, responsive va interaktiv bakery portfolio sayti. Loyiha InfinityFree bepul hosting cheklovlarini hisobga olib tayyorlanadi.
