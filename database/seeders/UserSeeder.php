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
        $users = [
            // ===== AKUN UTAMA UNTUK LOGIN =====
            [
                'name' => 'Administrator Adpim Setda Prov. Kalsel',
                'email' => 'admin@admin.com', // Gunakan ini untuk login admin
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nip' => '197903152005011002',
            ],
            [
                'name' => 'H. Muhammad Syahrani, S.Sos., M.Si',
                'email' => 'pimpinan@pimpinan.com', // Gunakan ini untuk login pimpinan
                'password' => Hash::make('pimpinan123'),
                'role' => 'pimpinan',
                'nip' => '197108231998031004',
            ],
            [
                'name' => 'Muhammad Rizky Ramadhan',
                'email' => 'staf@staf.com', // Gunakan ini untuk login staf
                'password' => Hash::make('staf123'),
                'role' => 'staf',
                'nip' => '199203102019031005',
            ],

            // ===== STAF PELIPUT LAPANGAN =====
            [
                'name' => 'Aulia Rahma Putri',
                'email' => 'aulia.rahma@adpim.kalselprov.go.id',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'nip' => '199504212022032006',
            ],
            [
                'name' => 'Fajar Nugroho',
                'email' => 'fajar.nugroho@adpim.kalselprov.go.id',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'nip' => '199001152019031007',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@adpim.kalselprov.go.id',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'nip' => '199612082022032008',
            ],
            [
                'name' => 'Dedi Kurniawan',
                'email' => 'dedi.kurniawan@adpim.kalselprov.go.id',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'nip' => '198805242019031009',
            ],

            // ===== STAF ADMINISTRATIF / PENDUKUNG =====
            [
                'name' => 'Rina Wulandari',
                'email' => 'rina.wulandari@adpim.kalselprov.go.id',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'nip' => '199102112020122010',
            ],
            [
                'name' => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@adpim.kalselprov.go.id',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'nip' => '198707192020121011',
            ],
            [
                'name' => 'Yulia Safitri',
                'email' => 'yulia.safitri@adpim.kalselprov.go.id',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'nip' => '199309252021032012',
            ],
            [
                'name' => 'Andi Prasetyo',
                'email' => 'andi.prasetyo@adpim.kalselprov.go.id',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'nip' => '198903022021031013',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@adpim.kalselprov.go.id',
                'password' => Hash::make('password'),
                'role' => 'staf',
                'nip' => '199511182022032014',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
