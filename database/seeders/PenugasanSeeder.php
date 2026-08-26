<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\PenugasanLiputan;
use App\Models\User;

class PenugasanSeeder extends Seeder
{
    public function run(): void
    {
        if (PenugasanLiputan::count() > 0) {
            return;
        }

        $peliput = User::whereIn('email', [
            'staf@staf.com',
            'aulia.rahma@adpim.kalselprov.go.id',
            'fajar.nugroho@adpim.kalselprov.go.id',
            'siti.nurhaliza@adpim.kalselprov.go.id',
            'dedi.kurniawan@adpim.kalselprov.go.id',
        ])->get();

        $tugasPool = [
            'Dokumentasi foto dan video kegiatan',
            'Pembuatan naskah berita kegiatan',
            'Pendokumentasian jalannya rapat',
            'Liputan langsung di lapangan',
            'Pengambilan gambar pejabat hadir',
            'Penyusunan materi publikasi',
        ];

        $keteranganPool = [
            'Hadir 30 menit sebelum acara dimulai dan koordinasi dengan Bagian Protokol.',
            'Melakukan pengambilan gambar secara menyeluruh mulai dari pembukaan hingga penutupan.',
            'Hasil dokumentasi diserahkan ke seksi dokumentasi pada hari yang sama.',
            'Mendampingi pejabat serta mencatat poin-poin penting selama kegiatan.',
            'Berkoordinasi dengan bagian humas untuk kebutuhan siaran pers.',
        ];

        $urutan = 0;

        Kegiatan::orderBy('id')->get()->each(function ($kegiatan) use ($peliput, $tugasPool, $keteranganPool, &$urutan) {
            $pemilik = User::find($kegiatan->user_id);

            PenugasanLiputan::create([
                'kegiatan_id' => $kegiatan->id,
                'user_id' => $pemilik->id,
                'jenis' => 'individu',
                'tugas' => $tugasPool[$urutan % count($tugasPool)],
                'keterangan' => $keteranganPool[$urutan % count($keteranganPool)],
            ]);

            // Sebagian kegiatan ditugaskan sebagai tim (2 peliput)
            if ($urutan % 8 == 0) {
                $rekan = $peliput->first(fn ($u) => $u->id !== $pemilik->id);
                PenugasanLiputan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'user_id' => $rekan->id,
                    'jenis' => 'tim',
                    'tugas' => 'Membantu dokumentasi dan pengambilan gambar sekunder',
                    'keterangan' => 'Bertugas membantu peliput utama saat kegiatan berlangsung.',
                ]);
            }

            $urutan++;
        });
    }
}
