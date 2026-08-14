<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\LpjTugas;
use App\Models\LpjTugasBukti;
use App\Models\Penandatangan;
use App\Support\NomorSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LpjTugasController extends Controller
{
    public function index()
    {
        $lpj = LpjTugas::with(['kegiatan.penugasan.user', 'user', 'bukti'])
            ->when(Auth::user()->role === 'staf', function ($q) {
                $q->where(function ($q) {
                    $q->where('user_id', Auth::id())
                        ->orWhereHas('kegiatan.penugasan', function ($p) {
                            $p->where('user_id', Auth::id());
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dokumen.lpj-tugas.index', compact('lpj'));
    }

    public function create()
    {
        $kegiatan = Kegiatan::with(['penugasan.user'])
            ->when(Auth::user()->role !== 'admin', function ($q) {
                $q->whereHas('penugasan', function ($p) {
                    $p->where('user_id', Auth::id());
                });
            })
            ->whereHas('penugasan')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('dokumen.lpj-tugas.create', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'uraian_hasil' => 'nullable|string',
            'penanggung_jawab_nama' => 'required|string|max:255',
            'penanggung_jawab_jabatan' => 'required|string|max:255',
            'tanggal_lpj' => 'required|date',
            'bukti' => 'nullable|array',
            'bukti.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ]);

        $kegiatan = Kegiatan::with('penugasan')->findOrFail($request->kegiatan_id);

        if (Auth::user()->role !== 'admin' && ! $kegiatan->penugasan->contains('user_id', Auth::id())) {
            abort(403);
        }

        $penugasanId = $kegiatan->penugasan->first()?->id;

        $lpj = LpjTugas::create([
            'user_id' => Auth::id(),
            'penugasan_id' => $penugasanId,
            'kegiatan_id' => $request->kegiatan_id,
            'uraian_hasil' => $request->uraian_hasil,
            'penanggung_jawab_nama' => $request->penanggung_jawab_nama,
            'penanggung_jawab_jabatan' => $request->penanggung_jawab_jabatan,
            'tanggal_lpj' => $request->tanggal_lpj,
        ]);

        $tahun = Carbon::parse($lpj->tanggal_lpj)->format('Y');
        $urutan = LpjTugas::whereYear('tanggal_lpj', $tahun)->count() + 1;
        $lpj->update(['no_lpj' => NomorSurat::format('LPJ', $urutan, Carbon::parse($lpj->tanggal_lpj))]);

        $this->simpanBukti($lpj, $request);

        return redirect()->route('dokumen.lpj-tugas.index')->with('success', 'LPJ Tugas berhasil dibuat.');
    }

    public function edit($id)
    {
        $lpj = LpjTugas::with(['kegiatan.penugasan.user', 'user', 'bukti'])->findOrFail($id);

        if ($lpj->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('dokumen.lpj-tugas.edit', compact('lpj'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'penanggung_jawab_nama' => 'required|string|max:255',
            'penanggung_jawab_jabatan' => 'required|string|max:255',
            'tanggal_lpj' => 'required|date',
            'uraian_hasil' => 'nullable|string',
        ]);

        $lpj = LpjTugas::findOrFail($id);

        if ($lpj->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $lpj->update($request->only([
            'penanggung_jawab_nama',
            'penanggung_jawab_jabatan',
            'tanggal_lpj',
            'uraian_hasil',
        ]));

        return back()->with('success', 'Informasi LPJ berhasil diperbarui.');
    }

    public function tambahBukti(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|array',
            'bukti.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
            'keterangan' => 'nullable|string',
        ]);

        $lpj = LpjTugas::findOrFail($id);

        if ($lpj->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $this->simpanBukti($lpj, $request);

        return back()->with('success', 'Bukti LPJ berhasil ditambahkan.');
    }

    public function destroyBukti($id)
    {
        $bukti = LpjTugasBukti::with('lpjTugas')->findOrFail($id);
        $lpj = $bukti->lpjTugas;

        if ($lpj->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        if (File::exists(public_path($bukti->file))) {
            File::delete(public_path($bukti->file));
        }
        $bukti->delete();

        return back()->with('success', 'Bukti LPJ berhasil dihapus.');
    }

    public function destroy($id)
    {
        $lpj = LpjTugas::with('bukti')->findOrFail($id);

        if ($lpj->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        foreach ($lpj->bukti as $b) {
            if (File::exists(public_path($b->file))) {
                File::delete(public_path($b->file));
            }
        }
        $lpj->delete();

        return redirect()->route('dokumen.lpj-tugas.index')->with('success', 'LPJ Tugas berhasil dihapus.');
    }

    public function cetak($id)
    {
        $lpj = LpjTugas::with(['kegiatan.penugasan.user', 'user', 'bukti'])->findOrFail($id);

        $penandatangan = Penandatangan::where('is_aktif', true)->first();

        if (! $penandatangan) {
            return back()->with('error', 'Data penandatangan aktif belum diatur. Hubungi admin.');
        }

        $pdf = Pdf::loadView('dokumen.lpj-tugas.pdf', [
            'lpj' => $lpj,
            'penandatangan' => $penandatangan,
        ]);

        return $pdf->setPaper('a4', 'portrait')->stream('lpj_tugas_'.$lpj->id.'.pdf');
    }

    private function simpanBukti(LpjTugas $lpj, Request $request): void
    {
        if (! $request->hasFile('bukti')) {
            return;
        }

        $destinationPath = public_path('uploads/lpj-bukti');
        if (! File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $keterangan = $request->keterangan;

        foreach ($request->file('bukti') as $index => $file) {
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            $lpj->bukti()->create([
                'file' => 'uploads/lpj-bukti/'.$fileName,
                'keterangan' => is_array($keterangan) ? ($keterangan[$index] ?? null) : $keterangan,
            ]);
        }
    }
}
