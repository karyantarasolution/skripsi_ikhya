@extends('laporan.pdf.layout')

@section('title', $title)

@section('content')
    <h3 class="judul-laporan">{{ $title }}</h3>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px; font-size: 11px; font-style: italic;">{{ $sub_judul }}</p>

    <table class="table-data" style="width: 80%; margin: 0 auto;">
        <thead>
            <tr>
                <th style="width: 10%;">No</th>
                <th style="width: 50%;">Kategori Kegiatan</th>
                <th style="width: 40%;">Jumlah Pelaksanaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategori as $index => $kat)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $kat->nama_kategori }}</strong></td>
                    <td style="text-align: center; font-size: 14px; font-weight: bold;">
                        {{ $kat->kegiatan_count }} Kali
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right; padding-right: 15px;">TOTAL KESELURUHAN KEGIATAN</th>
                <th style="text-align: center; font-size: 16px;">{{ $total_kegiatan }} Kegiatan</th>
            </tr>
        </tfoot>
    </table>
@endsection