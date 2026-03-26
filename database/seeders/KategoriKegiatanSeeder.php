<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriKegiatan;

class KategoriKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            [
                'nama_kategori' => 'Rapat Paripurna',
                'deskripsi' => 'Rapat paripurna bersama DPRD Provinsi',
                'warna_label' => '#EF4444', // Merah
            ],
            [
                'nama_kategori' => 'Kunjungan Kerja (Kunker)',
                'deskripsi' => 'Kunjungan kerja Gubernur/Wagub ke daerah atau instansi lain',
                'warna_label' => '#10B981', // Hijau
            ],
            [
                'nama_kategori' => 'Audiensi / Penerimaan Tamu',
                'deskripsi' => 'Menerima kunjungan tamu kehormatan atau tokoh masyarakat',
                'warna_label' => '#F59E0B', // Kuning/Orange
            ],
            [
                'nama_kategori' => 'Upacara / Peringatan',
                'deskripsi' => 'Upacara hari besar nasional atau daerah',
                'warna_label' => '#3B82F6', // Biru
            ],
            [
                'nama_kategori' => 'Inspeksi Mendadak (Sidak)',
                'deskripsi' => 'Peninjauan langsung ke lapangan secara mendadak',
                'warna_label' => '#8B5CF6', // Ungu
            ],
        ];

        foreach ($kategori as $kat) {
            KategoriKegiatan::create($kat);
        }
    }
}