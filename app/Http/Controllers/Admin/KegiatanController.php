<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KategoriKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with(['kategori', 'user'])
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('waktu', 'desc')
                    ->get();
        return view('peliputan.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        $kategori = KategoriKegiatan::all();
        return view('peliputan.kegiatan.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_kegiatans,id',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'lokasi' => 'required|string|max:255',
            'pejabat_hadir' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Set pembuat otomatis

        Kegiatan::create($data);

        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Jadwal Kegiatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kategori = KategoriKegiatan::all();
        return view('peliputan.kegiatan.edit', compact('kegiatan', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_kegiatans,id',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'lokasi' => 'required|string|max:255',
            'pejabat_hadir' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update($request->all());

        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Jadwal Kegiatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->delete();

        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Jadwal Kegiatan berhasil dihapus.');
    }
}