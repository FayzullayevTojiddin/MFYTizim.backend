<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskCategory;

class TaskCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Ishga o\'zi vaqtida kelish',
                'description' => 'Ishga o\'zi vaqtida kelish hamda ish vaqtida biriktirilgan mahallasida bo\'lish',
                'status' => true,
            ],
            [
                'title' => 'Doimiy ishga joylashtirish',
                'description' => 'Aholini doimiy ishga joylashtirish (daromad solig\'i to\'lovchi)',
                'status' => true,
            ],
            [
                'title' => 'Tadbirkorlikka jalb qilish',
                'description' => 'Tadbirkorlikka jalb qilish (o\'zini-o\'zi band qilish)',
                'status' => true,
            ],
            [
                'title' => 'Kambag\'allikdan chiqarish',
                'description' => 'Kambag\'al oilalarni kambag\'allikdan chiqarish',
                'status' => true,
            ],
            [
                'title' => 'Reintegratsiya',
                'description' => 'Migratsiyadan qaytgan fuqarolarning bandligini ta\'minlash (Reintegratsiya)',
                'status' => true,
            ],
            [
                'title' => 'Kasb-hunar va tadbirkorlik',
                'description' => 'Kasb-hunar va tadbirkorlikka o\'qitish dasturlari',
                'status' => true,
            ],
            [
                'title' => 'Mikroloyihalar',
                'description' => 'Mikroloyihalarni ishga tushurish va rivojlantirish',
                'status' => true,
            ],
            [
                'title' => 'Oilaviy tadbirkorlik krediti',
                'description' => 'Oilaviy tadbirkorlikni rivojlantirish dasturlari doirasida kredit ajratish',
                'status' => true,
            ],
            [
                'title' => 'Kichik biznes krediti',
                'description' => 'Kichik biznesni uzluksiz qo\'llab-quvvatlash kompleks dasturlari doirasida kredit ajratish',
                'status' => true,
            ],
            [
                'title' => 'Subsidiya',
                'description' => 'Davlat maqsadli jamg\'armalari hisobidan subsidiya ajratish',
                'status' => true,
            ],
            [
                'title' => 'Ssuda',
                'description' => 'Davlat maqsadli jamg\'armalari hisobidan ssuda ajratish',
                'status' => true,
            ],
            [
                'title' => 'Grant',
                'description' => 'Davlat maqsadli jamg\'armalari hisobidan grant ajratish',
                'status' => true,
            ],
            [
                'title' => 'Onlayn bozor',
                'description' => 'Aholining onlayn bozorda ishtirok etishini ta\'minlash',
                'status' => true,
            ],
            [
                'title' => 'Kichik biznes tashkil etish',
                'description' => 'O\'zini o\'zi band qilgan fuqarolarning kichik biznes subekti tashkil etishga ko\'maklashish',
                'status' => true,
            ],
            [
                'title' => 'Yakka tartibdagi tadbirkorlar',
                'description' => 'Yakka tartibdagi tadbirkorlik subektlarini qo\'llab-quvvatlash',
                'status' => true,
            ],
            [
                'title' => 'Yuridik shaxs tadbirkorlik',
                'description' => 'Yuridik shaxs maqomiga ega bo\'lgan tadbirkorlik subektlari (fermer xo\'jaliklaridan tashqari)',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            TaskCategory::updateOrCreate(
                ['title' => $category['title']],
                $category
            );
        }
    }
}