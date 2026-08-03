<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LpjTugasBukti extends Model
{
    use HasFactory;

    protected $fillable = [
        'lpj_tugas_id',
        'file',
        'keterangan',
    ];

    public function lpjTugas()
    {
        return $this->belongsTo(LpjTugas::class, 'lpj_tugas_id');
    }
}
