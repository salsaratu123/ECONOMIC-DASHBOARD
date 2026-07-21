<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gunakan firstOrCreate agar tidak error jika email sudah terdaftar
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Jika ada seeder lain, panggil di sini
        // $this->call([
        //     SettingSeeder::class,
        //     PortSeeder::class,
        // ]);
    }
}