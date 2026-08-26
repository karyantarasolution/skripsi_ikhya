<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">Pusat Laporan & Evaluasi</h2>
        <p class="text-sm text-gray-600 mt-1">Cetak dokumen laporan resmi peliputan lapangan Biro Adpim Setda Prov. Kalsel.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white border border-gray-200 text-gray-700 rounded-lg shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Penandatangan Laporan</h3>
                        <p class="text-sm text-gray-600 mt-1">Pilih pejabat yang berwenang menandatangani dokumen sebelum Anda mencetak laporan di bawah ini.</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3">
                    <select id="global_penandatangan" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 font-medium text-gray-700 bg-white">
                        <option value="" disabled selected>-- Pilih Pejabat Penandatangan --</option>
                        @foreach($penandatangan as $pejabat)
                            <option value="{{ $pejabat->id }}">{{ $pejabat->nama_pejabat }} ({{ $pejabat->jabatan }})</option>
                        @endforeach
                    </select>
                    @if($penandatangan->isEmpty())
                        <p class="text-xs text-red-500 mt-2 font-semibold">*Peringatan: Belum ada data Pejabat Penandatangan yang berstatus Aktif. Silakan hubungi Admin.</p>
                    @endif
                </div>
            </div>

            {{-- ========== A. OPERASIONAL ========== --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">A. Operasional</h3>
                        <p class="text-xs text-gray-500">Laporan kegiatan lapangan dan penugasan staf</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">1. Laporan Seluruh Kegiatan</h3>
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase rounded tracking-wider border border-blue-200">Master</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Mencetak daftar seluruh jadwal dan riwayat kegiatan peliputan lapangan yang pernah direkam di dalam sistem.</p>
                            <form action="{{ route('laporan.cetak.semua') }}" method="GET" target="_blank" class="print-form mt-auto pt-4 border-t border-gray-100">
                                <input type="hidden" name="penandatangan_id" class="target_penandatangan" required>
                                <button type="button" class="btn-cetak w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak PDF
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">2. Laporan Rentang Tanggal</h3>
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase rounded tracking-wider border border-blue-200">Filter</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Mencetak kegiatan yang dilaksanakan dalam periode tanggal tertentu (misalnya laporan bulanan).</p>
                            <form action="{{ route('laporan.cetak.tanggal') }}" method="GET" target="_blank" class="print-form mt-auto border-t border-gray-100 pt-4">
                                <input type="hidden" name="penandatangan_id" class="target_penandatangan" required>
                                <div class="flex gap-3 mb-4">
                                    <div class="flex-1">
                                        <label class="text-xs font-semibold text-gray-900 mb-1 block">Dari Tanggal</label>
                                        <input type="date" name="start_date" required class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-semibold text-gray-900 mb-1 block">Sampai Tanggal</label>
                                        <input type="date" name="end_date" required class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                </div>
                                <button type="button" class="btn-cetak w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak PDF
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">3. Berita Acara & Dokumentasi</h3>
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase rounded tracking-wider border border-blue-200">Spesifik</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Mencetak detail 1 kegiatan (waktu, lokasi, pejabat hadir) beserta lampiran foto-foto dokumentasinya.</p>
                            <form action="{{ route('laporan.cetak.berita-acara') }}" method="GET" target="_blank" class="print-form mt-auto border-t border-gray-100 pt-4">
                                <input type="hidden" name="penandatangan_id" class="target_penandatangan" required>
                                <div class="mb-4">
                                    <label class="text-xs font-semibold text-gray-900 mb-1 block">Pilih Judul Kegiatan (Album)</label>
                                    <select name="kegiatan_id" required class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                        <option value="" disabled selected>-- Pilih Kegiatan --</option>
                                        @foreach($kegiatan_list as $keg)
                                            <option value="{{ $keg->id }}">{{ \Carbon\Carbon::parse($keg->tanggal)->format('d/m/Y') }} - {{ Str::limit($keg->judul_kegiatan, 40) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn-cetak w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Cetak Berita Acara
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">4. Laporan Kinerja Peliput</h3>
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase rounded tracking-wider border border-blue-200">Filter</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Mencetak rekam jejak jadwal kegiatan yang diinput atau dikerjakan oleh staf peliput tertentu.</p>
                            <form action="{{ route('laporan.cetak.peliput') }}" method="GET" target="_blank" class="print-form mt-auto border-t border-gray-100 pt-4">
                                <input type="hidden" name="penandatangan_id" class="target_penandatangan" required>
                                <div class="mb-4">
                                    <label class="text-xs font-semibold text-gray-900 mb-1 block">Pilih Nama Peliput/Staf</label>
                                    <select name="user_id" required class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                        <option value="" disabled selected>-- Pilih Petugas --</option>
                                        @foreach($peliput as $staf)
                                            <option value="{{ $staf->id }}">{{ $staf->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn-cetak w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak PDF
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ========== B. AKUNTABILITAS ========== --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">B. Akuntabilitas</h3>
                        <p class="text-xs text-gray-500">Laporan pertanggungjawaban perjalanan dinas, LPJ, dan riwayat TTD digital</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">5. Rekap SPPD</h3>
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 text-[10px] font-bold uppercase rounded tracking-wider border border-green-200">Perjalanan Dinas</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Mencetak rekapitulasi data perjalanan dinas beserta rincian biaya untuk pertanggungjawaban anggaran.</p>
                            <form action="{{ route('laporan.cetak.rekap-sppd') }}" method="GET" target="_blank" class="print-form mt-auto border-t border-gray-100 pt-4">
                                <input type="hidden" name="penandatangan_id" class="target_penandatangan" required>
                                <div class="flex gap-3 mb-4">
                                    <div class="flex-1">
                                        <label class="text-xs font-semibold text-gray-900 mb-1 block">Dari Tanggal (Opsional)</label>
                                        <input type="date" name="start_date" class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-semibold text-gray-900 mb-1 block">Sampai Tanggal (Opsional)</label>
                                        <input type="date" name="end_date" class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                </div>
                                <button type="button" class="btn-cetak w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak Rekap SPPD
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">6. Rekap LPJ Tugas</h3>
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 text-[10px] font-bold uppercase rounded tracking-wider border border-green-200">LPJ</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Mencetak rekapitulasi laporan pertanggungjawaban tugas/anggaran beserta jumlah bukti lampiran.</p>
                            <form action="{{ route('laporan.cetak.rekap-lpj') }}" method="GET" target="_blank" class="print-form mt-auto border-t border-gray-100 pt-4">
                                <input type="hidden" name="penandatangan_id" class="target_penandatangan" required>
                                <div class="flex gap-3 mb-4">
                                    <div class="flex-1">
                                        <label class="text-xs font-semibold text-gray-900 mb-1 block">Dari Tanggal (Opsional)</label>
                                        <input type="date" name="start_date" class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-semibold text-gray-900 mb-1 block">Sampai Tanggal (Opsional)</label>
                                        <input type="date" name="end_date" class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                </div>
                                <button type="button" class="btn-cetak w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak Rekap LPJ
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">7. Riwayat TTD Digital</h3>
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 text-[10px] font-bold uppercase rounded tracking-wider border border-green-200">TTD Digital</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Mencetak catatan riwayat pengesahan dokumen secara digital beserta hash verifikasi dan status.</p>
                            <form action="{{ route('laporan.cetak.riwayat-ttd') }}" method="GET" target="_blank" class="print-form mt-auto border-t border-gray-100 pt-4">
                                <input type="hidden" name="penandatangan_id" class="target_penandatangan" required>
                                <div class="mb-4">
                                    <label class="text-xs font-semibold text-gray-900 mb-1 block">Jenis Dokumen (Opsional)</label>
                                    <select name="dokumen_type" class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                        <option value="">Semua Jenis</option>
                                        <option value="sppd">SPPD</option>
                                        <option value="lpj_tugas">LPJ Tugas</option>
                                        <option value="surat_tugas">Surat Tugas</option>
                                    </select>
                                </div>
                                <div class="flex gap-3 mb-4">
                                    <div class="flex-1">
                                        <label class="text-xs font-semibold text-gray-900 mb-1 block">Dari Tanggal (Opsional)</label>
                                        <input type="date" name="start_date" class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-semibold text-gray-900 mb-1 block">Sampai Tanggal (Opsional)</label>
                                        <input type="date" name="end_date" class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                </div>
                                <button type="button" class="btn-cetak w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Cetak Riwayat TTD
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ========== C. MANAJERIAL ========== --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">C. Manajerial</h3>
                        <p class="text-xs text-gray-500">Evaluasi kinerja staf, rekap berdasarkan kategori, dan tren kegiatan</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">8. Rekapitulasi per Kategori</h3>
                                <span class="px-2.5 py-1 bg-purple-50 text-purple-600 text-[10px] font-bold uppercase rounded tracking-wider border border-purple-200">Statistik</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Mencetak tabel statistik jumlah kegiatan untuk setiap kategori yang terdaftar dalam sistem.</p>
                            <form action="{{ route('laporan.cetak.statistik-kategori') }}" method="GET" target="_blank" class="print-form mt-auto border-t border-gray-100 pt-4">
                                <input type="hidden" name="penandatangan_id" class="target_penandatangan" required>
                                <button type="button" class="btn-cetak w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    Cetak Statistik PDF
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">9. Grafik/Tren Kegiatan per Bulan</h3>
                                <span class="px-2.5 py-1 bg-purple-50 text-purple-600 text-[10px] font-bold uppercase rounded tracking-wider border border-purple-200">Statistik</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">Mencetak tabel statistik jumlah kegiatan peliputan per bulan dalam satu tahun untuk analisis tren.</p>
                            <form action="{{ route('laporan.cetak.statistik-bulan') }}" method="GET" target="_blank" class="print-form mt-auto border-t border-gray-100 pt-4">
                                <input type="hidden" name="penandatangan_id" class="target_penandatangan" required>
                                <div class="mb-4">
                                    <label class="text-xs font-semibold text-gray-900 mb-1 block">Pilih Tahun Analisis</label>
                                    <select name="tahun" required class="w-full text-sm rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                                        <option value="{{ date('Y') }}" selected>{{ date('Y') }} (Tahun Berjalan)</option>
                                        <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }} (Tahun Lalu)</option>
                                    </select>
                                </div>
                                <button type="button" class="btn-cetak w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-bold shadow-md transition-transform transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    Cetak Statistik PDF
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const globalSelect = document.getElementById('global_penandatangan');
            const hiddenInputs = document.querySelectorAll('.target_penandatangan');
            const cetakButtons = document.querySelectorAll('.btn-cetak');

            function syncPenandatangan() {
                const selectedValue = globalSelect.value;
                hiddenInputs.forEach(input => {
                    input.value = selectedValue;
                });
            }

            globalSelect.addEventListener('change', syncPenandatangan);

            cetakButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!globalSelect.value) {
                        e.preventDefault();
                        alert('PERHATIAN: Anda wajib memilih "Pejabat Penandatangan Laporan" terlebih dahulu pada bagian atas halaman sebelum mencetak dokumen.');
                        globalSelect.focus();
                    } else {
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>
