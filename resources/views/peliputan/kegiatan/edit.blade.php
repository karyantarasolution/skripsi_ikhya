<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('peliputan.kegiatan.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Jadwal Kegiatan</h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui data informasi kegiatan dan penugasan liputan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <form action="{{ route('peliputan.kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Kegiatan <span class="text-red-500">*</span></label>
                            <input type="text" name="judul_kegiatan" value="{{ $kegiatan->judul_kegiatan }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Kegiatan <span class="text-red-500">*</span></label>
                            <select name="kategori_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ $kegiatan->kategori_id == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Acara <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi" value="{{ $kegiatan->lokasi }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" value="{{ $kegiatan->tanggal }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="time" name="waktu" value="{{ \Carbon\Carbon::parse($kegiatan->waktu)->format('H:i') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pejabat yang Hadir</label>
                            <textarea name="pejabat_hadir" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">{{ $kegiatan->pejabat_hadir }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Deskripsi Singkat</label>
                            <textarea name="deskripsi" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">{{ $kegiatan->deskripsi }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">File RAB</label>
                            @if($kegiatan->rab_file)
                                <div class="mb-2 flex items-center gap-2">
                                    <a href="{{ asset($kegiatan->rab_file) }}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat RAB saat ini</a>
                                </div>
                            @endif
                            <input type="file" name="rab_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti file.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Penugasan Staf Peliput</label>
                            <div class="grid grid-cols-1 gap-2 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                @php $penugasanIds = $kegiatan->penugasan->pluck('user_id')->toArray(); @endphp
                                @forelse($staf as $s)
                                    @php
                                        $penugasanStaf = $kegiatan->penugasan->firstWhere('user_id', $s->id);
                                    @endphp
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 p-2 bg-white rounded border border-gray-200">
                                        <label class="flex items-center gap-2 sm:w-52 flex-shrink-0 cursor-pointer">
                                            <input type="checkbox" name="penugasan[]" value="{{ $s->id }}" {{ in_array($s->id, $penugasanIds) ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                            <span class="text-sm text-gray-700">{{ $s->name }}</span>
                                        </label>
                                        <input type="text" name="tugas[{{ $s->id }}]" value="{{ $penugasanStaf->tugas ?? '' }}" class="flex-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 text-xs" placeholder="Tugas di lapangan (cth: Peliputan foto & video)">
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada staf terdaftar.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                    
                    <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('peliputan.kegiatan.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">Batal</a>
                        <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-medium shadow-sm transition-colors">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
