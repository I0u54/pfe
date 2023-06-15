<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

       
        \App\Models\User::factory()->create([
            'name' => 'Wazoo',
            'birthDay' => '2002-08-08',
            'password' => Hash::make('Wazoo@wazoo.com'),
            'pseudo'=>'@Wazoo',
            'email' => 'Wazoo@wazoo.com',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Elon Musk',
            'birthDay' => '2001-11-23',
            'password' => Hash::make('12345678'),
            'pseudo'=>'@ElonMusk',
            'email' => 'elon@example.com',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Andrew Tate',
            'birthDay' => '2002-08-08',
            'password' => Hash::make('12345678'),
            'pseudo'=>'@yassine',
            'email' => 'yassine@example.com',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'ahmed souinga',
            'birthDay' => '2007-08-08',
            'password' => Hash::make('12345678'),
            'pseudo'=>'@ahmed',
            'email' => 'ahmed@example.com',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Youssef Shoroqat',
            'birthDay' => '2006-08-08',
            'password' => Hash::make('12345678'),
            'pseudo'=>'@Youssef',
            'email' => 'Youssef@example.com',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 1,
            'description' => '#welcome_to_Wazoo project pfe 😊',
            'created_at'=>'2023-05-21 11:45:11',
            'updated_at'=>'2023-05-21 11:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 1,
            'description' => '#Better-Call-Saul 👌',
            'image' => 'https://pbs.twimg.com/media/FwmjTlZXsAAVamD?format=jpg&name=medium',
            'created_at'=>'2023-05-21 11:45:11',
            'updated_at'=>'2023-05-21 11:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 1,
            'description' => 'Elon Musk is the #topG .',
            'created_at'=>'2023-05-11 11:45:11',
            'updated_at'=>'2023-05-21 11:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 1,
            'description' => 'this project made be students of #OFPPT ✨✨✨',
            'created_at'=>'2023-05-11 11:45:11',
            'updated_at'=>'2023-05-11 11:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 3,
            'description' => 'GM',
            'image' => 'https://pbs.twimg.com/media/FwkcULoWIAM9dZB?format=jpg&name=small',
            'created_at'=>'2023-05-20 11:45:11',
            'updated_at'=>'2023-05-20 11:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 2,
            'description' => 'ahmad srghini the #topG',
            'created_at'=>'2023-05-18 11:45:11',
            'updated_at'=>'2023-05-18 11:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 3,
            'description' => '#topG fake it until you make it.',
            'created_at'=>'2023-05-20 11:45:11',
            'updated_at'=>'2023-05-20 11:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 3,
            'description' => 'GM🤳',
            'image' => 'https://pbs.twimg.com/media/Fwzqc3TWcAA_Lz3?format=jpg&name=small',
            'created_at'=>'2023-05-23 11:45:11',
            'updated_at'=>'2023-05-23 11:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 2,
            'description' => '#رونالدو',
            'image' => 'https://pbs.twimg.com/media/Fw0ONbOaAAQsM4Q?format=jpg&name=small',
            'created_at'=>'2023-05-23 11:45:11',
            'updated_at'=>'2023-05-23 11:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 4,
            'description' => "After a few months without seeing the one piece anime, it's time to catch up, I have like 30 chapters without seeing and fire moments are coming in the anime and luffy Gear 5 will be animated soon, so it's time. 👨🏻‍🍳🔥 #ONEPIECE #ART",
            'image' => 'https://pbs.twimg.com/media/Fw0U-RYaYAIhAd8?format=jpg&name=small',
            'created_at'=>'2023-05-24 03:45:11',
            'updated_at'=>'2023-05-24 03:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 4,
            'description' => "Ici commence votre success story !
            La CMC de la région Rabat-Salé-Kénitra vous ouvre ses portes.
            Inscrivez-vous vite sur : http://inscription.cmc.ac.ma avant le 15 janvier 2023.
            #CMC #OFPPT #formationprofessionnelle",
            'image' => 'https://pbs.twimg.com/media/FkHc5wxXgAA_JZB?format=jpg&name=small',
            'created_at'=>'2023-05-23 12:45:11',
            'updated_at'=>'2023-05-23 12:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 4,
            'description' => '#TheGodFather 🤣🤣🤣🤣',
            'image' => 'https://pbs.twimg.com/media/Fw0x2Q9XwAEQGxY?format=jpg&name=small',
            'created_at'=>'2023-05-23 17:45:11',
            'updated_at'=>'2023-05-23 12:45:11',
        ]);
        \App\Models\Tweet::create([
            'idUser' => 4,
            'description' => '#رونالدو Cristiano Ronaldo has the most goals in 2023. Remember, he’s 38. Timeless 🐐',
            'image' => 'https://pbs.twimg.com/media/Fw1xDTHXsAEYTdB?format=jpg&name=small',
            'created_at'=>'2023-05-23 17:45:11',
            'updated_at'=>'2023-05-23 12:45:11',
        ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'smail',
        //     'birthDay' => '2001-11-23',
        //     'password' => Hash::make('12345678'),
        //     'email' => 'smail@example.com',
        // ]);
    }
}
