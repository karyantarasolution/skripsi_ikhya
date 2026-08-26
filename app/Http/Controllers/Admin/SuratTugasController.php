<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Penandatangan;
use App\Models\DocumentApproval;
use App\Support\NomorSurat;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

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

        $qrSvg = null;
        $hash = null;

        if ($kegiatan->ttd_status === 'ditandatangani' && $kegiatan->qr_code_path && $kegiatan->hash_sha256) {
            $qrFullPath = public_path($kegiatan->qr_code_path);
            if (File::exists($qrFullPath)) {
                $qrSvg = file_get_contents($qrFullPath);
                $hash = $kegiatan->hash_sha256;
            }
        }

        $pdf = Pdf::loadView('dokumen.surat-tugas.pdf', [
            'kegiatan' => $kegiatan,
            'stafTim' => $stafTim,
            'penandatangan' => $penandatangan,
            'noSurat' => $noSurat,
            'qr_svg' => $qrSvg,
            'hash' => $hash,
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream('surat_tugas_'.$kegiatan->id.'.pdf');
    }

    // ========== WORKFLOW METHODS ==========

    public function ajukan($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $kegiatan->update(['status' => 'diajukan_st']);

        NotificationService::sendToRole('pimpinan', 'surat_tugas.diajukan', 'Surat Tugas Baru', Auth::user()->name . ' mengajukan Surat Tugas untuk kegiatan: ' . $kegiatan->judul_kegiatan, route('dokumen.surat-tugas.index'));

        return redirect()->route('dokumen.surat-tugas.index')->with('success', 'Surat Tugas diajukan untuk persetujuan.');
    }

    public function reviewKabag(Request $request, $id)
    {
        $request->validate([
            'pin' => 'required|string',
            'kabag_catatan' => 'nullable|string',
        ]);

        if (!Hash::check($request->pin, Auth::user()->password)) {
            return back()->with('error', 'PIN/Password salah. Review dibatalkan.');
        }

        $kegiatan = Kegiatan::findOrFail($id);

        if ($kegiatan->status !== 'diajukan_st') {
            return back()->with('error', 'Surat Tugas belum dalam status diajukan.');
        }

        $kegiatan->update([
            'status' => 'review_kabag_st',
            'kabag_reviewed_by' => Auth::id(),
            'kabag_reviewed_at' => now(),
            'kabag_catatan' => $request->kabag_catatan,
        ]);

        DocumentApproval::create([
            'dokumen_type' => 'surat_tugas',
            'dokumen_id' => $id,
            'tahapan' => 'review_kabag',
            'aksi' => 'approve',
            'user_id' => Auth::id(),
            'catatan' => $request->kabag_catatan,
        ]);

        NotificationService::send($kegiatan->user, 'surat_tugas.reviewed', 'Surat Tugas Telah Direview', 'Surat Tugas untuk ' . $kegiatan->judul_kegiatan . ' telah direview oleh Kabag.', route('dokumen.surat-tugas.index'));

        return redirect()->route('dokumen.surat-tugas.index')->with('success', 'Surat Tugas telah direview oleh Kabag dan siap untuk TTD Karo Adpim.');
    }

    public function returnToStaf(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);

        $kegiatan->update([
            'status' => 'draf',
            'kabag_catatan' => $request->catatan,
        ]);

        DocumentApproval::create([
            'dokumen_type' => 'surat_tugas',
            'dokumen_id' => $id,
            'tahapan' => 'review_kabag',
            'aksi' => 'return',
            'user_id' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        NotificationService::send($kegiatan->user, 'surat_tugas.returned', 'Surat Tugas Dikembalikan', 'Surat Tugas untuk ' . $kegiatan->judul_kegiatan . ' dikembalikan untuk perbaikan. Alasan: ' . $request->catatan, route('dokumen.surat-tugas.index'));

        return redirect()->route('dokumen.surat-tugas.index')->with('success', 'Surat Tugas dikembalikan ke staf untuk perbaikan.');
    }

    public function tolak(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate(['catatan' => 'required|string']);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update(['status' => 'ditolak_st', 'kabag_catatan' => $request->catatan]);

        DocumentApproval::create([
            'dokumen_type' => 'surat_tugas',
            'dokumen_id' => $id,
            'tahapan' => 'review_kabag',
            'aksi' => 'reject',
            'user_id' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('dokumen.surat-tugas.index')->with('success', 'Surat Tugas ditolak.');
    }
}
