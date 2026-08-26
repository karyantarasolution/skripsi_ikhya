<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use ZipArchive;

class DokumentasiController extends Controller
{
    public function index($kegiatan_id)
    {
        $kegiatan = Kegiatan::with(['kategori', 'dokumentasi.user'])->findOrFail($kegiatan_id);
        return view('peliputan.dokumentasi.index', compact('kegiatan'));
    }

    public function store(Request $request, $kegiatan_id)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpeg,png,jpg,mp4,pdf|max:10240',
        ]);

        $kegiatan = Kegiatan::findOrFail($kegiatan_id);

        if ($request->hasFile('files')) {
            $destinationPath = public_path('uploads/dokumentasi');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $extension = strtolower($file->getClientOriginalExtension());
                $tipe = 'dokumen';
                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $tipe = 'foto';
                } elseif ($extension == 'mp4') {
                    $tipe = 'video';
                }

                $file->move($destinationPath, $fileName);

                $filePath = $destinationPath . '/' . $fileName;

                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $this->addWatermark($filePath, $extension);
                    $this->extractMetadata($filePath, $kegiatan->id, $file->getClientOriginalName(), $fileName, $tipe);
                } else {
                    Dokumentasi::create([
                        'kegiatan_id' => $kegiatan->id,
                        'user_id' => Auth::id(),
                        'nama_file' => $file->getClientOriginalName(),
                        'file_path' => 'uploads/dokumentasi/' . $fileName,
                        'tipe_file' => $tipe,
                    ]);
                }
            }
        }

        return redirect()->route('peliputan.dokumentasi.index', $kegiatan->id)->with('success', 'Dokumentasi lapangan berhasil diunggah.');
    }

    public function destroy($id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);

        $filePath = public_path($dokumentasi->file_path);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        $kegiatan_id = $dokumentasi->kegiatan_id;
        $dokumentasi->delete();

        return redirect()->route('peliputan.dokumentasi.index', $kegiatan_id)->with('success', 'File dokumentasi berhasil dihapus.');
    }

    public function downloadAlbum($kegiatan_id)
    {
        $kegiatan = Kegiatan::with('dokumentasi')->findOrFail($kegiatan_id);
        $files = $kegiatan->dokumentasi;

        if ($files->isEmpty()) {
            return redirect()->route('peliputan.dokumentasi.index', $kegiatan_id)->with('error', 'Tidak ada file untuk diunduh.');
        }

        $zipFileName = 'album_' . $kegiatan->id . '_' . time() . '.zip';
        $tempPath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $file) {
                $fullPath = public_path($file->file_path);
                if (File::exists($fullPath)) {
                    $zip->addFile($fullPath, $file->nama_file);
                }
            }
            $zip->close();

            return response()->download($tempPath, $zipFileName, [
                'Content-Type' => 'application/zip',
            ])->deleteFileAfterSend(true);
        }

        return redirect()->route('peliputan.dokumentasi.index', $kegiatan_id)->with('error', 'Gagal membuat file ZIP.');
    }

    public function arsipGlobal(Request $request)
    {
        $kegiatan = Kegiatan::with('kategori')
            ->withCount('dokumentasi')
            ->having('dokumentasi_count', '>', 0)
            ->orderBy('tanggal', 'desc')
            ->paginate(12);

        return view('peliputan.dokumentasi.arsip-global', compact('kegiatan'));
    }

    private function addWatermark(string $filePath, string $extension): void
    {
        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) return;

        $mime = $imageInfo['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filePath);
                break;
            default:
                return;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $watermarkText = 'SIA BIRO ADPIM';
        $fontSize = max(12, intval($width / 40));
        $fontColor = imagecolorallocatealpha($image, 255, 255, 255, 50);
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 60);

        $x = $width - (strlen($watermarkText) * $fontSize * 0.6) - 15;
        $y = $height - $fontSize - 15;

        imagettftext($image, $fontSize, 0, $x + 1, $y + 1, $shadowColor, 'C:/Windows/Fonts/arial.ttf', $watermarkText);
        imagettftext($image, $fontSize, 0, $x, $y, $fontColor, 'C:/Windows/Fonts/arial.ttf', $watermarkText);

        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($image, $filePath, 90);
                break;
            case 'image/png':
                imagepng($image, $filePath);
                break;
        }

        imagedestroy($image);
    }

    private function extractMetadata(string $filePath, int $kegiatanId, string $originalName, string $savedFileName, string $tipe): void
    {
        $gpsLatitude = null;
        $gpsLongitude = null;
        $gpsLocationName = null;
        $fotoTakenAt = null;

        $exif = @exif_read_data($filePath);
        if ($exif) {
            if (!empty($exif['DateTimeOriginal'])) {
                $fotoTakenAt = \Carbon\Carbon::createFromFormat('Y:m:d H:i:s', str_replace(':', '-', substr($exif['DateTimeOriginal'], 0, 10)) . ' ' . substr($exif['DateTimeOriginal'], 11));
            } elseif (!empty($exif['DateTime'])) {
                $fotoTakenAt = \Carbon\Carbon::createFromFormat('Y:m:d H:i:s', str_replace(':', '-', substr($exif['DateTime'], 0, 10)) . ' ' . substr($exif['DateTime'], 11));
            }

            if (!empty($exif['GPSLatitude']) && !empty($exif['GPSLongitude'])) {
                $gpsLatitude = $this->gpsToDecimal($exif['GPSLatitude'], $exif['GPSLatitudeRef'] ?? 'N');
                $gpsLongitude = $this->gpsToDecimal($exif['GPSLongitude'], $exif['GPSLongitudeRef'] ?? 'E');
            }
        }

        Dokumentasi::create([
            'kegiatan_id' => $kegiatanId,
            'user_id' => Auth::id(),
            'nama_file' => $originalName,
            'file_path' => 'uploads/dokumentasi/' . $savedFileName,
            'tipe_file' => $tipe,
            'foto_taken_at' => $fotoTakenAt,
            'gps_latitude' => $gpsLatitude,
            'gps_longitude' => $gpsLongitude,
            'gps_location_name' => $gpsLocationName,
        ]);
    }

    private function gpsToDecimal(array $coordinate, string $direction): float
    {
        $degrees = $coordinate[0][0] / $coordinate[0][1];
        $minutes = $coordinate[1][0] / $coordinate[1][1];
        $seconds = $coordinate[2][0] / $coordinate[2][1];

        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
        if (strtoupper($direction) === 'S' || strtoupper($direction) === 'W') {
            $decimal = -$decimal;
        }

        return round($decimal, 6);
    }
}
