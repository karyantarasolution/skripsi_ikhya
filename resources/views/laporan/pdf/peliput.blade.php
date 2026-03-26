@extends('laporan.pdf.layout')

@section('title', $title)

@section('content')
    <h3 class="judul-laporan">{{ $title }}</h3>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px; font-size: 11px; font-style: italic;">{{ $sub_judul }}</p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 40%;">Kegiatan Diliput</th>
                <th style="width: 20%;">Kategori</th>
                <th style="width: 20%;">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kegiatan as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </td>
                    <td><strong>{{ $item->judul_kegiatan }}</strong></td>
                    <td style="text-align: center;">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->lokasi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Petugas ini belum mencatat/meliput kegiatan apapun.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection