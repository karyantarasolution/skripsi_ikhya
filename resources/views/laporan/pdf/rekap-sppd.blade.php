@extends('laporan.pdf.layout')

@section('title', $title)

@section('content')
    <h3 class="judul-laporan">{{ $title }}</h3>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px; font-size: 11px; font-style: italic;">{{ $sub_judul }}</p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">No. SPPD</th>
                <th style="width: 15%;">Petugas</th>
                <th style="width: 13%;">Peserta</th>
                <th style="width: 12%;">Tujuan</th>
                <th style="width: 10%;">Tgl Berangkat</th>
                <th style="width: 10%;">Tgl Kembali</th>
                <th style="width: 12%;">Total Biaya</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sppd as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center; font-size: 10px;">{{ $item->no_surat ?? '-' }}</td>
                    <td style="font-size: 10px;">{{ $item->user->name ?? '-' }}</td>
                    <td style="font-size: 10px;">
                        @foreach($item->peserta as $ps)
                            {{ $ps->user->name ?? '?' }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td style="font-size: 10px;">{{ $item->kota_tujuan }}</td>
                    <td style="text-align: center; font-size: 10px;">{{ \Carbon\Carbon::parse($item->tanggal_berangkat)->format('d/m/Y') }}</td>
                    <td style="text-align: center; font-size: 10px;">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}</td>
                    <td style="text-align: right; font-size: 10px;">Rp {{ number_format($item->totalBiaya(), 0, ',', '.') }}</td>
                    <td style="text-align: center; font-size: 10px; text-transform: uppercase; font-weight: bold;">{{ $item->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">Belum ada data SPPD tercatat.</td>
                </tr>
            @endforelse
        </tbody>
        @if($sppd->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="7" style="text-align: right; font-weight: bold; background-color: #f0f0f0;">TOTAL BIAYA</td>
                <td style="text-align: right; font-weight: bold; background-color: #f0f0f0;">Rp {{ number_format($total_biaya, 0, ',', '.') }}</td>
                <td style="background-color: #f0f0f0;"></td>
            </tr>
        </tfoot>
        @endif
    </table>
@endsection
