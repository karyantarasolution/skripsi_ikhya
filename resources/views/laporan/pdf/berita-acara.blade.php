@extends('laporan.pdf.layout')

@section('title', $title)

@section('content')
    <h3 class="judul-laporan">{{ $title }}</h3>
    
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px;">
        <tr>
            <td style="width: 20%; padding: 5px; font-weight: bold; border-bottom: 1px dotted #ccc;">Nama Kegiatan</td>
            <td style="width: 5%; padding: 5px; text-align: center; border-bottom: 1px dotted #ccc;">:</td>
            <td style="width: 75%; padding: 5px; font-weight: bold; font-size: 14px; border-bottom: 1px dotted #ccc;">{{ $kegiatan->judul_kegiatan }}</td>
        </tr>
        <tr>
            <td style="padding: 5px; font-weight: bold; border-bottom: 1px dotted #ccc;">Kategori</td>
            <td style="padding: 5px; text-align: center; border-bottom: 1px dotted #ccc;">:</td>
            <td style="padding: 5px; border-bottom: 1px dotted #ccc;">{{ $kegiatan->kategori->nama_kategori ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 5px; font-weight: bold; border-bottom: 1px dotted #ccc;">Waktu & Tanggal</td>
            <td style="padding: 5px; text-align: center; border-bottom: 1px dotted #ccc;">:</td>
            <td style="padding: 5px; border-bottom: 1px dotted #ccc;">{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('l, d F Y') }} | Pukul {{ \Carbon\Carbon::parse($kegiatan->waktu)->format('H:i') }} WITA</td>
        </tr>
        <tr>
            <td style="padding: 5px; font-weight: bold; border-bottom: 1px dotted #ccc;">Lokasi</td>
            <td style="padding: 5px; text-align: center; border-bottom: 1px dotted #ccc;">:</td>
            <td style="padding: 5px; border-bottom: 1px dotted #ccc;">{{ $kegiatan->lokasi }}</td>
        </tr>
        <tr>
            <td style="padding: 5px; font-weight: bold; border-bottom: 1px dotted #ccc;">Pejabat Hadir</td>
            <td style="padding: 5px; text-align: center; border-bottom: 1px dotted #ccc;">:</td>
            <td style="padding: 5px; border-bottom: 1px dotted #ccc;">{{ $kegiatan->pejabat_hadir ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 5px; font-weight: bold; vertical-align: top;">Deskripsi Singkat</td>
            <td style="padding: 5px; text-align: center; vertical-align: top;">:</td>
            <td style="padding: 5px; text-align: justify;">{{ $kegiatan->deskripsi ?? 'Tidak ada catatan khusus.' }}</td>
        </tr>
    </table>

    <h4 style="font-size: 13px; font-weight: bold; border-bottom: 2px solid #000; padding-bottom: 3px; margin-top: 30px;">LAMPIRAN DOKUMENTASI</h4>
    
    <div style="width: 100%; text-align: center;">
        @php $foto_count = 0; @endphp
        
        @foreach($kegiatan->dokumentasi as $doc)
            @if($doc->tipe_file == 'foto')
                @php $foto_count++; @endphp
                <div style="display: inline-block; width: 45%; margin: 10px 2%; text-align: center; border: 1px solid #ddd; padding: 5px; background: #f9f9f9;">
                    <img src="{{ public_path($doc->file_path) }}" style="width: 100%; height: 200px; object-fit: cover;" alt="Foto">
                    <p style="font-size: 10px; margin: 5px 0 0 0; word-break: break-all;">File: {{ $doc->nama_file }}</p>
                </div>
            @endif
        @endforeach

        @if($foto_count == 0)
            <p style="text-align: center; font-style: italic; color: #666;">-- Belum ada foto dokumentasi yang dilampirkan --</p>
        @endif
    </div>
@endsection