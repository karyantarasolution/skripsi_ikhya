<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;

class DokumentasiSeeder extends Seeder
{
    public function run(): void
    {
        if (Dokumentasi::count() > 0) {
            return;
        }

        // File foto asli yang sudah ada di folder uploads agar dapat dirender saat cetak berita acara
        $fotoPaths = [
            'uploads/dokumentasi/1774528658_69c528928ba16.png',
            'uploads/dokumentasi/1774528666_69c5289aa6e36.png',
            'uploads/dokumentasi/1774529198_69c52aae3f185.jpg',
        ];

        Kegiatan::orderBy('id')->get()->each(function ($kegiatan, $idx) use ($fotoPaths) {
            $tanggal = \Carbon\Carbon::parse($kegiatan->tanggal);
            $peliput = $kegiatan->user_id;

            $dokumentasi = [
                [
                    'nama_file' => 'IMG_' . $tanggal->format('Ymd') . '_' . str_pad((string) ($idx % 90 + 10), 4, '0', STR_PAD_LEFT) . '.jpg',
                    'file_path' => $fotoPaths[$idx % count($fotoPaths)],
                    'tipe_file' => 'foto',
                ],
                [
                    'nama_file' => 'DSC_' . $tanggal->format('Ymd') . '_' . str_pad((string) ($idx % 90 + 30), 4, '0', STR_PAD_LEFT) . '.png',
                    'file_path' => $fotoPaths[($idx + 1) % count($fotoPaths)],
                    'tipe_file' => 'foto',
                ],
                [
                    'nama_file' => 'VID_' . $tanggal->format('Ymd') . '_rekaman_kegiatan.mp4',
                    'file_path' => 'uploads/dokumentasi/seed/video_kegiatan.mp4',
                    'tipe_file' => 'video',
                ],
            ];

            // Sebagian kegiatan dilengkapi lampiran dokumen daftar hadir
            if ($idx % 5 == 0) {
                $dokumentasi[] = [
                    'nama_file' => 'DAFTAR_HADIR_' . $tanggal->format('Ymd') . '.pdf',
                    'file_path' => 'uploads/dokumentasi/seed/daftar_hadir.pdf',
                    'tipe_file' => 'dokumen',
                ];
            }

            foreach ($dokumentasi as $doc) {
                Dokumentasi::create(array_merge($doc, [
                    'kegiatan_id' => $kegiatan->id,
                    'user_id' => $peliput,
                ]));
            }
        });
    }
}
