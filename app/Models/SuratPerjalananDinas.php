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

    public function totalBiaya()
    {
        return $this->biaya->sum('total');
    }
}
