<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed User Admin (Aman dari error duplicate entry)
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin System',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Seed Default Settings untuk Tampilan Dashboard
        $defaultSettings = [
            'site_title' => 'Global Supply Chain Risk Intelligence Platform',
            'hero_heading' => 'Real-Time Maritime & Supply Chain Intelligence',
            'hero_subheading' => 'Pantau lalu lintas kapal dan risiko pasokan secara presisi.',
            'announcement_bar' => 'Sistem berjalan normal. Semua data live terhubung.',
            'admin_charts_config' => json_encode([
                ['id' => 'vessel_traffic', 'name' => 'Lalu Lintas Kapal Global', 'type' => 'line', 'order' => 1, 'visible' => true],
                ['id' => 'port_congestion', 'name' => 'Kepadatan Pelabuhan Utama', 'type' => 'bar', 'order' => 2, 'visible' => true],
                ['id' => 'supply_chain_risk', 'name' => 'Indeks Risiko Rantai Pasok', 'type' => 'pie', 'order' => 3, 'visible' => true],
            ])
        ];

        foreach ($defaultSettings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // 3. Seed Sample Ports (Jika Tabel Ports Ada)
        if (\Illuminate\Support\Facades\Schema::hasTable('ports')) {
            DB::table('ports')->updateOrInsert(
                ['code' => 'IDTPP'],
                [
                    'name' => 'Tanjung Priok',
                    'country' => 'Indonesia',
                    'latitude' => -6.1010,
                    'longitude' => 106.8830,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            
            DB::table('ports')->updateOrInsert(
                ['code' => 'SGSIN'],
                [
                    'name' => 'Port of Singapore',
                    'country' => 'Singapore',
                    'latitude' => 1.2640,
                    'longitude' => 103.8400,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}