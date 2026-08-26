<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penandatangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PenandatanganController extends Controller
{
    public function index()
    {
        $penandatangan = Penandatangan::orderBy('id', 'desc')->get();
        return view('admin.penandatangan.index', compact('penandatangan'));
    }

    private function generateQrCode($pejabat)
    {
        $qrDirectory = public_path('uploads/qrcodes');
        if (!File::exists($qrDirectory)) {
            File::makeDirectory($qrDirectory, 0755, true);
        }

        $qrFileName = 'qr_' . $pejabat->id . '_' . time() . '.svg';
        $qrPath = 'uploads/qrcodes/' . $qrFileName;

        $data = json_encode([
            'id' => $pejabat->id,
            'nama' => $pejabat->nama_pejabat,
            'nip' => $pejabat->nip,
            'jabatan' => $pejabat->jabatan,
        ]);

        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($data);

        file_put_contents(public_path($qrPath), $qrSvg);

        return $qrPath;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pejabat' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:penandatangans',
            'jabatan' => 'required|string|max:255',
            'is_aktif' => 'required|boolean',
        ]);

        $pejabat = Penandatangan::create($request->all());

        $qrPath = $this->generateQrCode($pejabat);
        $pejabat->update(['qr_code_path' => $qrPath]);

        return redirect()->route('admin.penandatangan.index')->with('success', 'Data Penandatangan berhasil ditambahkan dengan QR Code.');
    }

    public function update(Request $request, $id)
    {
        $pejabat = Penandatangan::findOrFail($id);

        $request->validate([
            'nama_pejabat' => 'required|string|max:255',
            'nip' => ['required', 'string', 'max:50', Rule::unique('penandatangans')->ignore($pejabat->id)],
            'jabatan' => 'required|string|max:255',
            'is_aktif' => 'required|boolean',
        ]);

        $pejabat->update($request->all());

        $qrPath = $this->generateQrCode($pejabat);
        $pejabat->update(['qr_code_path' => $qrPath]);

        return redirect()->route('admin.penandatangan.index')->with('success', 'Data Penandatangan berhasil diperbarui dengan QR Code.');
    }

    public function destroy($id)
    {
        $pejabat = Penandatangan::findOrFail($id);

        if ($pejabat->qr_code_path && File::exists(public_path($pejabat->qr_code_path))) {
            File::delete(public_path($pejabat->qr_code_path));
        }

        $pejabat->delete();

        return redirect()->route('admin.penandatangan.index')->with('success', 'Data Penandatangan berhasil dihapus.');
    }
}