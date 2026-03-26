<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\KategoriKegiatan;
use App\Models\User;
use App\Models\Penandatangan;
use App\Models\Dokumentasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
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

    public function cetakSemua(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);
        $kegiatan = Kegiatan::with(['kategori', 'user'])->orderBy('tanggal', 'asc')->get();
        
        $data = [
            'title' => 'Laporan Seluruh Kegiatan Biro Adpim',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Arsip Seluruh Data Kegiatan Tercatat'
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

        $data = [
            'title' => 'Laporan Kegiatan Berdasarkan Rentang Tanggal',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Periode: ' . $start->translatedFormat('d F Y') . ' s.d. ' . $end->translatedFormat('d F Y')
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

        $data = [
            'title' => 'Laporan Kegiatan Berdasarkan Kategori',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Kategori: ' . $kategori->nama_kategori
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

        $data = [
            'title' => 'Laporan Kinerja Staf Peliput',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Nama Petugas: ' . $user->name . ' (' . ucfirst($user->role) . ')'
        ];

        return $this->generatePdf('laporan.pdf.peliput', $data, 'laporan_kinerja_' . Str::slug($user->name) . '.pdf');
    }

    public function cetakBeritaAcara(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);
        $request->validate(['kegiatan_id' => 'required|exists:kegiatans,id']);

        $kegiatan = Kegiatan::with(['kategori', 'dokumentasi'])->findOrFail($request->kegiatan_id);

        $data = [
            'title' => 'Berita Acara & Dokumentasi Kegiatan',
            'kegiatan' => $kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Laporan Spesifik ID: ' . $kegiatan->id
        ];

        return $this->generatePdf('laporan.pdf.berita-acara', $data, 'berita_acara_giat_' . $kegiatan->id . '.pdf');
    }

    public function cetakStatistikKategori(Request $request)
    {
        $penandatangan = $this->getCommonCetakData($request);
        
        $kategori = KategoriKegiatan::withCount('kegiatan')->orderBy('nama_kategori', 'asc')->get();
        $total_kegiatan = Kegiatan::count();

        $data = [
            'title' => 'Laporan Rekapitulasi Jumlah Kegiatan per Kategori',
            'kategori' => $kategori,
            'total_kegiatan' => $total_kegiatan,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Statistik Akumulatif Jenis Kegiatan'
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

        $data = [
            'title' => 'Laporan Rekapitulasi Jumlah Kegiatan per Bulan',
            'data_bulan' => $data_bulan,
            'total_tahun' => $total_tahun,
            'tahun' => $tahun,
            'penandatangan' => $penandatangan,
            'sub_judul' => 'Tren Kegiatan Tahun Analisis: ' . $tahun
        ];

        return $this->generatePdf('laporan.pdf.statistik-bulan', $data, 'rekap_statistik_bulan_' . $tahun . '.pdf');
    }

    public function cetakPenandatangan(Request $request)
    {
        $penandatangan_surat = $this->getCommonCetakData($request);
        $daftar_pejabat = Penandatangan::orderBy('is_aktif', 'desc')->orderBy('id', 'asc')->get();

        $data = [
            'title' => 'Laporan Daftar Pejabat Penandatangan Laporan',
            'daftar_pejabat' => $daftar_pejabat,
            'penandatangan' => $penandatangan_surat,
            'sub_judul' => 'Arsip Data Master Pejabat Berwenang Biro Adpim'
        ];

        return $this->generatePdf('laporan.pdf.penandatangan', $data, 'daftar_pejabat_penandatangan.pdf');
    }
}