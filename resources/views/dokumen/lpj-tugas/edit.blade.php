<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dokumen.lpj-tugas.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Kelola LPJ Tugas</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $lpj->no_lpj }} - {{ $lpj->penugasan->kegiatan->judul_kegiatan }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p></div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Informasi LPJ</h3>
                </div>
                <form action="{{ route('dokumen.lpj-tugas.update', $lpj->id) }}" method="POST" class="p-6 sm:p-8">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Penanggung Jawab <span class="text-red-500">*</span></label>
                            <input type="text" name="penanggung_jawab_nama" required value="{{ old('penanggung_jawab_nama', $lpj->penanggung_jawab_nama) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan Penanggung Jawab <span class="text-red-500">*</span></label>
                            <input type="text" name="penanggung_jawab_jabatan" required value="{{ old('penanggung_jawab_jabatan', $lpj->penanggung_jawab_jabatan) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal LPJ <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lpj" required value="{{ old('tanggal_lpj', $lpj->tanggal_lpj) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Uraian Hasil Pelaksanaan Tugas</label>
                            <textarea name="uraian_hasil" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">{{ old('uraian_hasil', $lpj->uraian_hasil) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Bukti ({{ $lpj->bukti->count() }})</h3>
                </div>
                <div class="p-6">
                    @forelse($lpj->bukti as $index => $b)
                        <div class="flex items-center gap-4 p-3 rounded-lg border border-gray-100 mb-3 hover:bg-gray-50">
                            @if(in_array(strtolower(pathinfo($b->file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
                                <img src="{{ asset($b->file) }}" class="w-14 h-14 rounded-lg object-cover border border-gray-200" alt="bukti">
                            @else
                                <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $b->keterangan ?? 'Bukti ' . ($index + 1) }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ basename($b->file) }}</p>
                            </div>
                            <a href="{{ asset($b->file) }}" target="_blank" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-semibold">Lihat</a>
                            <form action="{{ route('dokumen.lpj-tugas.bukti.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus bukti ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-semibold">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic py-4 text-center">Belum ada bukti.</p>
                    @endforelse

                    <form action="{{ route('dokumen.lpj-tugas.bukti.store', $lpj->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 pt-5 border-t border-gray-100">
                        @csrf
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tambah Bukti Baru</label>
                        <input type="file" name="bukti[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, PDF, DOC. Maks 20MB per file.</p>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-sm">Upload Bukti</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
