<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penandatangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pejabat',
        'nip',
        'jabatan',
        'qr_code_path',
        'is_aktif',
    ];
}