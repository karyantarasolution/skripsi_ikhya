<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatTtdDigital extends Model
{
    protected $table = 'riwayat_ttd_digitals';

    protected $fillable = [
        'dokumen_type',
        'dokumen_id',
        'penandatangan_id',
        'nomor_dokumen',
        'hash_sha256',
        'qr_code_path',
        'disahkan_by',
        'disahkan_at',
        'pin_verified_at',
        'ip_address',
    ];

    protected $casts = [
        'disahkan_at' => 'datetime',
        'pin_verified_at' => 'datetime',
    ];

    public function penandatangan(): BelongsTo
    {
        return $this->belongsTo(Penandatangan::class);
    }

    public function disahkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disahkan_by');
    }

    public function dokumen()
    {
        return $this->morphTo();
    }
}
