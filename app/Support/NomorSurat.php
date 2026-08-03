<?php

namespace App\Support;

use Carbon\Carbon;

class NomorSurat
{
    public static function romawiBulan(int $bulan): string
    {
        $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romawi[$bulan] ?? '';
    }

    public static function format(string $kode, int $urutan, ?Carbon $tanggal = null): string
    {
        $tgl = $tanggal ?: Carbon::now();
        $pad = str_pad($urutan, 3, '0', STR_PAD_LEFT);
        return $kode . '.' . $pad . '/B.ADPIM-SETDA/' . self::romawiBulan((int)$tgl->format('n')) . '/' . $tgl->format('Y');
    }
}
