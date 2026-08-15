<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratPerjalananDinas;
use App\Models\SppdPeserta;
use App\Models\SppdBiaya;
use App\Models\Kegiatan;
use App\Models\User;
use App\Support\NomorSurat;
use Carbon\Carbon;

class SppdSeeder extends Seeder
{
    public function run(): void
    {
        if (SuratPerjalananDinas::count() > 0) {
            return;
        }

        $peliput = User::whereIn('email', [
            'staf@staf.com',
            'aulia.rahma@adpim.kalselprov.go.id',
            'fajar.nugroho@adpim.kalselprov.go.id',
            'siti.nurhaliza@adpim.kalselprov.go.id',
            'dedi.kurniawan@adpim.kalselprov.go.id',
        ])->get();

        $kegiatan = Kegiatan::orderBy('id')->get();
        $kegiatanDinas = $kegiatan->slice(0, 10)->values();

        $dataSppd = [
            [
                'tujuan' => 'Menghadiri Rapat Koordinasi Nasional Pengendalian Inflasi Daerah',
                'kota_tujuan' => 'Jakarta',
                'tanggal_berangkat' => '2026-01-14',
                'tanggal_kembali' => '2026-01-16',
                'kendaraan' => 'Pesawat Terbang',
                'keterangan' => 'Mendampingi Sekretaris Daerah dalam rakor nasional bersama Kementerian Dalam Negeri.',
                'status' => 'disetujui',
                'luar_daerah' => true,
            ],
            [
                'tujuan' => 'Koordinasi Persiapan Pelaksanaan MTQ Nasional',
                'kota_tujuan' => 'Banjarmasin',
                'tanggal_berangkat' => '2025-02-10',
                'tanggal_kembali' => '2025-02-11',
                'kendaraan' => 'Mobil Dinas',
                'keterangan' => 'Koordinasi dengan panitia daerah terkait kebutuhan dokumentasi.',
                'status' => 'disetujui',
                'luar_daerah' => false,
            ],
            [
                'tujuan' => 'Pendampingan Gubernur dalam Kunjungan Kerja',
                'kota_tujuan' => 'Kotabaru',
                'tanggal_berangkat' => '2025-04-22',
                'tanggal_kembali' => '2025-04-24',
                'kendaraan' => 'Kapal Cepat',
                'keterangan' => 'Peliputan kunjungan kerja dan peninjauan infrastruktur pelabuhan.',
                'status' => 'disetujui',
                'luar_daerah' => false,
            ],
            [
                'tujuan' => 'Sosialisasi SPBE dan E-Government se-Kalimantan',
                'kota_tujuan' => 'Balikpapan',
                'tanggal_berangkat' => '2025-06-09',
                'tanggal_kembali' => '2025-06-12',
                'kendaraan' => 'Pesawat Terbang',
                'keterangan' => 'Mengikuti sosialisasi dan berbagi pengalaman penerapan SPBE Pemprov Kalsel.',
                'status' => 'diajukan',
                'luar_daerah' => true,
            ],
            [
                'tujuan' => 'Monitoring dan Evaluasi Program Food Estate',
                'kota_tujuan' => 'Palangka Raya',
                'tanggal_berangkat' => '2025-08-19',
                'tanggal_kembali' => '2025-08-21',
                'kendaraan' => 'Mobil Dinas',
                'keterangan' => 'Dokumentasi kegiatan monitoring program ketahanan pangan nasional.',
                'status' => 'disetujui',
                'luar_daerah' => true,
            ],
            [
                'tujuan' => 'Rapat Koordinasi Penanganan Banjir',
                'kota_tujuan' => 'Amuntai',
                'tanggal_berangkat' => '2025-10-06',
                'tanggal_kembali' => '2025-10-07',
                'kendaraan' => 'Mobil Dinas',
                'keterangan' => 'Pendampingan tim tanggap bencana dan peliputan kondisi lapangan.',
                'status' => 'disetujui',
                'luar_daerah' => false,
            ],
            [
                'tujuan' => 'Pendampingan Wakil Gubernur Panen Raya Padi',
                'kota_tujuan' => 'Tanjung',
                'tanggal_berangkat' => '2026-03-17',
                'tanggal_kembali' => '2026-03-18',
                'kendaraan' => 'Mobil Dinas',
                'keterangan' => 'Peliputan kegiatan panen raya bersama petani di Kabupaten Tabalong.',
                'status' => 'disetujui',
                'luar_daerah' => false,
            ],
            [
                'tujuan' => 'Studi Tiru Pengelolaan Layanan Publik',
                'kota_tujuan' => 'Surabaya',
                'tanggal_berangkat' => '2026-05-12',
                'tanggal_kembali' => '2026-05-15',
                'kendaraan' => 'Pesawat Terbang',
                'keterangan' => 'Studi banding pengelolaan mall pelayanan publik dan sistem informasinya.',
                'status' => 'draf',
                'luar_daerah' => true,
            ],
            [
                'tujuan' => 'Koordinasi Pengembangan Bandara Syamsudin Noor',
                'kota_tujuan' => 'Banjarmasin',
                'tanggal_berangkat' => '2026-07-07',
                'tanggal_kembali' => '2026-07-07',
                'kendaraan' => 'Mobil Dinas',
                'keterangan' => 'Rapat koordinasi lanjutan dengan PT Angkasa Pura II.',
                'status' => 'disetujui',
                'luar_daerah' => false,
            ],
            [
                'tujuan' => 'Pendampingan Gubernur Audiensi dengan Kementerian PUPR',
                'kota_tujuan' => 'Jakarta',
                'tanggal_berangkat' => '2026-09-01',
                'tanggal_kembali' => '2026-09-03',
                'kendaraan' => 'Pesawat Terbang',
                'keterangan' => 'Dokumentasi audiensi terkait dukungan pembangunan infrastruktur daerah.',
                'status' => 'diajukan',
                'luar_daerah' => true,
            ],
        ];

        $urutanPerTahun = [];

        foreach ($dataSppd as $idx => $d) {
            $tanggalBerangkat = Carbon::parse($d['tanggal_berangkat']);
            $tanggalKembali = Carbon::parse($d['tanggal_kembali']);
            $tahun = $tanggalBerangkat->format('Y');

            $urutanPerTahun[$tahun] = ($urutanPerTahun[$tahun] ?? 0) + 1;
            $noSurat = NomorSurat::format('SPPD', $urutanPerTahun[$tahun], $tanggalBerangkat);

            $penulis = $peliput[$idx % count($peliput)];
            $kegiatanTerkait = $kegiatanDinas->get($idx % $kegiatanDinas->count());

            $sppd = SuratPerjalananDinas::create([
                'user_id' => $penulis->id,
                'kegiatan_id' => $kegiatanTerkait->id,
                'no_surat' => $noSurat,
                'tujuan' => $d['tujuan'],
                'kota_tujuan' => $d['kota_tujuan'],
                'tanggal_berangkat' => $d['tanggal_berangkat'],
                'tanggal_kembali' => $d['tanggal_kembali'],
                'kendaraan' => $d['kendaraan'],
                'pembebanan' => 'APBD Provinsi Kalimantan Selatan Tahun ' . $tahun,
                'keterangan' => $d['keterangan'],
                'status' => $d['status'],
            ]);

            $lamaHari = $tanggalBerangkat->diffInDays($tanggalKembali) + 1;

            // Peserta (1 - 3 orang)
            $jumlahPeserta = [1, 1, 2, 2, 1, 2, 3, 1, 2, 3][$idx];
            $pesertaTerpilih = collect(range(0, $jumlahPeserta - 1))
                ->map(fn ($offset) => $peliput[($idx + $offset) % count($peliput)]);

            foreach ($pesertaTerpilih as $peserta) {
                SppdPeserta::create([
                    'sppd_id' => $sppd->id,
                    'user_id' => $peserta->id,
                ]);
            }

            // Rincian biaya
            $uangHarian = $d['luar_daerah'] ? 370000 : 200000;
            $biayaItems = [
                [
                    'uraian' => 'Uang harian perjalanan dinas',
                    'volume' => $lamaHari,
                    'satuan' => 'OH',
                    'harga_satuan' => $uangHarian,
                ],
                [
                    'uraian' => str_contains($d['kendaraan'], 'Pesawat') ? 'Tiket pesawat perjalanan dinas' : 'Biaya transportasi darat (BBM, tol, parkir)',
                    'volume' => 1,
                    'satuan' => str_contains($d['kendaraan'], 'Pesawat') ? 'Tiket' : 'Paket',
                    'harga_satuan' => str_contains($d['kendaraan'], 'Pesawat') ? 1200000 : 600000,
                ],
                [
                    'uraian' => 'Penginapan hotel',
                    'volume' => max(1, $lamaHari - 1),
                    'satuan' => 'Malam',
                    'harga_satuan' => 450000,
                ],
            ];

            if ($lamaHari >= 3 && ! str_contains($d['kendaraan'], 'Pesawat')) {
                $biayaItems[] = [
                    'uraian' => 'Sewa kendaraan roda empat',
                    'volume' => $lamaHari,
                    'satuan' => 'Hari',
                    'harga_satuan' => 350000,
                ];
            }

            foreach ($biayaItems as $item) {
                SppdBiaya::create([
                    'sppd_id' => $sppd->id,
                    'uraian' => $item['uraian'],
                    'volume' => $item['volume'],
                    'satuan' => $item['satuan'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total' => $item['volume'] * $item['harga_satuan'],
                ]);
            }
        }
    }
}
