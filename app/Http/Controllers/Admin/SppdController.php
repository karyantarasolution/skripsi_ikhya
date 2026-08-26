<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Penandatangan;
use App\Models\SppdPeserta;
use App\Models\SuratPerjalananDinas;
use App\Models\User;
use App\Support\NomorSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\DocumentApproval;
use App\Services\NotificationService;
use Illuminate\Support\Facades\File;

class SppdController extends Controller
{
    public function index()
    {
        $sppd = SuratPerjalananDinas::with(['user', 'kegiatan', 'biaya', 'peserta'])
            ->when(Auth::user()->role === 'staf', function ($q) {
                $q->whereHas('peserta', function ($p) {
                    $p->where('user_id', Auth::id());
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dokumen.sppd.index', compact('sppd'));
    }

    public function create()
    {
        $kegiatan = Kegiatan::with('penugasan.user')->whereNotIn('status', ['draf', 'ditolak'])->orderBy('tanggal', 'desc')->get();
        $staf = User::whereIn('role', ['admin', 'staf'])->orderBy('name')->get();

        $kegiatanPeserta = [];
        foreach ($kegiatan as $k) {
            $kegiatanPeserta[$k->id] = $k->penugasan->pluck('user_id')->toArray();
        }

        return view('dokumen.sppd.create', compact('kegiatan', 'staf', 'kegiatanPeserta'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['user_id'] = Auth::id();
        $data['status'] = 'draf';

        $sppd = SuratPerjalananDinas::create($data);

        $this->simpanPeserta($sppd, $request);
        $this->simpanBiaya($sppd, $request);

        $tahun = Carbon::parse($sppd->tanggal_berangkat)->format('Y');
        $urutan = SuratPerjalananDinas::whereYear('tanggal_berangkat', $tahun)->count() + 1;
        $sppd->update(['no_surat' => NomorSurat::format('SPPD', $urutan, Carbon::parse($sppd->tanggal_berangkat))]);

        return redirect()->route('dokumen.sppd.index')->with('success', 'Surat Perjalanan Dinas berhasil dibuat.');
    }

    public function edit($id)
    {
        $sppd = SuratPerjalananDinas::with(['biaya', 'peserta'])->findOrFail($id);

        if (Auth::user()->role === 'staf' && ! $sppd->peserta->contains('user_id', Auth::id())) {
            abort(403);
        }

        $kegiatan = Kegiatan::with('penugasan.user')->whereNotIn('status', ['draf', 'ditolak'])->orderBy('tanggal', 'desc')->get();
        $staf = User::whereIn('role', ['admin', 'staf'])->orderBy('name')->get();

        $kegiatanPeserta = [];
        foreach ($kegiatan as $k) {
            $kegiatanPeserta[$k->id] = $k->penugasan->pluck('user_id')->toArray();
        }

        return view('dokumen.sppd.edit', compact('sppd', 'kegiatan', 'staf', 'kegiatanPeserta'));
    }

    public function update(Request $request, $id)
    {
        $sppd = SuratPerjalananDinas::findOrFail($id);

        if (Auth::user()->role === 'staf' && ! $sppd->peserta()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }

        if (in_array($sppd->status, ['disetujui', 'ditolak'])) {
            return back()->with('error', 'SPPD yang sudah disetujui/ditolak tidak dapat diubah.');
        }

        $data = $this->validated($request);
        $sppd->update($data);

        $sppd->peserta()->delete();
        $this->simpanPeserta($sppd, $request);

        $sppd->biaya()->delete();
        $this->simpanBiaya($sppd, $request);

        return redirect()->route('dokumen.sppd.index')->with('success', 'Surat Perjalanan Dinas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sppd = SuratPerjalananDinas::findOrFail($id);

        if (Auth::user()->role === 'staf' && ! $sppd->peserta()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }

        if (in_array($sppd->status, ['disetujui', 'ditolak'])) {
            return back()->with('error', 'SPPD yang sudah disetujui/ditolak tidak dapat dihapus.');
        }

        $sppd->delete();

        return redirect()->route('dokumen.sppd.index')->with('success', 'Surat Perjalanan Dinas berhasil dihapus.');
    }

    public function ajukan($id)
    {
        $sppd = SuratPerjalananDinas::findOrFail($id);

        if (Auth::user()->role === 'staf' && ! $sppd->peserta()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }

        $sppd->update(['status' => 'diajukan']);

        NotificationService::sendToRole('pimpinan', 'sppd.diajukan', 'SPPD Baru', Auth::user()->name . ' mengajukan SPPD ' . ($sppd->no_surat ?? 'baru'), route('dokumen.sppd.index'));

        return redirect()->route('dokumen.sppd.index')->with('success', 'SPPD diajukan untuk persetujuan.');
    }

    public function setujui($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $sppd = SuratPerjalananDinas::findOrFail($id);
        $sppd->update(['status' => 'disetujui', 'catatan' => null]);

        return redirect()->route('dokumen.sppd.index')->with('success', 'SPPD berhasil disetujui.');
    }

    public function tolak(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate(['catatan' => 'required|string']);

        $sppd = SuratPerjalananDinas::findOrFail($id);
        $sppd->update(['status' => 'ditolak', 'catatan' => $request->catatan]);

        DocumentApproval::create([
            'dokumen_type' => 'sppd',
            'dokumen_id' => $id,
            'tahapan' => 'review_kabag',
            'aksi' => 'reject',
            'user_id' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('dokumen.sppd.index')->with('success', 'SPPD ditolak.');
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

        $sppd = SuratPerjalananDinas::findOrFail($id);

        if ($sppd->status !== 'diajukan') {
            return back()->with('error', 'SPPD belum dalam status diajukan.');
        }

        $sppd->update([
            'status' => 'review_kabag',
            'kabag_reviewed_by' => Auth::id(),
            'kabag_reviewed_at' => now(),
            'kabag_catatan' => $request->kabag_catatan,
        ]);

        DocumentApproval::create([
            'dokumen_type' => 'sppd',
            'dokumen_id' => $id,
            'tahapan' => 'review_kabag',
            'aksi' => 'approve',
            'user_id' => Auth::id(),
            'catatan' => $request->kabag_catatan,
        ]);

        NotificationService::send($sppd->user, 'sppd.reviewed', 'SPPD Telah Direview', 'SPPD ' . ($sppd->no_surat ?? '') . ' telah direview oleh Kabag.', route('dokumen.sppd.index'));

        return redirect()->route('dokumen.sppd.index')->with('success', 'SPPD telah direview oleh Kabag dan siap untuk TTD Karo Adpim.');
    }

    public function returnToStaf(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        $sppd = SuratPerjalananDinas::findOrFail($id);

        $sppd->update([
            'status' => 'draf',
            'kabag_catatan' => $request->catatan,
        ]);

        DocumentApproval::create([
            'dokumen_type' => 'sppd',
            'dokumen_id' => $id,
            'tahapan' => 'review_kabag',
            'aksi' => 'return',
            'user_id' => Auth::id(),
            'catatan' => $request->catatan,
        ]);

        NotificationService::send($sppd->user, 'sppd.returned', 'SPPD Dikembalikan', 'SPPD ' . ($sppd->no_surat ?? '') . ' dikembalikan untuk perbaikan. Alasan: ' . $request->catatan, route('dokumen.sppd.index'));

        return redirect()->route('dokumen.sppd.index')->with('success', 'SPPD dikembalikan ke staf untuk perbaikan.');
    }

    public function cetak($id)
    {
        $sppd = SuratPerjalananDinas::with(['user', 'kegiatan', 'biaya', 'peserta.user'])->findOrFail($id);
        $penandatangan = Penandatangan::where('is_aktif', true)->first();

        if (! $penandatangan) {
            return back()->with('error', 'Data penandatangan aktif belum diatur. Hubungi admin.');
        }

        $lamaHari = Carbon::parse($sppd->tanggal_berangkat)->diffInDays(Carbon::parse($sppd->tanggal_kembali)) + 1;

        $qrSvg = null;
        $hash = null;

        if ($sppd->ttd_status === 'ditandatangani' && $sppd->qr_code_path && $sppd->hash_sha256) {
            $qrFullPath = public_path($sppd->qr_code_path);
            if (File::exists($qrFullPath)) {
                $qrSvg = file_get_contents($qrFullPath);
                $hash = $sppd->hash_sha256;
            }
        }

        $pdf = Pdf::loadView('dokumen.sppd.pdf', [
            'sppd' => $sppd,
            'penandatangan' => $penandatangan,
            'lamaHari' => $lamaHari,
            'qr_svg' => $qrSvg,
            'hash' => $hash,
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream('sppd_'.$sppd->id.'.pdf');
    }

    private function validated(Request $request)
    {
        return $request->validate([
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
            'tujuan' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            'kendaraan' => 'required|string|max:255',
            'pembebanan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'peserta' => 'required|array|min:1',
            'peserta.*' => 'exists:users,id',
        ]);
    }

    private function simpanPeserta(SuratPerjalananDinas $sppd, Request $request): void
    {
        foreach (array_unique($request->peserta) as $userId) {
            SppdPeserta::create([
                'sppd_id' => $sppd->id,
                'user_id' => $userId,
            ]);
        }
    }

    private function simpanBiaya(SuratPerjalananDinas $sppd, Request $request): void
    {
        $uraianList = $request->biaya_uraian ?? [];
        $volumeList = $request->biaya_volume ?? [];
        $satuanList = $request->biaya_satuan ?? [];
        $hargaList = $request->biaya_harga ?? [];

        foreach ($uraianList as $index => $uraian) {
            if (empty($uraian)) {
                continue;
            }

            $volume = (int) ($volumeList[$index] ?? 1);
            $satuan = $satuanList[$index] ?? null;
            $harga = (float) ($hargaList[$index] ?? 0);

            $sppd->biaya()->create([
                'uraian' => $uraian,
                'volume' => $volume ?: 1,
                'satuan' => $satuan,
                'harga_satuan' => $harga,
                'total' => $volume * $harga,
            ]);
        }
    }
}
