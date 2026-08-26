<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Jadwal & Workflow Kegiatan Lapangan</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola jadwal liputan, workflow persetujuan, dan dokumentasi kegiatan.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3"><p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p></div>
                    <div class="ml-auto pl-3">
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                    <div class="ml-3"><p class="text-sm font-medium text-red-800">{{ session('error') }}</p></div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Jadwal</h3>
                    <a href="{{ route('peliputan.kegiatan.create') }}" class="bg-gray-900 hover:bg-gray-800 text-white font-medium py-2.5 px-5 rounded-lg flex items-center transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Jadwal Baru
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-100/75 text-gray-700 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-4">Waktu & Tanggal</th>
                                <th class="px-6 py-4">Informasi Kegiatan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tim Liputan</th>
                                <th class="px-6 py-4 text-center">Dokumentasi</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $statusLabels = [
                                    'draf' => 'Draft',
                                    'diajukan' => 'Diajukan',
                                    'review_kabag' => 'Review Kabag',
                                    'disetujui' => 'Disetujui (TTD)',
                                    'ditolak' => 'Ditolak',
                                    'pelaksanaan' => 'Pelaksanaan',
                                    'selesai' => 'Selesai',
                                    'lpj' => 'LPJ',
                                ];
                                $statusClasses = [
                                    'draf' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    'diajukan' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'review_kabag' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'disetujui' => 'bg-green-100 text-green-800 border-green-200',
                                    'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                    'pelaksanaan' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'lpj' => 'bg-purple-100 text-purple-800 border-purple-200',
                                ];
                            @endphp
                            @forelse($kegiatan as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors" x-data="{ openDelete: false, openTolak: false, openLpj: false, openReviewKabag: false, openReturnStaf: false, openTtd: false }">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }} WITA
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 line-clamp-2">{{ $item->judul_kegiatan }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Oleh: {{ $item->user->name ?? 'User Terhapus' }}</div>
                                        <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            {{ $item->lokasi }}
                                        </div>
                                        @if($item->kategori)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-1" style="background-color: {{ $item->kategori->warna_label }}20; color: {{ $item->kategori->warna_label }};">
                                                {{ $item->kategori->nama_kategori }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $label = $statusLabels[$item->status] ?? $item->status; $class = $statusClasses[$item->status] ?? 'bg-gray-100 text-gray-800 border-gray-200'; @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $class }}">
                                            {{ $label }}
                                        </span>
                                        @if($item->status === 'ditolak' && $item->catatan_penolakan)
                                            <div class="text-xs text-red-600 mt-1" title="{{ $item->catatan_penolakan }}">Alasan: {{ Str::limit($item->catatan_penolakan, 30) }}</div>
                                        @endif
                                        @if($item->status === 'review_kabag' && $item->kabag_catatan)
                                            <div class="text-xs text-yellow-600 mt-1" title="{{ $item->kabag_catatan }}">Catatan: {{ Str::limit($item->kabag_catatan, 30) }}</div>
                                        @endif
                                        @if($item->rab_file)
                                            <div class="mt-1">
                                                <a href="{{ asset($item->rab_file) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat RAB</a>
                                            </div>
                                        @endif
                                        @if($item->lpj_file)
                                            <div class="mt-1">
                                                <a href="{{ asset($item->lpj_file) }}" target="_blank" class="text-xs text-purple-600 hover:underline">Lihat LPJ</a>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->penugasan && $item->penugasan->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($item->penugasan as $p)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700 border border-gray-200">
                                                        {{ $p->user->name ?? '?' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('peliputan.dokumentasi.index', $item->id) }}" class="inline-flex flex-col items-center justify-center p-2 text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors border border-indigo-100 group">
                                            <svg class="w-5 h-5 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[10px] font-bold uppercase tracking-wider">Upload File</span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5">
                                            {{-- Workflow Buttons --}}
                                            @if($item->status === 'draf')
                                                <form action="{{ route('peliputan.kegiatan.ajukan', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full text-xs px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Ajukan</button>
                                                </form>
                                            @endif

                                            @if($item->status === 'diajukan' && (Auth::user()->role === 'admin' || Auth::user()->role === 'pimpinan'))
                                                <button @click="openReviewKabag = true" class="w-full text-xs px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Review Kabag</button>
                                                <button @click="openReturnStaf = true" class="w-full text-xs px-2 py-1 bg-orange-500 text-white rounded hover:bg-orange-600">Kembalikan</button>
                                            @endif

                                            @if($item->status === 'review_kabag')
                                                <button @click="openTtd = true" class="w-full text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">TTD Karo Adpim</button>
                                            @endif

                                            @if($item->status === 'disetujui')
                                                <form action="{{ route('peliputan.kegiatan.mulai', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Mulai</button>
                                                </form>
                                            @endif

                                            @if($item->status === 'pelaksanaan')
                                                <form action="{{ route('peliputan.kegiatan.selesai', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full text-xs px-2 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700">Selesai</button>
                                                </form>
                                            @endif

                                            @if($item->status === 'selesai')
                                                <button @click="openLpj = true" class="w-full text-xs px-2 py-1 bg-purple-600 text-white rounded hover:bg-purple-700">Upload LPJ</button>
                                            @endif

                                            <div class="flex gap-1">
                                                @if(in_array($item->status, ['draf', 'ditolak', 'review_kabag']))
                                                    <a href="{{ route('peliputan.kegiatan.edit', $item->id) }}" class="flex-1 text-center text-xs px-2 py-1 text-blue-600 bg-blue-50 rounded hover:bg-blue-100">Edit</a>
                                                @endif
                                                <button @click="openDelete = true" class="flex-1 text-center text-xs px-2 py-1 text-red-600 bg-red-50 rounded hover:bg-red-100">Hapus</button>
                                            </div>
                                        </div>

                                        {{-- Modal Review Kabag --}}
                                        <div x-show="openReviewKabag" style="display: none;" class="relative z-50">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md" @click.away="openReviewKabag = false">
                                                        <form action="{{ route('peliputan-admin.kegiatan.review-kabag', $item->id) }}" method="POST">
                                                            @csrf
                                                            <div class="p-6">
                                                                <h3 class="text-lg font-bold text-gray-900 mb-2">Review Kabag</h3>
                                                                <p class="text-sm text-gray-500 mb-4">Masukkan PIN untuk mereview kegiatan ini:</p>
                                                                <input type="password" name="pin" required placeholder="PIN/Password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 mb-3">
                                                                <textarea name="kabag_catatan" rows="2" placeholder="Catatan (opsional)" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900"></textarea>
                                                            </div>
                                                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                                                                <button type="button" @click="openReviewKabag = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                                                                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Review & Setujui</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Kembalikan ke Staf --}}
                                        <div x-show="openReturnStaf" style="display: none;" class="relative z-50">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md" @click.away="openReturnStaf = false">
                                                        <form action="{{ route('peliputan-admin.kegiatan.return-staf', $item->id) }}" method="POST">
                                                            @csrf
                                                            <div class="p-6">
                                                                <h3 class="text-lg font-bold text-gray-900 mb-2">Kembalikan ke Staf</h3>
                                                                <p class="text-sm text-gray-500 mb-4">Kegiatan akan dikembalikan ke status Draft untuk perbaikan:</p>
                                                                <textarea name="catatan_penolakan" rows="3" required placeholder="Alasan pengembalian..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900"></textarea>
                                                            </div>
                                                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                                                                <button type="button" @click="openReturnStaf = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                                                                <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">Kembalikan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal TTD --}}
                                        <div x-show="openTtd" style="display: none;" class="relative z-50">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md" @click.away="openTtd = false">
                                                        <form action="{{ route('admin.ttd-digital.proses', ['surat_tugas', $item->id]) }}" method="POST">
                                                            @csrf
                                                            <div class="p-6">
                                                                <h3 class="text-lg font-bold text-gray-900 mb-2">Tanda Tangan Digital</h3>
                                                                <p class="text-sm text-gray-500 mb-4">Masukkan PIN untuk mengesahkan kegiatan ini secara digital:</p>
                                                                <input type="password" name="pin" required placeholder="PIN/Password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 mb-3">
                                                                <div>
                                                                    <label class="text-xs font-semibold text-gray-900 mb-1 block">Pejabat Penandatangan</label>
                                                                    <select name="penandatangan_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                                                        @foreach(\App\Models\Penandatangan::where('is_aktif', true)->get() as $pj)
                                                                            <option value="{{ $pj->id }}">{{ $pj->nama_pejabat }} ({{ $pj->jabatan }})</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                                                                <button type="button" @click="openTtd = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                                                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Proses TTD</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Hapus --}}
                                        <div x-show="openDelete" style="display: none;" class="relative z-50">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md" @click.away="openDelete = false">
                                                        <form action="{{ route('peliputan.kegiatan.destroy', $item->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <div class="p-6 text-center">
                                                                <svg class="mx-auto mb-4 text-red-500 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                                <h3 class="mb-2 text-lg font-bold text-gray-900">Hapus Jadwal</h3>
                                                                <p class="mb-6 text-sm text-gray-500">Yakin hapus jadwal <b>{{ $item->judul_kegiatan }}</b>?</p>
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

                                        {{-- Modal LPJ --}}
                                        <div x-show="openLpj" style="display: none;" class="relative z-50">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                                <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md" @click.away="openLpj = false">
                                                        <form action="{{ route('peliputan.kegiatan.upload-lpj', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="p-6">
                                                                <h3 class="text-lg font-bold text-gray-900 mb-4">Upload LPJ Kegiatan</h3>
                                                                <p class="text-sm text-gray-500 mb-4">Upload Laporan Pertanggungjawaban untuk kegiatan: <b>{{ $item->judul_kegiatan }}</b></p>
                                                                <input type="file" name="lpj_file" required accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                                                <p class="text-xs text-gray-400 mt-1">Format: PDF, DOC, XLS. Maks 20MB</p>
                                                            </div>
                                                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                                                                <button type="button" @click="openLpj = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                                                                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Upload LPJ</button>
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
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada jadwal kegiatan yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
