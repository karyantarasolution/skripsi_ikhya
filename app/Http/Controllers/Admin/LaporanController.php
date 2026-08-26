<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\KategoriKegiatan;
use App\Models\User;
use App\Models\Penandatangan;
use App\Models\Dokumentasi;
use App\Models\SuratPerjalananDinas;
use App\Models\LpjTugas;
use App\Models\RiwayatTtdDigital;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LaporanController extends Controller
{
    private $secretKey = 'SIA-BIROPIM-KALSEL-2026-SEC';

    public function index()
    {
        $kategori = KategoriKegiatan::orderBy('nama_kategori', 'asc')->get();
        $peliput = User::whereIn('role', ['admin', 'staf'])->orderBy('name', 'asc')->get();
        $kegiatan_list = Kegiatan::orderBy('tanggal', 'desc')->get();
        $penandatangan = Penandatangan::where('is_aktif', true)->get();

        return view('laporan.index', compact('kategori', 'peliput', 'kegiatan_list', 'penandatangan'));
    }

    private function generatePdf($view, $data, $filename, $paper = 'a4', $orientation = 'portrait')
    {
        $pdf = Pdf::loadView($view, $data);
        return $pdf->setPaper($paper, $orientation)->stream($filename);
    }

    private function getCommonCetakData(Request $request)
    {
        $request->validate(['penandatangan_id' => 'required|exists:penandatangans,id']);
        return Penandatangan::findOrFail($request->penandatangan_id);
    }

    private function generateLaporanQr(string $jenisLaporan, Penandatangan $penandatangan, array $params = []): array
    {
        $hashPayload = $jenisLaporan . '|' . $penandatangan->id . '|' . json_encode($params) . '|' . now()->timestamp . '|' . $this->secretKey;
        $hash = hash('sha256', $hashPayload);

        $verifyUrl = route('verifikasi-dokumen', $hash);

        $qrDirectory = public_path('uploads/qrcodes/laporan');
        if (!File::exists($qrDirectory)) {
            File::makeDirectory($qrDirectory, 0755, true);
        }

        $qrFileName = 'laporan_' . Str::slug($jenisLaporan) . '_' . time() . '.svg';
        $qrPath = 'uploads/qrcodes/laporan/' . $qrFileName;

        $qrSvg = QrCode::format('svg')
            ->size(150)
            ->margin(2)
            ->generate($verifyUrl);

        $qrDirectory = public_path('uploads/qrcodes/laporan');
        if (!File::exists($qrDirectory)) {
            File::makeDirectory($qrDirectory, 0755, true);
        }
        file_put_contents(public_path($qrPath), $qrSvg);

        $nomorDokumen = 'LAP-' . strtoupper(Str::slug($jenisLaporan, '-')) . '/' . Carbon::now()->format('Y');

        RiwayatTtdDigital::create([
            'dokumen_type' => 'laporan',
            'dokumen_id' => 0,
            'penandatangan_id' => $penandatangan->id,
            'nomor_dokumen' => $nomorDokumen,
            'hash_sha256' => $hash,
            'qr_code_path' => $qrPath,
            'disahkan_by' => Auth::id(),
            'disahkan_at' => now(),
            'pin_verified_at' => now(),
            'ip_address' => request()->ip(),
        ]);

        return [
            'qr_svg' => $qrSvg,
            'hash' => $hash,
        ];
    }

    // ========== OPERASIONAL ==========

    public function cetakSemua(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);
        $kegiatan = Kegiatan::with(['kategori', 'user'])->orderBy('tanggal', 'asc')->get();

        $qr = $this->generateLaporanQr('Seluruh Kegiatan', $penandatangan);

        $data = [
            'title' => 'Laporan Seluruh Kegiatan Biro Adpim',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Arsip Seluruh Data Kegiatan Tercatat',
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.semua', $data, 'laporan_seluruh_kegiatan.pdf');
    }

    public function cetakTanggal(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        $kegiatan = Kegiatan::with(['kategori', 'user'])
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->orderBy('tanggal', 'asc')
            ->get();

        $qr = $this->generateLaporanQr('Rentang Tanggal', $penandatangan, [
            'start' => $request->start_date,
            'end' => $request->end_date,
        ]);

        $data = [
            'title' => 'Laporan Kegiatan Berdasarkan Rentang Tanggal',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Periode: ' . $start->translatedFormat('d F Y') . ' s.d. ' . $end->translatedFormat('d F Y'),
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.tanggal', $data, 'laporan_kegiatan_periode_' . $request->start_date . '_sd_' . $request->end_date . '.pdf');
    }

    public function cetakKategori(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);
        $request->validate(['kategori_id' => 'required|exists:kategori_kegiatans,id']);

        $kategori = KategoriKegiatan::findOrFail($request->kategori_id);
        $kegiatan = Kegiatan::with(['user'])
            ->where('kategori_id', $request->kategori_id)
            ->orderBy('tanggal', 'asc')
            ->get();

        $qr = $this->generateLaporanQr('Kategori', $penandatangan, [
            'kategori_id' => $request->kategori_id,
        ]);

        $data = [
            'title' => 'Laporan Kegiatan Berdasarkan Kategori',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Kategori: ' . $kategori->nama_kategori,
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.kategori', $data, 'laporan_kegiatan_kategori_' . Str::slug($kategori->nama_kategori) . '.pdf');
    }

    public function cetakPeliput(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);
        $request->validate(['user_id' => 'required|exists:users,id']);

        $user = User::findOrFail($request->user_id);
        $kegiatan = Kegiatan::with(['kategori'])
            ->where('user_id', $request->user_id)
            ->orderBy('tanggal', 'asc')
            ->get();

        $qr = $this->generateLaporanQr('Kinerja Peliput', $penandatangan, [
            'user_id' => $request->user_id,
        ]);

        $data = [
            'title' => 'Laporan Kinerja Staf Peliput',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Nama Petugas: ' . $user->name . ' (' . ucfirst($user->role) . ')',
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.peliput', $data, 'laporan_kinerja_' . Str::slug($user->name) . '.pdf');
    }

    public function cetakBeritaAcara(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);
        $request->validate(['kegiatan_id' => 'required|exists:kegiatans,id']);

        $kegiatan = Kegiatan::with(['kategori', 'dokumentasi'])->findOrFail($request->kegiatan_id);

        $qr = $this->generateLaporanQr('Berita Acara', $penandatangan, [
            'kegiatan_id' => $request->kegiatan_id,
        ]);

        $data = [
            'title' => 'Berita Acara & Dokumentasi Kegiatan',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Laporan Spesifik ID: ' . $kegiatan->id,
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.berita-acara', $data, 'berita_acara_giat_' . $kegiatan->id . '.pdf');
    }

    // ========== AKUNTABILITAS ==========

    public function cetakRekapSppd(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);

        $query = SuratPerjalananDinas::with(['user', 'peserta.user', 'biaya', 'kegiatan']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_berangkat', [
                Carbon::parse($request->start_date)->toDateString(),
                Carbon::parse($request->end_date)->toDateString(),
            ]);
        }

        $sppd = $query->orderBy('tanggal_berangkat', 'asc')->get();
        $totalBiaya = $sppd->sum(fn($item) => $item->totalBiaya());

        $qr = $this->generateLaporanQr('Rekap SPPD', $penandatangan, [
            'start_date' => $request->start_date ?? null,
            'end_date' => $request->end_date ?? null,
        ]);

        $data = [
            'title' => 'Laporan Rekapitulasi Surat Perjalanan Dinas (SPPD)',
            'sppd' => $sppd,
            'total_biaya' => $totalBiaya,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Rekap Data Perjalanan Dinas Biro Adpim',
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.rekap-sppd', $data, 'rekap_sppd_biro_adpim.pdf');
    }

    public function cetakRekapLpj(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);

        $query = LpjTugas::with(['kegiatan', 'user', 'bukti']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_lpj', [
                Carbon::parse($request->start_date)->toDateString(),
                Carbon::parse($request->end_date)->toDateString(),
            ]);
        }

        $lpj = $query->orderBy('tanggal_lpj', 'asc')->get();

        $qr = $this->generateLaporanQr('Rekap LPJ', $penandatangan, [
            'start_date' => $request->start_date ?? null,
            'end_date' => $request->end_date ?? null,
        ]);

        $data = [
            'title' => 'Laporan Rekapitulasi LPJ Tugas',
            'lpj' => $lpj,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Rekap Laporan Pertanggungjawaban Tugas/Anggaran Biro Adpim',
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.rekap-lpj', $data, 'rekap_lpj_biro_adpim.pdf');
    }

    public function cetakRiwayatTtd(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);

        $query = RiwayatTtdDigital::with(['penandatangan', 'disahkanOleh']);

        if ($request->filled('dokumen_type')) {
            $query->where('dokumen_type', $request->dokumen_type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('disahkan_at', [
                Carbon::parse($request->start_date)->toDateString(),
                Carbon::parse($request->end_date)->addDay()->toDateString(),
            ]);
        }

        $riwayat = $query->orderBy('disahkan_at', 'desc')->get();

        $qr = $this->generateLaporanQr('Riwayat TTD', $penandatangan, [
            'dokumen_type' => $request->dokumen_type ?? null,
            'start_date' => $request->start_date ?? null,
            'end_date' => $request->end_date ?? null,
        ]);

        $data = [
            'title' => 'Laporan Riwayat Tanda Tangan Digital',
            'riwayat' => $riwayat,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Catatan Pengesahan Dokumen secara Digital Biro Adpim',
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.riwayat-ttd', $data, 'riwayat_ttd_digital.pdf');
    }

    // ========== MANAJERIAL ==========

    public function cetakStatistikKategori(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);

        $kategori = KategoriKegiatan::withCount('kegiatan')->orderBy('nama_kategori', 'asc')->get();
        $total_kegiatan = Kegiatan::count();

        $qr = $this->generateLaporanQr('Statistik Kategori', $penandatangan);

        $data = [
            'title' => 'Laporan Rekapitulasi Jumlah Kegiatan per Kategori',
            'kategori' => $kategori,
            'total_kegiatan' => $total_kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Statistik Akumulatif Jenis Kegiatan',
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.statistik-kategori', $data, 'rekap_statistik_kategori.pdf');
    }

    public function cetakStatistikBulan(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);
        $request->validate(['tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1)]);

        $tahun = $request->tahun;
        $rekap = Kegiatan::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get()
            ->pluck('jumlah', 'bulan');

        $data_bulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $data_bulan[] = [
                'nama' => Carbon::create()->month($i)->translatedFormat('F'),
                'jumlah' => $rekap->get($i, 0)
            ];
        }

        $total_tahun = Kegiatan::whereYear('tanggal', $tahun)->count();

        $qr = $this->generateLaporanQr('Statistik Bulan', $penandatangan, [
            'tahun' => $tahun,
        ]);

        $data = [
            'title' => 'Laporan Rekapitulasi Jumlah Kegiatan per Bulan',
            'data_bulan' => $data_bulan,
            'total_tahun' => $total_tahun,
            'tahun' => $tahun,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Tren Kegiatan Tahun Analisis: ' . $tahun,
            'qr_svg' => $qr['qr_svg'],
            'hash' => $qr['hash'],
        ];

        return $this->generatePdf('laporan.pdf.statistik-bulan', $data, 'rekap_statistik_bulan_' . $tahun . '.pdf');
    }
}
