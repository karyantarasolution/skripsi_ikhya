<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SppdPeserta extends Model
{
    protected $table = 'sppd_peserta';

    protected $fillable = [
        'sppd_id',
        'user_id',
    ];

    public function sppd()
    {
        return $this->belongsTo(SuratPerjalananDinas::class, 'sppd_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
