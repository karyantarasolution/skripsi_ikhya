<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penandatangan;

class PenandatanganSeeder extends Seeder
{
    public function run(): void
    {
        if (Penandatangan::count() > 0) {
            return;
        }

        $pejabat = [
            [
                'nama_pejabat' => 'H. Muhammad Syahrani, S.Sos., M.Si',
                'nip' => '197108231998031004',
                'jabatan' => 'Kepala Biro Administrasi Pimpinan',
                'is_aktif' => true,
            ],
            [
                'nama_pejabat' => 'Drs. H. Ahmad Fauzan, M.M.',
                'nip' => '196705281993031002',
                'jabatan' => 'Sekretaris Daerah Provinsi Kalimantan Selatan',
                'is_aktif' => true,
            ],
            [
                'nama_pejabat' => 'Hj. Noor Aisyah, S.E., M.M.',
                'nip' => '197409102000032005',
                'jabatan' => 'Kepala Bagian Protokol',
                'is_aktif' => true,
            ],
            [
                'nama_pejabat' => 'Ir. Bambang Supriyadi, M.T.',
                'nip' => '196903142000031003',
                'jabatan' => 'Kepala Bagian Umum',
                'is_aktif' => true,
            ],
            [
                'nama_pejabat' => 'H. Rahmadi, S.H., M.H.',
                'nip' => '197510152002121004',
                'jabatan' => 'Kepala Bagian Kerjasama',
                'is_aktif' => true,
            ],
            [
                'nama_pejabat' => 'Dra. Hj. Rusmawati, M.Pd.',
                'nip' => '196812032000122006',
                'jabatan' => 'Kepala Bagian Persandian & Statistik',
                'is_aktif' => true,
            ],
            [
                'nama_pejabat' => 'Muhammad Yusuf, S.T., M.T.',
                'nip' => '198101262006041007',
                'jabatan' => 'Kepala Sub Bagian Administrasi Biro Adpim',
                'is_aktif' => true,
            ],
            [
                'nama_pejabat' => 'H. Fathurrahman, S.Ag.',
                'nip' => '197012012003121008',
                'jabatan' => 'Kepala Bagian Hubungan Masyarakat',
                'is_aktif' => true,
            ],
            [
                'nama_pejabat' => 'Hj. Siti Rahma, S.Pd., M.A.P.',
                'nip' => '197702122002122009',
                'jabatan' => 'Kepala Bagian Kesejahteraan Rakyat',
                'is_aktif' => true,
            ],
            [
                'nama_pejabat' => 'Dr. H. Anwar Sadat, M.Si.',
                'nip' => '196811052001121010',
                'jabatan' => 'Kepala Bagian Perekonomian & SDA',
                'is_aktif' => false,
            ],
        ];

        foreach ($pejabat as $p) {
            Penandatangan::create($p);
        }
    }
}
