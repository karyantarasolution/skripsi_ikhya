@extends('laporan.pdf.layout')

@section('title', $title)

@section('content')
    <h3 class="judul-laporan">{{ $title }}</h3>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px; font-size: 11px; font-style: italic;">{{ $sub_judul }}</p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 14%;">Tanggal TTD</th>
                <th style="width: 15%;">Pengesah</th>
                <th style="width: 12%;">Jenis Dokumen</th>
                <th style="width: 15%;">No. Dokumen</th>
                <th style="width: 22%;">Hash SHA-256</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center; font-size: 10px;">{{ $item->disahkan_at ? \Carbon\Carbon::parse($item->disahkan_at)->format('d/m/Y H:i') : '-' }}</td>
                    <td style="font-size: 10px;">{{ $item->disahkanOleh->name ?? '-' }}</td>
                    <td style="text-align: center; font-size: 10px; text-transform: uppercase;">{{ $item->dokumen_type }}</td>
                    <td style="font-size: 10px;">{{ $item->nomor_dokumen ?? '-' }}</td>
                    <td style="font-size: 8px; font-family: monospace; word-break: break-all;">{{ $item->hash_sha256 }}</td>
                    <td style="text-align: center; font-size: 10px;">
                        <span style="color: green; font-weight: bold;">VALID</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Belum ada riwayat Tanda Tangan Digital.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
