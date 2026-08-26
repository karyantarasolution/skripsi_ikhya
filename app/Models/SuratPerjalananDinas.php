<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPerjalananDinas extends Model
{
    use HasFactory;

    protected $table = 'surat_perjalanan_dinas';

    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'no_surat',
        'tujuan',
        'kota_tujuan',
        'tanggal_berangkat',
        'tanggal_kembali',
        'kendaraan',
        'pembebanan',
        'keterangan',
        'status',
        'catatan',
        'ttd_status',
        'ttd_by',
        'ttd_at',
        'qr_code_path',
        'hash_sha256',
        'kabag_reviewed_by',
        'kabag_reviewed_at',
        'kabag_catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function biaya()
    {
        return $this->hasMany(SppdBiaya::class, 'sppd_id');
    }

    public function peserta()
    {
        return $this->hasMany(SppdPeserta::class, 'sppd_id')->with('user');
    }

    public function totalBiaya()
    {
        return $this->biaya->sum('total');
    }

    public function penandatangan()
    {
        return $this->belongsTo(Penandatangan::class, 'penandatangan_id');
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
            ->where('dokumen_type', 'sppd');
    }
}
