<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Administrator Adpim',
            'email' => 'admin@admin.com', // Gunakan ini untuk login
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 2. Akun Staf (Peliput)
        User::create([
            'name' => 'Staf Peliput Lapangan',
            'email' => 'staf@staf.com', // Gunakan ini untuk login
            'password' => Hash::make('staf123'),
            'role' => 'staf',
        ]);

        // 3. Akun Pimpinan
        User::create([
            'name' => 'Kepala Biro Adpim',
            'email' => 'pimpinan@pimpinan.com', // Gunakan ini untuk login
            'password' => Hash::make('pimpinan123'),
            'role' => 'pimpinan',
        ]);
    }
}