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
            'name' => 'Test User',
            'birthDay' => '2001-11-23',
            'password' => Hash::make('1234567'),
            'pseudo'=>'smail',
            'email' => 'smail@smail.com',
        ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'smail',
        //     'birthDay' => '2001-11-23',
        //     'password' => Hash::make('12345678'),
        //     'email' => 'smail@example.com',
        // ]);
    }
}
