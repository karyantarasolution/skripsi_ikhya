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
}