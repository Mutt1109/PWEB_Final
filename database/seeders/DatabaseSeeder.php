<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\IklMatch;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 * Mengisi data awal: akun admin, akun user, dan beberapa pertandingan contoh.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== AKUN ADMIN =====
        // Email: admin@gmail.com | Password: 1234
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('1234'),
                'role'     => 'admin',
            ]
        );

        // ===== AKUN USER BIASA =====
        // Email: user@gmail.com | Password: 1234
        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'     => 'User',
                'password' => bcrypt('1234'),
                'role'     => 'user',
            ]
        );

        // ===== DATA PERTANDINGAN CONTOH =====
        $matches = [
            ['tim_tamu' => 'RRQ',         'tim_kandang' => 'ONIC Esports',   'tanggal' => '2026-07-10', 'jam' => '19:00', 'venue' => 'GBK Arena Jakarta',    'harga' => 75000,  'kapasitas' => 500],
            ['tim_tamu' => 'Bigetron',     'tim_kandang' => 'Alter Ego',      'tanggal' => '2026-07-12', 'jam' => '15:00', 'venue' => 'ICE BSD Tangerang',     'harga' => 50000,  'kapasitas' => 300],
            ['tim_tamu' => 'Kagendra',     'tim_kandang' => 'Dominator',      'tanggal' => '2026-07-15', 'jam' => '19:00', 'venue' => 'GBK Arena Jakarta',    'harga' => 60000,  'kapasitas' => 400],
            ['tim_tamu' => 'Talon ID',     'tim_kandang' => 'Mahadewa',       'tanggal' => '2026-07-18', 'jam' => '16:00', 'venue' => 'Istora Senayan',        'harga' => 80000,  'kapasitas' => 600],
            ['tim_tamu' => 'ONIC Esports', 'tim_kandang' => 'Bigetron',       'tanggal' => '2026-07-20', 'jam' => '19:00', 'venue' => 'GBK Arena Jakarta',    'harga' => 100000, 'kapasitas' => 500],
        ];

        foreach ($matches as $match) {
            IklMatch::firstOrCreate(
                ['tim_tamu' => $match['tim_tamu'], 'tim_kandang' => $match['tim_kandang'], 'tanggal' => $match['tanggal']],
                $match
            );
        }
    }
}
