<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Laporan Biro Adpim')</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        /* KOP SURAT */
        .kop-surat {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-surat td {
            vertical-align: middle;
        }
        .logo-container {
            width: 15%;
            text-align: center;
        }
        .logo {
            width: 80px; /* Sesuaikan ukuran logo */
            height: auto;
        }
        .text-kop {
            width: 85%;
            text-align: center;
        }
        .text-kop h1 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
        }
        .text-kop h2 {
            font-size: 20px;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-kop p {
            font-size: 12px;
            margin: 2px 0 0 0;
        }
        
        /* CONTENT BERSAMA */
        h3.judul-laporan {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        table.table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.table-data th, table.table-data td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        table.table-data th {
            background-color: #e2e2e2;
            font-weight: bold;
            text-align: center;
        }
        
        /* BAGIAN TANDA TANGAN */
        .ttd-container {
            width: 100%;
            margin-top: 40px;
        }
        .ttd-box {
            float: right;
            width: 300px;
            text-align: center;
        }
        .ttd-box p {
            margin: 2px 0;
        }
        .ttd-space {
            height: 80px; /* Ruang untuk tanda tangan basah/stempel */
        }
        .ttd-qr {
            text-align: center;
            margin-top: 5px;
        }
        .ttd-qr img {
            width: 100px;
            height: 100px;
        }
        .ttd-qr p {
            font-size: 8px;
            color: #666;
            margin: 2px 0 0 0;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <table>
            <tr>
                <td class="logo-container">
                    <img src="{{ public_path('images/logoikhya.png') }}" class="logo" alt="Logo">
                </td>
                <td class="text-kop">
                    <h1>PEMERINTAH PROVINSI KALIMANTAN SELATAN</h1>
                    <h2>SEKRETARIAT DAERAH</h2>
                    <p>Jl. Dharma Praja No.1, Kawasan Perkantoran Pemprov Kalsel, Banjarbaru</p>
                    <p>Email: biroadpim@kalselprov.go.id | Website: adpim.kalselprov.go.id</p>
                </td>
            </tr>
        </table>
    </div>

    @yield('content')

    <div class="ttd-container clearfix">
        <div class="ttd-box">
            <p>Banjarbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>{{ $penandatangan->jabatan }}</strong></p>
            <div class="ttd-space"></div>
           <p style="text-decoration: underline; font-weight: bold;">{{ $penandatangan->nama_pejabat ?? $penandatangan->nama }}</p>
            <p>NIP. {{ $penandatangan->nip }}</p>
            @if(!empty($qr_svg) && !empty($hash))
                <div class="ttd-qr">
                    <img src="data:image/svg+xml;base64,{{ base64_encode($qr_svg) }}" alt="QR Code Verifikasi">
                    <p>Verifikasi: {{ substr($hash, 0, 16) }}...</p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>