<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatTtdDigital;
use App\Models\SuratPerjalananDinas;
use App\Models\LpjTugas;
use App\Models\Kegiatan;
use App\Models\Penandatangan;
use App\Models\DocumentApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Services\NotificationService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

class TtdDigitalController extends Controller
{
    private $secretKey = 'SIA-BIROPIM-KALSEL-2026-SEC';

    public function riwayatTtd(Request $request)
    {
        $query = RiwayatTtdDigital::with(['penandatangan', 'disahkanOleh'])
            ->orderBy('disahkan_at', 'desc');

        if ($request->filled('dokumen_type')) {
            $query->where('dokumen_type', $request->dokumen_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_dokumen', 'like', "%{$search}%")
                  ->orWhere('hash_sha256', 'like', "%{$search}%");
            });
        }

        $riwayat = $query->paginate(20);

        return view('admin.ttd-digital.riwayat', compact('riwayat'));
    }

    public function prosesTtd(Request $request, string $tipe, int $id)
    {
        $request->validate([
            'pin' => 'required|string',
            'penandatangan_id' => 'required|exists:penandatangans,id',
        ]);

        if (!Hash::check($request->pin, Auth::user()->password)) {
            return back()->with('error', 'PIN/Password salah. Pengesahan dibatalkan.');
        }

        $dokumen = $this->getDokumen($tipe, $id);
        if (!$dokumen) {
            return back()->with('error', 'Dokumen tidak ditemukan.');
        }

        if ($this->sudahDitandatangani($tipe, $dokumen)) {
            return back()->with('error', 'Dokumen ini sudah ditandatangani sebelumnya.');
        }

        $hash = $this->generateHash($tipe, $id);
        $nomorDokumen = $this->getNomorDokumen($tipe, $dokumen);

        $qrDirectory = public_path('uploads/qrcodes/ttd');
        if (!File::exists($qrDirectory)) {
            File::makeDirectory($qrDirectory, 0755, true);
        }

        $qrFileName = 'ttd_' . $tipe . '_' . $id . '_' . time() . '.png';
        $qrPath = 'uploads/qrcodes/ttd/' . $qrFileName;
        $verifyUrl = route('verifikasi-dokumen', $hash);

        QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($verifyUrl, public_path($qrPath));

        $riwayat = RiwayatTtdDigital::create([
            'dokumen_type' => $tipe,
            'dokumen_id' => $id,
            'penandatangan_id' => $request->penandatangan_id,
            'nomor_dokumen' => $nomorDokumen,
            'hash_sha256' => $hash,
            'qr_code_path' => $qrPath,
            'disahkan_by' => Auth::id(),
            'disahkan_at' => now(),
            'pin_verified_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        $this->updateDokumenTtd($tipe, $dokumen, $hash, $qrPath);

        DocumentApproval::create([
            'dokumen_type' => $tipe,
            'dokumen_id' => $id,
            'tahapan' => 'ttd_karo',
            'aksi' => 'sign',
            'user_id' => Auth::id(),
            'catatan' => 'TTD Digital berhasil. Hash: ' . substr($hash, 0, 16) . '...',
        ]);

        $pesanTipe = match($tipe) {
            'sppd' => 'SPPD',
            'lpj_tugas' => 'LPJ Tugas',
            'surat_tugas' => 'Surat Tugas',
            default => 'Dokumen',
        };

        $dokumen = $this->getDokumen($tipe, $id);
        if ($dokumen && $dokumen->user) {
            NotificationService::send($dokumen->user, 'ttd.selesai', $pesanTipe . ' Telah Ditandatangani', $pesanTipe . ' Anda telah ditandatangani secara digital oleh ' . Auth::user()->name . '.', route('verifikasi-dokumen', $hash));
        }

        return redirect()->back()->with('success', $pesanTipe . ' berhasil ditandatangani secara digital. Hash: ' . substr($hash, 0, 16) . '...');
    }

    private function getDokumen(string $tipe, int $id)
    {
        return match($tipe) {
            'sppd' => SuratPerjalananDinas::with('user')->find($id),
            'lpj_tugas' => LpjTugas::with('user')->find($id),
            'surat_tugas' => Kegiatan::with('user')->find($id),
            default => null,
        };
    }

    private function sudahDitandatangani(string $tipe, $dokumen): bool
    {
        return match($tipe) {
            'sppd' => $dokumen->ttd_status === 'ditandatangani',
            'lpj_tugas' => $dokumen->ttd_status === 'ditandatangani',
            default => false,
        };
    }

    private function getNomorDokumen(string $tipe, $dokumen): ?string
    {
        return match($tipe) {
            'sppd' => $dokumen->no_surat,
            'lpj_tugas' => $dokumen->no_lpj,
            'surat_tugas' => 'ST-' . $dokumen->id . '/' . date('Y'),
            default => null,
        };
    }

    private function generateHash(string $tipe, int $id): string
    {
        $payload = $tipe . '|' . $id . '|' . now()->timestamp . '|' . $this->secretKey;
        return hash('sha256', $payload);
    }

    private function updateDokumenTtd(string $tipe, $dokumen, string $hash, string $qrPath): void
    {
        $updateData = [
            'ttd_status' => 'ditandatangani',
            'ttd_by' => Auth::id(),
            'ttd_at' => now(),
            'qr_code_path' => $qrPath,
            'hash_sha256' => $hash,
        ];

        match($tipe) {
            'sppd' => $dokumen->update($updateData),
            'lpj_tugas' => $dokumen->update($updateData),
            default => null,
        };
    }

    public function verifikasi(string $hash)
    {
        $riwayat = RiwayatTtdDigital::with(['penandatangan', 'disahkanOleh'])
            ->where('hash_sha256', $hash)
            ->first();

        $valid = $riwayat !== null;

        return view('verifikasi-dokumen.index', compact('riwayat', 'valid'));
    }
}
