<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('peliputan.kegiatan.index') }}" class="p-2 bg-white rounded-md border border-gray-200 text-gray-500 hover:text-gray-800 hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">Album Dokumentasi</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola file peliputan untuk kegiatan yang dipilih.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl shadow-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                    <div class="flex-shrink-0"><svg class="h-6 w-6 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p></div>
                    <div class="ml-auto pl-3"><button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button></div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-xl shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0"><svg class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Gagal mengunggah file:</h3>
                            <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 flex flex-col md:flex-row gap-6 justify-between items-start md:items-center">
                <div class="flex-1">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 mb-3 uppercase tracking-wide border border-blue-200 shadow-sm">{{ $kegiatan->kategori->nama_kategori ?? 'Umum' }}</span>
                    <h3 class="text-2xl font-black text-gray-900 mb-2 leading-tight">{{ $kegiatan->judul_kegiatan }}</h3>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 font-medium">
                        <div class="flex items-center"><svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d M Y') }}</div>
                        <div class="flex items-center"><svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ \Carbon\Carbon::parse($kegiatan->waktu)->format('H:i') }} WITA</div>
                        <div class="flex items-center"><svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg> {{ $kegiatan->lokasi }}</div>
                    </div>
                </div>
                <div class="w-full md:w-auto text-center md:text-right bg-gray-50 px-6 py-4 rounded-xl border border-gray-200">
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-1">Total File Tersimpan</p>
                    <p class="text-4xl font-black text-gray-900">{{ $kegiatan->dokumentasi->count() }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Form Upload Dokumentasi</h3>
                </div>
                <form action="{{ route('peliputan.dokumentasi.store', $kegiatan->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    
                    <p class="text-sm text-gray-600 mb-4">Format yang didukung: <span class="font-bold text-gray-900">JPG, PNG, MP4, PDF</span>. Maksimal ukuran: <span class="font-bold text-gray-900">10MB/file</span>. (Gunakan tombol Ctrl untuk memilih banyak file sekaligus).</p>
                    
                    <div class="flex items-center justify-center w-full mb-6">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-gray-400 transition-all relative group overflow-hidden">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-500 group-hover:text-gray-700">
                                <svg class="w-12 h-12 mb-3 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="mb-1 text-base"><span class="font-bold text-gray-900">Klik area ini</span> untuk memilih file dari perangkat Anda</p>
                                <p class="text-xs font-mono text-gray-400 mt-2" id="file-name-display">Belum ada file dipilih</p>
                            </div>
                            <input id="dropzone-file" type="file" name="files[]" multiple required accept=".jpg,.jpeg,.png,.mp4,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('file-name-display').textContent = this.files.length + ' file dipilih';" />
                        </label>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-bold shadow-md transition-all flex items-center transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Mulai Unggah File
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($kegiatan->dokumentasi as $doc)
                    <div class="group relative bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="aspect-w-4 aspect-h-3 bg-gray-100 flex items-center justify-center overflow-hidden border-b border-gray-100">
                            @if($doc->tipe_file == 'foto')
                                <img src="{{ asset($doc->file_path) }}" alt="Foto" class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500 ease-in-out">
                            @elseif($doc->tipe_file == 'video')
                                <div class="w-full h-full bg-gray-900 flex items-center justify-center text-white">
                                    <svg class="w-12 h-12 opacity-60" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                                </div>
                            @else
                                <div class="w-full h-full bg-red-50 flex items-center justify-center text-red-500">
                                    <svg class="w-12 h-12 opacity-80" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-3 bg-white">
                            <p class="text-xs font-bold text-gray-900 truncate" title="{{ $doc->nama_file }}">{{ $doc->nama_file }}</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Tipe: {{ $doc->tipe_file }}</p>
                            @if($doc->user)
                                <p class="text-[10px] text-gray-400 mt-0.5">Oleh: {{ $doc->user->name }}</p>
                            @endif
                        </div>

                        <div class="absolute inset-0 bg-gray-900 bg-opacity-70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3 backdrop-blur-sm">
                            <a href="{{ asset($doc->file_path) }}" target="_blank" class="p-2 bg-white text-gray-900 rounded-full hover:bg-gray-200 transition-colors shadow-lg" title="Lihat Penuh">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <form action="{{ route('peliputan.dokumentasi.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Hapus file {{ $doc->nama_file }}?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors shadow-lg" title="Hapus File">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center flex flex-col items-center justify-center">
                        <div class="p-4 bg-white rounded-full shadow-sm mb-4">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-gray-900 font-bold text-lg">Belum ada file dokumentasi.</p>
                        <p class="text-sm text-gray-500 mt-1">Silakan pilih file dan klik tombol "Mulai Unggah File" di atas.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
