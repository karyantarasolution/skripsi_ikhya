<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LpjTugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'penugasan_id',
        'kegiatan_id',
        'no_lpj',
        'uraian_hasil',
        'penanggung_jawab_nama',
        'penanggung_jawab_jabatan',
        'tanggal_lpj',
        'status',
        'kabag_reviewed_by',
        'kabag_reviewed_at',
        'kabag_catatan',
        'ttd_status',
        'ttd_by',
        'ttd_at',
        'qr_code_path',
        'hash_sha256',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penugasan()
    {
        return $this->belongsTo(PenugasanLiputan::class, 'penugasan_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function bukti()
    {
        return $this->hasMany(LpjTugasBukti::class, 'lpj_tugas_id');
    }

    public function ttdOleh()
    {
        return $this->belongsTo(User::class, 'ttd_by');
    }

    public function kabagReviewer()
    {
        return $this->belongsTo(User::class, 'kabag_reviewed_by');
    }

    public function riwayatTtd()
    {
        return $this->hasMany(RiwayatTtdDigital::class, 'dokumen_id')
            ->where('dokumen_type', 'lpj_tugas');
    }
}
