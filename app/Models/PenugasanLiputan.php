<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanLiputan extends Model
{
    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'jenis',
        'keterangan',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
