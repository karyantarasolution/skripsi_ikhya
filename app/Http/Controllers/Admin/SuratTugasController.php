<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Penandatangan;
use App\Support\NomorSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SuratTugasController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'staf') {
            $kegiatan = Kegiatan::with(['kategori', 'penugasan.user'])
                ->whereHas('penugasan', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->orderBy('tanggal', 'desc')
                ->get();
        } else {
            $kegiatan = Kegiatan::with(['kategori', 'penugasan.user'])
                ->whereHas('penugasan')
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        return view('dokumen.surat-tugas.index', compact('kegiatan'));
    }

    public function cetak($kegiatanId)
    {
        $kegiatan = Kegiatan::with(['kategori', 'penugasan.user'])->findOrFail($kegiatanId);

        if (Auth::user()->role === 'staf' && ! $kegiatan->penugasan->contains('user_id', Auth::id())) {
            abort(403);
        }

        $stafTim = $kegiatan->penugasan;

        if ($stafTim->isEmpty()) {
            return back()->with('error', 'Belum ada staf yang ditugaskan pada kegiatan ini.');
        }

        $penandatangan = Penandatangan::where('is_aktif', true)->first();

        if (! $penandatangan) {
            return back()->with('error', 'Data penandatangan aktif belum diatur. Hubungi admin.');
        }

        $noSurat = NomorSurat::format('ST', $kegiatan->id);

        $pdf = Pdf::loadView('dokumen.surat-tugas.pdf', [
            'kegiatan' => $kegiatan,
            'stafTim' => $stafTim,
            'penandatangan' => $penandatangan,
            'noSurat' => $noSurat,
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream('surat_tugas_'.$kegiatan->id.'.pdf');
    }
}
