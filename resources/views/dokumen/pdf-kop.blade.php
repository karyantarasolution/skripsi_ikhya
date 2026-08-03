<style>
    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
        line-height: 1.5;
        color: #000;
        margin: 0;
        padding: 0;
    }
    .kop-surat {
        width: 100%;
        border-bottom: 3px solid #000;
        padding-bottom: 8px;
        margin-bottom: 18px;
    }
    .kop-surat table {
        width: 100%;
        border-collapse: collapse;
    }
    .kop-surat td {
        vertical-align: middle;
    }
    .logo-container {
        width: 14%;
        text-align: center;
    }
    .logo {
        width: 80px;
        height: auto;
    }
    .text-kop {
        width: 86%;
        text-align: center;
    }
    .text-kop h1 {
        font-size: 16px;
        margin: 0;
        text-transform: uppercase;
    }
    .text-kop h2 {
        font-size: 19px;
        margin: 0;
        font-weight: bold;
        text-transform: uppercase;
    }
    .text-kop p {
        font-size: 11px;
        margin: 2px 0 0 0;
    }
    h3.judul {
        text-align: center;
        font-size: 15px;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: underline;
        margin: 4px 0 2px 0;
    }
    p.nomor {
        text-align: center;
        font-size: 12px;
        margin: 0 0 16px 0;
    }
    table.table-data {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
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
    .ttd-container {
        width: 100%;
        margin-top: 30px;
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
        height: 80px;
    }
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
    .tab { padding-left: 40px; }
    .text-center { text-align: center; }
</style>

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
