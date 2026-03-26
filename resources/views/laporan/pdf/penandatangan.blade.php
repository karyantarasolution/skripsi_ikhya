@extends('laporan.pdf.layout')

@section('title', $title)

@section('content')
    <h3 class="judul-laporan">{{ $title }}</h3>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px; font-size: 11px; font-style: italic;">{{ $sub_judul }}</p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Lengkap</th>
                <th style="width: 25%;">NIP</th>
                <th style="width: 30%;">Jabatan</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftar_pejabat as $index => $pejabat)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $pejabat->nama_pejabat ?? $pejabat->nama }}</strong></td>
                    <td>{{ $pejabat->nip }}</td>
                    <td>{{ $pejabat->jabatan }}</td>
                    <td style="text-align: center;">
                        @if($pejabat->is_aktif)
                            <span style="color: green; font-weight: bold;">Aktif</span>
                        @else
                            <span style="color: red;">Nonaktif</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Belum ada data pejabat yang didaftarkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection