<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentApproval extends Model
{
    protected $table = 'document_approvals';

    protected $fillable = [
        'dokumen_type',
        'dokumen_id',
        'tahapan',
        'aksi',
        'user_id',
        'catatan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
