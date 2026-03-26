<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DokumentasiController extends Controller
{
    public function index($kegiatan_id)
    {
        $kegiatan = Kegiatan::with(['kategori', 'dokumentasi'])->findOrFail($kegiatan_id);
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
            // Pastikan folder tujuan ada
            $destinationPath = public_path('uploads/dokumentasi');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            foreach ($request->file('files') as $file) {
                // Buat nama file unik
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Deteksi tipe file
                $extension = strtolower($file->getClientOriginalExtension());
                $tipe = 'dokumen';
                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $tipe = 'foto';
                } elseif ($extension == 'mp4') {
                    $tipe = 'video';
                }

                // PINDAHKAN FILE LANGSUNG KE FOLDER public/uploads/dokumentasi
                $file->move($destinationPath, $fileName);

                // Simpan PATH BARU ke database
                Dokumentasi::create([
                    'kegiatan_id' => $kegiatan->id,
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => 'uploads/dokumentasi/' . $fileName, // Path baru
                    'tipe_file' => $tipe,
                ]);
            }
        }

        return redirect()->route('peliputan.dokumentasi.index', $kegiatan->id)->with('success', 'Dokumentasi lapangan berhasil diunggah.');
    }

    public function destroy($id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);
        
        // Hapus file fisik dari folder public
        $filePath = public_path($dokumentasi->file_path);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
        
        $kegiatan_id = $dokumentasi->kegiatan_id;
        $dokumentasi->delete();

        return redirect()->route('peliputan.dokumentasi.index', $kegiatan_id)->with('success', 'File dokumentasi berhasil dihapus.');
    }

public function arsipGlobal(Request $request)
    {
     
        $kegiatan = Kegiatan::with('kategori')
            ->withCount('dokumentasi') // Ini akan bikin variabel 'dokumentasi_count'
            ->having('dokumentasi_count', '>', 0)
            ->orderBy('tanggal', 'desc')
            ->paginate(12);

        return view('peliputan.dokumentasi.arsip-global', compact('kegiatan'));
    }
}