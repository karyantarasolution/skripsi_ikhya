<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen - SIA Biro Adpim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .hash-text { font-family: 'Fira Code', 'Consolas', monospace; word-break: break-all; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-4">
                <img src="{{ asset('images/logoikhya.png') }}" alt="Logo" class="h-12 w-auto bg-white rounded-full p-1 shadow">
                <div class="text-left">
                    <h1 class="text-lg font-bold text-gray-900">SIA Biro Adpim</h1>
                    <p class="text-xs text-gray-500">Pemprov Kalsel</p>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Verifikasi Dokumen</h2>
            <p class="text-sm text-gray-500 mt-1">Pemeriksaan keaslian dokumen digital</p>
        </div>

        @if($valid && $riwayat)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-emerald-500 px-6 py-4 text-center">
                    <svg class="w-16 h-16 text-white mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-white">DOKUMEN VALID</h3>
                    <p class="text-emerald-100 text-sm mt-1">Dokumen ini telah terverifikasi dan sah</p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Jenis Dokumen</p>
                            <p class="text-sm font-bold text-gray-900 mt-1 uppercase">{{ $riwayat->dokumen_type }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Nomor Dokumen</p>
                            <p class="text-sm font-bold text-gray-900 mt-1">{{ $riwayat->nomor_dokumen ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Tanggal Pengesahan</p>
                            <p class="text-sm font-bold text-gray-900 mt-1">{{ $riwayat->disahkan_at ? \Carbon\Carbon::parse($riwayat->disahkan_at)->translatedFormat('d F Y H:i') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Pejabat Penandatangan</p>
                            <p class="text-sm font-bold text-gray-900 mt-1">{{ $riwayat->penandatangan->nama_pejabat ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $riwayat->penandatangan->jabatan ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Disahkan Oleh</p>
                            <p class="text-sm font-bold text-gray-900 mt-1">{{ $riwayat->disahkanOleh->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">IP Address</p>
                            <p class="text-sm font-mono text-gray-900 mt-1">{{ $riwayat->ip_address ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Hash Verifikasi SHA-256</p>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <code class="hash-text text-xs text-gray-800">{{ $riwayat->hash_sha256 }}</code>
                        </div>
                    </div>

                    @if($riwayat->qr_code_path)
                    <div class="text-center pt-2">
                        <img src="{{ asset($riwayat->qr_code_path) }}" alt="QR Code TTD" class="inline-block w-24 h-24 border border-gray-200 rounded-lg">
                        <p class="text-xs text-gray-400 mt-1">QR Code Pengesahan</p>
                    </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-red-500 px-6 py-4 text-center">
                    <svg class="w-16 h-16 text-white mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-white">TIDAK VALID</h3>
                    <p class="text-red-100 text-sm mt-1">Dokumen ini tidak ditemukan atau tidak terverifikasi</p>
                </div>

                <div class="p-6 text-center">
                    <p class="text-gray-600 text-sm mb-4">Hash yang Anda masukkan tidak cocok dengan dokumen manapun dalam sistem kami.</p>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 mb-4">
                        <code class="hash-text text-xs text-gray-500">{{ request()->segment(count(request()->segments())) }}</code>
                    </div>
                    <p class="text-xs text-gray-400">Kemungkinan penyebab:</p>
                    <ul class="text-xs text-gray-400 mt-2 space-y-1 text-left inline-block">
                        <li>• QR Code yang dipindai bukan dari sistem ini</li>
                        <li>• Dokumen belum ditandatangani secara digital</li>
                        <li>• Data telah dimanipulasi</li>
                    </ul>
                </div>
            </div>
        @endif

        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
