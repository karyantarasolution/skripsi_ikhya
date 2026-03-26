<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKegiatan extends Model
{
    use HasFactory;

    // Tambahkan properti $fillable ini untuk mengizinkan Mass Assignment
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'warna_label',
    ];
}