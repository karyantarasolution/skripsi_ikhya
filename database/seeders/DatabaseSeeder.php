<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seluruh seeder secara berurutan
        $this->call([
            UserSeeder::class,
            KategoriKegiatanSeeder::class,
            PenandatanganSeeder::class,
            KegiatanSeeder::class,
            PenugasanSeeder::class,
            DokumentasiSeeder::class,
            SppdSeeder::class,
            LpjTugasSeeder::class,
        ]);
    }
}