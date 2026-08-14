<x-app-layout>
    @php
        $existingRows = $sppd->biaya->map(fn($b) => [
            'uraian' => $b->uraian,
            'volume' => $b->volume,
            'satuan' => $b->satuan ?? '',
            'harga' => (float)$b->harga_satuan,
        ])->toArray();

        if (empty($existingRows)) {
            $existingRows = [[ 'uraian' => '', 'volume' => 1, 'satuan' => '', 'harga' => '' ]];
        }

        $initialPeserta = $sppd->peserta->pluck('user_id')->map(fn($id) => (int)$id)->toArray();
    @endphp

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dokumen.sppd.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Surat Perjalanan Dinas</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $sppd->no_surat }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <ul class="text-sm text-red-800 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <form action="{{ route('dokumen.sppd.update', $sppd->id) }}" method="POST" class="p-6 sm:p-8"
                      x-data="sppdForm({{ json_encode($kegiatanPeserta) }}, {{ json_encode($existingRows) }}, {{ json_encode($initialPeserta) }}, '{{ $sppd->kegiatan_id }}', '{{ $sppd->tanggal_berangkat }}', '{{ $sppd->tanggal_kembali }}')">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kegiatan Terkait (Opsional)</label>
                            <select name="kegiatan_id" x-model="kegiatanId" @change="onKegiatanChange()" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="">-- Tidak Terkait Kegiatan --</option>
                                @foreach($kegiatan as $k)
                                    <option value="{{ $k->id }}">{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }} - {{ $k->judul_kegiatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Peserta Perjalanan Dinas <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                @foreach($staf as $s)
                                    <label class="flex items-center gap-2 p-2 bg-white rounded border border-gray-200 hover:border-gray-400 cursor-pointer">
                                        <input type="checkbox" value="{{ $s->id }}" @change="togglePeserta({{ $s->id }})"
                                               :checked="peserta.includes({{ $s->id }})"
                                               class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                        <span class="text-sm text-gray-700">{{ $s->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="text-xs text-gray-500 mt-2">
                                Jumlah peserta: <strong x-text="peserta.length">0</strong> orang.
                                <template x-for="id in peserta" :key="id">
                                    <input type="hidden" name="peserta[]" :value="id">
                                </template>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Untuk Kepentingan <span class="text-red-500">*</span></label>
                            <input type="text" name="tujuan" required value="{{ old('tujuan', $sppd->tujuan) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kota Tujuan <span class="text-red-500">*</span></label>
                            <input type="text" name="kota_tujuan" required value="{{ old('kota_tujuan', $sppd->kota_tujuan) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berangkat <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_berangkat" x-model="berangkat" @change="updateUangHarian()" required value="{{ old('tanggal_berangkat', $sppd->tanggal_berangkat) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kembali <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_kembali" x-model="kembali" @change="updateUangHarian()" required value="{{ old('tanggal_kembali', $sppd->tanggal_kembali) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alat Angkutan <span class="text-red-500">*</span></label>
                            <select name="kendaraan" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="" disabled>-- Pilih Kendaraan --</option>
                                @foreach(['Kendaraan Dinas', 'Kendaraan Pribadi', 'Pesawat Terbang', 'Kapal Laut', 'Bus / Kereta Api', 'Sewa Mobil'] as $kendaraan)
                                    <option value="{{ $kendaraan }}" @selected($sppd->kendaraan === $kendaraan)>{{ $kendaraan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pembebanan Anggaran <span class="text-red-500">*</span></label>
                            <input type="text" name="pembebanan" required value="{{ old('pembebanan', $sppd->pembebanan) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">{{ old('keterangan', $sppd->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-semibold text-gray-700">Rincian Biaya Perjalanan</label>
                            <button type="button" @click="addRow()" class="text-xs font-semibold px-3 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg hover:bg-indigo-100">+ Tambah Rincian</button>
                        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100/75 text-gray-700 uppercase text-xs font-semibold">
                                    <tr>
                                        <th class="px-4 py-3">Uraian Biaya</th>
                                        <th class="px-4 py-3 w-20">Volume</th>
                                        <th class="px-4 py-3 w-28">Satuan</th>
                                        <th class="px-4 py-3 w-36">Harga Satuan (Rp)</th>
                                        <th class="px-4 py-3 w-10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in rows" :key="index">
                                        <tr class="border-t border-gray-100">
                                            <td class="px-4 py-2"><input type="text" x-model="row.uraian" :name="'biaya_uraian[' + index + ']'" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 text-xs"></td>
                                            <td class="px-4 py-2"><input type="number" min="1" x-model="row.volume" :name="'biaya_volume[' + index + ']'" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 text-xs"></td>
                                            <td class="px-4 py-2"><input type="text" x-model="row.satuan" :name="'biaya_satuan[' + index + ']'" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 text-xs"></td>
                                            <td class="px-4 py-2"><input type="number" min="0" step="0.01" x-model="row.harga" :name="'biaya_harga[' + index + ']'" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 text-xs"></td>
                                            <td class="px-4 py-2 text-center">
                                                <button type="button" @click="removeRow(index)" class="text-red-500 hover:text-red-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            Volume baris "Uang Harian" terisi otomatis = <span x-text="peserta.length">0</span> peserta × <span x-text="lamaHari">1</span> hari.
                        </p>
                    </div>

                    <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('dokumen.sppd.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Batal</a>
                        <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function sppdForm(kegiatanPeserta = {}, initialRows = [], initialPeserta = [], kegiatanId = '', berangkat = '', kembali = '') {
            return {
                kegiatanId: kegiatanId,
                peserta: initialPeserta,
                berangkat: berangkat,
                kembali: kembali,
                kegiatanPeserta: kegiatanPeserta,
                rows: (initialRows && initialRows.length) ? initialRows : [{ uraian: '', volume: 1, satuan: '', harga: '' }],
                get lamaHari() {
                    if (!this.berangkat || !this.kembali) return 1;
                    const a = new Date(this.berangkat);
                    const b = new Date(this.kembali);
                    const diff = Math.round((b - a) / 86400000) + 1;
                    return diff > 0 ? diff : 1;
                },
                onKegiatanChange() {
                    const ids = this.kegiatanPeserta[this.kegiatanId] || [];
                    this.peserta = ids;
                    this.updateUangHarian();
                },
                togglePeserta(id) {
                    const idx = this.peserta.indexOf(id);
                    if (idx >= 0) {
                        this.peserta.splice(idx, 1);
                    } else {
                        this.peserta.push(id);
                    }
                    this.updateUangHarian();
                },
                updateUangHarian() {
                    const row = this.rows.find(r => (r.uraian || '').toLowerCase().includes('uang harian'));
                    if (row) {
                        row.volume = Math.max(1, this.peserta.length * this.lamaHari);
                    }
                },
                addRow() {
                    this.rows.push({ uraian: '', volume: 1, satuan: '', harga: '' });
                },
                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    }
                }
            };
        }
    </script>
</x-app-layout>
