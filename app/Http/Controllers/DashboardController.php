<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Fungsi bantuan untuk mengambil data yang sama untuk semua role
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
            // Mengambil 5 jadwal terdekat mulai dari hari ini ke depan
            'jadwal_terdekat' => Kegiatan::with('kategori')
                                        ->where('tanggal', '>=', $hariIni)
                                        ->orderBy('tanggal', 'asc')
                                        ->orderBy('waktu', 'asc')
                                        ->take(5)->get(),
        ];
    }

    public function admin()
    {
        $data = $this->getCommonData();
        $data['total_user'] = User::count(); // Admin butuh melihat jumlah user
        return view('admin.dashboard', $data);
    }

    public function staf()
    {
        $data = $this->getCommonData();
        return view('staf.dashboard', $data);
    }

    public function pimpinan()
    {
        $data = $this->getCommonData();
        return view('pimpinan.dashboard', $data);
    }
}