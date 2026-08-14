<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\User;
use App\Models\PenugasanLiputan;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with(['penugasan.user', 'kategori'])
                    ->whereHas('penugasan')
                    ->orderBy('tanggal', 'desc')
                    ->get();
        return view('admin.penugasan.index', compact('kegiatan'));
    }

    public function create()
    {
        $kegiatan = Kegiatan::whereNotIn('status', ['selesai', 'lpj'])->orderBy('tanggal', 'desc')->get();
        $staf = User::whereIn('role', ['admin', 'staf'])->orderBy('name')->get();
        return view('admin.penugasan.create', compact('kegiatan', 'staf'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id',
            'jenis' => 'required|in:individu,tim',
            'tugas' => 'nullable|array',
            'keterangan' => 'nullable|string',
        ]);

        foreach ($request->user_id as $userId) {
            PenugasanLiputan::create([
                'kegiatan_id' => $request->kegiatan_id,
                'user_id' => $userId,
                'jenis' => $request->jenis,
                'tugas' => $request->tugas[$userId] ?? null,
                'keterangan' => $request->keterangan,
            ]);
        }

        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan liputan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $penugasan = PenugasanLiputan::with('kegiatan', 'user')->findOrFail($id);
        $staf = User::whereIn('role', ['admin', 'staf'])->orderBy('name')->get();
        return view('admin.penugasan.edit', compact('penugasan', 'staf'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis' => 'required|in:individu,tim',
            'tugas' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $penugasan = PenugasanLiputan::findOrFail($id);
        $penugasan->update($request->all());

        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan liputan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penugasan = PenugasanLiputan::findOrFail($id);
        $penugasan->delete();

        return redirect()->route('admin.penugasan.index')->with('success', 'Penugasan liputan berhasil dihapus.');
    }

    public function byStaff()
    {
        $staf = User::whereIn('role', ['admin', 'staf'])
                ->withCount(['penugasan', 'dokumentasi'])
                ->with(['penugasan.kegiatan' => function ($q) {
                    $q->orderBy('tanggal', 'desc');
                }])
                ->orderBy('name')
                ->get();

        return view('admin.penugasan.by-staff', compact('staf'));
    }
}
