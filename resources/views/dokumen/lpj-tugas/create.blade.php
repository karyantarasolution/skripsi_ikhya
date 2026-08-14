<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dokumen.lpj-tugas.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Buat LPJ Tugas</h2>
                <p class="text-sm text-gray-500 mt-1">Satu LPJ per kegiatan, memuat seluruh staf yang bertugas.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <ul class="text-sm text-red-800 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($kegiatan->isEmpty())
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg shadow-sm">
                    <p class="text-sm font-medium text-amber-800">Belum ada kegiatan dengan penugasan yang dapat dibuatkan LPJ. LPJ dibuat berdasarkan kegiatan yang memiliki staf bertugas.</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <form action="{{ route('dokumen.lpj-tugas.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Kegiatan <span class="text-red-500">*</span></label>
                            <select name="kegiatan_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="" disabled selected>-- Pilih Kegiatan --</option>
                                @foreach($kegiatan as $k)
                                    <option value="{{ $k->id }}">
                                        {{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }} - {{ $k->judul_kegiatan }}
                                        ({{ $k->penugasan->pluck('user.name')->implode(', ') }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Semua staf yang bertugas pada kegiatan tersebut otomatis tercantum dalam LPJ ini.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Penanggung Jawab di Lokasi <span class="text-red-500">*</span></label>
                            <input type="text" name="penanggung_jawab_nama" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900" placeholder="cth: Nama penyelenggara / ketua panitia">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan Penanggung Jawab <span class="text-red-500">*</span></label>
                            <input type="text" name="penanggung_jawab_jabatan" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900" placeholder="cth: Ketua Panitia / Kepala Sekolah">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal LPJ <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lpj" required value="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Uraian Hasil Pelaksanaan Tugas</label>
                            <textarea name="uraian_hasil" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900" placeholder="cth: Melakukan liputan dan dokumentasi kegiatan, hasil foto diserahkan kepada penyelenggara..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Bukti Pelaksanaan (Foto / Dokumen) <span class="text-red-500">*</span></label>
                            <input type="file" name="bukti[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                            <p class="text-xs text-gray-400 mt-1">Bisa pilih lebih dari satu file. Format: JPG, PNG, PDF, DOC. Maks 20MB per file.</p>
                        </div>
                    </div>
                    <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('dokumen.lpj-tugas.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Batal</a>
                        <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium shadow-sm">Simpan LPJ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
