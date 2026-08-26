<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Pertanggungjawaban Tugas</title>
    <style>
        .bukti-img {
            width: 100%;
            max-height: 340px;
            object-fit: contain;
            border: 1px solid #ccc;
            margin-bottom: 6px;
        }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @include('dokumen.pdf-kop')

    <h3 class="judul">LEMBAR PERTANGGUNGJAWABAN TUGAS</h3>
    <p class="nomor">Nomor: {{ $lpj->no_lpj }}</p>

    <p>Berdasarkan Surat Tugas Nomor {{ \App\Support\NomorSurat::format('ST', $lpj->kegiatan->id) }}, yang bertanda tangan di bawah ini:</p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama / NIP</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lpj->kegiatan->penugasan as $index => $ps)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $ps->user->name ?? '-' }}</strong>
                        <br>NIP. {{ $ps->user->nip ?? '-' }}
                    </td>
                    <td>{{ $ps->user->role === 'admin' ? 'Staf/Admin' : 'Staf Peliput' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Menyatakan dengan sesungguhnya bahwa tugas yang dilaksanakan meliputi:</p>
    <table class="table-data" style="width: 90%;">
        <tr>
            <td style="width: 30%;">Kegiatan</td>
            <td>: {{ $lpj->kegiatan->judul_kegiatan }}</td>
        </tr>
        <tr>
            <td>Hari / Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($lpj->kegiatan->tanggal)->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td>Tempat / Lokasi</td>
            <td>: {{ $lpj->kegiatan->lokasi }}</td>
        </tr>
        <tr>
            <td>Acara</td>
            <td>: {{ $lpj->kegiatan->deskripsi ?? '-' }}</td>
        </tr>
        @if($lpj->kegiatan->penugasan->first()?->tugas)
            <tr>
                <td>Tugas yang Dilaksanakan</td>
                <td>: {{ $lpj->kegiatan->penugasan->pluck('tugas')->unique()->implode(', ') }}</td>
            </tr>
        @endif
        @if($lpj->penanggung_jawab_nama)
            <tr>
                <td>Penanggung Jawab di Lokasi</td>
                <td>: {{ $lpj->penanggung_jawab_nama }} ({{ $lpj->penanggung_jawab_jabatan }})</td>
            </tr>
        @endif
        @if($lpj->uraian_hasil)
            <tr>
                <td>Uraian Hasil Pelaksanaan</td>
                <td>: {{ $lpj->uraian_hasil }}</td>
            </tr>
        @endif
    </table>

    <p>telah <strong>diselesaikan dengan baik</strong> dan dapat dipertanggungjawabkan. Adapun bukti pelaksanaan tugas sebagai berikut:</p>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Bukti Pelaksanaan</th>
                <th style="width: 35%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lpj->bukti as $index => $b)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $b->file }}</td>
                    <td>{{ $b->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada bukti lampiran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($lpj->bukti->count() > 0)
        <div class="page-break"></div>
        @include('dokumen.pdf-kop')
        <h3 class="judul">LAMPIRAN BUKTI PELAKSANAAN TUGAS</h3>
        @foreach($lpj->bukti as $index => $b)
            <div style="text-align: center; margin-bottom: 12px;">
                <p style="margin: 6px 0 2px 0;"><strong>Gambar {{ $index + 1 }}.</strong> {{ $b->keterangan ?? '' }}</p>
                @if(in_array(strtolower(pathinfo($b->file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                    <img src="{{ public_path($b->file) }}" class="bukti-img" alt="Bukti {{ $index + 1 }}">
                @endif
            </div>
        @endforeach
    @endif

    <p style="margin-top: 30px;">Demikian Lembar Pertanggungjawaban ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>

    <div class="ttd-container clearfix">
        <div class="ttd-box" style="float: left; text-align: center;">
            <p>Banjarbaru, {{ \Carbon\Carbon::parse($lpj->tanggal_lpj)->translatedFormat('d F Y') }}</p>
            <p><strong>Yang Melaporkan / Petugas</strong></p>
            <div class="ttd-space"></div>
            @foreach($lpj->kegiatan->penugasan as $ps)
                <p style="text-decoration: underline; font-weight: bold;">{{ $ps->user->name ?? '-' }}</p>
            @endforeach
        </div>
        <div class="ttd-box">
            <p><strong>Mengetahui,</strong><br>Kepala Biro Administrasi Pimpinan</p>
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
