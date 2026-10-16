<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Locale;
use App\Models\News;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use App\Models\UiTranslation;
use App\Support\Locales;
use App\Support\SiteSettings;
use App\Support\Ui;
use Illuminate\Database\Seeder;

/**
 * Inglizcha va ruscha tarjimalar.
 *
 * Saytdagi barcha kontent (kategoriya, mahsulot, yangilik, slayd, filial,
 * sahifa, sozlamalar) va interfeys matnlari shu yerda uch tilda
 * to'ldiriladi. Seeder idempotent: qayta ishga tushirilsa mavjud
 * tarjimalarni yangilaydi.
 */
class LocalizationSeeder extends Seeder
{
    public function run(): void
    {
        $this->locales();
        $this->content();
        $this->pages();
        $this->settings();
        $this->ui();

        Locales::flush();
        SiteSettings::flush();
        Ui::flush();
    }

    /**
     * Faol tillar: o'zbekcha (asosiy), inglizcha va ruscha.
     */
    private function locales(): void
    {
        $locales = [
            ['code' => 'uz', 'name' => 'O‘zbekcha', 'is_active' => true, 'is_default' => true, 'sort_order' => 1],
            ['code' => 'en', 'name' => 'English', 'is_active' => true, 'is_default' => false, 'sort_order' => 2],
            ['code' => 'ru', 'name' => 'Русский', 'is_active' => true, 'is_default' => false, 'sort_order' => 3],
        ];

        foreach ($locales as $locale) {
            Locale::query()->updateOrCreate(['code' => $locale['code']], $locale);
        }
    }

    /**
     * Kategoriya, mahsulot, yangilik, slayd va filial tarjimalari.
     */
    private function content(): void
    {
        foreach ($this->categories() as $slug => $translations) {
            Category::query()->whereSlug($slug, 'uz')->first()?->saveTranslations($translations);
        }

        foreach ($this->products() as $slug => $translations) {
            Product::query()->whereSlug($slug, 'uz')->first()?->saveTranslations($translations);
        }

        foreach ($this->news() as $slug => $translations) {
            News::query()->whereSlug($slug, 'uz')->first()?->saveTranslations($translations);
        }

        $slides = HeroSlide::query()->with('translations')->get();

        foreach ($this->heroSlides() as $uzTitle => $translations) {
            $slides->first(fn (HeroSlide $slide): bool => $slide->tr('title', 'uz') === $uzTitle)
                ?->saveTranslations($translations);
        }

        $branches = Branch::query()->with('translations')->get();

        foreach ($this->branches() as $uzName => $translations) {
            $branches->first(fn (Branch $branch): bool => $branch->tr('name', 'uz') === $uzName)
                ?->saveTranslations($translations);
        }
    }

    /**
     * Statik sahifalar: about, careers, contacts.
     */
    private function pages(): void
    {
        foreach ($this->pagesData() as $slug => $locales) {
            $page = Page::query()->firstOrNew(['slug' => $slug]);
            $page->save();

            $translations = [];

            foreach ($locales as $locale => $data) {
                $translations[$locale] = [
                    'title' => $data['title'],
                    'content' => json_encode($data['content'], JSON_UNESCAPED_UNICODE),
                ];
            }

            $page->saveTranslations($translations);
        }
    }

    /**
     * Sozlamalarning tarjima qilinadigan maydonlari (overlay).
     */
    private function settings(): void
    {
        $setting = Setting::query()->firstOrCreate(['key' => 'site'], ['value' => []]);
        $setting->saveTranslations($this->settingTranslations());
    }

    /**
     * Interfeys matnlari (ui_translations jadvali).
     */
    private function ui(): void
    {
        foreach ($this->uiTranslations() as $locale => $strings) {
            foreach ($strings as $key => $value) {
                UiTranslation::query()->updateOrCreate(
                    ['locale' => $locale, 'key' => $key],
                    ['value' => $value]
                );
            }
        }
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function categories(): array
    {
        return [
            'tortlar' => [
                'en' => [
                    'name' => 'Cakes',
                    'slug' => 'cakes',
                    'description' => 'Almond, chocolate and fruit cakes for holidays and everyday tables.',
                ],
                'ru' => [
                    'name' => 'Торты',
                    'slug' => 'torty',
                    'description' => 'Миндальные, шоколадные и фруктовые торты для праздничного и повседневного стола.',
                ],
            ],
            'pishiriqlar' => [
                'en' => [
                    'name' => 'Pastries',
                    'slug' => 'pastries',
                    'description' => 'Cinnamon rolls, eclairs and tarts — soft flavours for the tea table.',
                ],
                'ru' => [
                    'name' => 'Выпечка',
                    'slug' => 'vypechka',
                    'description' => 'Синабоны, эклеры и тарты — мягкие вкусы к чайному столу.',
                ],
            ],
            'kruassanlar' => [
                'en' => [
                    'name' => 'Croissants',
                    'slug' => 'croissants',
                    'description' => 'Crisp layered dough and buttery flavour: breakfast favourites.',
                ],
                'ru' => [
                    'name' => 'Круассаны',
                    'slug' => 'kruassany',
                    'description' => 'Хрустящее слоёное тесто и сливочный вкус — любимцы завтрака.',
                ],
            ],
            'pechenye' => [
                'en' => [
                    'name' => 'Cookies',
                    'slug' => 'cookies',
                    'description' => 'Almond and chocolate cookies — for tea and conversation.',
                ],
                'ru' => [
                    'name' => 'Печенье',
                    'slug' => 'pechenye',
                    'description' => 'Миндальное и шоколадное печенье — к чаю и беседе.',
                ],
            ],
            'shirinlik-toplamlari' => [
                'en' => [
                    'name' => 'Sweet Gift Sets',
                    'slug' => 'gift-sets',
                    'description' => 'Decorated boxes and assorted sets for gifts.',
                ],
                'ru' => [
                    'name' => 'Наборы сладостей',
                    'slug' => 'nabory-sladostey',
                    'description' => 'Украшенные коробки и ассорти-наборы для подарков.',
                ],
            ],
            'ichimliklar' => [
                'en' => [
                    'name' => 'Drinks',
                    'slug' => 'drinks',
                    'description' => 'Almond latte, hot chocolate and other hot drinks.',
                ],
                'ru' => [
                    'name' => 'Напитки',
                    'slug' => 'napitki',
                    'description' => 'Миндальный латте, горячий шоколад и другие горячие напитки.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function products(): array
    {
        return [
            'bodomli-tort' => [
                'en' => [
                    'name' => 'Almond Cake',
                    'slug' => 'almond-cake',
                    'weight' => '1 kg',
                    'description' => 'Soft almond sponge, delicate cream and a layer of roasted almonds. The warm taste of almond blossom in every slice.',
                    'composition' => 'Almonds, wheat flour, butter, eggs, cream',
                ],
                'ru' => [
                    'name' => 'Миндальный торт',
                    'slug' => 'mindalnyj-tort',
                    'weight' => '1 кг',
                    'description' => 'Мягкий миндальный бисквит, нежный сливочный крем и слой обжаренного миндаля. Тёплый вкус миндального цвета в каждом кусочке.',
                    'composition' => 'Миндаль, пшеничная мука, сливочное масло, яйца, сливочный крем',
                ],
            ],
            'shokoladli-tort' => [
                'en' => [
                    'name' => 'Chocolate Cake',
                    'slug' => 'chocolate-cake',
                    'weight' => '1 kg',
                    'description' => 'Dark chocolate sponge and a silky ganache layer. Rich and balanced for chocolate lovers.',
                    'composition' => 'Cocoa, dark chocolate, butter, eggs, cream',
                ],
                'ru' => [
                    'name' => 'Шоколадный торт',
                    'slug' => 'shokoladnyj-tort',
                    'weight' => '1 кг',
                    'description' => 'Тёмный шоколадный бисквит и шелковистый слой ганаша. Насыщенный и сбалансированный вкус для любителей шоколада.',
                    'composition' => 'Какао, тёмный шоколад, сливочное масло, яйца, сливки',
                ],
            ],
            'malinali-chizkeyk' => [
                'en' => [
                    'name' => 'Raspberry Cheesecake',
                    'slug' => 'raspberry-cheesecake',
                    'weight' => '1 slice',
                    'description' => 'A light cream-cheese cheesecake with fresh raspberry sauce. Served chilled to open up the flavour.',
                    'composition' => 'Cream cheese, raspberries, cookie base, cream',
                ],
                'ru' => [
                    'name' => 'Малиновый чизкейк',
                    'slug' => 'malinovyy-chizkejk',
                    'weight' => '1 кусок',
                    'description' => 'Лёгкий чизкейк на основе сливочного сыра и соус из свежей малины. В охлаждённом виде вкус раскрывается полнее.',
                    'composition' => 'Сливочный сыр, малина, основа из печенья, сливки',
                ],
            ],
            'qulupnayli-kapkeyk' => [
                'en' => [
                    'name' => 'Strawberry Cupcake',
                    'slug' => 'strawberry-cupcake',
                    'weight' => '1 pc',
                    'description' => 'Airy sponge, cream frosting and fresh strawberry. Turns any day into a little celebration.',
                    'composition' => 'Strawberries, buttercream, wheat flour, eggs',
                ],
                'ru' => [
                    'name' => 'Клубничный капкейк',
                    'slug' => 'klubnichnyj-kapkejk',
                    'weight' => '1 шт.',
                    'description' => 'Воздушный бисквит, сливочный крем и свежая клубника. Превращает любой день в маленький праздник.',
                    'composition' => 'Клубника, сливочный крем, пшеничная мука, яйца',
                ],
            ],
            'bodomli-kruassan' => [
                'en' => [
                    'name' => 'Almond Croissant',
                    'slug' => 'almond-croissant',
                    'weight' => '1 pc',
                    'description' => 'Layered buttery dough with almond cream, topped with roasted almond flakes.',
                    'composition' => 'Butter dough, almond cream, roasted almonds',
                ],
                'ru' => [
                    'name' => 'Миндальный круассан',
                    'slug' => 'mindalnyj-kruassan',
                    'weight' => '1 шт.',
                    'description' => 'Слоёное сливочное тесто и миндальный крем. Сверху — обжаренные лепестки миндаля.',
                    'composition' => 'Сливочное тесто, миндальный крем, обжаренный миндаль',
                ],
            ],
            'shokoladli-kruassan' => [
                'en' => [
                    'name' => 'Chocolate Croissant',
                    'slug' => 'chocolate-croissant',
                    'weight' => '1 pc',
                    'description' => 'Crisp croissant coated with dark chocolate. A great choice for breakfast.',
                    'composition' => 'Butter dough, dark chocolate',
                ],
                'ru' => [
                    'name' => 'Шоколадный круассан',
                    'slug' => 'shokoladnyj-kruassan',
                    'weight' => '1 шт.',
                    'description' => 'Хрустящий круассан с тёмным шоколадом. Отличный выбор для завтрака.',
                    'composition' => 'Сливочное тесто, тёмный шоколад',
                ],
            ],
            'sariyogli-kruassan' => [
                'en' => [
                    'name' => 'Butter Croissant',
                    'slug' => 'butter-croissant',
                    'weight' => '1 pc',
                    'description' => 'The classic French croissant: crisp outside, soft and buttery inside.',
                    'composition' => 'Butter laminated dough',
                ],
                'ru' => [
                    'name' => 'Сливочный круассан',
                    'slug' => 'slivochnyj-kruassan',
                    'weight' => '1 шт.',
                    'description' => 'Классический французский круассан: хрустящий снаружи, мягкий и сливочный внутри.',
                    'composition' => 'Слоёное сливочное тесто',
                ],
            ],
            'bodomli-pechenye' => [
                'en' => [
                    'name' => 'Almond Cookies',
                    'slug' => 'almond-cookies',
                    'weight' => '500 g',
                    'description' => 'Cookies with finely ground almonds and crisp edges. Pairs perfectly with tea.',
                    'composition' => 'Almond flour, butter, sugar, wheat flour',
                ],
                'ru' => [
                    'name' => 'Миндальное печенье',
                    'slug' => 'mindalnoe-pechene',
                    'weight' => '500 г',
                    'description' => 'Печенье с мелко смолотым миндалём и хрустящими краями. Идеально к чаю.',
                    'composition' => 'Миндальная мука, сливочное масло, сахар, пшеничная мука',
                ],
            ],
            'shokolad-chipli-pechenye' => [
                'en' => [
                    'name' => 'Chocolate Chip Cookies',
                    'slug' => 'chocolate-chip-cookies',
                    'weight' => '500 g',
                    'description' => 'A soft centre with big chocolate chunks. Best enjoyed with warm milk.',
                    'composition' => 'Chocolate chunks, butter, sugar, eggs',
                ],
                'ru' => [
                    'name' => 'Печенье с шоколадом',
                    'slug' => 'pechene-s-shokoladom',
                    'weight' => '500 г',
                    'description' => 'Мягкая середина и крупные кусочки шоколада. Лучше всего — с тёплым молоком.',
                    'composition' => 'Кусочки шоколада, сливочное масло, сахар, яйца',
                ],
            ],
            'makaron-toplama' => [
                'en' => [
                    'name' => 'Macaron Set',
                    'slug' => 'macaron-set',
                    'weight' => '12 pcs',
                    'description' => 'A set of 12 delicate macarons with almond, raspberry and pistachio creams.',
                    'composition' => 'Almond, raspberry and pistachio creams, almond flour',
                ],
                'ru' => [
                    'name' => 'Набор макаронов',
                    'slug' => 'nabor-makaronov',
                    'weight' => '12 шт.',
                    'description' => 'Набор из 12 изящных макаронов с миндальным, малиновым и фисташковым кремом.',
                    'composition' => 'Миндальный, малиновый и фисташковый крем, миндальная мука',
                ],
            ],
            'bodom-gulli-shirinlik-qutisi' => [
                'en' => [
                    'name' => 'Sweet Gift Box',
                    'slug' => 'sweet-gift-box',
                    'weight' => 'Gift set',
                    'description' => 'A decorated box for the holiday table: macarons, cookies and chocolate assortment together.',
                    'composition' => 'Assorted sweets set',
                ],
                'ru' => [
                    'name' => 'Подарочная коробка',
                    'slug' => 'podarochnaya-korobka',
                    'weight' => 'Набор',
                    'description' => 'Украшенная коробка для праздничного стола: макароны, печенье и шоколадное ассорти вместе.',
                    'composition' => 'Ассорти сладостей',
                ],
            ],
            'kanel' => [
                'en' => [
                    'name' => 'Cinnamon Roll',
                    'slug' => 'cinnamon-roll',
                    'weight' => '1 pc',
                    'description' => 'A warm cinnamon roll wrapped with butter and finished with white glaze.',
                    'composition' => 'Cinnamon, butter, sugar glaze',
                ],
                'ru' => [
                    'name' => 'Синабон',
                    'slug' => 'sinabon',
                    'weight' => '1 шт.',
                    'description' => 'Тёплая булочка с корицей и сливочным маслом, покрытая белой глазурью.',
                    'composition' => 'Корица, сливочное масло, сахарная глазурь',
                ],
            ],
            'ekler-toplama' => [
                'en' => [
                    'name' => 'Eclair Set',
                    'slug' => 'eclair-set',
                    'weight' => '6 pcs',
                    'description' => 'A set of 6 eclairs filled with cream and covered in chocolate.',
                    'composition' => 'Cream, chocolate coating, choux dough',
                ],
                'ru' => [
                    'name' => 'Набор эклеров',
                    'slug' => 'nabor-ehklerov',
                    'weight' => '6 шт.',
                    'description' => 'Набор из 6 эклеров с кремом и шоколадной глазурью.',
                    'composition' => 'Крем, шоколадная глазурь, заварное тесто',
                ],
            ],
            'mevali-tart' => [
                'en' => [
                    'name' => 'Fruit Tart',
                    'slug' => 'fruit-tart',
                    'weight' => '1 pc (18 cm)',
                    'description' => 'Crisp shortcrust base, light cream and seasonal fruit.',
                    'composition' => 'Seasonal fruits, cream, shortcrust base',
                ],
                'ru' => [
                    'name' => 'Фруктовый тарт',
                    'slug' => 'fruktovyj-tart',
                    'weight' => '1 шт. (18 см)',
                    'description' => 'Хрустящая песочная основа, лёгкий крем и сезонные фрукты.',
                    'composition' => 'Сезонные фрукты, крем, песочная основа',
                ],
            ],
            'bodomli-latte' => [
                'en' => [
                    'name' => 'Almond Latte',
                    'slug' => 'almond-latte',
                    'weight' => '300 ml',
                    'description' => 'Espresso, hot milk and almond syrup, topped with ground almonds.',
                    'composition' => 'Espresso, milk, almond syrup',
                ],
                'ru' => [
                    'name' => 'Миндальный латте',
                    'slug' => 'mindalnyj-latte',
                    'weight' => '300 мл',
                    'description' => 'Эспрессо, горячее молоко и миндальный сироп. Сверху — измельчённый миндаль.',
                    'composition' => 'Эспрессо, молоко, миндальный сироп',
                ],
            ],
            'issiq-shokolad' => [
                'en' => [
                    'name' => 'Hot Chocolate',
                    'slug' => 'hot-chocolate',
                    'weight' => '300 ml',
                    'description' => 'Thick hot chocolate with cream and marshmallows. The best choice for cold days.',
                    'composition' => 'Cocoa, milk, cream, marshmallows',
                ],
                'ru' => [
                    'name' => 'Горячий шоколад',
                    'slug' => 'goryachij-shokolad',
                    'weight' => '300 мл',
                    'description' => 'Густой горячий шоколад со сливками и маршмеллоу. Лучший выбор для холодных дней.',
                    'composition' => 'Какао, молоко, сливки, маршмеллоу',
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function news(): array
    {
        return [
            'kuzgi-tamlar-kolleksiyasi' => [
                'en' => [
                    'title' => 'The Autumn Flavour Collection has been introduced',
                    'slug' => 'autumn-flavour-collection',
                    'tag' => 'News',
                    'excerpt' => 'Almond, cinnamon and raspberry — the three key flavours of the autumn collection.',
                    'body' => "Our autumn collection brings together three directions that feel warm and homely: roasted almond, cinnamon and fresh raspberry. Every product was reworked around these flavours.\n\nThe collection includes favourites such as almond cake, almond croissant, cinnamon roll and almond latte. The sweet gift sets were also updated with autumn decorations.\n\nAll information on this page is a sample news item prepared for this portfolio demo.",
                ],
                'ru' => [
                    'title' => 'Представлена осенняя коллекция вкусов',
                    'slug' => 'kollekciya-osennih-vkusov',
                    'tag' => 'Новости',
                    'excerpt' => 'Миндаль, корица и малина — три главных вкуса осенней коллекции.',
                    'body' => "В нашей осенней коллекции объединились три направления, дарующие тепло и уют: обжаренный миндаль, корица и свежая малина. Каждый продукт был переработан вокруг этих вкусов.\n\nВ коллекцию вошли любимые позиции: миндальный торт, миндальный круассан, синабон и миндальный латте. Наборы сладостей также обновлены осенними украшениями.\n\nВся информация на этой странице — образец новости, подготовленный для портфолио-демо.",
                ],
            ],
            'bodomli-kruassan-har-kuni' => [
                'en' => [
                    'title' => 'The almond croissant is now available every day',
                    'slug' => 'almond-croissant-every-day',
                    'tag' => 'News',
                    'excerpt' => 'Our favourite almond croissant has earned a permanent place on the daily menu.',
                    'body' => "One of our customers' most requested items — the almond croissant — is now on the counter every day. The dough is folded fresh each morning and baked locally.\n\nThe croissant is filled with almond cream and topped with roasted almond flakes. Its flavour opens best when served warm.\n\nAs this is a demo site, this news item is also based on sample text.",
                ],
                'ru' => [
                    'title' => 'Миндальный круассан — теперь каждый день',
                    'slug' => 'mindalnyj-kruassan-kazhdyj-den',
                    'tag' => 'Новости',
                    'excerpt' => 'Любимый миндальный круассан занял постоянное место в ежедневном меню.',
                    'body' => "Одна из самых востребованных позиций у наших гостей — миндальный круассан — теперь на витрине каждый день. Тесто раскатывается каждое утро и выпекается на месте.\n\nКруассан наполнен миндальным кремом, сверху — обжаренные лепестки миндаля. Лучше всего вкус раскрывается в тёплом виде.\n\nЭто демо-сайт, поэтому данная новость также основана на образце текста.",
                ],
            ],
            'sovga-toplamlari-yangilandi' => [
                'en' => [
                    'title' => 'Sweet gift sets have been updated',
                    'slug' => 'gift-sets-updated',
                    'tag' => 'Promo',
                    'excerpt' => 'New assortment boxes are ready for holidays and thank-you gifts.',
                    'body' => "The gift sets have moved to newly designed boxes. You can now choose the set's contents from macarons, cookies and chocolate assortment.\n\nEach box is decorated with a ribbon and includes a gift card. Special options are available for large orders.\n\nNote: the offers and prices on this page are shown for portfolio demo purposes.",
                ],
                'ru' => [
                    'title' => 'Наборы сладостей обновлены',
                    'slug' => 'nabory-sladostej-obnovleny',
                    'tag' => 'Акция',
                    'excerpt' => 'Новые ассорти-коробки готовы к праздникам и подаркам.',
                    'body' => "Подарочные наборы переехали в коробки нового дизайна. Теперь состав набора можно выбрать из макаронов, печенья и шоколадного ассорти.\n\nКаждая коробка украшена лентой и дополнена открыткой. Для крупных заказов есть отдельные варианты.\n\nВнимание: акции и цены на этой странице приведены для демонстрации портфолио.",
                ],
            ],
            'ichimliklar-menyusi-yangilandi' => [
                'en' => [
                    'title' => 'Almond latte added to the drinks menu',
                    'slug' => 'drinks-menu-updated',
                    'tag' => 'News',
                    'excerpt' => 'Espresso, hot milk and almond syrup — a new favourite drink.',
                    'body' => "Almond latte has been added to the menu. It combines espresso, hot milk and a light almond flavour.\n\nThe drink is available both hot and iced. It is topped with ground almonds.\n\nThe menu and prices are provided as demo information.",
                ],
                'ru' => [
                    'title' => 'В меню напитков добавлен миндальный латте',
                    'slug' => 'menyu-napitkov-obnovleno',
                    'tag' => 'Новости',
                    'excerpt' => 'Эспрессо, горячее молоко и миндальный сироп — новый любимый напиток.',
                    'body' => "В меню добавлен миндальный латте. Он сочетает эспрессо, горячее молоко и лёгкий миндальный вкус.\n\nНапиток готовится как в горячем, так и в холодном варианте. Сверху добавляется измельчённый миндаль.\n\nМеню и цены приведены как демонстрационные данные.",
                ],
            ],
        ];
    }

    /**
     * Kalit — slaydning o'zbekcha sarlavhasi.
     *
     * @return array<string, array<string, array<string, string>>>
     */
    private function heroSlides(): array
    {
        return [
            'Bodom guli ilhomida yaratilgan ta’mlar' => [
                'en' => [
                    'title' => 'Flavours inspired by almond blossom',
                    'eyebrow' => 'Seasonal collection',
                    'text' => 'Almond, cinnamon and raspberry in the autumn collection — warmth in every bite.',
                    'primary_label' => 'View the catalog',
                    'secondary_label' => 'Locations',
                    'alt' => 'A composition of almond cake and croissants',
                ],
                'ru' => [
                    'title' => 'Вкусы, вдохновлённые миндальным цветом',
                    'eyebrow' => 'Сезонная коллекция',
                    'text' => 'В осенней коллекции — миндаль, корица и малина: тепло в каждом кусочке.',
                    'primary_label' => 'Смотреть каталог',
                    'secondary_label' => 'Филиалы',
                    'alt' => 'Композиция из миндального торта и круассанов',
                ],
            ],
            'Nonvoyxona peshtaxtasida issiq kruassanlar' => [
                'en' => [
                    'title' => 'Warm croissants on the bakery counter',
                    'eyebrow' => 'Fresh every day',
                    'text' => 'Layered dough and the scent of butter — the best start to the morning.',
                    'primary_label' => 'View croissants',
                    'secondary_label' => 'About us',
                    'alt' => 'Warm croissants and a cup of coffee',
                ],
                'ru' => [
                    'title' => 'Тёплые круассаны на витрине пекарни',
                    'eyebrow' => 'Свежее каждый день',
                    'text' => 'Слоёное тесто и аромат сливочного масла — лучшее начало утра.',
                    'primary_label' => 'Смотреть круассаны',
                    'secondary_label' => 'О нас',
                    'alt' => 'Тёплые круассаны и чашка кофе',
                ],
            ],
            'Yaqinlaringiz uchun shirinlik to‘plamlari' => [
                'en' => [
                    'title' => 'Sweet gift sets for your loved ones',
                    'eyebrow' => 'Gift ideas',
                    'text' => 'Elegantly decorated boxes for a holiday or an unexpected joy.',
                    'primary_label' => 'View the sets',
                    'secondary_label' => 'Contacts',
                    'alt' => 'A gift box with sweets',
                ],
                'ru' => [
                    'title' => 'Наборы сладостей для близких',
                    'eyebrow' => 'Идеи для подарков',
                    'text' => 'Элегантно украшенные коробки для праздника или неожиданной радости.',
                    'primary_label' => 'Смотреть наборы',
                    'secondary_label' => 'Контакты',
                    'alt' => 'Подарочная коробка и сладости',
                ],
            ],
        ];
    }

    /**
     * Kalit — filialning o'zbekcha nomi.
     *
     * @return array<string, array<string, array<string, string>>>
     */
    private function branches(): array
    {
        return [
            'Bodomgul Yunusobod' => [
                'en' => [
                    'name' => 'Bodomgul Yunusabad',
                    'district' => 'Yunusabad district',
                    'address' => '108 Amir Temur Avenue',
                    'hours' => 'Daily, 08:00 — 22:00',
                ],
                'ru' => [
                    'name' => 'Bodomgul Юнусабад',
                    'district' => 'Юнусабадский район',
                    'address' => 'проспект Амира Темура, 108',
                    'hours' => 'Ежедневно, 08:00 — 22:00',
                ],
            ],
            'Bodomgul Chilonzor' => [
                'en' => [
                    'name' => 'Bodomgul Chilanzar',
                    'district' => 'Chilanzar district',
                    'address' => '42 Bunyodkor Avenue',
                    'hours' => 'Daily, 08:00 — 23:00',
                ],
                'ru' => [
                    'name' => 'Bodomgul Чиланзар',
                    'district' => 'Чиланзарский район',
                    'address' => 'проспект Бунёдкор, 42',
                    'hours' => 'Ежедневно, 08:00 — 23:00',
                ],
            ],
            'Bodomgul Mirzo Ulug‘bek' => [
                'en' => [
                    'name' => 'Bodomgul Mirzo Ulugbek',
                    'district' => 'Mirzo Ulugbek district',
                    'address' => '15 Universitet Street',
                    'hours' => 'Daily, 08:00 — 22:00',
                ],
                'ru' => [
                    'name' => 'Bodomgul Мирзо Улугбек',
                    'district' => 'Мирзо-Улугбекский район',
                    'address' => 'улица Университетская, 15',
                    'hours' => 'Ежедневно, 08:00 — 22:00',
                ],
            ],
            'Bodomgul Shayxontohur' => [
                'en' => [
                    'name' => 'Bodomgul Shaykhantahur',
                    'district' => 'Shaykhantahur district',
                    'address' => '7 Zarqaynar Street',
                    'hours' => 'Daily, 09:00 — 22:00',
                ],
                'ru' => [
                    'name' => 'Bodomgul Шайхантахур',
                    'district' => 'Шайхантахурский район',
                    'address' => 'улица Заркайнар, 7',
                    'hours' => 'Ежедневно, 09:00 — 22:00',
                ],
            ],
            'Bodomgul Yakkasaroy' => [
                'en' => [
                    'name' => 'Bodomgul Yakkasaray',
                    'district' => 'Yakkasaray district',
                    'address' => '26 Babur Street',
                    'hours' => 'Daily, 08:00 — 21:00',
                ],
                'ru' => [
                    'name' => 'Bodomgul Яккасарай',
                    'district' => 'Яккасарайский район',
                    'address' => 'улица Бабура, 26',
                    'hours' => 'Ежедневно, 08:00 — 21:00',
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, array{title: string, content: array<string, mixed>}>>
     */
    private function pagesData(): array
    {
        return [
            Page::ABOUT => [
                'en' => [
                    'title' => 'About us',
                    'content' => [
                        'description' => 'It all started with sweets baked with my grandmother’s love...',
                        'story' => [
                            'eyebrow' => 'Our story',
                            'title' => 'It all started with my grandmother’s sweets...',
                            'image' => 'images/brand/about.svg',
                            'paragraphs' => [
                                'Our journey began with warm memories from a family kitchen and the aroma of sweets baked with love. Our grandmother’s old recipes, the magic of almonds and honey, and heartfelt hospitality became the foundation of today’s large confectionery chain.',
                                'Today, Safia and Bodomgul fill the city with joy every morning — freshly baked croissants, delicate cakes, sweet desserts and fragrant coffee in hundreds of branches across the capital and the whole country.',
                            ],
                        ],
                        'mission' => [
                            'badge' => 'Our mission',
                            'text' => '«Through the art of confectionery, to bring the joy of celebration into every home and share warmth and unforgettable sweet moments with family and friends.»',
                            'attribution' => 'The Safia & Bodomgul philosophy',
                        ],
                        'values' => [
                            ['icon' => 'bi-shield-check', 'title' => 'Honesty and quality', 'text' => 'Only natural, carefully selected ingredients: real butter, fine chocolate and fresh fruit. No artificial additives.'],
                            ['icon' => 'bi-cup-hot', 'title' => 'Hospitality', 'text' => 'Every guest is a member of our family. Every visit is welcomed with a smile and warm respect.'],
                            ['icon' => 'bi-award', 'title' => 'Art and craftsmanship', 'text' => 'Confectionery is not just food — it is a true work of art that demands aesthetics and fine taste.'],
                            ['icon' => 'bi-people', 'title' => 'One big family', 'text' => 'Our staff, our bakers and our dear customers — together we are one big, cheerful team.'],
                        ],
                        'stats' => [
                            ['value' => '100+', 'label' => 'Convenient branches across the city'],
                            ['value' => '250+', 'label' => 'Refined recipes and desserts'],
                            ['value' => '1 200+', 'label' => 'Friendly and experienced staff'],
                            ['value' => '50 000+', 'label' => 'Delighted guests every day'],
                        ],
                    ],
                ],
                'ru' => [
                    'title' => 'О нас',
                    'content' => [
                        'description' => 'Всё началось со сладостей, испечённых бабушкой с любовью...',
                        'story' => [
                            'eyebrow' => 'Наша история',
                            'title' => 'Всё началось со сладостей бабушки...',
                            'image' => 'images/brand/about.svg',
                            'paragraphs' => [
                                'Наш путь начался с тёплых воспоминаний о семейной кухне и аромате сладостей, испечённых с любовью. Старинные рецепты бабушки, волшебное сочетание миндаля и мёда, искреннее гостеприимство стали основой большой кондитерской сети сегодня.',
                                'Сегодня Safia и Bodomgul каждое утро наполняют город радостью: свежеиспечённые круассаны, изысканные торты, сладкие десерты и ароматный кофе в сотнях филиалов по столице и всей республике.',
                            ],
                        ],
                        'mission' => [
                            'badge' => 'Наша миссия',
                            'text' => '«Развивая искусство кондитерского дела, приносить радость праздника в каждый дом, делиться теплом и незабываемыми сладкими моментами с семьёй и друзьями.»',
                            'attribution' => 'Философия Safia & Bodomgul',
                        ],
                        'values' => [
                            ['icon' => 'bi-shield-check', 'title' => 'Честность и качество', 'text' => 'Только натуральное и отборное сырьё: настоящее сливочное масло, качественный шоколад и свежие фрукты. Без искусственных добавок.'],
                            ['icon' => 'bi-cup-hot', 'title' => 'Гостеприимство', 'text' => 'Каждый гость — член нашей семьи. Каждому визиту — улыбка и тёплое уважение.'],
                            ['icon' => 'bi-award', 'title' => 'Искусство и мастерство', 'text' => 'Кондитерское дело — это не просто еда, а настоящее произведение искусства, требующее эстетики и тонкого вкуса.'],
                            ['icon' => 'bi-people', 'title' => 'Большая дружная семья', 'text' => 'Наши сотрудники, пекари и дорогие гости — все мы одна большая и дружная команда.'],
                        ],
                        'stats' => [
                            ['value' => '100+', 'label' => 'Удобных филиалов по городу'],
                            ['value' => '250+', 'label' => 'Изысканных рецептов и десертов'],
                            ['value' => '1 200+', 'label' => 'Приветливых и опытных сотрудников'],
                            ['value' => '50 000+', 'label' => 'Довольных гостей каждый день'],
                        ],
                    ],
                ],
            ],

            Page::CAREERS => [
                'en' => [
                    'title' => 'Careers',
                    'content' => [
                        'hero' => [
                            'badge' => 'The Safia & Bodomgul team',
                            'title' => 'Become part of the Safia family',
                            'text' => 'We support the personal and professional growth of every employee. If you love the art of confectionery and want to work in a warm atmosphere, our team welcomes you with pleasure!',
                            'image' => 'images/brand/team.svg',
                            'badge_text' => '1 200+ employees',
                        ],
                        'perks' => [
                            'title' => 'Why choose us?',
                            'subtitle' => 'The main benefits of working in the Safia and Bodomgul team',
                            'items' => [
                                ['icon' => 'bi-graph-up-arrow', 'title' => 'Career growth', 'text' => 'Free training at our academy, master classes with head chefs and fast career advancement.'],
                                ['icon' => 'bi-clock-history', 'title' => 'Flexible schedule', 'text' => 'Shift schedules suited to students and different lifestyles (day and evening shifts).'],
                                ['icon' => 'bi-cup-hot', 'title' => 'Hot meals and uniform', 'text' => 'Two free hot meals a day and a free corporate uniform for every employee.'],
                                ['icon' => 'bi-shield-check', 'title' => 'Official employment', 'text' => 'Official hiring fully compliant with the Labour Code of the Republic of Uzbekistan and salaries paid on time.'],
                            ],
                        ],
                        'vacancies' => [
                            'title' => 'Open positions',
                            'subtitle' => 'Choose the direction that suits you and leave a request',
                            'items' => [
                                ['title' => 'Pastry chef / assistant', 'tag' => 'Full-time', 'text' => 'Preparing and decorating cakes, desserts and pastries according to the recipe.', 'location' => 'Tashkent city', 'salary' => '5 000 000 – 8 500 000 UZS', 'schedule' => 'Shift schedule (2/2)'],
                                ['title' => 'Barista / cashier', 'tag' => 'Shift work', 'text' => 'Welcoming service for guests, preparing coffee and drinks, working with the till.', 'location' => 'Branches (Tashkent)', 'salary' => '4 500 000 – 6 500 000 UZS', 'schedule' => '08:00 – 16:00 / 15:00 – 23:00'],
                                ['title' => 'Bakery specialist (baker)', 'tag' => 'Full-time', 'text' => 'Working with laminated croissant dough, baguettes and pastry dough, and baking in the oven.', 'location' => 'Central workshop (Tashkent)', 'salary' => '6 000 000 – 9 000 000 UZS', 'schedule' => 'Morning shift'],
                                ['title' => 'Branch manager (administrator)', 'tag' => 'Management', 'text' => 'Managing the cafe branch, staff discipline and service quality control.', 'location' => 'Tashkent city', 'salary' => '7 000 000 – 11 000 000 UZS', 'schedule' => 'Full working day'],
                                ['title' => 'Delivery driver (courier)', 'tag' => 'Flexible', 'text' => 'Delivering orders to the specified address carefully and on time.', 'location' => 'Across Tashkent', 'salary' => '5 500 000 – 9 000 000 UZS', 'schedule' => 'Flexible schedule'],
                            ],
                        ],
                        'cta' => [
                            'title' => 'Do you have a CV?',
                            'text' => 'Send your details to our HR specialist via Telegram or give us a call. We review every application within 24 hours.',
                            'phone_label' => '+998 78 113 40 40',
                            'telegram_label' => '@safia_hr_bot',
                            'telegram_url' => 'https://t.me/safia_hr_bot',
                        ],
                    ],
                ],
                'ru' => [
                    'title' => 'Карьера',
                    'content' => [
                        'hero' => [
                            'badge' => 'Команда Safia & Bodomgul',
                            'title' => 'Станьте частью семьи Safia',
                            'text' => 'Мы поддерживаем личностный и профессиональный рост каждого сотрудника. Если вы тоже любите кондитерское искусство и хотите работать в тёплой атмосфере — наша команда с радостью ждёт вас!',
                            'image' => 'images/brand/team.svg',
                            'badge_text' => '1 200+ сотрудников',
                        ],
                        'perks' => [
                            'title' => 'Почему именно мы?',
                            'subtitle' => 'Главные преимущества работы в команде Safia и Bodomgul',
                            'items' => [
                                ['icon' => 'bi-graph-up-arrow', 'title' => 'Профессиональный рост', 'text' => 'Бесплатные тренинги в нашей академии, мастер-классы от шеф-кондитеров и быстрое продвижение.'],
                                ['icon' => 'bi-clock-history', 'title' => 'Удобный график', 'text' => 'Гибкий сменный график, подходящий студентам и разному ритму жизни (дневные и вечерние смены).'],
                                ['icon' => 'bi-cup-hot', 'title' => 'Горячее питание и форма', 'text' => 'Два бесплатных горячих обеда в день и бесплатная корпоративная форма для всех сотрудников.'],
                                ['icon' => 'bi-shield-check', 'title' => 'Официальное трудоустройство', 'text' => 'Полное оформление по Трудовому кодексу Республики Узбекистан и зарплата, выплачиваемая в срок.'],
                            ],
                        ],
                        'vacancies' => [
                            'title' => 'Открытые вакансии',
                            'subtitle' => 'Выберите подходящее направление и оставьте заявку',
                            'items' => [
                                ['title' => 'Кондитер / помощник кондитера', 'tag' => 'Полная занятость', 'text' => 'Приготовление и оформление тортов, десертов и выпечки по рецептуре.', 'location' => 'город Ташкент', 'salary' => '5 000 000 – 8 500 000 сум', 'schedule' => 'Сменный график (2/2)'],
                                ['title' => 'Бариста — кассир', 'tag' => 'Сменный график', 'text' => 'Гостеприимное обслуживание гостей, приготовление кофе и напитков, работа с кассой.', 'location' => 'Филиалы (Ташкент)', 'salary' => '4 500 000 – 6 500 000 сум', 'schedule' => '08:00 – 16:00 / 15:00 – 23:00'],
                                ['title' => 'Специалист пекарни (пекарь)', 'tag' => 'Полная занятость', 'text' => 'Работа со слоёным тестом для круассанов, багетами и тестом для выпечки, выпекание в печи.', 'location' => 'Центральный цех (Ташкент)', 'salary' => '6 000 000 – 9 000 000 сум', 'schedule' => 'Утренняя смена'],
                                ['title' => 'Администратор филиала', 'tag' => 'Управление', 'text' => 'Управление работой филиала кафе, дисциплина сотрудников, контроль качества сервиса.', 'location' => 'город Ташкент', 'salary' => '7 000 000 – 11 000 000 сум', 'schedule' => 'Полный рабочий день'],
                                ['title' => 'Водитель-курьер по доставке', 'tag' => 'Гибкий график', 'text' => 'Аккуратная и своевременная доставка заказов по указанному адресу.', 'location' => 'По всему Ташкенту', 'salary' => '5 500 000 – 9 000 000 сум', 'schedule' => 'Гибкий график'],
                            ],
                        ],
                        'cta' => [
                            'title' => 'У вас есть резюме?',
                            'text' => 'Отправьте свои данные нашему HR-специалисту через Telegram или позвоните нам. Каждую заявку мы рассматриваем в течение 24 часов.',
                            'phone_label' => '+998 78 113 40 40',
                            'telegram_label' => '@safia_hr_bot',
                            'telegram_url' => 'https://t.me/safia_hr_bot',
                        ],
                    ],
                ],
            ],

            Page::CONTACTS => [
                'en' => [
                    'title' => 'Contacts & Catering',
                    'content' => [
                        'description' => 'Contact us with questions, orders, banquets and catering services.',
                        'services_title' => 'Safia & Bodomgul services',
                        'services' => [
                            'Custom cakes for holidays and weddings.',
                            'Corporate catering and buffet services.',
                            'Fast delivery across the city.',
                        ],
                        'form_title' => 'Leave us a message',
                        'form_note' => 'Demo form: the message is validated and accepted on the server.',
                    ],
                ],
                'ru' => [
                    'title' => 'Контакты и кейтеринг',
                    'content' => [
                        'description' => 'Свяжитесь с нами по вопросам заказов, банкетов и кейтеринга.',
                        'services_title' => 'Услуги Safia & Bodomgul',
                        'services' => [
                            'Торты на заказ для праздников и свадеб.',
                            'Корпоративный кейтеринг и фуршетные услуги.',
                            'Быстрая доставка по городу.',
                        ],
                        'form_title' => 'Оставьте нам сообщение',
                        'form_note' => 'Демо-форма: сообщение проходит валидацию и принимается на сервере.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function settingTranslations(): array
    {
        return [
            'en' => [
                'brand.motto' => '«Through the art of confectionery, we bring the joy of celebration into every home»',
                'brand.tagline' => 'Confectionery inspired by almond blossom',
                'brand.about' => 'Bodomgul is a confectionery and bakery brand inspired by the elegance of almond blossom. Cakes, croissants and sweets are prepared fresh every day.',
                'brand.announcement' => 'Sign up for the mobile app and get a free coffee on your first purchase!',
                'brand.hours' => 'Mon–Sun: 08:00 to 23:00',
                'brand.address' => 'Tashkent city (demo address)',
                'brand.copyright_note' => 'Safia Cafe & Bakery (Bodomgul Portfolio Clone). All rights reserved.',
                'promo.eyebrow' => 'Seasonal offer',
                'promo.title' => 'Almond and cinnamon — the warmest flavours of autumn',
                'promo.text' => 'The seasonal collection features almond croissant, cinnamon roll and almond latte. Make the autumn table sweet.',
                'promo.cta_label' => 'View the collection',
            ],
            'ru' => [
                'brand.motto' => '«Развивая искусство кондитерского дела, мы приносим радость праздника в каждый дом»',
                'brand.tagline' => 'Кондитерские изделия, вдохновлённые миндальным цветом',
                'brand.about' => 'Bodomgul — бренд кондитерских изделий и пекарни, вдохновлённый изяществом миндального цвета. Торты, круассаны и сладости готовятся свежими каждый день.',
                'brand.announcement' => 'Зарегистрируйтесь в мобильном приложении и получите кофе в подарок при первой покупке!',
                'brand.hours' => 'Пн–Вс: с 08:00 до 23:00',
                'brand.address' => 'город Ташкент (демо-адрес)',
                'brand.copyright_note' => 'Safia Cafe & Bakery (Bodomgul Portfolio Clone). Все права защищены.',
                'promo.eyebrow' => 'Сезонное предложение',
                'promo.title' => 'Миндаль и корица — самые тёплые вкусы осени',
                'promo.text' => 'В сезонной коллекции — миндальный круассан, синабон и миндальный латте. Сделайте осенний стол сладким.',
                'promo.cta_label' => 'Смотреть коллекцию',
            ],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function uiTranslations(): array
    {
        return [
            'en' => [
                'nav.about' => 'About us',
                'nav.catalog' => 'Catalog',
                'nav.news' => 'News & offers',
                'nav.branches' => 'Locations',
                'nav.careers' => 'Careers',
                'nav.catering' => 'Catering',

                'header.skip_link' => 'Skip to main content',
                'header.city' => 'Tashkent',
                'header.search' => 'Search',
                'header.search_placeholder' => 'Enter a product name...',
                'header.search_button' => 'Search',
                'header.search_label' => 'Product search',
                'header.language' => 'Language',
                'header.favorites' => 'Favorites',
                'header.cart' => 'Cart',
                'header.menu' => 'Menu',
                'header.close_menu' => 'Close menu',

                'footer.about_title' => 'About the company',
                'footer.links_title' => 'Useful links',
                'footer.contacts_title' => 'Contacts',
                'footer.app_title' => 'Mobile app',
                'footer.app_text' => 'Easy to connect: open your phone camera to install our app or download it from the store.',
                'footer.loyalty' => 'Loyalty program',
                'footer.faq' => 'FAQ',
                'footer.privacy' => 'Privacy policy',
                'footer.demo_note' => 'Portfolio Demo — created in the style of Safia Bakery.',

                'common.currency' => 'UZS',
                'common.home' => 'Home',
                'common.view_all' => 'View all',
                'common.more' => 'Details',
                'common.view' => 'View',
                'common.demo_data' => 'Sample demo data',

                'home.hero.prev' => 'Previous slide',
                'home.hero.next' => 'Next slide',
                'home.catalog.title' => 'Catalog',
                'home.catalog.prev' => 'Previous categories',
                'home.catalog.next' => 'Next categories',
                'home.app.title' => 'Sign up for the mobile app and get a free coffee on your first purchase!',
                'home.app.text' => 'Easy to connect: point your phone camera at the QR code to install our app, or download it from the app store.',
                'home.app.qr_title' => 'QR code',
                'home.app.qr_hint' => 'Point your camera',
                'home.products.title' => 'Favourite products',
                'home.products.subtitle' => 'Freshly baked refined desserts and sweets every day',
                'home.join.title' => 'Join',
                'home.join.title_accent' => 'our family',
                'home.join.badge_title' => '1 200+ employees',
                'home.join.badge_text' => 'A close-knit, welcoming team',
                'home.join.perk1_title' => 'Career growth',
                'home.join.perk1_text' => 'Free education at our confectionery academy, master classes and fast career advancement.',
                'home.join.perk2_title' => 'Hot meals and comfort',
                'home.join.perk2_text' => 'Two free hot meals daily, a nice corporate uniform and a convenient schedule.',
                'home.join.perk3_title' => 'Official employment',
                'home.join.perk3_text' => 'Full official hiring under the Labour Code, salaries and bonuses paid on time.',
                'home.join.quote' => '«Do you love the art of confectionery too? Join our team — together we will create new sweets!»',
                'home.join.cta' => 'View open positions',
                'home.news.title' => 'News & offers',
                'home.news.subtitle' => 'Latest events, discounts and updates',
                'home.news.view_all' => 'All news',
                'home.branches.title' => 'Locations and branches',
                'home.branches.subtitle' => 'We are waiting for you every day from 08:00 to 23:00',
                'home.branches.view_all' => 'All branches',
                'home.promo_label' => 'Seasonal offer',

                'catalog.title' => 'Catalog',
                'catalog.description' => 'All Bodomgul products: cakes, pastries, croissants, cookies, sweet gift sets and drinks.',
                'catalog.search_placeholder' => 'Enter a product name...',
                'catalog.search_category_placeholder' => 'Search in this category...',
                'catalog.search_label' => 'Search within category',
                'catalog.filter_label' => 'Filter by category',
                'catalog.sort_label' => 'Sort',
                'catalog.sort_default' => 'Recommended',
                'catalog.sort_price_asc' => 'Cheapest first',
                'catalog.sort_price_desc' => 'Most expensive first',
                'catalog.sort_name_asc' => 'By name',
                'catalog.all' => 'All',
                'catalog.found' => ':count products found',
                'catalog.found_suffix' => 'products found',
                'catalog.empty_title' => 'No products found',
                'catalog.empty_text' => 'Change your search term or clear the filters.',
                'catalog.empty_category_text' => 'Try a different search term or choose another category.',
                'catalog.clear_filters' => 'Clear filters',
                'catalog.clear_search' => 'Clear search',

                'product.new_badge' => 'New',
                'product.add_to_cart' => 'To cart',
                'product.details' => 'Details',
                'product.favorite_add' => ':name — add to favourites',
                'product.cart_add' => ':name — add to cart',
                'product.category_label' => 'Category',
                'product.weight_label' => 'Weight / volume',
                'product.composition_label' => 'Ingredients',
                'product.status_label' => 'Status',
                'product.weight_unit' => '/ :weight',
                'product.qty_label' => 'Quantity',
                'product.qty_decrease' => 'Decrease quantity',
                'product.qty_increase' => 'Increase quantity',
                'product.add_to_cart_long' => 'Add to cart',
                'product.favorite_long' => 'To favourites',
                'product.demo_alert' => 'This is a portfolio demo page: prices and availability are sample data, no order is submitted. The cart is stored in your browser.',
                'product.related_title' => 'Similar products',
                'product.related_subtitle' => 'More sweets in the :category category',

                'news.title' => 'News & offers',
                'news.description' => 'News, seasonal collections and announcements from Safia and Bodomgul.',
                'news.back' => 'All news',
                'news.related_eyebrow' => 'Read more',
                'news.related_title' => 'Other news',
                'news.share_label' => 'Share',

                'cart.title' => 'Cart',
                'cart.description' => 'Your cart is stored in the browser (localStorage). This is a portfolio demo — no order or payment is submitted.',
                'cart.noscript' => 'The cart works with JavaScript. If JavaScript is disabled, you cannot view or manage products.',
                'cart.unit_price' => ':price / :weight',
                'cart.row_total' => 'Total',
                'cart.remove' => ':name — remove from cart',
                'cart.empty_title' => 'Your cart is empty',
                'cart.empty_text' => 'Choose your favourite pastries and sweets from the catalog.',
                'cart.go_catalog' => 'Go to catalog',
                'cart.summary_title' => 'Order summary',
                'cart.items_count' => 'Number of items',
                'cart.items_count_value' => ':count pcs',
                'cart.items_suffix' => 'pcs',
                'cart.subtotal' => 'Subtotal',
                'cart.delivery' => 'Delivery',
                'cart.delivery_demo' => 'Not calculated (demo)',
                'cart.total' => 'Total',
                'cart.note' => 'This is a portfolio demo: payment, delivery and order submission are not available.',
                'cart.clear' => 'Clear the cart',
                'cart.continue' => 'Continue shopping',

                'favorites.title' => 'Favourites',
                'favorites.description' => 'The favourites list is stored in your browser (localStorage) — it will still be there when you return.',
                'favorites.noscript' => 'Favourites work with JavaScript. If JavaScript is disabled, you cannot view selected products.',
                'favorites.count' => ':count favourite products',
                'favorites.count_suffix' => 'favourite products',
                'favorites.empty_text_alt' => 'You can add products to favourites with the heart button in the catalog.',
                'favorites.empty_title' => 'Your favourites list is empty',
                'favorites.empty_text' => 'Mark the products you like in the catalog — they will be saved here.',

                'branches.title' => 'Locations and branches',
                'branches.description' => 'Find Safia & Bodomgul branches in Tashkent: address, phone and working hours.',
                'branches.search_placeholder' => 'Branch name, district or address...',
                'branches.search_label' => 'Search branches',
                'branches.found' => ':count branches found',
                'branches.found_suffix' => 'branches found',
                'branches.empty_title' => 'No branches found',
                'branches.empty_text' => 'Try searching with a different name, district or address.',
                'branches.map_link' => 'View on the map',
                'branches.demo_badge' => 'Demo',

                'about.title' => 'About us',
                'about.description' => 'It all started with sweets baked with my grandmother’s love...',
                'about.view_catalog' => 'View the catalog',
                'about.values_title' => 'Values — What do we believe in?',
                'about.values_subtitle' => 'The principles behind everything we do and make',

                'careers.title' => 'Careers',
                'careers.meta_description' => 'Careers at the Safia and Bodomgul confectionery chain: open positions, a friendly team, comfortable working conditions.',
                'careers.vacancies_button' => 'Open positions',
                'careers.hr_bot' => 'HR Telegram bot',
                'careers.apply' => 'Contact',
                'careers.vacancies_count' => ':count positions',
                'careers.vacancies_count_suffix' => 'positions',

                'contacts.title' => 'Contacts & Catering',
                'contacts.description' => 'Contact us with questions, orders, banquets and catering services.',
                'contacts.phone_title' => 'Single contact centre',
                'contacts.email_title' => 'Email',
                'contacts.address_title' => 'Head office',
                'contacts.hours_title' => 'Working hours',
                'contacts.view_branches' => 'View branches',
                'contacts.go_catalog' => 'Go to catalog',
                'contacts.name_label' => 'Your name',
                'contacts.contact_label' => 'Phone or email',
                'contacts.message_label' => 'Message',
                'contacts.form_submit' => 'Send message (demo)',

                'form.name_required' => 'Please enter your name.',
                'form.name_min' => 'The name must be at least 2 characters.',
                'form.name_max' => 'The name must not exceed 80 characters.',
                'form.contact_required' => 'Enter a phone number or email for contact.',
                'form.contact_min' => 'The contact information is too short.',
                'form.contact_max' => 'The contact information must not exceed 120 characters.',
                'form.message_required' => 'Please enter the message text.',
                'form.message_min' => 'The message must be at least 10 characters.',
                'form.message_max' => 'The message must not exceed 2000 characters.',
                'form.demo_notice' => 'Thank you, :name! This is a portfolio demo form — your message was not sent to a real address and was not saved.',
                'form.check_fields' => 'Please check the following:',

                'js.added_to_cart' => 'Added to cart',
                'js.removed_from_cart' => 'Removed from cart',
                'js.cart_cleared' => 'Cart cleared',
                'js.added_to_favorites' => 'Added to favourites',
                'js.removed_from_favorites' => 'Removed from favourites',
                'js.filters_cleared' => 'Filters cleared',
                'js.nothing_found' => 'Nothing found',
                'js.products_found' => ':count products',
                'js.branches_found' => ':count branches',
                'js.cart_aria' => 'Cart, :count items',
                'js.favorites_aria' => 'Favourites, :count items',
                'js.items_suffix' => 'pcs',

                'common.breadcrumb' => 'Breadcrumb',
                'meta.home_title' => 'Bodomgul — confectionery and bakery',
                'meta.home_description' => 'Bodomgul — cakes, croissants, cookies and sweet gift sets inspired by almond blossom. A portfolio site in the style of Safia Bakery.',
                'errors.not_found_title' => 'Sorry, this page was not found',
                'errors.not_found_text' => 'The page may have been moved or the address mistyped. You can continue via the links below.',
                'errors.server_title' => 'An unexpected server error occurred',
                'errors.server_text' => 'The problem may be temporary. Please try again a little later or return to the home page.',
            ],

            'ru' => [
                'nav.about' => 'О нас',
                'nav.catalog' => 'Каталог',
                'nav.news' => 'Новости и акции',
                'nav.branches' => 'Локации',
                'nav.careers' => 'Карьера',
                'nav.catering' => 'Кейтеринг',

                'header.skip_link' => 'Перейти к основному содержимому',
                'header.city' => 'Ташкент',
                'header.search' => 'Поиск',
                'header.search_placeholder' => 'Введите название продукта...',
                'header.search_button' => 'Искать',
                'header.search_label' => 'Поиск продуктов',
                'header.language' => 'Язык',
                'header.favorites' => 'Избранное',
                'header.cart' => 'Корзина',
                'header.menu' => 'Меню',
                'header.close_menu' => 'Закрыть меню',

                'footer.about_title' => 'О компании',
                'footer.links_title' => 'Полезные ссылки',
                'footer.contacts_title' => 'Контакты',
                'footer.app_title' => 'Мобильное приложение',
                'footer.app_text' => 'Подключиться просто: откройте камеру телефона, чтобы установить приложение, или скачайте его из магазина.',
                'footer.loyalty' => 'Программа лояльности',
                'footer.faq' => 'Вопросы и ответы',
                'footer.privacy' => 'Политика конфиденциальности',
                'footer.demo_note' => 'Портфолио-демо — создано в стиле Safia Bakery.',

                'common.currency' => 'сум',
                'common.home' => 'Главная',
                'common.view_all' => 'Смотреть все',
                'common.more' => 'Подробнее',
                'common.view' => 'Посмотреть',
                'common.demo_data' => 'Демонстрационные данные',

                'home.hero.prev' => 'Предыдущий слайд',
                'home.hero.next' => 'Следующий слайд',
                'home.catalog.title' => 'Каталог',
                'home.catalog.prev' => 'Предыдущие категории',
                'home.catalog.next' => 'Следующие категории',
                'home.app.title' => 'Зарегистрируйтесь в мобильном приложении и получите кофе в подарок при первой покупке!',
                'home.app.text' => 'Подключиться просто: наведите камеру телефона на QR-код, чтобы установить приложение, или скачайте его из магазина приложений.',
                'home.app.qr_title' => 'QR-код',
                'home.app.qr_hint' => 'Наведите камеру',
                'home.products.title' => 'Любимые продукты',
                'home.products.subtitle' => 'Свежие изысканные десерты и сладости каждый день',
                'home.join.title' => 'Присоединяйтесь',
                'home.join.title_accent' => 'к нашей семье',
                'home.join.badge_title' => '1 200+ сотрудников',
                'home.join.badge_text' => 'Дружная и гостеприимная команда',
                'home.join.perk1_title' => 'Карьерный рост',
                'home.join.perk1_text' => 'Бесплатное обучение в нашей кондитерской академии, мастер-классы и быстрое продвижение.',
                'home.join.perk2_title' => 'Горячее питание и комфорт',
                'home.join.perk2_text' => 'Два бесплатных горячих обеда в день, красивая корпоративная форма и удобный график.',
                'home.join.perk3_title' => 'Официальное трудоустройство',
                'home.join.perk3_text' => 'Полное официальное оформление по Трудовому кодексу, своевременная зарплата и премии.',
                'home.join.quote' => '«Вы тоже любите кондитерское искусство? Присоединяйтесь к нашей команде — вместе мы создадим новые сладости!»',
                'home.join.cta' => 'Смотреть открытые вакансии',
                'home.news.title' => 'Новости и акции',
                'home.news.subtitle' => 'Последние события, скидки и новости',
                'home.news.view_all' => 'Все новости',
                'home.branches.title' => 'Локации и филиалы',
                'home.branches.subtitle' => 'Ждём вас каждый день с 08:00 до 23:00',
                'home.branches.view_all' => 'Все филиалы',
                'home.promo_label' => 'Сезонная акция',

                'catalog.title' => 'Каталог',
                'catalog.description' => 'Все продукты Bodomgul: торты, выпечка, круассаны, печенье, наборы сладостей и напитки.',
                'catalog.search_placeholder' => 'Введите название продукта...',
                'catalog.search_category_placeholder' => 'Искать в этой категории...',
                'catalog.search_label' => 'Поиск внутри категории',
                'catalog.filter_label' => 'Фильтр по категории',
                'catalog.sort_label' => 'Сортировка',
                'catalog.sort_default' => 'Рекомендуемые',
                'catalog.sort_price_asc' => 'Сначала дешёвые',
                'catalog.sort_price_desc' => 'Сначала дорогие',
                'catalog.sort_name_asc' => 'По названию',
                'catalog.all' => 'Все',
                'catalog.found' => 'найдено :count товаров',
                'catalog.found_suffix' => 'товаров найдено',
                'catalog.empty_title' => 'Продукты не найдены',
                'catalog.empty_text' => 'Измените поисковый запрос или очистите фильтры.',
                'catalog.empty_category_text' => 'Попробуйте изменить запрос или выбрать другую категорию.',
                'catalog.clear_filters' => 'Очистить фильтры',
                'catalog.clear_search' => 'Очистить поиск',

                'product.new_badge' => 'Новинка',
                'product.add_to_cart' => 'В корзину',
                'product.details' => 'Подробнее',
                'product.favorite_add' => ':name — добавить в избранное',
                'product.cart_add' => ':name — добавить в корзину',
                'product.category_label' => 'Категория',
                'product.weight_label' => 'Вес / объём',
                'product.composition_label' => 'Состав',
                'product.status_label' => 'Статус',
                'product.weight_unit' => '/ :weight',
                'product.qty_label' => 'Количество',
                'product.qty_decrease' => 'Уменьшить количество',
                'product.qty_increase' => 'Увеличить количество',
                'product.add_to_cart_long' => 'Добавить в корзину',
                'product.favorite_long' => 'В избранное',
                'product.demo_alert' => 'Это демонстрационная страница портфолио: цены и наличие — образцы, заказ не отправляется. Корзина хранится в браузере.',
                'product.related_title' => 'Похожие продукты',
                'product.related_subtitle' => 'Другие сладости из категории :category',

                'news.title' => 'Новости и акции',
                'news.description' => 'Новости, сезонные коллекции и объявления Safia и Bodomgul.',
                'news.back' => 'Все новости',
                'news.related_eyebrow' => 'Читать ещё',
                'news.related_title' => 'Другие новости',
                'news.share_label' => 'Поделиться',

                'cart.title' => 'Корзина',
                'cart.description' => 'Ваша корзина хранится в браузере (localStorage). Это портфолио-демо — заказ и оплата не отправляются.',
                'cart.noscript' => 'Корзина работает через JavaScript. Если JavaScript отключён, просмотр и управление продуктами недоступны.',
                'cart.unit_price' => ':price / :weight',
                'cart.row_total' => 'Итого',
                'cart.remove' => ':name — удалить из корзины',
                'cart.empty_title' => 'Ваша корзина пуста',
                'cart.empty_text' => 'Выберите любимую выпечку и сладости из каталога.',
                'cart.go_catalog' => 'Перейти в каталог',
                'cart.summary_title' => 'Итог заказа',
                'cart.items_count' => 'Количество товаров',
                'cart.items_count_value' => ':count шт.',
                'cart.items_suffix' => 'шт.',
                'cart.subtotal' => 'Промежуточная сумма',
                'cart.delivery' => 'Доставка',
                'cart.delivery_demo' => 'Не рассчитывается (демо)',
                'cart.total' => 'Итого',
                'cart.note' => 'Это портфолио-демо: оплата, доставка и оформление заказа недоступны.',
                'cart.clear' => 'Очистить корзину',
                'cart.continue' => 'Продолжить покупки',

                'favorites.title' => 'Избранное',
                'favorites.description' => 'Список избранного хранится в браузере (localStorage) — он останется на месте, когда вы вернётесь.',
                'favorites.noscript' => 'Избранное работает через JavaScript. Если JavaScript отключён, просмотр избранных продуктов недоступен.',
                'favorites.count' => ':count избранных продуктов',
                'favorites.count_suffix' => 'избранных продуктов',
                'favorites.empty_text_alt' => 'Добавлять продукты в избранное можно кнопкой-сердечком в каталоге.',
                'favorites.empty_title' => 'Список избранного пуст',
                'favorites.empty_text' => 'Отметьте понравившиеся продукты в каталоге — они сохранятся здесь.',

                'branches.title' => 'Локации и филиалы',
                'branches.description' => 'Найдите филиалы Safia & Bodomgul в Ташкенте: адрес, телефон и часы работы.',
                'branches.search_placeholder' => 'Название филиала, район или адрес...',
                'branches.search_label' => 'Поиск филиалов',
                'branches.found' => 'найдено :count филиалов',
                'branches.found_suffix' => 'филиалов найдено',
                'branches.empty_title' => 'Филиалы не найдены',
                'branches.empty_text' => 'Попробуйте поискать по другому названию, району или адресу.',
                'branches.map_link' => 'Посмотреть на карте',
                'branches.demo_badge' => 'Демо',

                'about.title' => 'О нас',
                'about.description' => 'Всё началось со сладостей, испечённых бабушкой с любовью...',
                'about.view_catalog' => 'Смотреть каталог',
                'about.values_title' => 'Ценности — во что мы верим?',
                'about.values_subtitle' => 'Принципы, лежащие в основе каждого нашего дела и продукта',

                'careers.title' => 'Карьера',
                'careers.meta_description' => 'Карьера в кондитерской сети Safia и Bodomgul: открытые вакансии, дружная команда, комфортные условия.',
                'careers.vacancies_button' => 'Открытые вакансии',
                'careers.hr_bot' => 'HR Telegram-бот',
                'careers.apply' => 'Связаться',
                'careers.vacancies_count' => ':count вакансий',
                'careers.vacancies_count_suffix' => 'вакансий',

                'contacts.title' => 'Контакты и кейтеринг',
                'contacts.description' => 'Свяжитесь с нами по вопросам заказов, банкетов и кейтеринга.',
                'contacts.phone_title' => 'Единый контакт-центр',
                'contacts.email_title' => 'Электронная почта',
                'contacts.address_title' => 'Главный офис',
                'contacts.hours_title' => 'Часы работы',
                'contacts.view_branches' => 'Смотреть филиалы',
                'contacts.go_catalog' => 'Перейти в каталог',
                'contacts.name_label' => 'Ваше имя',
                'contacts.contact_label' => 'Телефон или email',
                'contacts.message_label' => 'Сообщение',
                'contacts.form_submit' => 'Отправить сообщение (демо)',

                'form.name_required' => 'Введите ваше имя.',
                'form.name_min' => 'Имя должно содержать не менее 2 символов.',
                'form.name_max' => 'Имя не должно превышать 80 символов.',
                'form.contact_required' => 'Введите телефон или email для связи.',
                'form.contact_min' => 'Контактные данные слишком короткие.',
                'form.contact_max' => 'Контактные данные не должны превышать 120 символов.',
                'form.message_required' => 'Введите текст сообщения.',
                'form.message_min' => 'Сообщение должно содержать не менее 10 символов.',
                'form.message_max' => 'Сообщение не должно превышать 2000 символов.',
                'form.demo_notice' => 'Спасибо, :name! Это демонстрационная форма портфолио — ваше сообщение не было отправлено на реальный адрес и не сохранено.',
                'form.check_fields' => 'Пожалуйста, проверьте следующее:',

                'js.added_to_cart' => 'Добавлено в корзину',
                'js.removed_from_cart' => 'Удалено из корзины',
                'js.cart_cleared' => 'Корзина очищена',
                'js.added_to_favorites' => 'Добавлено в избранное',
                'js.removed_from_favorites' => 'Удалено из избранного',
                'js.filters_cleared' => 'Фильтры очищены',
                'js.nothing_found' => 'Ничего не найдено',
                'js.products_found' => ':count товаров',
                'js.branches_found' => ':count филиалов',
                'js.cart_aria' => 'Корзина, товаров: :count',
                'js.favorites_aria' => 'Избранное, товаров: :count',
                'js.items_suffix' => 'шт.',

                'common.breadcrumb' => 'Навигационная цепочка',
                'meta.home_title' => 'Bodomgul — кондитерская и пекарня',
                'meta.home_description' => 'Bodomgul — торты, круассаны, печенье и наборы сладостей, вдохновлённые миндальным цветом. Портфолио-сайт в стиле Safia Bakery.',
                'errors.not_found_title' => 'Извините, страница не найдена',
                'errors.not_found_text' => 'Возможно, страница была перемещена или адрес введён неверно. Вы можете продолжить по ссылкам ниже.',
                'errors.server_title' => 'На сервере произошла непредвиденная ошибка',
                'errors.server_text' => 'Возможно, проблема временная. Попробуйте ещё раз чуть позже или вернитесь на главную страницу.',
            ],
        ];
    }
}
