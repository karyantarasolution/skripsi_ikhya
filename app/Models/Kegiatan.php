<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul_kegiatan',
        'tanggal',
        'waktu',
        'lokasi',
        'pejabat_hadir',
        'deskripsi',
        'status',
        'rab_file',
        'lpj_file',
        'catatan_penolakan',
        'approved_by',
        'approved_at',
        'kabag_reviewed_by',
        'kabag_reviewed_at',
        'kabag_catatan',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriKegiatan::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'kegiatan_id');
    }

    public function penugasan()
    {
        return $this->hasMany(PenugasanLiputan::class, 'kegiatan_id');
    }

    public function approval()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function kabagReviewer()
    {
        return $this->belongsTo(User::class, 'kabag_reviewed_by');
    }

    public function riwayatTtd()
    {
        return $this->hasMany(RiwayatTtdDigital::class, 'dokumen_id')
            ->where('dokumen_type', 'kegiatan');
    }

    public function approvals()
    {
        return $this->hasMany(DocumentApproval::class, 'dokumen_id')
            ->where('dokumen_type', 'kegiatan');
    }
}