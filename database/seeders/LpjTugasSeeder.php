<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LpjTugas;
use App\Models\LpjTugasBukti;
use App\Models\Kegiatan;
use App\Support\NomorSurat;
use Carbon\Carbon;

class LpjTugasSeeder extends Seeder
{
    public function run(): void
    {
        if (LpjTugas::count() > 0) {
            return;
        }

        $kegiatanTerlaksana = Kegiatan::with(['penugasan', 'user'])
            ->whereIn('status', ['selesai', 'lpj'])
            ->orderBy('id')
            ->take(10)
            ->get();

        $uraianHasilPool = [
            'Seluruh rangkaian kegiatan terdokumentasi dengan baik dalam bentuk foto dan video.',
            'Naskah berita kegiatan berhasil disusun dan dipublikasikan melalui kanal resmi.',
            'Dokumentasi kegiatan diserahkan kepada seksi dokumentasi untuk keperluan arsip.',
            'Liputan berjalan lancar, seluruh momen penting berhasil direkam dengan kualitas baik.',
            'Materi publikasi berhasil dikompilasi dan didistribusikan kepada media terkait.',
        ];

        $penanggungJawab = [
            ['nama' => 'H. Muhammad Syahrani, S.Sos., M.Si', 'jabatan' => 'Kepala Biro Administrasi Pimpinan'],
            ['nama' => 'Hj. Noor Aisyah, S.E., M.M.', 'jabatan' => 'Kepala Bagian Protokol'],
            ['nama' => 'Ir. Bambang Supriyadi, M.T.', 'jabatan' => 'Kepala Bagian Umum'],
        ];

        $fotoPaths = [
            'uploads/dokumentasi/1774528658_69c528928ba16.png',
            'uploads/dokumentasi/1774528666_69c5289aa6e36.png',
            'uploads/dokumentasi/1774529198_69c52aae3f185.jpg',
        ];

        $urutanPerTahun = [];

        foreach ($kegiatanTerlaksana as $idx => $kegiatan) {
            $penugasan = $kegiatan->penugasan->first();

            $tanggalLpj = Carbon::parse($kegiatan->tanggal)->addDays(3);
            if ($tanggalLpj->isFuture()) {
                $tanggalLpj = Carbon::now()->subDays(5);
            }

            $tahun = $tanggalLpj->format('Y');
            $urutanPerTahun[$tahun] = ($urutanPerTahun[$tahun] ?? 0) + 1;
            $noLpj = NomorSurat::format('LPJ', $urutanPerTahun[$tahun], $tanggalLpj);

            $pj = $penanggungJawab[$idx % count($penanggungJawab)];
            $uraian = $uraianHasilPool[$idx % count($uraianHasilPool)];

            $lpj = LpjTugas::create([
                'user_id' => $kegiatan->user_id,
                'penugasan_id' => $penugasan->id ?? null,
                'kegiatan_id' => $kegiatan->id,
                'no_lpj' => $noLpj,
                'uraian_hasil' => $uraian,
                'penanggung_jawab_nama' => $pj['nama'],
                'penanggung_jawab_jabatan' => $pj['jabatan'],
                'tanggal_lpj' => $tanggalLpj->toDateString(),
            ]);

            $jumlahBukti = ($idx % 3 == 0) ? 2 : 1;
            for ($b = 0; $b < $jumlahBukti; $b++) {
                LpjTugasBukti::create([
                    'lpj_tugas_id' => $lpj->id,
                    'file' => $fotoPaths[($idx + $b) % count($fotoPaths)],
                    'keterangan' => 'Dokumentasi pelaksanaan kegiatan ' . $kegiatan->judul_kegiatan,
                ]);
            }
        }
    }
}
