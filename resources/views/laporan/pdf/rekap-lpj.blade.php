@extends('laporan.pdf.layout')

@section('title', $title)

@section('content')
    <h3 class="judul-laporan">{{ $title }}</h3>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px; font-size: 11px; font-style: italic;">{{ $sub_judul }}</p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">No. LPJ</th>
                <th style="width: 18%;">Kegiatan</th>
                <th style="width: 12%;">Pelapor</th>
                <th style="width: 20%;">Uraian Hasil</th>
                <th style="width: 10%;">Tgl LPJ</th>
                <th style="width: 12%;">Penanggung Jawab</th>
                <th style="width: 12%;">Jumlah Bukti</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lpj as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center; font-size: 10px;">{{ $item->no_lpj ?? '-' }}</td>
                    <td style="font-size: 10px;">{{ $item->kegiatan->judul_kegiatan ?? '-' }}</td>
                    <td style="font-size: 10px;">{{ $item->user->name ?? '-' }}</td>
                    <td style="font-size: 10px;">{{ \Illuminate\Support\Str::limit($item->uraian_hasil, 60) ?? '-' }}</td>
                    <td style="text-align: center; font-size: 10px;">{{ \Carbon\Carbon::parse($item->tanggal_lpj)->format('d/m/Y') }}</td>
                    <td style="font-size: 10px;">{{ $item->penanggung_jawab_nama ?? '-' }}</td>
                    <td style="text-align: center; font-size: 10px;">{{ $item->bukti->count() }} file</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Belum ada data LPJ tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
