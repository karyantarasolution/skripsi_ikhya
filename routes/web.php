<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\KategoriKegiatanController;
use App\Http\Controllers\Admin\UserController; 
use App\Http\Controllers\Admin\PenandatanganController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\DokumentasiController;
use App\Http\Controllers\Admin\PenugasanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\LaporanController;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/dashboard', function () {
    $role = Auth::user()->role;
    
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'staf') {
        return redirect()->route('staf.dashboard');
    } elseif ($role === 'pimpinan') {
        return redirect()->route('pimpinan.dashboard');
    }
    
    abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        Route::resource('kategori', KategoriKegiatanController::class)->except(['create', 'show', 'edit']);
        Route::resource('user', UserController::class)->except(['create', 'show', 'edit']); 
        Route::resource('penandatangan', PenandatanganController::class)->except(['create', 'show', 'edit']);

        Route::prefix('penugasan')->name('penugasan.')->group(function () {
            Route::get('/', [PenugasanController::class, 'index'])->name('index');
            Route::get('/create', [PenugasanController::class, 'create'])->name('create');
            Route::post('/', [PenugasanController::class, 'store'])->name('store');
            Route::get('/{penugasan}/edit', [PenugasanController::class, 'edit'])->name('edit');
            Route::put('/{penugasan}', [PenugasanController::class, 'update'])->name('update');
            Route::delete('/{penugasan}', [PenugasanController::class, 'destroy'])->name('destroy');
            Route::get('/by-staff', [PenugasanController::class, 'byStaff'])->name('by-staff');
        });
    });

    Route::middleware('role:staf')->prefix('staf')->name('staf.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'staf'])->name('dashboard');
    });

    Route::middleware('role:pimpinan')->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pimpinan'])->name('dashboard');
    });

    Route::middleware('role:admin,staf')->prefix('peliputan')->name('peliputan.')->group(function () {
        Route::resource('kegiatan', KegiatanController::class);
        
        Route::post('kegiatan/{kegiatan}/ajukan', [KegiatanController::class, 'ajukan'])->name('kegiatan.ajukan');
        Route::post('kegiatan/{kegiatan}/mulai', [KegiatanController::class, 'mulaiPelaksanaan'])->name('kegiatan.mulai');
        Route::post('kegiatan/{kegiatan}/selesai', [KegiatanController::class, 'selesai'])->name('kegiatan.selesai');
        Route::post('kegiatan/{kegiatan}/upload-lpj', [KegiatanController::class, 'uploadLpj'])->name('kegiatan.upload-lpj');

        Route::get('kegiatan/{kegiatan}/dokumentasi', [DokumentasiController::class, 'index'])->name('dokumentasi.index');
        Route::post('kegiatan/{kegiatan}/dokumentasi', [DokumentasiController::class, 'store'])->name('dokumentasi.store');
        Route::delete('dokumentasi/{dokumentasi}', [DokumentasiController::class, 'destroy'])->name('dokumentasi.destroy');
        Route::get('arsip-global', [DokumentasiController::class, 'arsipGlobal'])->name('arsip.global');
    });

    Route::middleware('role:pimpinan')->prefix('persetujuan')->name('persetujuan.')->group(function () {
        Route::post('kegiatan/{kegiatan}/approve', [KegiatanController::class, 'approve'])->name('kegiatan.approve');
        Route::post('kegiatan/{kegiatan}/tolak', [KegiatanController::class, 'tolak'])->name('kegiatan.tolak');
    });

    Route::middleware('role:admin,pimpinan')->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        
        Route::get('/cetak-semua', [LaporanController::class, 'cetakSemua'])->name('cetak.semua');
        Route::get('/cetak-tanggal', [LaporanController::class, 'cetakTanggal'])->name('cetak.tanggal');
        Route::get('/cetak-kategori', [LaporanController::class, 'cetakKategori'])->name('cetak.kategori');
        Route::get('/cetak-peliput', [LaporanController::class, 'cetakPeliput'])->name('cetak.peliput');
        Route::get('/cetak-berita-acara', [LaporanController::class, 'cetakBeritaAcara'])->name('cetak.berita-acara');
        Route::get('/cetak-statistik-kategori', [LaporanController::class, 'cetakStatistikKategori'])->name('cetak.statistik-kategori');
        Route::get('/cetak-statistik-bulan', [LaporanController::class, 'cetakStatistikBulan'])->name('cetak.statistik-bulan');
        Route::get('/cetak-penandatangan', [LaporanController::class, 'cetakPenandatangan'])->name('cetak.penandatangan');
        Route::get('/cetak-statistik-upload', [LaporanController::class, 'cetakStatistikUpload'])->name('cetak.statistik-upload');
    });
});

require __DIR__.'/auth.php';
