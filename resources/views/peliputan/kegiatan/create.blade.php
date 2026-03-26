<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('peliputan.kegiatan.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Tambah Jadwal Baru</h2>
                <p class="text-sm text-gray-500 mt-1">Input data agenda kegiatan pimpinan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <form action="{{ route('peliputan.kegiatan.store') }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Kegiatan <span class="text-red-500">*</span></label>
                            <input type="text" name="judul_kegiatan" required placeholder="Contoh: Rapat Paripurna DPRD" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Kegiatan <span class="text-red-500">*</span></label>
                            <select name="kategori_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Acara <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi" required placeholder="Gedung Mahligai Pancasila" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="time" name="waktu" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pejabat yang Hadir</label>
                            <textarea name="pejabat_hadir" rows="2" placeholder="Gubernur, Wakil Gubernur, dll..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Deskripsi Singkat</label>
                            <textarea name="deskripsi" rows="3" placeholder="Tambahan informasi kegiatan..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors"></textarea>
                        </div>

                    </div>
                    
                    <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('peliputan.kegiatan.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">Batal</a>
                        <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium shadow-sm transition-colors">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>