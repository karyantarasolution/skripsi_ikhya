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

    <p>Yang bertanda tangan di bawah ini, <strong>{{ $penandatangan->jabatan }}</strong> atas nama Biro Administrasi Pimpinan:</p>
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
                <th style="width: 28%;">Nama / NIP</th>
                <th>Jabatan</th>
                <th style="width: 22%;">Tugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stafTim as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $p->user->name ?? '-' }}</strong>
                        <br>NIP. {{ $p->user->nip ?? '-' }}
                    </td>
                    <td>{{ $p->user->role === 'admin' ? 'Staf/Admin' : 'Staf Peliput' }}</td>
                    <td>{{ $p->tugas ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Untuk melaksanakan tugas pada:</p>
    <table class="table-data" style="width: 85%;">
        <tr>
            <td style="width: 30%;">Kegiatan</td>
            <td>: {{ $kegiatan->judul_kegiatan }}</td>
        </tr>
        <tr>
            <td>Hari / Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>: {{ \Carbon\Carbon::parse($kegiatan->waktu)->format('H:i') }} WITA</td>
        </tr>
        <tr>
            <td>Tempat / Lokasi</td>
            <td>: {{ $kegiatan->lokasi }}</td>
        </tr>
        <tr>
            <td>Acara / Tugas</td>
            <td>: {{ $kegiatan->deskripsi ?? '-' }}</td>
        </tr>
    </table>

    <p>
        Demikian Surat Tugas ini dibuat untuk dipergunakan sebagaimana mestinya dan dilaksanakan dengan penuh tanggung jawab.
    </p>

    <div class="ttd-container clearfix">
        <div class="ttd-box">
            <p>Banjarbaru, {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}</p>
            <p><strong>Kepala Biro Administrasi Pimpinan</strong></p>
            <div class="ttd-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">{{ $penandatangan->nama_pejabat }}</p>
            <p>NIP. {{ $penandatangan->nip }}</p>
            @if(!empty($qr_svg) && !empty($hash))
                <div style="text-align: center; margin-top: 5px;">
                    <img src="data:image/svg+xml;base64,{{ base64_encode($qr_svg) }}" alt="QR Code Verifikasi" style="width: 100px; height: 100px;">
                    <p style="font-size: 8px; color: #666; margin: 2px 0 0 0;">Verifikasi: {{ substr($hash, 0, 16) }}...</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
