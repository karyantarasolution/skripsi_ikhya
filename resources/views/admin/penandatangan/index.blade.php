<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Data Penandatangan (TTD)
        </h2>
        <p class="text-sm text-gray-500 mt-1">Kelola data pejabat berwenang untuk validasi cetak laporan PDF.</p>
    </x-slot>

    <div class="py-8" x-data="{ openAdd: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button @click="show = false" class="inline-flex text-emerald-500 focus:outline-none focus:text-emerald-700 transition ease-in-out duration-150"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Daftar Pejabat</h3>
                    </div>
                    <button @click="openAdd = true" class="w-full sm:w-auto bg-gray-900 hover:bg-gray-800 text-white font-medium py-2.5 px-5 rounded-lg flex items-center justify-center transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Pejabat
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-100/75 text-gray-700 uppercase text-xs font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nama & NIP</th>
                                <th scope="col" class="px-6 py-4">Jabatan</th>
                                <th scope="col" class="px-6 py-4 text-center">Status</th>
                                <th scope="col" class="px-6 py-4 text-center">QR Code</th>
                                <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($penandatangan as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200" x-data="{ openEdit: false, openDelete: false }">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">{{ $item->nama_pejabat }}</p>
                                        <p class="text-xs text-gray-500 font-mono mt-1">NIP. {{ $item->nip }}</p>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-800">{{ $item->jabatan }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->is_aktif)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full"></span> Aktif Dipakai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="w-2 h-2 mr-1.5 bg-gray-500 rounded-full"></span> Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->qr_code_path)
                                            <img src="{{ asset($item->qr_code_path) }}" alt="QR {{ $item->nama_pejabat }}" class="inline-block w-12 h-12 border border-gray-200 rounded">
                                        @else
                                            <span class="text-xs text-gray-400">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <button @click="openEdit = true" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 hover:text-blue-800 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <button @click="openDelete = true" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 hover:text-red-800 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>

                                        <div x-show="openEdit" style="display: none;" class="relative z-50">
                                            <div x-show="openEdit" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                    <div x-show="openEdit" class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg" @click.away="openEdit = false">
                                                        <form action="{{ route('admin.penandatangan.update', $item->id) }}" method="POST">
                                                            @csrf @method('PUT')
                                                            <div class="bg-white px-6 pb-4 pt-5 sm:p-6">
                                                                <h3 class="text-xl font-bold text-gray-900 mb-5">Edit Data Pejabat</h3>
                                                                <div class="space-y-4">
                                                                    <div>
                                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap & Gelar</label>
                                                                        <input type="text" name="nama_pejabat" value="{{ $item->nama_pejabat }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                                                        <input type="text" name="nip" value="{{ $item->nip }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan (Untuk di Laporan)</label>
                                                                        <input type="text" name="jabatan" value="{{ $item->jabatan }}" required placeholder="Contoh: Kepala Biro Administrasi Pimpinan" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Penggunaan</label>
                                                                        <select name="is_aktif" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                                                            <option value="1" {{ $item->is_aktif ? 'selected' : '' }}>Aktif (Tampil di cetakan PDF)</option>
                                                                            <option value="0" {{ !$item->is_aktif ? 'selected' : '' }}>Tidak Aktif (Arsip)</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                                                                <button type="button" @click="openEdit = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                                                                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div x-show="openDelete" style="display: none;" class="relative z-50">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md" @click.away="openDelete = false">
                                                        <form action="{{ route('admin.penandatangan.destroy', $item->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <div class="p-6 text-center">
                                                                <svg class="mx-auto mb-4 text-red-500 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                                <h3 class="mb-2 text-lg font-bold text-gray-900">Hapus Pejabat</h3>
                                                                <p class="mb-6 text-sm text-gray-500">Yakin hapus data <b>{{ $item->nama_pejabat }}</b>?</p>
                                                                <div class="flex justify-center gap-3">
                                                                    <button type="button" @click="openDelete = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Ya, Hapus</button>
                                                                </div>
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
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada data Pejabat/Penandatangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="openAdd" style="display: none;" class="relative z-50">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg" @click.away="openAdd = false">
                        <form action="{{ route('admin.penandatangan.store') }}" method="POST">
                            @csrf
                            <div class="bg-white px-6 pb-4 pt-5 sm:p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-5">Tambah Pejabat Penandatangan</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_pejabat" required placeholder="H. Sahbirin Noor, S.Sos., M.H." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP <span class="text-red-500">*</span></label>
                                        <input type="text" name="nip" required placeholder="19671112 199... " class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                                        <input type="text" name="jabatan" required placeholder="Gubernur Kalimantan Selatan" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Penggunaan <span class="text-red-500">*</span></label>
                                        <select name="is_aktif" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                            <option value="1">Aktif (Tampil di cetakan PDF)</option>
                                            <option value="0">Tidak Aktif (Arsip)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl border-t border-gray-100">
                                <button type="button" @click="openAdd = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>