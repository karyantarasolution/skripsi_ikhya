<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">LPJ Tugas (Lembar Pertanggungjawaban)</h2>
                <p class="text-sm text-gray-500 mt-1">Bukti bahwa tugas telah diselesaikan, lengkap dengan bukti foto dan tanda tangan penyelenggara.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
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
                    <h3 class="text-lg font-bold text-gray-900">Daftar LPJ Tugas</h3>
                    <a href="{{ route('dokumen.lpj-tugas.create') }}" class="bg-gray-900 hover:bg-gray-800 text-white font-medium py-2.5 px-5 rounded-lg flex items-center transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat LPJ Tugas
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-100/75 text-gray-700 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-4">Nomor LPJ</th>
                                <th class="px-6 py-4">Kegiatan</th>
                                <th class="px-6 py-4">Petugas Bertugas</th>
                                <th class="px-6 py-4">Penanggung Jawab</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Bukti</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $lpjLabels = [
                                    'draf' => 'Draft',
                                    'diajukan' => 'Diajukan',
                                    'review_kabag' => 'Review Kabag',
                                    'disetujui' => 'Disetujui (TTD)',
                                    'ditolak' => 'Ditolak',
                                ];
                                $lpjClasses = [
                                    'draf' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    'diajukan' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'review_kabag' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'disetujui' => 'bg-green-100 text-green-800 border-green-200',
                                    'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                            @endphp
                            @forelse($lpj as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors" x-data="{ openDelete: false, openReviewKabag: false, openReturnStaf: false, openTtd: false }">
                                    <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">{{ $item->no_lpj }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $item->kegiatan->judul_kegiatan }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->kegiatan->tanggal)->format('d M Y') }} - {{ $item->kegiatan->lokasi }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($item->kegiatan->penugasan as $ps)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    {{ $ps->user->name ?? '?' }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic">{{ $item->user->name ?? '?' }}</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $item->penanggung_jawab_nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->penanggung_jawab_jabatan }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $label = $lpjLabels[$item->status] ?? 'Draft'; $class = $lpjClasses[$item->status] ?? 'bg-gray-100 text-gray-800 border-gray-200'; @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $class }}">
                                            {{ $label }}
                                        </span>
                                        @if($item->status === 'ditolak' && $item->kabag_catatan)
                                            <div class="text-xs text-red-600 mt-1">Alasan: {{ Str::limit($item->kabag_catatan, 30) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->bukti->count() > 0)
                                            <div class="flex -space-x-2">
                                                @foreach($item->bukti->take(4) as $b)
                                                    <img src="{{ asset($b->file) }}" class="w-8 h-8 rounded-full border-2 border-white object-cover" alt="bukti">
                                                @endforeach
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $item->bukti->count() }} file</div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5 items-center">
                                            <a href="{{ route('dokumen.lpj-tugas.cetak', $item->id) }}" target="_blank" class="w-full text-center text-xs px-2 py-1 bg-gray-900 text-white rounded hover:bg-gray-800">Cetak PDF</a>

                                            @if(in_array($item->status, ['draf', 'ditolak']))
                                                <form action="{{ route('dokumen.lpj-tugas.ajukan', $item->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="w-full text-xs px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Ajukan</button>
                                                </form>
                                            @endif

                                            @if($item->status === 'diajukan' && (Auth::user()->role === 'admin' || Auth::user()->role === 'pimpinan'))
                                                <button @click="openReviewKabag = true" class="w-full text-xs px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Review Kabag</button>
                                            @endif

                                            @if(in_array($item->status, ['diajukan', 'review_kabag']) && (Auth::user()->role === 'admin' || Auth::user()->role === 'pimpinan'))
                                                <button @click="openReturnStaf = true" class="w-full text-xs px-2 py-1 bg-orange-500 text-white rounded hover:bg-orange-600">Kembalikan</button>
                                            @endif

                                            @if($item->status === 'review_kabag')
                                                <button @click="openTtd = true" class="w-full text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">TTD Karo Adpim</button>
                                            @endif

                                            @if(in_array($item->status, ['draf', 'ditolak', 'review_kabag']))
                                                <a href="{{ route('dokumen.lpj-tugas.edit', $item->id) }}" class="w-full text-center text-xs px-2 py-1 text-indigo-600 bg-indigo-50 rounded hover:bg-indigo-100">Kelola Bukti</a>
                                            @endif

                                            <form action="{{ route('dokumen.lpj-tugas.destroy', $item->id) }}" method="POST" class="w-full" onsubmit="return confirm('Hapus LPJ ini beserta semua buktinya?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full text-xs px-2 py-1 text-red-600 bg-red-50 rounded hover:bg-red-100">Hapus</button>
                                            </form>

                                            {{-- Modal Review Kabag --}}
                                            <div x-show="openReviewKabag" style="display: none;" class="relative z-50">
                                                <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                                                <div class="fixed inset-0 z-10 overflow-y-auto">
                                                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md" @click.away="openReviewKabag = false">
                                                            <form action="{{ route('dokumen-admin.lpj-tugas.review-kabag', $item->id) }}" method="POST">
                                                                @csrf
                                                                <div class="p-6">
                                                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Review Kabag</h3>
                                                                    <p class="text-sm text-gray-500 mb-4">Masukkan PIN untuk mereview LPJ ini:</p>
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
                                                            <form action="{{ route('dokumen-admin.lpj-tugas.return-staf', $item->id) }}" method="POST">
                                                                @csrf
                                                                <div class="p-6">
                                                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Kembalikan ke Staf</h3>
                                                                    <p class="text-sm text-gray-500 mb-4">LPJ akan dikembalikan ke status Draft untuk perbaikan:</p>
                                                                    <textarea name="catatan" rows="3" required placeholder="Alasan pengembalian..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900"></textarea>
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
                                                            <form action="{{ route('admin.ttd-digital.proses', ['lpj_tugas', $item->id]) }}" method="POST">
                                                                @csrf
                                                                <div class="p-6">
                                                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Tanda Tangan Digital</h3>
                                                                    <p class="text-sm text-gray-500 mb-4">Masukkan PIN untuk mengesahkan LPJ ini secara digital:</p>
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

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada LPJ Tugas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
