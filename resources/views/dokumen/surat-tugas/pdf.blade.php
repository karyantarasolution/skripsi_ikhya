<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas</title>
</head>
<body>
    @include('dokumen.pdf-kop')

    <h3 class="judul">SURAT TUGAS</h3>
    <p class="nomor">Nomor: {{ $noSurat }}</p>

    <p>Yang bertanda tangan di bawah ini:</p>
    <table class="table-data" style="width: 55%;">
        <tr>
            <td style="width: 30%;">Nama</td>
            <td>: {{ $penandatangan->nama_pejabat }}</td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>: {{ $penandatangan->nip }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: {{ $penandatangan->jabatan }}</td>
        </tr>
    </table>

    <p>Dengan ini menugaskan kepada:</p>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama / NIP</th>
                <th>Jabatan</th>
                <th style="width: 12%;">Jenis</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stafTim as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $p->user->name ?? '-' }}</strong>
                        <br>NIP. -
                    </td>
                    <td>{{ $p->user->role === 'admin' ? 'Staf/Admin' : 'Staf Peliput' }}</td>
                    <td class="text-center">{{ ucfirst($p->jenis) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Untuk melaksanakan tugas liputan pada:</p>
    <table class="table-data" style="width: 85%;">
        <tr>
            <td style="width: 30%;">Kegiatan</td>
            <td>: {{ $penugasan->kegiatan->judul_kegiatan }}</td>
        </tr>
        <tr>
            <td>Hari / Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($penugasan->kegiatan->tanggal)->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>: {{ \Carbon\Carbon::parse($penugasan->kegiatan->waktu)->format('H:i') }} WITA</td>
        </tr>
        <tr>
            <td>Tempat / Lokasi</td>
            <td>: {{ $penugasan->kegiatan->lokasi }}</td>
        </tr>
        <tr>
            <td>Acara</td>
            <td>: {{ $penugasan->kegiatan->deskripsi ?? '-' }}</td>
        </tr>
        @if($penugasan->keterangan)
            <tr>
                <td>Keterangan</td>
                <td>: {{ $penugasan->keterangan }}</td>
            </tr>
        @endif
    </table>

    <p>
        Demikian Surat Tugas ini dibuat untuk dipergunakan sebagaimana mestinya dan dilaksanakan dengan penuh tanggung jawab.
    </p>

    <div class="ttd-container clearfix">
        <div class="ttd-box">
            <p>Banjarbaru, {{ \Carbon\Carbon::parse($penugasan->kegiatan->tanggal)->translatedFormat('d F Y') }}</p>
            <p><strong>{{ $penandatangan->jabatan }}</strong></p>
            <div class="ttd-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">{{ $penandatangan->nama_pejabat }}</p>
            <p>NIP. {{ $penandatangan->nip }}</p>
        </div>
    </div>
</body>
</html>
