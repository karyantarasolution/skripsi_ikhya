<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\KategoriKegiatan;
use App\Models\User;
use Carbon\Carbon;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        if (Kegiatan::count() > 0) {
            return;
        }

        $kategoriList = KategoriKegiatan::orderBy('id')->get();
        $peliput = User::whereIn('email', [
            'staf@staf.com',
            'aulia.rahma@adpim.kalselprov.go.id',
            'fajar.nugroho@adpim.kalselprov.go.id',
            'siti.nurhaliza@adpim.kalselprov.go.id',
            'dedi.kurniawan@adpim.kalselprov.go.id',
        ])->get()->keyBy('email');

        $pimpinan = User::where('role', 'pimpinan')->first();

        $judulPerKategori = [
            'Rapat Paripurna' => [
                'Rapat Paripurna DPRD Kalsel tentang Raperda APBD Tahun 2026',
                'Rapat Paripurna Penyampaian Pemandangan Umum Fraksi terhadap Raperda Kearsipan Daerah',
                'Rapat Paripurna Penetapan Raperda Perubahan APBD Tahun 2025',
                'Rapat Paripurna DPRD Peringatan HUT ke-75 Provinsi Kalimantan Selatan',
                'Rapat Paripurna Penyampaian LKPJ Gubernur Tahun Anggaran 2025',
                'Rapat Paripurna Pembahasan Raperda RPJPD Provinsi Kalsel 2025-2045',
                'Rapat Paripurna Pengambilan Keputusan Raperda Penataan PKL Kota Banjarbaru',
                'Rapat Paripurna DPRD Penetapan Pimpinan DPRD Masa Jabatan 2026-2031',
                'Rapat Paripurna Persetujuan Raperda tentang Pajak Daerah dan Retribusi Daerah',
                'Rapat Paripurna Pembahasan Raperda Perubahan Kedua tentang Ketertiban Umum',
            ],
            'Kunjungan Kerja (Kunker)' => [
                'Kunjungan Kerja Gubernur ke Kawasan Food Estate Kalimantan Tengah',
                'Kunker Wakil Gubernur ke Kabupaten Kotabaru Meninjau Infrastruktur Pelabuhan',
                'Kunjungan Kerja Gubernur ke Jakarta Audiensi dengan Kementerian PUPR',
                'Kunjungan Kerja Gubernur ke Kabupaten Balangan Meninjau Pembangunan Jembatan',
                'Kunker Tim Biro Adpim ke Kota Banjarmasin Persiapan MTQ Nasional',
                'Kunjungan Kerja Gubernur ke Kabupaten Tanah Bumbu Meninjau Kawasan Industri Batulicin',
                'Kunker Wakil Gubernur ke Kabupaten Hulu Sungai Selatan Meninjau Bendungan Tapin',
                'Kunjungan Kerja Gubernur ke Kabupaten Tabalong Panen Raya Padi',
                'Kunker Delegasi Pemprov Kalsel ke Provinsi Kalimantan Timur Studi Banding E-Government',
                'Kunjungan Kerja Gubernur ke Kabupaten Banjar Meninjau Revitalisasi Pasar Rantau',
            ],
            'Audiensi / Penerimaan Tamu' => [
                'Audiensi Pengurus Barisan Muda Indonesia dengan Gubernur di Ruang Kerja',
                'Penerimaan Audiensi PHRI Kalimantan Selatan oleh Sekda Prov. Kalsel',
                'Audiensi Direksi Bank Kalsel dengan Sekda terkait Penyaluran KUR Daerah',
                'Penerimaan Kunjungan Tim Verifikasi Lomba Inovasi Pelayanan Publik',
                'Audiensi DPD PWI Kalsel tentang Program Peliputan Media Pemerintah',
                'Penerimaan Audiensi BAZNAS Kalsel terkait Zakat ASN Pemprov',
                'Audiensi Rektor UNLAM dengan Gubernur tentang Kerjasama Riset Daerah',
                'Penerimaan Audiensi Pengurus Aisyiyah Kalsel dalam Rangka Muswil',
                'Audiensi Asosiasi Petani Kelapa Sawit dengan Wakil Gubernur di Rumah Banjar',
                'Penerimaan Audiensi PT Angkasa Pura II terkait Pengembangan Bandara Syamsudin Noor',
            ],
            'Upacara / Peringatan' => [
                'Upacara Peringatan HUT ke-78 Kemerdekaan RI di Halaman Kantor Gubernur',
                'Upacara Peringatan Hari Jadi ke-75 Provinsi Kalimantan Selatan',
                'Upacara Peringatan Hari Kesaktian Pancasila di Lapangan Kantor Gubernur',
                'Peringatan Hari Anti Korupsi Sedunia oleh Pemprov Kalsel',
                'Upacara Peringatan Hari Otonomi Daerah se-Indonesia di Banjarbaru',
                'Peringatan Hari Bumi se-Kalimantan Selatan di Kota Banjarbaru',
                'Upacara Peringatan Hari Santri Nasional di Pelataran Kantor Gubernur',
                'Peringatan Hari Kesehatan Nasional bersama RSUD Ulin Banjarmasin',
                'Upacara Peringatan Hari Pendidikan Nasional di Halaman Setda Provinsi Kalsel',
                'Peringatan Hari Pahlawan dengan Ziarah Nasional di TMP Banjarbaru',
            ],
            'Inspeksi Mendadak (Sidak)' => [
                'Sidak Gubernur ke Mall Pelayanan Publik (MPP) Kota Banjarbaru',
                'Sidak Tim Biro Adpim ke Rumah Sakit Daerah Penerima Bantuan',
                'Sidak Gubernur ke Gudang Beras Bulog Banjarmasin',
                'Sidak ke Posko Pengendalian Banjir Kabupaten Banjar',
                'Sidak Gubernur ke Pasar Terapung Lok Baintan Kabupaten Banjar',
                'Sidak Tim Biro Adpim ke Proyek Pembangunan Gedung UPTD Barito Kuala',
                'Sidak Gubernur ke TPA Regional Kabupaten Tanah Laut',
                'Sidak ke Lokasi Bencana Banjir Kabupaten Hulu Sungai Utara',
                'Sidak Gubernur ke Sekolah Dasar Penerima BOS di Banjarbaru',
                'Sidak Tim Biro Adpim ke Kios Sembako Pasar Bauntung Banjarbaru',
            ],
        ];

        $lokasiPool = [
            'Kantor Gubernur Kalsel, Banjarbaru',
            'Ruang Rapat Paripurna DPRD Kalsel, Banjarbaru',
            'Aula Idham Chalid Setda Prov. Kalsel, Banjarbaru',
            'Kawasan Perkantoran Pemprov Kalsel, Banjarbaru',
            'Halaman Kantor Gubernur Kalsel, Banjarbaru',
            'Gedung Sultan Suriansyah, Banjarmasin',
            'Rumah Banjar Setda Prov. Kalsel, Banjarbaru',
            'Kota Banjarmasin',
            'Kabupaten Kotabaru',
            'Kabupaten Tanah Bumbu',
            'Kabupaten Balangan',
            'Kabupaten Banjar',
            'Kabupaten Hulu Sungai Selatan',
            'Kabupaten Tabalong',
            'Kabupaten Hulu Sungai Utara',
            'Kabupaten Tanah Laut',
            'Kabupaten Barito Kuala',
        ];

        $lokasiPerKategori = [
            'Rapat Paripurna' => ['Ruang Rapat Paripurna DPRD Kalsel, Banjarbaru', 'Gedung Sultan Suriansyah, Banjarmasin', 'Kantor Gubernur Kalsel, Banjarbaru'],
            'Kunjungan Kerja (Kunker)' => ['Kabupaten Kotabaru', 'Kabupaten Tanah Bumbu', 'Kabupaten Balangan', 'Kabupaten Banjar', 'Kabupaten Tabalong', 'Kabupaten Hulu Sungai Selatan', 'Kota Banjarmasin'],
            'Audiensi / Penerimaan Tamu' => ['Ruang Kerja Gubernur, Kantor Gubernur Kalsel, Banjarbaru', 'Rumah Banjar Setda Prov. Kalsel, Banjarbaru', 'Aula Idham Chalid Setda Prov. Kalsel, Banjarbaru'],
            'Upacara / Peringatan' => ['Halaman Kantor Gubernur Kalsel, Banjarbaru', 'Lapangan Kantor Gubernur Kalsel, Banjarbaru', 'Kawasan Perkantoran Pemprov Kalsel, Banjarbaru', 'TMP Banjarbaru'],
            'Inspeksi Mendadak (Sidak)' => ['Kota Banjarbaru', 'Kota Banjarmasin', 'Kabupaten Banjar', 'Kabupaten Tanah Laut', 'Kabupaten Hulu Sungai Utara', 'Kabupaten Barito Kuala'],
        ];

        $pejabatHadirPool = [
            'Gubernur Kalimantan Selatan',
            'Wakil Gubernur Kalimantan Selatan',
            'Sekretaris Daerah Provinsi Kalsel',
            'H. Muhammad Syahrani, S.Sos., M.Si (Kepala Biro Adpim)',
            'Hj. Noor Aisyah, S.E., M.M. (Kepala Bagian Protokol)',
            'Ketua DPRD Provinsi Kalsel',
            'Para Anggota DPRD Provinsi Kalsel',
            'Kepala Dinas Komunikasi dan Informatika Kalsel',
            'Kepala Dinas Pekerjaan Umum dan Penataan Ruang Kalsel',
            'Kepala Badan Perencanaan Pembangunan Daerah Kalsel',
            'Rektor Universitas Lambung Mangkurat',
            'Direktur Utama Bank Kalsel',
        ];

        $deskripsiPool = [
            'Kegiatan berlangsung dengan tertib dan lancar, dihadiri oleh pejabat terkait serta tamu undangan.',
            'Peliputan meliputi dokumentasi foto dan video untuk kebutuhan kehumasan dan arsip Biro Adpim.',
            'Rangkaian kegiatan dilaksanakan sesuai jadwal yang ditetapkan dengan pengamanan yang memadai.',
            'Acara berjalan khidmat dan lancar. Protokol biro adpim bertugas mengatur jalannya kegiatan.',
            'Dokumentasi hasil liputan diserahkan kepada bagian dokumentasi untuk diarsipkan.',
            'Kegiatan ini merupakan rangkaian agenda kerja Gubernur/Wakil Gubernur dalam kunjungan ke daerah.',
            'Selama kegiatan, staf peliput mendokumentasikan momen penting untuk bahan pemberitaan resmi.',
            'Acara dihadiri lebih dari seratus peserta yang terdiri atas pejabat, ASN, dan undangan terkait.',
        ];

        $waktuPool = ['08:00', '08:30', '09:00', '09:30', '10:00', '13:00', '13:30', '14:00', '19:00'];

        $today = Carbon::now()->toDateString();
        $urutan = 0;

        foreach ($kategoriList as $cIdx => $kategori) {
            $titles = $judulPerKategori[$kategori->nama_kategori] ?? [];

            foreach ($titles as $i => $judul) {
                $year = ($i % 2 == 0) ? 2025 : 2026;
                $month = (($i * 7 + $cIdx * 3) % 12) + 1;
                $day = 8 + $i;
                $tanggal = Carbon::create($year, $month, $day);

                $peliputEmail = ['staf@staf.com', 'aulia.rahma@adpim.kalselprov.go.id', 'fajar.nugroho@adpim.kalselprov.go.id', 'siti.nurhaliza@adpim.kalselprov.go.id', 'dedi.kurniawan@adpim.kalselprov.go.id'][$urutan % 5];
                $user = $peliput[$peliputEmail];

                $lokasiList = $lokasiPerKategori[$kategori->nama_kategori] ?? $lokasiPool;
                $lokasi = $lokasiList[$i % count($lokasiList)];

                $pejabat = $pejabatHadirPool[($urutan + $cIdx) % count($pejabatHadirPool)];
                $deskripsi = $deskripsiPool[$urutan % count($deskripsiPool)];
                $waktu = $waktuPool[$urutan % count($waktuPool)];

                $status = ($tanggal->toDateString() >= $today) ? 'disetujui' : 'selesai';

                if ($i == 2) {
                    $status = 'lpj';
                } elseif ($i == 4) {
                    $status = 'diajukan';
                } elseif ($i == 6) {
                    $status = 'pelaksanaan';
                }

                $data = [
                    'user_id' => $user->id,
                    'kategori_id' => $kategori->id,
                    'judul_kegiatan' => $judul,
                    'tanggal' => $tanggal->toDateString(),
                    'waktu' => $waktu,
                    'lokasi' => $lokasi,
                    'pejabat_hadir' => $pejabat,
                    'deskripsi' => $deskripsi,
                    'status' => $status,
                ];

                if (in_array($status, ['disetujui', 'pelaksanaan', 'selesai', 'lpj'])) {
                    $data['approved_by'] = $pimpinan->id ?? null;
                    $data['approved_at'] = $tanggal->copy()->subDay()->setTime(10, 0);
                }

                Kegiatan::create($data);
                $urutan++;
            }
        }
    }
}
