<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">Arsip Dokumentasi</h2>
        <p class="text-sm text-gray-600 mt-1">Pilih kegiatan di bawah ini untuk melihat detail file peliputannya.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Album Kegiatan</h3>
                        <p class="text-sm text-gray-500">Menampilkan kegiatan yang sudah memiliki dokumentasi lapangan.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($kegiatan as $item)
                    <a href="{{ route('peliputan.dokumentasi.index', $item->id) }}" class="group block bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 transform hover:-translate-y-1">
                        
                        <div class="bg-gray-50 p-6 flex justify-center items-center border-b border-gray-100 group-hover:bg-indigo-50 transition-colors relative">
                            <svg class="w-16 h-16 text-gray-300 group-hover:text-indigo-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                            </svg>
                            <div class="absolute top-4 right-4 bg-white border border-gray-200 text-gray-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm group-hover:border-indigo-200 group-hover:text-indigo-600 transition-colors">
                                {{ $item->dokumentasi_count }} File
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <span class="inline-block px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 mb-2 border border-gray-200">
                                {{ $item->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                            <h4 class="text-base font-bold text-gray-900 leading-snug line-clamp-2 mb-2 group-hover:text-indigo-600 transition-colors">
                                {{ $item->judul_kegiatan }}
                            </h4>
                            
                            <div class="space-y-1.5 mt-3 pt-3 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </div>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    <span class="truncate">{{ $item->lokasi }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex items-center justify-between group-hover:bg-indigo-600 group-hover:border-indigo-600 transition-colors">
                            <span class="text-xs font-semibold text-gray-500 group-hover:text-white transition-colors">Buka Arsip</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-white transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center flex flex-col items-center justify-center">
                        <div class="p-4 bg-white rounded-full shadow-sm mb-4">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-gray-900 font-bold text-lg">Belum ada album kegiatan.</p>
                        <p class="text-sm text-gray-500 mt-1">Silakan unggah foto pada salah satu jadwal kegiatan untuk membuat album baru.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-8">
                {{ $kegiatan->links() }}
            </div>

        </div>
    </div>
</x-app-layout>