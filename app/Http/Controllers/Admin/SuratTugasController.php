<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenugasanLiputan;
use App\Models\Penandatangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SuratTugasController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'staf') {
            $penugasan = PenugasanLiputan::with(['kegiatan.kategori', 'user'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $penugasan = PenugasanLiputan::with(['kegiatan.kategori', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('dokumen.surat-tugas.index', compact('penugasan'));
    }

    public function cetak($id)
    {
        $penugasan = PenugasanLiputan::with(['kegiatan.kategori', 'user'])->findOrFail($id);

        if (Auth::user()->role === 'staf' && $penugasan->user_id !== Auth::id()) {
            abort(403);
        }

        $penandatangan = Penandatangan::where('is_aktif', true)->first();

        if (!$penandatangan) {
            return back()->with('error', 'Data penandatangan aktif belum diatur. Hubungi admin.');
        }

        $stafTim = PenugasanLiputan::with('user')
            ->where('kegiatan_id', $penugasan->kegiatan_id)
            ->get();

        $noSurat = \App\Support\NomorSurat::format('ST', $penugasan->id);

        $pdf = Pdf::loadView('dokumen.surat-tugas.pdf', [
            'penugasan' => $penugasan,
            'stafTim' => $stafTim,
            'penandatangan' => $penandatangan,
            'noSurat' => $noSurat,
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream('surat_tugas_' . $penugasan->id . '.pdf');
    }
}
