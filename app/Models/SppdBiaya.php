<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppdBiaya extends Model
{
    use HasFactory;

    protected $fillable = [
        'sppd_id',
        'uraian',
        'volume',
        'satuan',
        'harga_satuan',
        'total',
    ];

    public function sppd()
    {
        return $this->belongsTo(SuratPerjalananDinas::class, 'sppd_id');
    }
}
