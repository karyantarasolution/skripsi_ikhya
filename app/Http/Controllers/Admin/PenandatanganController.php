<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penandatangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenandatanganController extends Controller
{
    public function index()
    {
        $penandatangan = Penandatangan::orderBy('id', 'desc')->get();
        return view('admin.penandatangan.index', compact('penandatangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pejabat' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:penandatangans',
            'jabatan' => 'required|string|max:255',
            'is_aktif' => 'required|boolean',
        ]);

        // Opsional: Logika pembuatan QR Code bisa ditambahkan di sini nanti
        // Saat ini kita simpan data dasarnya dulu

        Penandatangan::create($request->all());

        return redirect()->route('admin.penandatangan.index')->with('success', 'Data Penandatangan berhasil ditambahkan.');
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

        return redirect()->route('admin.penandatangan.index')->with('success', 'Data Penandatangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pejabat = Penandatangan::findOrFail($id);
        $pejabat->delete();

        return redirect()->route('admin.penandatangan.index')->with('success', 'Data Penandatangan berhasil dihapus.');
    }
}