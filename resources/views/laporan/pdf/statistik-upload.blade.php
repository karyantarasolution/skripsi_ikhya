@extends('laporan.pdf.layout')

@section('title', $title)

@section('content')
    <h3 class="judul-laporan">{{ $title }}</h3>
    <p style="text-align: center; margin-top: -15px; margin-bottom: 20px; font-size: 11px; font-style: italic;">{{ $sub_judul }}</p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Staf Peliput</th>
                <th style="width: 15%;">Role</th>
                <th style="width: 20%;">Jumlah Upload File</th>
                <th style="width: 15%;">Jumlah Penugasan</th>
                <th style="width: 15%;">Kinerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staf as $index => $s)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $s->name }}</strong></td>
                    <td style="text-align: center;">{{ ucfirst($s->role) }}</td>
                    <td style="text-align: center;">
                        <strong>{{ $s->dokumentasi_count }}</strong> file
                    </td>
                    <td style="text-align: center;">
                        {{ $s->penugasan_count ?? $s->penugasan->count() ?? 0 }} tugas
                    </td>
                    <td style="text-align: center;">
                        @if($s->dokumentasi_count > 20)
                            Sangat Baik
                        @elseif($s->dokumentasi_count > 10)
                            Baik
                        @elseif($s->dokumentasi_count > 5)
                            Cukup
                        @else
                            Perlu Ditingkatkan
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Belum ada data staf peliput.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="text-align: right; font-size: 11px; margin-top: 10px;">
        <strong>Total Seluruh File Terupload:</strong> {{ $total_upload }} file
    </p>
@endsection
