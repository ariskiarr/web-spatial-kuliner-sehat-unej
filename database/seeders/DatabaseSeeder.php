<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);


        User::factory()->create([
            'name' => 'boy',
            'email' => 'boy@example.com',
            'role' => 'admin'

        ]);

        $this -> call([kategori::class,
            Tempat_Makan::class,
            user_favorites::class,
            menu_makan::class,
            ulasan::class,
            kampus::class,

    ]);


    }




}
