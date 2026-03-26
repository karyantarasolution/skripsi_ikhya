@extends('laporan.pdf.layout')

@section('title', $title)

@section('content')
    <h3 class="judul-laporan">{{ $title }}</h3>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px; font-size: 11px; font-style: italic;">{{ $sub_judul }}</p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Waktu & Tanggal</th>
                <th style="width: 30%;">Nama Kegiatan</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 20%;">Lokasi</th>
                <th style="width: 15%;">Peliput</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kegiatan as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}<br>
                        <span style="font-size: 10px;">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }} WITA</span>
                    </td>
                    <td><strong>{{ $item->judul_kegiatan }}</strong></td>
                    <td style="text-align: center;">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td style="text-align: center; font-size: 11px;">{{ $item->user->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ditemukan data kegiatan pada periode tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection