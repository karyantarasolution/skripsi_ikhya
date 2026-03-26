<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKegiatan;
use Illuminate\Http\Request;

class KategoriKegiatanController extends Controller
{
    public function index()
    {
        $kategori = KategoriKegiatan::orderBy('id', 'desc')->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'warna_label' => 'required|string|max:7',
        ]);

        KategoriKegiatan::create($request->all());

        return redirect()->route('admin.kategori.index')->with('success', 'Data Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'warna_label' => 'required|string|max:7',
        ]);

        $kategori = KategoriKegiatan::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('admin.kategori.index')->with('success', 'Data Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriKegiatan::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Data Kategori berhasil dihapus.');
    }
}