<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'warna_label',
    ];

    // TAMBAHKAN RELASI INI
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'kategori_id');
    }
}