<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;

class WorkerSeeder extends Seeder
{
    public function run(): void
    {
        $workers = [
            ['mahalla' => 'Beshkapa', 'name' => 'Raxmankulov Abdulaziz Ibadullayevich', 'phone_number' => '(88)849-11-19'],
            ['mahalla' => 'Dehqon', 'name' => 'Almardanov Nuriddin Mamayusupovich', 'phone_number' => '(97)078-01-37'],
            ['mahalla' => 'Kichik Jonchekkа', 'name' => 'Abdiyev Azamat Ashurovich', 'phone_number' => '(90)410-00-09'],
            ['mahalla' => 'Mustaqillik', 'name' => 'Shamsiyev Akbar Hakim o\'g\'li', 'phone_number' => '(97)775-90-04'],
            ['mahalla' => 'Mustaqillikning 22 yilligi', 'name' => 'Maxmudov Sharofiddin Qaxramonovich', 'phone_number' => '(91)903-13-13'],
            ['mahalla' => 'Obod yurt', 'name' => 'Kulboyev Bolta Aliyarovich', 'phone_number' => '(88)553-11-72'],
            ['mahalla' => 'Oqostona', 'name' => 'Safarov Jasurbek Zokir o\'g\'li', 'phone_number' => '(77)096-08-90'],
            ['mahalla' => 'Sahovat', 'name' => 'Kurbonov Abdusamat Abdulaxatovich', 'phone_number' => '(91)237-64-64'],
            ['mahalla' => 'Serharakat', 'name' => 'Ashuraliyev Nurali Abduraximovich', 'phone_number' => '(97)808-76-04'],
            ['mahalla' => 'Chaqar', 'name' => 'Raxmonov Jaxongir Saydullayevich', 'phone_number' => '(94)070-08-00'],
            ['mahalla' => 'Chinor', 'name' => 'Xasanov Kurbanali Abdirayimovich', 'phone_number' => '(99)525-14-65'],
            ['mahalla' => 'Guliston', 'name' => 'Nozimov Furkat Jurakulovich', 'phone_number' => '(97)840-81-81'],
            ['mahalla' => 'Do\'stlik', 'name' => 'Ne\'matov Tursunmurod Nuriddin o\'g\'li', 'phone_number' => '(97)810-06-27'],
            ['mahalla' => 'Istiqlol', 'name' => 'Yo\'ldoshev Elmurod O\'ktam o\'g\'li', 'phone_number' => '(94)900-77-37'],
            ['mahalla' => 'Qarashiq', 'name' => 'Boymatov Akbarjon Axmadjonovich', 'phone_number' => '(33)388-40-00'],
            ['mahalla' => 'Malandiyon', 'name' => 'Muxammadiyev Nuriddin Zaynilobiddinovich', 'phone_number' => '(97)351-52-80'],
            ['mahalla' => 'Mehnat', 'name' => 'Bobokalonov Shuxrat Maxmatmurotovich', 'phone_number' => '(99)376-04-79'],
            ['mahalla' => 'Obizarang', 'name' => 'Ortiqov Jasur Tojidinovich', 'phone_number' => '(91)514-88-33'],
            ['mahalla' => 'Tojikobod', 'name' => 'Botirov Sunnatullo Xabibulloyevich', 'phone_number' => '(97)552-49-99'],
            ['mahalla' => 'Tomchi', 'name' => 'Kamolov Mexriddin Zuxriddinovich', 'phone_number' => '(97)237-43-23'],
            ['mahalla' => 'Uzun qishloq', 'name' => 'Norbutayev Shamsuddin Normengliyevich', 'phone_number' => '(99)741-02-67'],
            ['mahalla' => 'X.Qahramon', 'name' => 'Jumanazarov Asomiddin Baxtiyor o\'g\'li', 'phone_number' => '(91)900-69-04'],
            ['mahalla' => 'Yangi yo\'l', 'name' => 'Norkulov Ilxom Ibroximovich', 'phone_number' => '(97)244-27-70'],
            ['mahalla' => 'Yangi kuch', 'name' => 'Raximov Bahodir Baxtiyarjonovich', 'phone_number' => '(99)507-85-07'],
            ['mahalla' => 'Yangi ruzg\'or', 'name' => 'Ergashov Axrorjon Mo\'minjon o\'g\'li', 'phone_number' => '(99)337-73-73'],
            ['mahalla' => 'Yangi hayot', 'name' => 'Vohidov Shahzod Dilshod o\'g\'li', 'phone_number' => '(91)908-10-05'],
            ['mahalla' => 'Yangi shahar', 'name' => 'Toshev Farxod Abduganiyevich', 'phone_number' => '(90)248-75-55'],
            ['mahalla' => 'Bobotog\'', 'name' => 'Azizov Ruzi Maxmanazarovich', 'phone_number' => '(99)672-81-78'],
            ['mahalla' => 'Dug\'ob', 'name' => 'Umirov Mansur Umarali o\'g\'li', 'phone_number' => '(94)519-30-98'],
            ['mahalla' => 'Jiyidabuloq', 'name' => 'Ashurov Ilxom Choriyevich', 'phone_number' => '(99)842-22-45'],
            ['mahalla' => 'Zarkamar', 'name' => 'Eshmurodova Maxliyo Erkinovna', 'phone_number' => '(97)692-91-03'],
            ['mahalla' => 'Madaniy turmush', 'name' => 'Ro\'ziyev Muxiddin Saparmamatovich', 'phone_number' => '(99)503-00-77'],
            ['mahalla' => 'Nurafshon', 'name' => 'Urunov Baxriddin Buriyevich', 'phone_number' => '(99)424-00-79'],
            ['mahalla' => 'Nurli kelajak', 'name' => 'Abdullayev Shamsiddin Usanovich', 'phone_number' => '(97)535-66-01'],
            ['mahalla' => 'Oqmachit', 'name' => 'Karimov Fozil Raxmatullayevich', 'phone_number' => '(95)221-82-82'],
            ['mahalla' => 'Pahlаvon', 'name' => 'Tursunov Ulug\'bek Abdusattorovich', 'phone_number' => '(97)075-87-89'],
            ['mahalla' => 'Surxon', 'name' => 'Husanov Jahongir Abdiqodir o\'g\'li', 'phone_number' => '(77)125-03-16'],
            ['mahalla' => 'Toltu\'g\'ay', 'name' => 'Mamadiyorov Safar Xabibullayevich', 'phone_number' => '(99)673-81-03'],
            ['mahalla' => 'O\'lanqul', 'name' => 'Kenjayev Akbarjon Axmatovich', 'phone_number' => '(99)670-00-55'],
            ['mahalla' => 'O\'rmonchi', 'name' => 'Mavlonov Sherzod Abdusamatovich', 'phone_number' => '(91)237-53-98'],
            ['mahalla' => 'Fayzobod', 'name' => 'Suyunov Akbarali Olimjon o\'g\'li', 'phone_number' => '(97)226-69-99'],
            ['mahalla' => 'Fayzova', 'name' => 'Tashimov Mengziya Choriyevich', 'phone_number' => '(99)377-04-43'],
            ['mahalla' => 'Xo\'jaqulsin', 'name' => 'Buriyev Nurbay Rustamovich', 'phone_number' => '(97)808-07-72'],
            ['mahalla' => 'Xursand', 'name' => 'Jovunov Muxammadi Shoymardanovich', 'phone_number' => '(88)840-44-88'],
            ['mahalla' => 'Yangiobod', 'name' => 'Ziyayev Alisher Ulug\'berdiyevich', 'phone_number' => '(99)675-86-80'],
            ['mahalla' => 'Bahoriston', 'name' => 'Mavlanov Jovoxir Abdusamatovich', 'phone_number' => '(99)944-13-79'],
            ['mahalla' => 'Yoshlik', 'name' => 'Jurayev Dustmurad Baymuratovich', 'phone_number' => '(94)654-52-45'],
            ['mahalla' => 'Ittifoq', 'name' => 'Iminov Solijon Izzatullayevich', 'phone_number' => '(97)225-27-65'],
            ['mahalla' => 'Navru\'z', 'name' => 'Uchqunxonov Behruz Behzod o\'g\'li', 'phone_number' => '(90)523-22-21'],
            ['mahalla' => 'O\'zbekiston', 'name' => 'Islomov Asomiddin Jaloliddin o\'g\'li', 'phone_number' => '(91)550-85-66'],
            ['mahalla' => 'Erkinlik', 'name' => 'Komilov Anvar Musinjanovich', 'phone_number' => '(99)172-72-38'],
        ];

        foreach ($workers as $index => $worker) {
            $email = strtolower(str_replace(['\'', ' '], ['', '_'], $worker['mahalla'])) . '@mfy.uz';
            
            $user = User::create([
                'name' => $worker['name'],
                'email' => $email,
                'password' => Hash::make('password'),
            ]);

            Worker::create([
                'title' => $worker['mahalla'] . ' MFY',
                'phone_number' => $worker['phone_number'],
                'user_id' => $user->id,
            ]);
        }
    }
}