<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;
use App\Models\User;
use App\Models\PenugasanLiputan;
use App\Models\KategoriKegiatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function getCommonData()
    {
        $hariIni = Carbon::today()->toDateString();
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        return [
            'total_kegiatan' => Kegiatan::count(),
            'kegiatan_bulan_ini' => Kegiatan::whereMonth('tanggal', $bulanIni)
                                            ->whereYear('tanggal', $tahunIni)->count(),
            'total_dokumentasi' => Dokumentasi::count(),
            'jadwal_terdekat' => Kegiatan::with('kategori')
                                        ->where('tanggal', '>=', $hariIni)
                                        ->whereNotIn('status', ['selesai', 'lpj', 'ditolak'])
                                        ->orderBy('tanggal', 'asc')
                                        ->orderBy('waktu', 'asc')
                                        ->take(5)->get(),
        ];
    }

    public function admin()
    {
        $data = $this->getCommonData();
        $data['total_user'] = User::count();
        $data['total_penugasan'] = PenugasanLiputan::count();
        $data['kegiatan_diajukan'] = Kegiatan::where('status', 'diajukan')->count();
        $data['kegiatan_disetujui'] = Kegiatan::where('status', 'disetujui')->count();
        $data['kegiatan_lpj'] = Kegiatan::where('status', 'lpj')->count();

        $staf = User::whereIn('role', ['admin', 'staf'])->withCount('dokumentasi')->orderBy('dokumentasi_count', 'desc')->get();
        $data['staf_upload'] = $staf;

        $data['chart_bulan'] = collect();
        $data['chart_jumlah'] = collect();
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $data['chart_bulan']->push($bulan->format('M Y'));
            $data['chart_jumlah']->push(
                Kegiatan::whereMonth('tanggal', $bulan->month)
                    ->whereYear('tanggal', $bulan->year)
                    ->count()
            );
        }

        $kategoriData = Kegiatan::select('kategori_id', \DB::raw('count(*) as total'))
            ->groupBy('kategori_id')
            ->with('kategori:id,nama_kategori,warna_label')
            ->orderByDesc('total')
            ->get();
        $data['chart_kategori'] = $kategoriData;

        return view('admin.dashboard', $data);
    }

    public function staf()
    {
        $data = $this->getCommonData();
        $userId = Auth::id();
        $data['penugasan_saya'] = PenugasanLiputan::with('kegiatan.kategori')
            ->where('user_id', $userId)
            ->whereHas('kegiatan', function ($q) {
                $q->whereNotIn('status', ['selesai', 'lpj', 'ditolak']);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $data['upload_saya'] = Dokumentasi::where('user_id', $userId)->count();
        return view('staf.dashboard', $data);
    }

    public function pimpinan()
    {
        $data = $this->getCommonData();
        $data['kegiatan_diajukan'] = Kegiatan::where('status', 'diajukan')->count();
        $data['kegiatan_disetujui'] = Kegiatan::where('status', 'disetujui')->count();
        $data['kegiatan_ditolak'] = Kegiatan::where('status', 'ditolak')->count();
        $data['kegiatan_lpj'] = Kegiatan::where('status', 'lpj')->count();

        $data['pengajuan_terbaru'] = Kegiatan::with(['kategori', 'user'])
            ->where('status', 'diajukan')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('pimpinan.dashboard', $data);
    }
}
