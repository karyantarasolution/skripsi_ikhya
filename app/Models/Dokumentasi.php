<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    // INI BAGIAN YANG PALING PENTING UNTUK MENGHINDARI ERROR MASS ASSIGNMENT!
    protected $fillable = [
        'kegiatan_id',
        'nama_file',
        'file_path',
        'tipe_file',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}