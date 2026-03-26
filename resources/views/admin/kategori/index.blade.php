<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Manajemen Kategori Kegiatan
        </h2>
        <p class="text-sm text-gray-500 mt-1">Kelola daftar klasifikasi kegiatan pimpinan untuk pelaporan.</p>
    </x-slot>

    <div class="py-8" x-data="{ openAdd: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button @click="show = false" class="inline-flex text-emerald-500 focus:outline-none focus:text-emerald-700 transition ease-in-out duration-150">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Daftar Kategori</h3>
                        <p class="text-sm text-gray-500">Total: {{ count($kategori) }} Kategori</p>
                    </div>
                    <button @click="openAdd = true" class="w-full sm:w-auto bg-gray-900 hover:bg-gray-800 focus:ring-4 focus:ring-gray-200 text-white font-medium py-2.5 px-5 rounded-lg flex items-center justify-center transition-all duration-200 ease-in-out shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Data
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-100/75 text-gray-700 uppercase text-xs font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-4 rounded-tl-lg">No</th>
                                <th scope="col" class="px-6 py-4">Warna Label</th>
                                <th scope="col" class="px-6 py-4">Nama Kategori</th>
                                <th scope="col" class="px-6 py-4 w-1/3">Deskripsi</th>
                                <th scope="col" class="px-6 py-4 text-center rounded-tr-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($kategori as $index => $item)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200" x-data="{ openEdit: false, openDelete: false }">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <span class="w-5 h-5 rounded-full shadow-inner border border-gray-200" style="background-color: {{ $item->warna_label }}"></span>
                                            <span class="text-xs text-gray-500 uppercase font-mono">{{ $item->warna_label }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->nama_kategori }}</td>
                                    <td class="px-6 py-4 text-gray-500 line-clamp-2" title="{{ $item->deskripsi }}">{{ $item->deskripsi ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <button @click="openEdit = true" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 hover:text-blue-800 transition-colors" title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            
                                            <button @click="openDelete = true" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 hover:text-red-800 transition-colors" title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>

                                        <div x-show="openEdit" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div x-show="openEdit" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                                            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                                    <div x-show="openEdit" 
                                                         x-transition:enter="ease-out duration-300" 
                                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                                         x-transition:leave="ease-in duration-200" 
                                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                         class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg" 
                                                         @click.away="openEdit = false">
                                                        
                                                        <form action="{{ route('admin.kategori.update', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="bg-white px-6 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                                <div class="sm:flex sm:items-start">
                                                                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                                        <h3 class="text-xl font-bold leading-6 text-gray-900 mb-6" id="modal-title">Edit Kategori</h3>
                                                                        
                                                                        <div class="space-y-5">
                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                                                                                <input type="text" name="nama_kategori" value="{{ $item->nama_kategori }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                                                                            </div>
                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Warna Label <span class="text-red-500">*</span></label>
                                                                                <div class="flex items-center space-x-3">
                                                                                    <input type="color" name="warna_label" value="{{ $item->warna_label }}" required class="h-10 w-16 rounded-lg border-gray-300 shadow-sm cursor-pointer p-1">
                                                                                    <span class="text-xs text-gray-500 italic">Pilih warna untuk jadwal kegiatan ini</span>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                                                                                <textarea name="deskripsi" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">{{ $item->deskripsi }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-100">
                                                                <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 sm:ml-3 sm:w-auto transition-colors">Simpan Perubahan</button>
                                                                <button type="button" @click="openEdit = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div x-show="openDelete" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div x-show="openDelete" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                                            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                                    <div x-show="openDelete" 
                                                         x-transition:enter="ease-out duration-300" 
                                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                                         x-transition:leave="ease-in duration-200" 
                                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                         class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md" 
                                                         @click.away="openDelete = false">
                                                        
                                                        <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                                <div class="sm:flex sm:items-start">
                                                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                        </svg>
                                                                    </div>
                                                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                                                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Hapus Kategori</h3>
                                                                        <div class="mt-2">
                                                                            <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus kategori <span class="font-bold text-gray-900">"{{ $item->nama_kategori }}"</span>? Data yang dihapus tidak dapat dikembalikan.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                                                                <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">Ya, Hapus Data</button>
                                                                <button type="button" @click="openDelete = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="text-base font-medium">Belum ada data kategori</p>
                                            <p class="text-sm mt-1">Silakan tambahkan kategori baru untuk memulai.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="openAdd" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="openAdd" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="openAdd" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg" 
                         @click.away="openAdd = false">
                        
                        <form action="{{ route('admin.kategori.store') }}" method="POST">
                            @csrf
                            <div class="bg-white px-6 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                        <h3 class="text-xl font-bold leading-6 text-gray-900 mb-6" id="modal-title">Tambah Kategori Baru</h3>
                                        
                                        <div class="space-y-5">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                                                <input type="text" name="nama_kategori" required placeholder="Contoh: Rapat Paripurna" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Warna Label <span class="text-red-500">*</span></label>
                                                <div class="flex items-center space-x-3">
                                                    <input type="color" name="warna_label" value="#3B82F6" required class="h-10 w-16 rounded-lg border-gray-300 shadow-sm cursor-pointer p-1">
                                                    <span class="text-xs text-gray-500 italic">Pilih warna untuk jadwal kegiatan ini</span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                                                <textarea name="deskripsi" rows="3" placeholder="Opsional..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 transition-colors"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-100">
                                <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 sm:ml-3 sm:w-auto transition-colors">Simpan Kategori</button>
                                <button type="button" @click="openAdd = false" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>