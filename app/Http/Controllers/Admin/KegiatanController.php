<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KategoriKegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with(['kategori', 'user', 'penugasan.user'])
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('waktu', 'desc')
                    ->get();
        return view('peliputan.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        $kategori = KategoriKegiatan::all();
        $staf = User::whereIn('role', ['admin', 'staf'])->orderBy('name')->get();
        return view('peliputan.kegiatan.create', compact('kategori', 'staf'));
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
            'rab_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480',
            'penugasan' => 'nullable|array',
            'penugasan.*' => 'exists:users,id',
            'tugas' => 'nullable|array',
        ]);

        $data = $request->except(['rab_file', 'penugasan']);
        $data['user_id'] = Auth::id();
        $data['status'] = $request->status ?? 'draf';

        if ($request->hasFile('rab_file')) {
            $destinationPath = public_path('uploads/rab');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->file('rab_file')->getClientOriginalExtension();
            $request->file('rab_file')->move($destinationPath, $fileName);
            $data['rab_file'] = 'uploads/rab/' . $fileName;
        }

        $kegiatan = Kegiatan::create($data);

        if ($request->has('penugasan')) {
            foreach ($request->penugasan as $userId) {
                $kegiatan->penugasan()->create([
                    'user_id' => $userId,
                    'jenis' => count($request->penugasan) > 1 ? 'tim' : 'individu',
                    'tugas' => $request->tugas[$userId] ?? null,
                ]);
            }
        }

        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Jadwal Kegiatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::with('penugasan')->findOrFail($id);
        $kategori = KategoriKegiatan::all();
        $staf = User::whereIn('role', ['admin', 'staf'])->orderBy('name')->get();
        return view('peliputan.kegiatan.edit', compact('kegiatan', 'kategori', 'staf'));
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
            'rab_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480',
            'penugasan' => 'nullable|array',
            'penugasan.*' => 'exists:users,id',
            'tugas' => 'nullable|array',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $data = $request->except(['rab_file', 'penugasan']);

        if ($request->hasFile('rab_file')) {
            if ($kegiatan->rab_file && File::exists(public_path($kegiatan->rab_file))) {
                File::delete(public_path($kegiatan->rab_file));
            }
            $destinationPath = public_path('uploads/rab');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->file('rab_file')->getClientOriginalExtension();
            $request->file('rab_file')->move($destinationPath, $fileName);
            $data['rab_file'] = 'uploads/rab/' . $fileName;
        }

        $kegiatan->update($data);

        if ($request->has('penugasan')) {
            $kegiatan->penugasan()->delete();
            foreach ($request->penugasan as $userId) {
                $kegiatan->penugasan()->create([
                    'user_id' => $userId,
                    'jenis' => count($request->penugasan) > 1 ? 'tim' : 'individu',
                    'tugas' => $request->tugas[$userId] ?? null,
                ]);
            }
        }

        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Jadwal Kegiatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        if ($kegiatan->rab_file && File::exists(public_path($kegiatan->rab_file))) {
            File::delete(public_path($kegiatan->rab_file));
        }
        if ($kegiatan->lpj_file && File::exists(public_path($kegiatan->lpj_file))) {
            File::delete(public_path($kegiatan->lpj_file));
        }
        $kegiatan->delete();

        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Jadwal Kegiatan berhasil dihapus.');
    }

    public function ajukan($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update(['status' => 'diajukan']);
        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Kegiatan diajukan untuk persetujuan.');
    }

    public function approve($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update([
            'status' => 'disetujui',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Kegiatan berhasil disetujui.');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['catatan_penolakan' => 'required|string']);
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update([
            'status' => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);
        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Kegiatan ditolak.');
    }

    public function mulaiPelaksanaan($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update(['status' => 'pelaksanaan']);
        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Kegiatan sedang dalam pelaksanaan.');
    }

    public function selesai($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update(['status' => 'selesai']);
        return redirect()->route('peliputan.kegiatan.index')->with('success', 'Kegiatan telah selesai.');
    }

    public function uploadLpj(Request $request, $id)
    {
        $request->validate([
            'lpj_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:20480',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);

        if ($kegiatan->lpj_file && File::exists(public_path($kegiatan->lpj_file))) {
            File::delete(public_path($kegiatan->lpj_file));
        }

        $destinationPath = public_path('uploads/lpj');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        $fileName = time() . '_' . uniqid() . '.' . $request->file('lpj_file')->getClientOriginalExtension();
        $request->file('lpj_file')->move($destinationPath, $fileName);

        $kegiatan->update([
            'lpj_file' => 'uploads/lpj/' . $fileName,
            'status' => 'lpj',
        ]);

        return redirect()->route('peliputan.kegiatan.index')->with('success', 'LPJ berhasil diunggah.');
    }
}
