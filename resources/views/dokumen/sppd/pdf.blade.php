<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Perjalanan Dinas</title>
</head>
<body>
    @include('dokumen.pdf-kop')

    <h3 class="judul">SURAT PERINTAH PERJALANAN DINAS</h3>
    <p class="nomor">Nomor: {{ $sppd->no_surat }}</p>

    <p>Kepala Biro Administrasi Pimpinan dengan ini memerintahkan kepada:</p>

    <table class="table-data">
        <tr>
            <td style="width: 30%;">Nama</td>
            <td>: <strong>{{ $sppd->user->name }}</strong></td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>: -</td>
        </tr>
        <tr>
            <td>Pangkat / Golongan</td>
            <td>: -</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: {{ ucfirst($sppd->user->role) }}</td>
        </tr>
        <tr>
            <td>Untuk Kepentingan</td>
            <td>: {{ $sppd->tujuan }}</td>
        </tr>
        <tr>
            <td>Tempat Tujuan</td>
            <td>: {{ $sppd->kota_tujuan }}</td>
        </tr>
        <tr>
            <td>Lama Perjalanan</td>
            <td>: {{ $lamaHari }} hari, tanggal {{ \Carbon\Carbon::parse($sppd->tanggal_berangkat)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($sppd->tanggal_kembali)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Alat Angkutan</td>
            <td>: {{ $sppd->kendaraan }}</td>
        </tr>
        <tr>
            <td>Pembebanan Anggaran</td>
            <td>: {{ $sppd->pembebanan }}</td>
        </tr>
        @if($sppd->kegiatan)
            <tr>
                <td>Dalam Rangka</td>
                <td>: {{ $sppd->kegiatan->judul_kegiatan }}</td>
            </tr>
        @endif
    </table>

    <p>Rincian biaya yang diperlukan:</p>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Uraian</th>
                <th style="width: 10%;">Volume</th>
                <th style="width: 12%;">Satuan</th>
                <th style="width: 18%;">Harga Satuan (Rp)</th>
                <th style="width: 18%;">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sppd->biaya as $index => $b)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $b->uraian }}</td>
                    <td class="text-center">{{ $b->volume }}</td>
                    <td class="text-center">{{ $b->satuan }}</td>
                    <td style="text-align: right;">{{ number_format($b->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($b->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">-</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">Jumlah Total</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($sppd->totalBiaya(), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if($sppd->keterangan)
        <p>Keterangan: {{ $sppd->keterangan }}</p>
    @endif

    <p>Surat Perintah Perjalanan Dinas ini dibuat untuk dipergunakan sebagai dasar pelaksanaan tugas dan pencairan biaya perjalanan dinas sesuai ketentuan yang berlaku.</p>

    <div class="ttd-container clearfix">
        <div class="ttd-box">
            <p>Banjarbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>{{ $penandatangan->jabatan }}</strong></p>
            <div class="ttd-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">{{ $penandatangan->nama_pejabat }}</p>
            <p>NIP. {{ $penandatangan->nip }}</p>
        </div>
    </div>
</body>
</html>
