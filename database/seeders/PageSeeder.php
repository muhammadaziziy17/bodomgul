<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Statik sahifalarning boshlang'ich kontenti (o'zbekcha).
     */
    public function run(): void
    {
        foreach ($this->pages() as $slug => $page) {
            $model = Page::query()->firstOrNew(['slug' => $slug]);
            $model->save();

            $model->saveTranslations([
                'uz' => [
                    'title' => $page['title'],
                    'content' => json_encode($page['content'], JSON_UNESCAPED_UNICODE),
                ],
            ]);
        }
    }

    /**
     * @return array<string, array{title: string, content: array<string, mixed>}>
     */
    private function pages(): array
    {
        return [
            Page::ABOUT => [
                'title' => 'Biz haqimizda',
                'content' => [
                    'description' => 'Hammasi buvimning mehr bilan pishirilgan shirinliklaridan boshlandi...',
                    'story' => [
                        'eyebrow' => 'Bizning tariximiz',
                        'title' => 'Hammasi buvimning shirinliklaridan boshlandi...',
                        'image' => 'images/brand/about.svg',
                        'paragraphs' => [
                            'Bizning yo‘limiz oilaviy oshxonadagi iliq xotiralar va mehr bilan pishirilgan shirinliklar iforidan boshlangan. Buvimizning qadimiy retseptlari, bodom va asalning sehrli uyg‘unligi hamda samimiy mehmondo‘stlik bugungi kunda katta qandolatchilik tarmog‘ining asosiy poydevoriga aylandi.',
                            'Bugun Bodomgul poytaxtimiz va butun respublika bo‘ylab yuzlab filiallarda har kuni erta tongdan yangi pishirilgan kruassanlar, nozik tortlar, shirin desertlar va xushbo‘y kofe bilan shahar ahlining kunini quvonchga to‘ldirmoqda.',
                        ],
                    ],
                    'mission' => [
                        'badge' => 'Bizning missiyamiz',
                        'text' => '«Qandolatchilik san’atini rivojlantirish orqali, har bir xonadonga bayram shukuhini olib kirish, oilalar va do‘stlar davrasiga iliqlik hamda unutilmas shirin damlarni ulashish.»',
                        'attribution' => 'Bodomgul falsafasi',
                    ],
                    'values' => [
                        [
                            'icon' => 'bi-shield-check',
                            'title' => 'Halollik va sifat',
                            'text' => 'Faqat tabiiy va sara xomashyo: tabiiy sariyog‘, sifatli shokolad va yangi mevalar. Sun’iy qo‘shimchalarsiz.',
                        ],
                        [
                            'icon' => 'bi-cup-hot',
                            'title' => 'Mehmondo‘stlik',
                            'text' => 'Har bir mehmonimiz bizning qadrdon oila a’zomiz. Har bir tashrif tabassum va iliq ehtirom bilan kutib olinadi.',
                        ],
                        [
                            'icon' => 'bi-award',
                            'title' => 'San’at va mahorat',
                            'text' => 'Qandolatchilik — bu shunchaki taom emas, balki estetika va nozik did talab qiladigan haqiqiy san’at asaridir.',
                        ],
                        [
                            'icon' => 'bi-people',
                            'title' => 'Katta ahil oila',
                            'text' => 'Xodimlarimiz, oshpazlarimiz va aziz mijozlarimiz — biz barchamiz yagona katta va quvnoq jamoamiz.',
                        ],
                    ],
                    'stats' => [
                        ['value' => '100+', 'label' => 'Shahar bo‘ylab qulay filiallar'],
                        ['value' => '250+', 'label' => 'Nafis retseptlar va desertlar'],
                        ['value' => '1 200+', 'label' => 'Do‘stona va tajribali xodimlar'],
                        ['value' => '50 000+', 'label' => 'Har kuni mamnun mehmonlar'],
                    ],
                ],
            ],

            Page::CAREERS => [
                'title' => 'Karyera',
                'content' => [
                    'hero' => [
                        'badge' => 'Bodomgul jamoasi',
                        'title' => 'Bodomgul oilasining bir qismi bo‘ling',
                        'text' => 'Biz har bir xodimimizning shaxsiy va kasbiy o‘sishini qo‘llab-quvvatlaymiz. Agar siz ham qandolatchilik san’atini sevsangiz va iliq muhitda ishlashni istasangiz, bizning jamoamiz sizni mamnuniyat bilan kutib oladi!',
                        'image' => 'images/brand/team.svg',
                        'badge_text' => '1 200+ xodim',
                    ],
                    'perks' => [
                        'title' => 'Nega aynan biz?',
                        'subtitle' => 'Bodomgul jamoasida ishlashning asosiy qulayliklari',
                        'items' => [
                            [
                                'icon' => 'bi-graph-up-arrow',
                                'title' => 'Kasbiy o‘sish',
                                'text' => 'O‘z akademiyamizda bepul treninglar, usta oshpazlardan mahorat darslari va tez martaba yuksalishi.',
                            ],
                            [
                                'icon' => 'bi-clock-history',
                                'title' => 'Qulay ish grafigi',
                                'text' => 'Talabalar va turli turmush tarziga moslashuvchan smenali grafik (kunduzgi va kechki smenalar).',
                            ],
                            [
                                'icon' => 'bi-cup-hot',
                                'title' => 'Issiq tushlik va forma',
                                'text' => 'Barcha xodimlarimiz uchun bepul 2 mahal to‘yimli issiq tushlik va bepul korporativ ish kiyimi taqdim etiladi.',
                            ],
                            [
                                'icon' => 'bi-shield-check',
                                'title' => 'Rasmiy bandlik',
                                'text' => 'O‘zbekiston Respublikasi Mehnat kodeksiga to‘liq mos rasmiy ishga qabul qilish va vaqtida to‘lanadigan oylik.',
                            ],
                        ],
                    ],
                    'vacancies' => [
                        'title' => 'Ochiq vakansiyalar',
                        'subtitle' => 'O‘zingizga mos yo‘nalishni tanlang va so‘rov qoldiring',
                        'items' => [
                            [
                                'title' => 'Qandolatchi usta / yordamchi',
                                'tag' => 'To‘liq bandlik',
                                'text' => 'Tortlar, desertlar va pishiriqlarni retseptura bo‘yicha tayyorlash va bezash.',
                                'location' => 'Toshkent shahri',
                                'salary' => '5 000 000 – 8 500 000 so‘m',
                                'schedule' => 'Smenali grafik (2/2)',
                            ],
                            [
                                'title' => 'Barista - Kassir',
                                'tag' => 'Smenali grafik',
                                'text' => 'Mijozlarga mehmondo‘st xizmat ko‘rsatish, kofe va ichimliklar tayyorlash, kassa bilan ishlash.',
                                'location' => 'Filiallar (Toshkent)',
                                'salary' => '4 500 000 – 6 500 000 so‘m',
                                'schedule' => '08:00 – 16:00 / 15:00 – 23:00',
                            ],
                            [
                                'title' => 'Novvoyxona mutaxassisi (Nonvoy)',
                                'tag' => 'To‘liq bandlik',
                                'text' => 'Qatlamli kruassanlar, bagetlar, pishiriqlar xamiri bilan ishlash va pechda pishirish.',
                                'location' => 'Markaziy tsex (Toshkent)',
                                'salary' => '6 000 000 – 9 000 000 so‘m',
                                'schedule' => 'Tonggi smena',
                            ],
                            [
                                'title' => 'Filial ma’muri (Administrator)',
                                'tag' => 'Boshqaruv',
                                'text' => 'Kafe filiali faoliyatini boshqarish, xodimlar intizomi, servis sifatini nazorat qilish.',
                                'location' => 'Toshkent shahri',
                                'salary' => '7 000 000 – 11 000 000 so‘m',
                                'schedule' => 'To‘liq ish kuni',
                            ],
                            [
                                'title' => 'Yetkazib beruvchi haydovchi (Kuryer)',
                                'tag' => 'Moslashuvchan',
                                'text' => 'Buyurtmalarni belgilangan manzilga ehtiyotkorlik bilan va o‘z vaqtida yetkazib berish.',
                                'location' => 'Toshkent bo‘ylab',
                                'salary' => '5 500 000 – 9 000 000 so‘m',
                                'schedule' => 'Moslashuvchan jadval',
                            ],
                        ],
                    ],
                    'cta' => [
                        'title' => 'Rezyumengiz bormi?',
                        'text' => 'Telegram orqali HR mutaxassisimizga ma’lumotlaringizni yuboring yoki qo‘ng‘iroq qiling. Har bir arizani 24 soat ichida ko‘rib chiqamiz.',
                        'phone_label' => '+998 78 113 40 40',
                        'telegram_label' => '@bodomgul_hr_bot',
                        'telegram_url' => 'https://t.me/bodomgul_hr_bot',
                    ],
                ],
            ],

            Page::CONTACTS => [
                'title' => 'Kontaktlar & Keytering',
                'content' => [
                    'description' => 'Savollar, buyurtmalar, banket va keytering xizmati bo‘yicha biz bilan bog‘laning.',
                    'services_title' => 'Bodomgul servislari',
                    'services' => [
                        'Bayram va to‘ylar uchun buyurtma tortlar.',
                        'Korporativ keytering va furshet xizmatlari.',
                        'Shahar bo‘ylab tezkor yetkazib berish.',
                    ],
                    'form_title' => 'Bizga xabar qoldiring',
                    'form_note' => 'Demo forma: xabar serverda validatsiyadan o‘tadi va qabul qilinadi.',
                ],
            ],
        ];
    }
}
